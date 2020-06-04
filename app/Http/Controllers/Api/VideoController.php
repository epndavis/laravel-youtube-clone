<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Http\Controllers\Controller;

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
        return Video::find($id);
    }
}
