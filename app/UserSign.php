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

    /**
     * @param $month
     * @return mixed
     * @author :Ericivan
     * @name : getSignUserCountByDateInMonth
     * @description 当月内每天签到用户数
     */
    public static function getSignUserCountByDateInMonth($month)
    {
        return static::whereMonth('created_at', $month)
            ->select(\DB::raw('count(id) as count,date(created_at) as date'))
            ->where('is_resign',0)
            ->groupBy(\DB::raw('date(created_at)'))
            ->get();
    }

    /**
     * @param $month
     * @return mixed
     * @author :Ericivan
     * @name : getResignUserCountByDateInMonth
     * @description 获取当月内每天补签用户数量
     */
    public static function getResignUserCountByDateInMonth($month)
    {
        return static::whereMonth('created_at', $month)
            ->select(\DB::raw('count(id) as count,date(created_at) as date,date(resign_at) as resign_time'))
            ->where('is_resign',1)
            ->groupBy(\DB::raw('date'))
            ->get();
    }

    /**
     * @param $month
     * @return mixed
     * @author :Ericivan
     * @name : getUserSignCountStatisc
     * @description 用户成功签到次数
     */
    public static function getUserSignCountStatisc($month)
    {
        return static::whereMonth('created_at', $month)
            ->selectRaw('count(id) as count,user_id')
            ->groupBy('user_id')
            ->get();
    }

}
