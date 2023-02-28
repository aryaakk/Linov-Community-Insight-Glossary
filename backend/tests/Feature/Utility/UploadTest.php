<?php

namespace Tests\Feature\Utility;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use function PHPUnit\Framework\assertJson;

class UploadTest extends TestCase
{
    public function test_user_can_upload_to_storage(){
        $response = $this->actingAs($this->user)
                         ->postJson('/api/upload/content', ['file'=>UploadedFile::fake()->image('icon.jpg')->size(130)]);

        $response->assertStatus(200)
                 ->assertJson(['link'=>Storage::disk('oss')->url($response->json()['file_name'])]);
        
        Storage::disk('oss')->delete($response->json()['file_name']);
    }
}
