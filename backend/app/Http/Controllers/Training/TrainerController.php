<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Profiles\UserSocial;
use App\Models\Training\Trainer;
use App\Models\Training\TrainerCertificate;
use App\Models\Training\TrainerDetail;
use App\Models\Training\TrainerEducation;
use App\Models\Training\TrainerProvider;
use App\Models\Training\TrxEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainerController extends Controller
{
    public function index(Request $request)
    {
        if($request->headers->get('Origin')==config('app.admin_url')){
            $data = Trainer::select('tre_trainers.id', 'name', 'tre_trainers.description', 'photo', 'company', 'industry_name', 'tre_trainers.is_active')
                    ->leftJoin(DB::raw("(select tre_trainer_details.*, com_industries.name industry_name from tre_trainer_details join com_industries on com_industries.id=tre_trainer_details.industry_id Group By trainer_id) details"), 'details.trainer_id', 'tre_trainers.id')
                    ->when($request->status, function($query) use($request){
                        $query->where('tre_trainers.is_active', '1');
                    })
                    ->orderable($request->orders)
                    ->searchable($request->columns, $request->search);
            return response()->json($data->paging($request->perpage??10));
        }
        
        $data = Trainer::selectRaw('tre_trainers.id,name,photo')
                ->when($request->provider_id, function($query) use($request){
                    $query->selectRaw('provider_id')
                        ->leftJoin('tre_train_providers', 'tre_train_providers.trainer_id','tre_trainers.id')
                        ->where('provider_id', $request->provider_id);
                })
                ->when($request->has('search'), function($query) use($request){
                    $query->where('name', 'LIKE', "%$request->search%");
                })
                ->where('tre_trainers.is_active', '1');

                switch($request->sort){
                    case 'Z to A':
                        $data->orderBy('name', 'desc');
                        break;
                    case 'Newest':
                        $data->orderBy('created_at', 'desc');
                        break;
                    default:
                        $data->orderBy('name', 'asc');
                        break;
                 }
  
        return response()->json($data->paging($request->perpage??10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|max:36|unique:tre_trainers,code',
            'name' => 'required|max:100',
            'motto'=> 'nullable|max:200',
            'description' => 'nullable',
            'photo' => 'required|image|max:5120',
            'num' => 'nullable',
            'skills'=> 'nullable',
            'is_active' => 'required',
        ]);
    
        $validated['photo'] = $request->hasFile('photo') ? $request->file('photo')->store('public/trainer', 'oss') : '';//upload foto
        $validated['skills']= json_decode($request->skills??'');
        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request['is_active'] ? '1' : '0';

        // try {
            $trainer = DB::transaction(function () use($request, $validated){
                $educations = [];
                $experiences= [];
                $providers  = [];
                $socials    = [];
                $certificates=[];

                $trainer = new Trainer();
                $trainer->fill($validated);
                $trainer->save();

                foreach(json_decode($request->educations)??[] as $education){   
                    $education = (object)$education;             
                    $de_id = @$education->title_degree_id;
                    $un_id = @$education->university_id;
                    $mj_id = @$education->major_id;

                    $educations[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        $de_id ? 'title_degree_id' : 'other_title'=> $de_id ?  $de_id : $education->other_title,
                        $un_id  ? 'university_id' : 'other_university'=> $un_id  ? $un_id  : $education->other_university,
                        $mj_id ? 'major_id' : 'other_major'=> $mj_id ? $mj_id : $education->other_major,
                        'is_current'        => '0',
                        'start_date'        => $education->start_date,
                        'end_date'          => $education->end_date,
                        'created_by'        => auth()->id(),
                        'created_at'=> now()->toDateTimeString(),
                    ];
                }
            
                foreach(json_decode($request->experiences)??[] as $experience){  
                    $experience = (object)$experience;

                    $experiences[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        'industry_id' => @$experience->industry_id,
                        'city_id' => $experience->city_id,
                        'company' => $experience->company,
                        'position' => @$experience->position,
                        'description' => @$experience->description,
                        'is_current' => '0',
                        'start_date' => $experience->start_date,
                        'end_date' => $experience->end_date,
                        'created_by' => auth()->id(),
                        'created_at'=> now()->toDateTimeString(),
                    ];
                }

                foreach (json_decode($request->providers)??[] as $provider) {
                    $provider = (object) $provider;
 
                    $providers[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        'provider_id' => $provider->trainer_id,
                        'is_active' => '1',
                        'created_by' => auth()->id(),
                        'created_at'=> now()->toDateTimeString(),
                    ];
                }

                foreach (json_decode($request->socials)??[] as $social){
                    $social = (object)$social;
                    $socials[] = [
                        'id' => Str::uuid()->toString(),
                        'user_id'=> $trainer->id,
                        'social_media_id'=> $social->social_media_id,
                        'url' => $social->url,
                    ];
                }

                foreach(json_decode($request->certificates)??[] as $cert){
                    $cert = (object)$cert;
                    $certificates[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        'title' => $cert->title,
                        'organization'=> $cert->organization,
                        'link'=> $cert->link,
                        'is_novalidate'=> $cert->is_novalidate
                    ];
                }

                TrainerEducation::insert($educations);
                TrainerDetail::insert($experiences);
                TrainerProvider::insert($providers);
                UserSocial::insert($socials);
                TrainerCertificate::insert($certificates);
                return $trainer;
            });

            return response()->json($trainer, 201);
        // } catch (\Throwable $th) {
        //     Storage::delete($validated['photo']);
        //     return response()->json(['message' => $th->getMessage()], 500);
        // }
    }

    public function show($id)
    {
        $data = Trainer::with('experiences', 'educations', 'providers', 'socials:id,user_id,social_media_id,url,icon', 'certificates')->findOrFail($id);

        return response()->json($data);
    }

    public function course(Request $request, $id){
        $data= TrxEvent::selectRaw('tre_trx_train_events.id,slug_id,title,type,provider_id,location,level,type_question_id,banner_card')
                          ->with('provider:id,name,logo', 'category:id,name,color')
                          ->withClass()
                          ->leftJoin('tre_trx_trainers', 'tre_trx_trainers.trx_train_event_id', '=', 'tre_trx_train_events.id')
                          ->where('trainer_id', $id)
                          ->orderBy('classes.date', 'asc')
                          ->limit(3)
                          ->get();

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|max:36|unique:tre_trainers,code,'.$id,
            'name' => 'required|max:100',
            'motto'=> 'nullable|max:200',
            'description' => 'nullable',
            'photo' => 'nullable|image|max:5120',
            'skills'=> 'nullable',
            // 'is_list_prior' => 'required',
            'num' => 'nullable',
            'is_active' => 'required',
        ]);

        $trainer = Trainer::find($id);

        $validated['photo'] = $request->hasFile('photo') ? $request->file('photo')->store('public/trainer', 'oss') : $trainer->photo;//upload foto
        $validated['skills']= json_decode($request->skills??[]);
        $validated['is_active'] = $request['is_active'] ? '1' : '0';
        
        // try {
            DB::transaction(function () use($request, $validated, $trainer){
                $educations = [];
                $experiences= [];
                $providers  = [];
                $socials    = [];
                $certificates=[];

                $trainer->fill($validated);
                $trainer->save();

                foreach(json_decode($request->educations)??[] as $education){   
                    $education = (object)$education;             
                    $de_id = is_string(@$education->title_degree_id) ? (object)['id'=>@$education->title_degree_id] : @$education->title_degree_id;
                    $un_id = is_string(@$education->university_id) ? (object)['id'=>@$education->university_id] : @$education->university_id;
                    $mj_id = is_string(@$education->major_id) ? (object)['id'=>@$education->major_id] : @$education->major_id;

                    $educations[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        $de_id ? 'title_degree_id' : 'other_title'=> $de_id ?  $de_id->id : $education->other_title,
                        $un_id  ? 'university_id' : 'other_university'=> $un_id  ? $un_id->id  : $education->other_university,
                        $mj_id ? 'major_id' : 'other_major'=> $mj_id ? @$mj_id->id : $education->other_major,
                        'is_current'        => '0',
                        'start_date'        => $education->start_date,
                        'end_date'          => $education->end_date,
                        'created_by'        => auth()->id(),
                        'created_at'=> now()->toDateTimeString(),
                    ];
                }

                foreach(json_decode($request->experiences)??[] as $experience){  
                    $experience = (object)$experience;
                    $experiences[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        'industry_id' => @$experience->industry_id,
                        'city_id' => $experience->city_id,
                        'company' => $experience->company,
                        'position' => $experience->position,
                        'description' => @$experience->description,
                        'is_current' => '0',
                        'start_date' => $experience->start_date,
                        'end_date' => $experience->end_date,
                        'created_by' => auth()->id(),
                        'created_at'=> now()->toDateTimeString(),
                    ];
                }

                foreach (json_decode($request->providers)??[] as $provider) {
                    $provider = (object)$provider;
                    $providers[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        'provider_id' => $provider->id,
                        'is_active' => '1',
                        'created_by' => auth()->id(),
                        'created_at'=> now()->toDateTimeString(),
                    ];
                }

                foreach (json_decode($request->socials)??[] as $social){
                    $social = (object)$social;
                    $socials[] = [
                        'id' => Str::uuid()->toString(),
                        'user_id'=> $trainer->id,
                        'social_media_id'=> $social->social_media_id,
                        'url' => $social->url,
                    ];
                }

                foreach(json_decode($request->certificates)??[] as $cert){
                    $cert = (object)$cert;
                    $certificates[] = [
                        'id' => Str::uuid()->toString(),
                        'trainer_id' => $trainer->id,
                        'title' => $cert->title,
                        'organization'=> $cert->organization,
                        'link'=> $cert->link,
                        'is_novalidate'=> $cert->is_novalidate
                    ];
                }

                TrainerEducation::where('trainer_id', $trainer->id)->delete();
                TrainerDetail::where('trainer_id', $trainer->id)->delete();
                TrainerProvider::where('trainer_id', $trainer->id)->delete();
                TrainerCertificate::where('trainer_id', $trainer->id)->delete();
                UserSocial::where('user_id', $trainer->id)->delete();
                TrainerEducation::insert($educations);
                TrainerDetail::insert($experiences);
                TrainerProvider::insert($providers);
                UserSocial::insert($socials);
                TrainerCertificate::insert($certificates);
            });

            return response()->json($trainer, 200);
        // } catch (\Throwable $th) {
        //     Storage::delete($validated['photo']);
        //     return response()->json(['message' => $th->getMessage()], 500);
        // }
    }

    public function destroy(Request $request)
    {
        try {
            Trainer::whereIn('id', $request->all())->delete();
            return response()->noContent();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
        return response()->noContent();
    }
}
