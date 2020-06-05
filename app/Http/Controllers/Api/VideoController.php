<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Http\Controllers\Controller;
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
        return Video::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Video
     */
    public function show($id)
    {
        return Video::with('media')
            ->find($id);
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

        // TODO Set uploaded at to authenticated user
        $video->uploaded_by = $request->input('uploaded_by');

        $video->save();

        $video->addMedia($request->file('file'))->toMediaCollection();

        return response()->json($video);
    }
}
