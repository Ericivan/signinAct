<?php

namespace App;

use App\Events\DatabaseEvent;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public $table = 'videos';

    public $guarded = [];

    public $timestamps=false;

    public static function boot()
    {
        parent::boot();

        static::created(function (Video $video) {
            event(new DatabaseEvent($video));
        });
    }
}
