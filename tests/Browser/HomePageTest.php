<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageTest extends DuskTestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     *
     * @return void
     */
    public function can_load_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Youtube Clone');
        });
    }

    /** 
     * @test
     */
    public function can_click_through_to_video() 
    {
        factory(User::class)->create();
        
        $videoFile = UploadedFile::fake()->image('video.png');

        Storage::fake(config('media-library.disk_name'));

        $this->json('POST', route('video.store'), [
            'title' => 'Example Title',
            'description' => 'Example Description',
            'video' => $videoFile,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->waitFor('a h3')
                    ->click('a h3')
                    ->waitFor('#video')
                    ->assertSee('Example Description');
        });
    }
}
