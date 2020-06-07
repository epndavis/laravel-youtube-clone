<?php

// TODO Set uploaded at to authenticated user in store function

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Http\Requests\StoreVideoRequest;

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
            Video::with('media')->get()
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
                ->find($id)
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

        $video->addMedia($request->file('video'))->toMediaCollection();

        return response()->json($video);
    }
}
