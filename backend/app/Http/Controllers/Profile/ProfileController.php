<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\NotifHelper;
use App\Http\Controllers\Controller;
use App\Models\Profiles\PhoneCode;
use App\Models\Profiles\State;
use App\Models\Profiles\UserCertificate;
use App\Models\Profiles\UserEducation;
use App\Models\Profiles\UserEmail;
use App\Models\Profiles\UserExperience;
use App\Models\Profiles\UserSocial;
use App\Models\Threads\Thread;
use App\Models\Consults\Thread as Consultation;
use App\Models\User;
use App\Notifications\PasswordChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function profile($id=null){
        $user    = $id ? User::findOrFail($id) : auth()->user();
        $profile = $user->profile()
        ->selectRaw('id,user_id,phone,company_name,skills,industry_id,photo,position_id,other_position,city_id,birthdate,birthplace,link,postal_code')->first();
        if(!$profile){
            return response()->json( $user);
        }
        try {
            $profile->position_id  = $profile->position()->selectRaw('id,name')->first();
            $profile->industry_id  = $profile->industry()->selectRaw('id,name')->first();
            $profile->city_id  = $profile->city()->selectRaw('id,name,state_id')->first();
            $profile->state_id = State::selectRaw('id, name, phone_code_id')->find(@$profile->city_id->state_id);
            $profile->country_id = PhoneCode::selectRaw('id, name')->find(@$profile->state_id->phone_code_id);
            $profile->email   = $user->email;
            $profile->name    = $user->name;
            $profile->is_consultant = $user->is_consultant;
            $profile->is_active = $user->is_active;
            if($user->is_consultant=='1'){
                $profile->category=  $user->category_consultant_id;
                $profile->category_name= @$user->category->name;
            }
            $profile->socials = $user->socials()->selectRaw('com_user_socials.id, url ,name, icon')->get();
   
            $profile->experiences= $user->experiences()->selectRaw('id,user_id,company,position,start_date,end_date')->get();
            $profile->educations = $user->educations()->get();
            $profile->certificates = $user->certificates()->get();
       
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json($profile);
    }

    public function threads(){
        $id= request()->id ? request()->id : auth()->id();
        $data = Thread::query()
                ->selectRaw("hom_threads.id, 
                hom_threads.title,
                hom_threads.slug_id,
                hom_threads.description,
                hom_threads.user_id,
                hom_threads.created_at")
                ->with('types:thread_id,name,color', 'smallloves:thread_id,hom_love_threads.user_id,name,photo', 'pollings.details', 'images', 'files','author:id,name,photo')
                ->withCountData()
                ->withLoveStatus()
                ->withBookmarkStatus()
                ->withDismisStatus()
                ->limitDescription(235)
                ->where('user_id', $id)
                ->orderBy('hom_threads.created_at', 'DESC')
                ->paging(10);

        return response()->json($data);
    }

    public function liked(){
        $data = Thread::query()
                ->selectRaw("hom_threads.id, 
                hom_threads.title,
                hom_threads.slug_id,
                hom_threads.description,
                hom_threads.user_id,
                hom_threads.created_at")
                ->with('types:thread_id,name,color', 'smallloves:thread_id,hom_love_threads.user_id,name,photo', 'pollings.details', 'images', 'files','author:id,name,photo')
                ->withCountData()
                ->withLoveStatus()
                ->withBookmarkStatus()
                ->withDismisStatus()
                ->limitDescription(235)
                ->where('love_status', 1)
                ->orderBy('hom_threads.created_at', 'DESC')
                ->paging(10);

        return response()->json($data);
    }

    public function bookmarked(){
        $data = Thread::query()
        ->selectRaw("hom_threads.id, 
        hom_threads.title,
        hom_threads.slug_id,
        hom_threads.description,
        hom_threads.user_id,
        hom_threads.created_at")
        ->with('types:thread_id,name,color', 'smallloves:thread_id,hom_love_threads.user_id,name,photo', 'pollings.details', 'images', 'files','author:id,name,photo')
        ->withCountData()
        ->withLoveStatus()
        ->withBookmarkStatus()
        ->withDismisStatus()
        ->limitDescription(235)
        ->where('bookmark_status', 1)
        ->orderBy('hom_threads.created_at', 'DESC')
        ->paging(10);

        return response()->json($data);
    }

    public function consultation(){
        $id = request()->id ? request()->id : auth()->id();
        $data = Consultation::query()
        ->selectRaw("con_consul_threads.id, 
        slug_id,
        title,
        description,
        is_private,
        user_id,
        category_id,
        con_consul_threads.created_at")
        ->with('types:id,name,code,color,color_bg', 'smallloves:consul_thread_id,con_love_threads.user_id,name,photo', 'images', 'files','author')
        ->withCountData()
        ->withLoveStatus()
        ->limitDescription(235)
        ->where('user_id', $id)
        ->orderBy('con_consul_threads.created_at', 'DESC')
        ->paging(10);

        return response()->json($data);
    }

    public function comment(){
        $id = request()->id ? request()->id : auth()->id();
        $data = Thread::query()
        ->selectRaw("hom_threads.id, 
        hom_threads.title,
        hom_threads.slug_id,
        hom_threads.description,
        hom_threads.user_id,
        hom_threads.created_at")
        ->with('types:thread_id,name,color', 'smallloves:thread_id,hom_love_threads.user_id,name,photo', 'pollings.details', 'images', 'files','author:id,name,photo')
        ->withCountData()
        ->withLoveStatus()
        ->withBookmarkStatus()
        ->withDismisStatus()
        ->withCommentStatus($id)
        ->limitDescription(235)
        ->where('comment_status', 1)
        ->orderBy('hom_threads.created_at', 'DESC')
        ->paging(10);

        return response()->json($data);
    }

    public function notification(Request $request){
        $data = UserEmail::query()->selectRaw('id,user_id,is_email,is_newsletter')->where('user_id', auth()->id())->first();

        return response()->json($data);
    }

    public function updateProfile(Request $request){
        if($request->socials){
            try {
                $socials = [];
                foreach($request->socials as $social){
                    if($social['url']=='undefined'){
                        UserSocial::where('social_media_id', $social['social_media_id'])
                            ->where('user_id', auth()->id())->delete();
                    }else{
                        $userSocial = UserSocial::updateOrCreate(
                            ['user_id'=>auth()->id(), 'social_media_id'=>$social['social_media_id']],
                            array_merge($social, ['user_id'=>auth()->id(), 'created_by'=>auth()->id()])
                        );//update social media

                        $socials[]=$userSocial->id;
                    }
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status'=>'Error',
                    'message'=>$th->getMessage()
                ], 500);
            }

            return response()->json($socials);
        }
     
        if($request->experiences){
            $experiences = [];
            if($request->experiences=='null'){
                $request->experiences = [];
            }
           foreach($request->experiences as $exp){
            $exp['id']      = Str::uuid()->toString();
            $exp['user_id'] = auth()->id();
            $exp['is_active']='1';
            $experiences[]=$exp;
           }

           UserExperience::where('user_id', auth()->id())->delete();
           UserExperience::insert($experiences);

           return response()->json(UserExperience::select('id')->where('user_id', auth()->id())->pluck('id'));
        }

        if($request->educations){
           $educations = [];
           if($request->educations=='null'){
                $request->educations = [];
           }
           foreach($request->educations as $edu){
            $edu['id']      = Str::uuid()->toString();
            $edu['user_id'] = auth()->id();
            $educations[]=$edu;
           }

           UserEducation::where('user_id', auth()->id())->delete();
           UserEducation::insert($educations);

           return response()->json(UserEducation::select('id')->where('user_id', auth()->id())->pluck('id'));
        }

        if($request->certificates){
            $certificates = [];
            if($request->certificates=='null'){
                $request->certificates = [];
            }
           foreach($request->certificates as $cert){
            $cert['id']      = Str::uuid()->toString();
            $cert['user_id'] = auth()->id();
            $certificates[]=$cert;
           }

           UserCertificate::where('user_id', auth()->id())->delete();
           UserCertificate::insert($certificates);

           return response()->json(UserCertificate::select('id')->where('user_id', auth()->id())->pluck('id'));
        }

        if($request->skills){
            if($request->skills=='null'){
                $request->skills = [];
            }
            auth()->user()->profile()->update(['skills'=>$request->skills]);

            return response()->json($request->skills);
        }

        $validated = $request->validate([
            'phone' => 'required',
            'company_name' => 'required|string|max:255',
            'industry_id' => 'required',
            'position_id' => 'required',
            'about_me'=> 'nullable',
            'other_position'=> 'nullable',
            'city_id' => 'required',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        if($request->hasFile('photo')){
           $request->validate(['photo'=>'image|max:5048']);
           $validated['photo'] = $request->file('photo')->store('public/profile');
        }

        auth()->user()->update(['name'=>$request->name]);//update name
        auth()->user()->profile()->update($validated);//update profile

        return response()->json(auth()->user()->profile()->first());
    }

    public function updatePassword(Request $request){
        if(!Hash::check($request->old_password, auth()->user()->password)){
            throw ValidationException::withMessages([
                'old_password' => ['The old password is incorrect.']
            ]);
        }

        $request->validate([
            'new_password' => ['required', Rules\Password::defaults(), 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed'],
        ]);

        auth()->user()->update(['password'=>Hash::make($request->new_password)]);

        (new NotifHelper)->to(auth()->user())->notify(new PasswordChange([
            'detail_id'=> auth()->id(),
            'path'     => null,
            'message'  =>"Password anda berhasil diganti, silahkan login ulang!"
        ]));

        return response()->noContent();
    }

    public function updateNotification(Request $request){
        $notification = UserEmail::updateOrCreate(
           ['user_id'=>auth()->id()],
           [
            'user_id'=>auth()->id(), 
            'is_email'=>$request->is_email ? '1' : '0', 
            'is_newsletter'=>$request->is_newsletter ? '1': '0', 
            'created_by'=>auth()->id()
          ]);//update social media

        return response()->json($notification);
    }
}
