<?php

namespace App\Models;

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use RuntimeException;
use Illuminate\Support\Str;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Coordinate\Dimension;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Filesystem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Video extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            $uuid = (string) Str::uuid();
            
            while (self::where('uuid', $uuid)->exists()) {
                $uuid = (string) Str::uuid();
            }

            $video->uuid = $uuid;
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->extractVideoFrameAtSecond(1);

        $ffprobe = FFProbe::create([
            'ffmpeg.binaries'  => config('media-library.ffmpeg_path'),
            'ffprobe.binaries' => config('media-library.ffprobe_path'),
        ]);

        $ffmpeg = (new FFMpeg(FFMpegDriver::create(), $ffprobe));
        $video = $ffmpeg->open($media->getPath());

        $path = storage_path('app' . '/' . config('media-library.disk_name') . '/' . app(Filesystem::class)->getConversionDirectory($media));

        $video->gif(TimeCode::fromSeconds(0), (new Dimension(368, 232)), 3)
              ->save($path . Str::slug($media->name) . '-gif.gif');
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
