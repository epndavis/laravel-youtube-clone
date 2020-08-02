<?php

// TODO Get duration of video when uploading and save it to media
// TODO Format duration with no leading zeros

namespace App\Http\Resources;

use Illuminate\Support\Str;
use App\Services\VideoService;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Filesystem;

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
        $video = $this->media->first();

        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'viewCount' => $this->shortViewCount(mt_rand(100000, 2000000)),
            'uploadedWhen' => $this->created_at->diffForHumans(),
            'video' => [
                'src' => $video->getFullUrl(),
                'thumb' => $video->getFullUrl('thumb'),
                'gif' => (new VideoService)->getGifUrl($video),
                'duration' => $video->getCustomProperty('info')['duration'],
            ],
        ];
    }
}
