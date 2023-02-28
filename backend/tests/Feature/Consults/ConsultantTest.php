<?php

namespace Tests\Feature\Consults;

use App\Models\Consults\Category;
use App\Models\Profiles\Profile;
use App\Models\Profiles\Skill;
use App\Models\Profiles\TrxCategory;
use App\Models\Profiles\TrxConsultant;
use App\Models\Profiles\UserCertificate;
use App\Models\Profiles\UserEducation;
use App\Models\Profiles\UserExperience;
use App\Models\Profiles\UserSocial;
use App\Models\Threads\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConsultantTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_get_all_categories()
    {
        Category::factory()->count(5)->create();
        
        $response = $this->actingAs($this->user)->get("/api/consultant/category");

        $response->assertStatus(200)
                ->assertJsonCount(5);
    }

    
                /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_some_consultant()
    {
        $users = User::factory()->count(4)->create([
            'is_consultant'=>'1', 
            'category_consultant_id' => Category::factory()->create()->id
        ]);
        foreach ($users as $key => $user) {
            Profile::factory()->create(['user_id'=>$user->id]);
        }
        $response= $this->actingAs($this->user)->get('/api/consultant?perpage=3');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

                /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_get_list_consultant()
    {
        User::factory()->count(4)->create([
            'is_consultant'=>'1', 
            'category_consultant_id' => Category::factory()->create()->id
        ]);

        $response= $this->actingAs($this->user)->get('/api/consultant/list?perpage=3');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_user_can_submit_consult_verify()
    {
        $consultant = TrxConsultant::factory()->make()->only('reason', 'is_cv');
        $consultant['category']   = json_encode([Category::factory()->create()]);
        $consultant['upfiles']['cv'] = UploadedFile::fake()->create('test.pdf', 100);
        $consultant['upfiles']['ktp'] = UploadedFile::fake()->create('test.pdf', 100);
        $consultant['upfiles']['ijazah'] = UploadedFile::fake()->create('test.pdf', 100);
        $consultant['upfiles']['foto'] = UploadedFile::fake()->create('test.jpg', 100);
        
        $response = $this->actingAs($this->user)->postJson("/api/consultant/verify", $consultant);

        $response->assertStatus(200)
                 ->assertJson(['reason'=>$consultant['reason']]);

        Storage::deleteDirectory('private/trx-consultant');
    }

    public function test_user_can_get_one_submited_data()
    {
        $trx =TrxConsultant::factory()->create([
            'user_id'=>$this->user->id,
            'is_active'=>'1'
        ]);

        TrxCategory::factory()->count(2)->create([
            'trx_consultant_id'=>$trx->id
        ]);

        $response = $this->actingAs($this->user)->getJson("/api/consultant/submission/$trx->id");

        $response->assertStatus(200)
                ->assertJson(['id'=>$trx->id]);
    }

    public function test_user_can_get_submission_data()
    {
        TrxConsultant::factory()->count(5)->create([
            'user_id'=>$this->user->id,
            'is_active'=>'1'
        ]);

        $response = $this->actingAs($this->user)->getJson("/api/consultant/submission");

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_get_submissions_data()
    {
        $users = User::factory()->count(5)->create(['is_consultant'=>'0']);
        
        foreach($users as $user){
            TrxConsultant::factory()->create([
                'user_id'=>$user->id,
                'is_active'=>'1'
            ]);
        }

        $response = $this->actingAs($this->user)->getJson("/api/consultant/submissions");

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    public function test_admin_can_verify_submission_data()
    {
        $trx = TrxConsultant::factory()->create(['status'=>'waiting']);

        $response = $this->actingAs($this->user)->postJson("/api/consultant/submission/$trx->id",["status"=>"approved"]);
        
        $response->assertStatus(200)
                 ->assertJson(['status'=>'approved']);
    }

    public function test_admin_can_save_consultant_data()
    {
        $faker = \Faker\Factory::create();

        $profile = Profile::factory()->make()->only(['phone','city_id','birthplace','birthdate','skills','about_me','postal_code']);

        $profile['name'] = $faker->firstName();
        $profile['email']= $faker->unique()->email();
        $profile['is_active'] = '1';
        $profile['category']  = Type::select('id')->inRandomOrder()->first()->id;
        $profile['socials'] = UserSocial::factory()->count(2)->make();
        $profile['experiences'] = UserExperience::factory()->count(2)->make();
        $profile['educations'] = UserEducation::factory()->count(2)->make();
        $profile['certificates'] = UserCertificate::factory()->count(2)->make();
        $profile['skills'] = Skill::factory()->count(2)->make()->pluck('name')->toArray();

        Notification::fake();

        $response = $this->actingAs($this->user)->postJson("/api/consultant",$profile);

        $response->assertStatus(201)
            ->assertJson(['email'=>$profile['email']]);

        $user = User::find($response->json('id'));

        // Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_admin_can_update_consultant_data()
    {
        $faker = \Faker\Factory::create();

        $profileData = Profile::factory()->create();
        $profile = $profileData->only(['phone','city_id','birthplace','birthdate','skills','about_me','postal_code']);

        $profile['name'] = $faker->firstName();
        $profile['email']= $faker->unique()->email();
        $profile['is_active'] = '1';

        $profile['category']  = Type::select('id')->inRandomOrder()->first()->id;
        $profile['socials'] = UserSocial::factory()->count(2)->make();
        $profile['experiences'] = UserExperience::factory()->count(2)->make();
        $profile['educations'] = UserEducation::factory()->count(2)->make();
        $profile['certificates'] = UserCertificate::factory()->count(2)->make();
        $profile['skills'] = Skill::factory()->count(2)->make()->pluck('name')->toArray();

        Notification::fake();

        $response = $this->actingAs($this->user)->putJson("/api/consultant/$profileData->user_id",$profile);

        $response->assertStatus(200)
            ->assertJson(['email'=>$profile['email']]);

        $this->assertDatabaseHas('com_users', [
            'email' => $profile['email'],
        ]);

        // $user = User::find($response->json('id'));

        // Notification::assertSentTo($user, ResetPassword::class);
    }
}
