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
     */
    function can_store_video()
    {
        factory(User::class)->create();

        Storage::fake(config('media-library.disk_name'));

        $this->json('POST', route('video.store'), [
            'title' => 'Example Title',
            'description' => 'Example Description',
            'video' => UploadedFile::fake()->image('video.png'),
        ])
        ->assertStatus(200);

        $video = Video::with('media')->first();
        $videoMedia = $video->media->first();

        $this->assertFileExists($videoMedia->getPath());
        $this->assertFileExists($videoMedia->getPath('thumb'));
    }

    /**
     * @test
     */
    function can_get_video_by_id_from_api()
    {
        factory(User::class)->create();

        Storage::fake(config('media-library.disk_name'));

        $response = $this->json('POST', route('video.store'), [
            'title' => 'Example Title',
            'description' => 'Example Description',
            'video' => UploadedFile::fake()->image('video.png'),
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
                        'thumb' => 'http://youtube-clone.test/storage/1/conversions/video-thumb.jpg',
                    ]
                ],           
            ])
            ->assertJsonStructure(['data' => ['title', 'description', 'video']]);
    }

    /**
     * @test
     * @return void
     */
    public function can_get_list_of_videos_from_api()
    {
        factory(User::class)->create();

        Storage::fake(config('media-library.disk_name'));

        $this->json('POST', route('video.store'), [
            'title' => 'Example Title',
            'description' => 'Example Description',
            'video' => UploadedFile::fake()->image('video.png'),
        ]);

        $this->json('POST', route('video.store'), [
            'title' => 'Example Title 2',
            'description' => 'Example Description 2',
            'video' => UploadedFile::fake()->image('video2.png'),
        ]);

        $this->json('GET', '/api/videos', [])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [ 
                        'title' => 'Example Title',
                    ],
                    [ 
                        'title' => 'Example Title 2',
                    ],
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'video' => ['thumb']]
                ],
            ]);
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
