<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Mail\TrainingPayOrder;
use App\Mail\TrainingSuccess;
use App\Models\Training\TrxClass;
use App\Models\Training\TrxOrder;
use App\Models\Training\TrxParticipant;
use App\Models\Training\TrxSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TrxOrder::leftJoin(
            DB::raw("(select trx_order_id,name,email,phone from tre_trx_order_participants group by trx_order_id) participant"), 'participant.trx_order_id', 'tre_trx_orders.id')
            ->leftJoin(DB::raw("(select tre_trx_classes.id class_id, title, type_class from tre_trx_classes join tre_trx_train_events on tre_trx_train_events.id=trx_train_event_id) tre_trx_classes"), 'tre_trx_classes.class_id', 'tre_trx_orders.class_id')
            ->orderable($request->orders)
            ->searchable($request->columns, $request->search)
            ->paging($request->perpage??10);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $participant = $request->validate([
            'participant.name' => 'required|max:100',
            'participant.email' => 'required|max:100|email',
            'participant.phone' => 'required|max:100',
        ]);

        $validated = $request->validate([
            'schedule_id' => 'required|exists:tre_trx_schedules,id',
            'class_id' => 'required|max:36|exists:tre_trx_classes,id',
            'quantity' => 'required|integer',
            'note' => 'nullable|max:100',
            'is_confirm_agree' => 'required|in:0,1',
            'account_name' => 'required|max:100',
            'account_bank' => 'required|max:100',
            'bank_id' => 'required|max:36|exists:utl_banks,id',
        ]);
        $totalOrder= TrxOrder::where('class_id',$validated['class_id'])->where('status', 'paid')->sum('quantity');
        $class     = TrxClass::select('price', 'is_discount', 'price_discount', 'max_participant')->find($validated['class_id']);

        if($totalOrder >= $class->max_participant){
            throw \Illuminate\Validation\ValidationException::withMessages([
                'quantity' => ['The quantity is over the max participant'],
             ]);
        }

        $validated['price']    = $class->price * $validated['quantity'];
        $validated['discount'] = $class->is_discount ? $class->price_discount * $validated['quantity'] : 0;
        $validated['total_price']= $validated['price'] - $validated['discount'];
        $validated['user_id']    = @$request->user()->id;
        $validated['trx_date']   = now()->toDateTimeString();
        $validated['created_by'] = auth()->id();

        try {
            $order = DB::transaction(function () use($validated, $participant){
                $order = TrxOrder::create($validated);
                $participant['participant']['trx_order_id'] = $order->id;
                TrxParticipant::create($participant['participant']);
                return $order;
            });
            $order = TrxOrder::withEvent()->find($order->id);
            
            Mail::to(@$participant['participant']['email'])
                ->send(new TrainingPayOrder($order));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->json($order, 201);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function free(Request $request)
    {
        $participant = $request->validate([
            'participant.name' => 'required|max:100',
            'participant.email' => 'required|max:100|email',
            'participant.phone' => 'required|max:100',
        ]);

        $validated = $request->validate([
            'schedule_id' => 'required|exists:tre_trx_schedules,id',
            'class_id' => 'required|max:36|exists:tre_trx_classes,id',
            'quantity' => 'required|integer|max:1',
            'note' => 'nullable|max:100',
            'position'=> 'required|max:100',
            'known_from'=> 'required|max:100',
            'tf_upload' => 'required|image|max:1024',
            'is_confirm_agree' => 'required',
        ]);
        $totalOrder= TrxOrder::where('class_id',$validated['class_id'])->where('status', 'paid')->sum('quantity');
        $class     = TrxClass::select('price', 'is_discount', 'price_discount', 'max_participant')->find($validated['class_id']);

        if($totalOrder >= $class->max_participant){
            throw \Illuminate\Validation\ValidationException::withMessages([
                'quantity' => ['The quantity is over the max participant'],
             ]);
        }

        if($class->price>0){
            throw \Illuminate\Validation\ValidationException::withMessages([
                'quantity' => ['Invalid training or event data!'],
            ]);
        }

        $validated['tf_upload']= $request->hasFile('tf_upload') ? $request->tf_upload->store('private/training/order', 'oss') : null;
        $validated['price']    = $class->price * $validated['quantity'];
        $validated['discount'] = $class->is_discount ? $class->price_discount * $validated['quantity'] : 0;
        $validated['total_price']= $validated['price'] - $validated['discount'];
        $validated['user_id']    = @$request->user()->id;
        $validated['trx_date']   = now()->toDateTimeString();
        $validated['status']     = 'paid';
        $validated['created_by'] = auth()->id();

        try {
            $order = DB::transaction(function () use($validated, $participant){
                $order = TrxOrder::create($validated);
                $participant['participant']['trx_order_id'] = $order->id;
                TrxParticipant::create($participant['participant']);
                return $order;
            });
            $order = TrxOrder::withEvent()->with('schedule:id,date,end_date,start_hour,end_hour')->find($order->id);

            Mail::to(@$participant['participant']['email'])
                ->send(new TrainingSuccess($order));
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = TrxOrder::withEvent()->selectRaw('position,known_from,participant.*')->leftJoin(
            DB::raw("(select trx_order_id,name,email,phone from tre_trx_order_participants group by trx_order_id) participant"), 'participant.trx_order_id', 'tre_trx_orders.id')->findOrFail($id);
        
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tf_upload' => 'required|image',
        ]);
        $order = TrxOrder::findOrFail($id);
        $validated['tf_upload'] = $request->file('tf_upload')->store('private/training/payment/'.$order->id, 'oss');
        $order->update($validated);

        return response()->json($order, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            TrxOrder::destroy($id);
            TrxParticipant::where('trx_order_id', $id)->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }

        return response()->noContent();
    }
}
