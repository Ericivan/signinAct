<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    public $table = 'rewards';

    public $guarded = [];

    public static function getRewardByDate($date)
    {
        return static::whereDate('date', $date)->first();
    }
}
