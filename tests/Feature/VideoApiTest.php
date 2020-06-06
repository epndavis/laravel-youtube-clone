<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
    function can_store_video()
    {
        $user = factory(User::class)->create();

        $videoFile = UploadedFile::fake()->image('video.png');

        Storage::fake(config('media-library.disk_name'));

        $this->json('POST', route('video.store'), [
            'title' => 'Example Title',
            'description' => 'Example Description',
            'uploaded_by' => $user->id,
            'video' => $videoFile,
        ])
        ->assertStatus(200);

        $video = Video::with('media')->first();

        $this->assertFileExists($video->media->first()->getPath());
    }

    /**
     * @test
     */
    function can_get_video_by_id_from_api()
    {
        factory(User::class)->create();

        $videoFile = UploadedFile::fake()->image('video.png');

        Storage::fake(config('media-library.disk_name'));

        $response = $this->json('POST', route('video.store'), [
            'title' => 'Example Title',
            'description' => 'Example Description',
            'video' => $videoFile,
        ]);

        $video = json_decode($response->getContent());

        $this->json('GET', '/api/videos/' . $video->id, [])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'title' => 'Example Title', 
                    'description' => 'Example Description',
                    'video' => [
                        'src' => 'http://youtube-clone.test/storage/1/video.png',
                    ]
                ],           
            ])
            ->assertJsonStructure(['data' => ['title', 'description', 'video']]);
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
                'video',
            ]);
    }

    public function teardown(): void
    {
        Storage::fake('public');
    }
}
