<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
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
}
