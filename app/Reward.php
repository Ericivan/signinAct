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

    public static function countRewardByMonth($month)
    {
        $list = UserSign::whereMonth('created_at', 4)
            ->orderBy('created_at','asc')
            ->get();

        $userSigns = $list->groupBy( function ($value, $key) {
            $time = explode(' ', $value['created_at']);

            return $time[0];
        });

        $rewards = Reward::whereMonth('date', 4)->get();

        $list = $userSigns->map(function ($item,$key)use($rewards) {
            $rewardIds = $item->pluck('reward_id')->unique()->toArray();

            $rewardAccount= $rewards->whereIn('id', $rewardIds)
                ->sum('name');

            return [
                'date' => $key,
                'reward_count' => $rewardAccount,
            ];
        })->values();


        return $list;

    }
}
