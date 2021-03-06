<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('videos')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(368)
              ->height(232)
              ->extractVideoFrameAtSecond(1);
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function shortViewCount($num) 
    {
        $units = ['', 'K', 'M', 'B', 'T'];
        for ($i = 0; $num >= 1000; $i++) {
            $num /= 1000;
        }
        return round($num, 1) . $units[$i];
    }
}
