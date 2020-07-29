<?php

namespace App\Services;

use FFMpeg\FFMpeg;
use Illuminate\Support\Str;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VideoService
{
    /**
     * @param Media $media
     * @param int $seconds
     * @param int $duration
     * @return string $path
     */
    public function generateGif(Media $media, $seconds = 0, $duration = 3)
    {
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => config('media-library.ffmpeg_path'),
            'ffprobe.binaries' => config('media-library.ffprobe_path'),
        ]);

        $file = $ffmpeg->open($media->getPath());

        $path = $this->getGifPath($media);

        $gif = $file->gif(TimeCode::fromSeconds($seconds), (new Dimension(368, 232)), $duration);
        $gif->save($path);

        return $path;
    }

    /**
     * @param Media $media
     * @return string
     */
    public function getGifUrl(Media $media)
    {
        return Storage::disk(config('media-library.disk_name'))
            ->url($this->gifPath($media));
    }

    /**
     * @param Media $media
     * @return string
     */
    public function getGifPath(Media $media)
    {
        return Storage::disk(config('media-library.disk_name'))
            ->path($this->gifPath($media));
    }

    /**
     * @param Media $media
     * @return string
     */
    public function gifPath(Media $media)
    {
        return app(Filesystem::class)->getConversionDirectory($media) . Str::slug($media->name) . '-gif.gif';
    }
}
