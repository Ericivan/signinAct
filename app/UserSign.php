<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserSign extends Model
{
    public $table = 'user_sign';

    public $guarded = [];


    public static function checkUserHasSign($userId, $date)
    {
        return static::whereDate('created_at', $date)
            ->where('user_id', $userId)->first();
    }

    public static function getUserSign($userId)
    {
        return static::select(\DB::raw('date(created_at) as date'))
                 ->where('user_id', $userId)->get();
    }

    public static function getUserHasReSign($userId,$date)
    {
        return static::where('user_id', $userId)
            ->whereDate('created_at',$date)
            ->whereDate('resign_at',Carbon::now()->toDateString())
            ->where('is_resign', 1)
            ->first();
    }

    public static function getUserSignCount($userId, $month)
    {
        return static::whereMonth('created_at', '=', $month)
            ->where('user_id', $userId)
            ->select(\DB::raw('count(id) as count'))
            ->first()->count??0;
    }

}
