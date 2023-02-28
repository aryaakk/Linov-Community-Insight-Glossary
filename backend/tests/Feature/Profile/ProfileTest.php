<?php

namespace Tests\Feature\Profile;

use App\Models\Consults\Category;
use App\Models\Threads\Love;
use App\Models\Profiles\Profile;
use App\Models\Profiles\Skill;
use App\Models\Profiles\TrxConsultant;
use App\Models\Profiles\TrxEducation;
use App\Models\Profiles\TrxExperience;
use App\Models\Profiles\UserCertificate;
use App\Models\Profiles\UserEducation;
use App\Models\Profiles\UserEmail;
use App\Models\Profiles\UserExperience;
use App\Models\Profiles\UserSocial;
use App\Models\Threads\Bookmark;
use App\Models\Threads\Thread;
use App\Models\Consults\Thread as Consultation;
use App\Models\Threads\Comment;
use App\Models\Threads\TypeQuestion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        Storage::disk('oss')->deleteDirectory('private/trx-consultant');
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_profile()
    {
        Profile::factory()->create([
            'user_id'=>$this->user->id
        ]);

        $response = $this->actingAs($this->user)->get("/api/profile/user");

        $response->assertStatus(200)
                ->assertJson([
                    'user_id'=>$this->user->id
                ]);
    }

    public function test_user_can_get_profile_thread()
    {
        $threads = Thread::factory()->count(5)->create(['user_id'=>$this->user->id]);
        foreach ($threads as $key => $thread) {
            TypeQuestion::factory()->create([
                'thread_id' => $thread->id,
            ]);
        }

        $response = $this->actingAs($this->user)->get('/api/profile/threads');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5);

    }

    public function test_user_can_get_profile_liked()
    {
        $threads = Thread::factory()->count(5)->create(['user_id'=>$this->user->id]);
        foreach ($threads as $key => $thread) {
            TypeQuestion::factory()->create([
                'thread_id' => $thread->id,
            ]);
            Love::factory()->create([
                'thread_id' => $thread->id,
                'user_id' => $this->user->id
            ]);
        }

        $response = $this->actingAs($this->user)->get('/api/profile/like');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5);
    }

    public function test_user_can_get_profile_bookmarked()
    {
        $threads = Thread::factory()->count(5)->create(['user_id'=>$this->user->id]);
        foreach ($threads as $key => $thread) {
            TypeQuestion::factory()->create([
                'thread_id' => $thread->id,
            ]);
            Bookmark::factory()->create([
                'thread_id' => $thread->id,
                'user_id' => $this->user->id
            ]);
        }

        $response = $this->actingAs($this->user)->get('/api/profile/bookmark');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5);
    }

    public function test_user_can_get_profile_comment()
    {
        $threads = Thread::factory()->count(5)->create(['user_id'=>$this->user->id]);
        foreach ($threads as $key => $thread) {
            Comment::factory()->create([
                'thread_id' => $thread->id,
                'user_id' => $this->user->id
            ]);
        }

        $response = $this->actingAs($this->user)->get('/api/profile/comment');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5);
    }

    public function test_user_can_get_profile_consultation()
    {
        Consultation::factory()->count(5)->create(['user_id'=>$this->user->id]);

        $response = $this->actingAs($this->user)->get('/api/profile/consultation');

        $response->assertStatus(200)
                ->assertJsonPath('total', 5);
    }

    public function test_user_can_get_profile_notification()
    {
        UserEmail::factory()->create(['user_id'=>$this->user->id]);

        $response = $this->actingAs($this->user)->get("/api/profile/notification");

        $response->assertStatus(200)
                ->assertJson([
                    'user_id'=>$this->user->id
                ]);
    }

    public function test_user_can_update_notification()
    {
        $notification = UserEmail::factory()->make(['user_id'=>$this->user->id]);
 
        $response = $this->actingAs($this->user)->postJson("/api/profile/notification", ['is_email'=>$notification->is_email, 'is_newsletter'=>$notification->is_newsletter]);

        $response->assertStatus(200)
                ->assertJson([
                    'user_id'=>$this->user->id,
                    'is_email'=>$notification->is_email,
                    'is_newsletter'=>$notification->is_newsletter,
                ]);
    }

    public function test_user_can_update_password()
    {
        $newPassword = 'New_password123';
        $this->user->password=Hash::make('password');
        $this->user->save();

        $response = $this->actingAs($this->user)->postJson("/api/profile/password",[
            'old_password'=>'password',
            'new_password'=> $newPassword,
            'new_password_confirmation'=>$newPassword,
        ]);

        $response->assertStatus(204);

        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => $newPassword,
        ]);

        $this->assertAuthenticated();

        $this->assertDatabaseHas('com_notifications', [
            'notifiable_id' => $this->user->id,
            'detail_id' => $this->user->id
        ]);
    }

    public function test_user_can_update_profile()
    {
        $profile = Profile::factory()->create(['user_id'=>$this->user->id]);

        $profile->phone="628574464646";
        $profile->about_me = "test";
        unset($profile->skills);

        $response = $this->actingAs($this->user)->postJson("/api/profile",$profile->toArray());
  
        $response->assertStatus(200)
                 ->assertJson([
                    'phone'=>'628574464646',
                    'about_me'=> 'test'
                 ]);
    }

    public function test_user_can_update_social_profile()
    {
        $socials = UserSocial::factory()->count(2)->make();

        $response = $this->actingAs($this->user)->postJson("/api/profile",['socials'=>$socials->toArray()]);

        $response->assertStatus(200)
                 ->assertJsonCount($socials->count());
    }

    public function test_user_can_update_experience_profile()
    {
        $experiences = UserExperience::factory()->count(2)->make();

        $response = $this->actingAs($this->user)->postJson("/api/profile",['experiences'=>$experiences->toArray()]);
 
        $response->assertStatus(200)
                 ->assertJsonCount($experiences->count());
    }

    public function test_user_can_update_education_profile()
    {
        $educations = UserEducation::factory()->count(2)->make();

        $response = $this->actingAs($this->user)->postJson("/api/profile",['educations'=>$educations->toArray()]);
 
        $response->assertStatus(200)
                 ->assertJsonCount($educations->count());
    }

    public function test_user_can_update_certificate_profile()
    {
        $certificates = UserCertificate::factory()->count(2)->make();

        $response = $this->actingAs($this->user)->postJson("/api/profile",['certificates'=>$certificates->toArray()]);
 
        $response->assertStatus(200)
                 ->assertJsonCount($certificates->count());
    }

    public function test_user_can_update_skill_profile()
    {
        $skills = Skill::factory()->count(2)->make();

        $response = $this->actingAs($this->user)->postJson("/api/profile",['skills'=>$skills->pluck('name')->toArray()]);
 
        $response->assertStatus(200)
                 ->assertJsonCount($skills->count());
    }

    public function test_user_can_update_photo_profile()
    {
        $profile = Profile::factory()->create(['user_id'=>$this->user->id]);
        unset($profile->skills);
        $profile->phone="628574464646";
        $profile->photo=UploadedFile::fake()->image('profile.jpg', 300, 300)->size(200);

        $response = $this->actingAs($this->user)->postJson("/api/profile",$profile->toArray());

        $response->assertStatus(200)
                 ->assertJson([
                    'phone'=>'628574464646'
                 ]);
        Storage::assertExists(str_replace(url('/'),'',@$response->json()['photo']));
        Storage::deleteDirectory('public/profile');
    }
}
