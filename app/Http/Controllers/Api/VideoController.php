<?php

// TODO Set uploaded at to authenticated user in store function

namespace App\Http\Controllers\Api;

use FFMpeg\FFProbe;
use App\Models\Video;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Http\Requests\StoreVideoRequest;
use App\Services\VideoService;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return VideoResource::collection(
            Video::has('media')
                ->with('media')
                ->get()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Video
     */
    public function show($id)
    {
        return new VideoResource(
            Video::with('media')
                ->where('uuid', $id)
                ->first()
        );
    }

    /**
     * @param Request $request
     * 
     * @return Video $video
     */
    public function store(StoreVideoRequest $request)
    {
        $video = new Video();

        $video->title = $request->input('title');
        $video->description = $request->input('description');
        $video->uploaded_by = 1;

        $video->save();

        $file = $request->file('video');
        $ffprobe = FFProbe::create([
            'ffmpeg.binaries'  => config('media-library.ffmpeg_path'),
            'ffprobe.binaries' => config('media-library.ffprobe_path'),
        ]);

        $videoFile = $ffprobe->streams($file)
            ->videos()                   
            ->first(); 

        $duration = $videoFile->get('duration');

        $video->addMedia($file)
            ->withCustomProperties([
                'info' => [
                    'duration' => $duration
                ],
            ])
            ->toMediaCollection('videos');

        (new VideoService)->generateGif($video->getFirstMedia('videos'));

        return new VideoResource($video);
    }
}
