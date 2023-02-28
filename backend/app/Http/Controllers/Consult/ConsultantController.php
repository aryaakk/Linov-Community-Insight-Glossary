<?php

namespace App\Http\Controllers\Consult;

use App\Helpers\NotifHelper;
use App\Http\Controllers\Controller;
use App\Models\Consults\Category;
use App\Models\Profiles\Profile;
use App\Models\Profiles\TrxAttachment;
use App\Models\Profiles\TrxCategory;
use App\Models\Profiles\TrxConsultant;
use App\Models\Profiles\UserCertificate;
use App\Models\Profiles\UserEducation;
use App\Models\Profiles\UserExperience;
use App\Models\Profiles\UserSocial;
use App\Models\User;
use App\Notifications\VerifyConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = User::selectRaw('id,name,is_consultant,category_consultant_id')
        ->with('profile:id,user_id,photo', 'category:id,code,name')
        ->where('is_consultant', 1)
        ->when($request->has('recomended'),function($query){
            $query->orderByRaw('RAND()');
        })->when($request->has('filter'), function($query) use($request){
            $query->whereIn('category_consultant_id', $request->filter);
        })->when($request->has('search'), function($query) use($request){
            $query->where('name','like', '%'.$request->search.'%');
        });

        if($request->perpage){
            switch($request->sort){
                case 'Z to A':
                    $data->orderBy('name', 'desc');
                    break;
                case 'Newest':
                    $data->orderBy('created_at', 'desc');
                    break;
                case 'A to Z':
                    $data->orderBy('name', 'asc');
                    break;
            }
            return response()->json($data->paging($request->perpage));
        }
        return response()->json($data->get());
    }

     /**
     * Save submission data for user
     */
    public function submit(Request $request){
        
        $request->validate([
            'reason'     => 'required',
            'category'   => 'required',
            'upfiles.cv' => "required_if:is_cv,1|mimes:pdf,png,jpg,jpeg|max:2048",
            'upfiles.ktp'=> "required_if:is_cv,0|mimes:pdf,png,jpg,jpeg|max:2048",
            'upfiles.ijazah'=> 'required_if:is_cv,0|mimes:pdf,png,jpg,jpeg|max:2048',
            'upfiles.foto'=> 'required_if:is_cv,0|mimes:png,jpg,jpeg|max:2048'
        ]);
        $files =[];
        $fileCat = ['cv'=>1, 'ktp'=>2, 'ijazah'=>3, 'foto'=>4];

        if($request->hasFile('upfiles')){
            foreach($request->upfiles as $idx=>$file){
                if($file=="null") continue;
                $fileName= $file->store("private/trx-consultant/$idx", "oss");
                $files[] = [
                    'id' => Str::uuid()->toString(),
                    'category'    => $fileCat[$idx],
                    'file_attach' => $fileName,
                    'file_type'   => $file->extension(),
                    'is_success'  => '1',
                    'created_by'  => auth()->id(),
                    'created_at'  => date('Y-m-d H:i:s')
                ];
            }
        }

        try {
        $consultant = DB::transaction(function () use($request, $files){
            $categories = [];

            $consultant = TrxConsultant::create([
                'trx_code'  =>'trx-consult',
                'trx_date'  => now()->toDateString(),
                'user_id'   => auth()->id(),
                'status'    => 'waiting',
                'is_cv'     => $request->is_cv,
                'reason'    => $request->reason,
                'is_active' =>'1',
                'created_by'=> auth()->id(),
            ]);

            foreach(json_decode($request->category)??[] as $category){
                $category = (object) $category;
                $categories[] = [
                    'id' => Str::uuid()->toString(),
                    'trx_consultant_id' => @$consultant->id,
                    'category_consultant_id' => $category->id,
                    'created_by' => auth()->id(),
                    'created_at' => now()->toDateTimeString()
                ];
            }

            foreach ($files as $key => $value) {
                $files[$key]['trx_consultant_id'] = $consultant->id;
            }

            TrxCategory::insert($categories);

            TrxAttachment::insert($files);

            return $consultant;

        });
        } catch (\Throwable $th) {
            return response()->json([$th->getMessage()], 200);
        }

        return response()->json($consultant);
    
    }

    /**
     * Get One Submission data
     */
    public function submited($id){
        $data = TrxConsultant::selectRaw('con_trx_consultants.id,con_trx_consultants.user_id,trx_date,reason,status,name,email,phone,postal_code,skills,region.*')   
                ->with('categories','experiences','educations','certificates','attachments')
                ->withProfile() 
                ->leftJoin('com_users', 'com_users.id', 'con_trx_consultants.user_id')
                ->findOrFail($id);

        return response()->json($data);
    }

        /**
     * Save submission data for user
     */
    public function verify(Request $request, $id){
        $trx = TrxConsultant::find($id);
        $trx->update(['status'=>$request->status]);
        if($trx->status=='approved'){
            $user = User::find($trx->user_id);
            $user->is_consultant = '1';
            $user->save();
            (new NotifHelper)->to($user)->notify(new VerifyConsultant([
                'detail_id'=> $id,
                'path'     => "/profile",
                'message'  =>"<b>Selamat</b> pengajuan sebagai konsultan telah di setujui."
            ]));
        }
        return response()->json($trx);
    }

    /**
     * Submission data for user
     */
    public function submission(Request $request){
        $data = TrxConsultant::selectRaw('id,trx_date,reason,status')    
                ->where('user_id', auth()->id())
                ->paging($request->perpage??10);

        return response()->json($data);
    }

    /**
     * Submission data for admin
     */
    public function submissions(Request $request){
        $data = TrxConsultant::selectRaw('con_trx_consultants.id,trx_date,reason,status,name,email')    
                ->leftJoin('com_users', 'com_users.id', 'con_trx_consultants.user_id')
                ->paging($request->perpage??10);

        return response()->json($data);
    }

    /**
     * index.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $data = User::selectRaw('id,name,email,is_consultant,category_consultant_id,is_active')
        ->with('profile:id,user_id,photo,company_name,other_position', 'category:id,code,name')
        ->where('is_consultant', 1)
        ->searchable($request->columns, $request->search)
        ->orderable($request->orders)
        ->paging($request->perpage);

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
        $validated = $request->validate([
            'email'=> 'required|max:36|unique:com_users,email',
            'name' => 'required|max:100',
            'is_active'=>'required',
            'phone' => 'required',
            'category'=>'required',
            'about_me'=> 'nullable',
            'city_id' => 'required',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        try {
            $user = DB::transaction(function() use($validated, $request){
                $user = User::create([
                    'role_id' => Str::uuid()->toString(),
                    'name'    => $validated['name'],
                    'email'   => $validated['email'],
                    'login_by'=> 'konven',
                    'fb_token'=> '',
                    'is_verify'   => '0',
                    'is_active'   => '0',
                    'is_consultant'=>'1',
                    'category_consultant_id'=>$request->category,
                    'created_by' => auth()->id(),
                    'token_linov' => Str::random(255),
                ]);
                $profile = collect($validated)->except('name', 'email', 'is_active','category')->toArray();
                $profile = Profile::create(array_merge($profile, [
                    'user_id'=>$user->id,
                    'created_by'=>auth()->id(),
                ]));
                $experiences = [];
                foreach($request->experiences??[] as $exp){
                    unset($exp['city_id']);
                    $exp['id']      = Str::uuid()->toString();
                    $exp['user_id'] = $user->id;
                    $exp['is_active']='1';
                    $experiences[]=$exp;
                }
                $educations=[];
                foreach($request->educations??[] as $edu){
                    $edu['id']      = Str::uuid()->toString();
                    $edu['user_id'] = $user->id;
                    $edu['is_current'] =  @$edu['is_current'] ? '1' : '0';
                    $educations[]=$edu;
                }
                $certificates = [];
                foreach($request->certificates??[] as $cert){
                    $cert['id']      = Str::uuid()->toString();
                    $cert['user_id'] = $user->id;
                    $certificates[]=$cert;
                }
                foreach($request->socials??[] as $social){
                    UserSocial::updateOrCreate(
                        ['user_id'=>$user->id, 'social_media_id'=>$social['social_media_id']],
                        array_merge($social, ['user_id'=>$user->id, 'created_by'=>auth()->id()])
                    );//update social media
                }
                $profile->update(['skills'=>$request->skills]);
                UserExperience::insert($experiences);
                UserEducation::insert($educations);
                UserCertificate::insert($certificates);

                $status = Password::sendResetLink($request->only('email'));
                
                if ($status != Password::RESET_LINK_SENT) {
                    throw ValidationException::withMessages([
                        'email' => [__($status)],
                    ]);
                }
                return $user;
            });
        } catch (\Throwable $th) {
           return response()->json(['status'=>'Fail!', 'message'=>$th->getMessage()], 500);
        }

        return response()->json($user, 201);
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
            'email'=> 'required|max:36|unique:com_users,email,'.$id,
            'name' => 'required|max:100',
            'is_active'=>'required',
            'phone' => 'required',
            'category'=>'required',
            'about_me'=> 'nullable',
            'city_id' => 'required',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        // try {
            $user = DB::transaction(function() use($validated, $id, $request){
                $user = User::find($id);
                $user->update([
                    'name'    => $validated['name'],
                    'email'   => $validated['email'],
                    'is_active'=> $validated['is_active'],
                    'category_consultant_id'=>$request->category,
                ]);
                $profile = collect($validated)->except('name', 'email', 'is_active','category')->toArray();
                $profile = Profile::where('user_id', $id)->update(array_merge($profile, ['skills'=>$request->skills]));
                $experiences = [];
                foreach($request->experiences??[] as $exp){
                    unset($exp['city_id']);
                    $exp['id']      = Str::uuid()->toString();
                    $exp['user_id'] = $user->id;
                    $exp['is_active']='1';
                    $experiences[]=$exp;
                }
                $educations=[];
                foreach($request->educations??[] as $edu){
                    unset($edu['degree_name']);
                    unset($edu['major_name']);
                    unset($edu['university_name']);
                    $edu['id']      = Str::uuid()->toString();
                    $edu['user_id'] = $user->id;
                    $edu['is_current'] =  @$edu['is_current'] ? '1' : '0';
                    $educations[]=$edu;
                }
                $certificates = [];
                foreach($request->certificates??[] as $cert){
                    $cert['id']      = Str::uuid()->toString();
                    $cert['user_id'] = $user->id;
                    $certificates[]=$cert;
                }
                $socials =[];
                foreach($request->socials??[] as $social){
                    $social['id']      = Str::uuid()->toString();
                    $social['user_id'] = $user->id;
                    $socials[]=$social;
                }
                UserSocial::where('user_id', $id)->delete();
                UserExperience::where('user_id', $id)->delete();
                UserEducation::where('user_id', $id)->delete();
                UserCertificate::where('user_id', $id)->delete();

                UserSocial::insert($socials);
                UserExperience::insert($experiences);
                UserEducation::insert($educations);
                UserCertificate::insert($certificates);

                return $user;
            });
        // } catch (\Throwable $th) {
        //    return response()->json(['status'=>'Fail!', 'message'=>$th->getMessage()]);
        // }

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
