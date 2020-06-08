<?php

// TODO Get duration of video when uploading and save it to media
// TODO Format duration with no leading zeros

namespace App\Http\Resources;

use FFMpeg\FFProbe;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ffprobe = FFProbe::create([
            'ffmpeg.binaries'  => config('media-library.ffmpeg_path'),
            'ffprobe.binaries' => config('media-library.ffprobe_path'),
        ]);
        $video = $this->media->first();

        $duration = $ffprobe->streams($video->getPath())
            ->videos()                   
            ->first()                  
            ->get('duration');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'video' => [
                'src' => $video->getFullUrl(),
                'thumb' => $video->getFullUrl('thumb'),
                'duration' => gmdate('H:i:s', $duration),
            ],
        ];
    }
}
