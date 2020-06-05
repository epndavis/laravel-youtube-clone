<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function can_get_list_of_videos_from_api()
    {
        $user = factory(User::class)->create();

        factory(Video::class)->create([
            'title' => 'Example title',
            'description' => 'Example description',
            'uploaded_by' => $user->id,
        ]);

        factory(Video::class)->create([
            'title' => 'Example title 2',
            'description' => 'Example description 2',
            'uploaded_by' => $user->id,
        ]);

        $this->json('GET', '/api/videos', [])
            ->assertStatus(200)
            ->assertJson([
                [ 
                    'title' => 'Example title', 
                    'description' => 'Example description' ,
                ],
                [ 
                    'title' => 'Example title 2', 
                    'description' => 'Example description 2', 
                ],
            ])
            ->assertJsonStructure([
                '*' => ['id', 'title', 'description', 'uploaded_by', 'created_at', 'updated_at'],
            ]);
    }

    /**
     * @test
     */
    function can_get_video_by_id_from_api()
    {
        $user = factory(User::class)->create();

        $video = factory(Video::class)->create([
            'title' => 'Example title',
            'description' => 'Example description',
            'uploaded_by' => $user->id,
        ]);

        $this->json('GET', '/api/videos/' . $video->id, [])
            ->assertOk()
            ->assertJson([
                'id' => $video->id,
                'title' => 'Example title', 
                'description' => 'Example description' ,
            ])
            ->assertJsonStructure(['id', 'title', 'description', 'uploaded_by', 'created_at', 'updated_at']);
    }

    /**
     * @test
     */
    function can_store_video()
    {
        $user = factory(User::class)->create();

        $file = UploadedFile::fake()->image('vidoe.png');

        $this->json('POST', route('video.store'), [
            'title' => 'Example Title',
            'description' => 'Example Description',
            'uploaded_by' => $user->id,
            'file' => $file,
        ])
        ->assertStatus(200);

        $video = Video::with('media')->first();

        $this->assertFileExists($video->media->first()->getPath());
    }

    /**
     * @test
     */
    function throws_error_if_no_data_is_given()
    {
        $this->json('POST', route('video.store'), []) 
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors',
            ])
            ->assertJsonValidationErrors([
                'title',
                'description',
                'file',
            ]);
    }
}
