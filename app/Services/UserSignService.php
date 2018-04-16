<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 17:03
 */

namespace App\Services;


use App\UserSign;
use Illuminate\Support\Collection;

class UserSignService
{
    /**
     * @param $month
     * @return array
     * @author :Ericivan
     * @name : getEntireMonthSignCount
     * @description 统计每天成功签到用户数
     */
    public function getEntireMonthSignCount($month)
    {
        $timeInterval = monthdate($month);

        $list = UserSign::getSignUserCountByDateInMonth($month);

        $return = $timeInterval->map(function ($item) use ($list) {

            $curentDate = $list->where('date', $item['date'])->first();

            return [
                'date' => $item['date'],
                'count' => $curentDate->count ?? 0,
            ];
        });

        return $return->toArray();
    }

    /**
     * @param $month
     * @return array
     * @author :Ericivan
     * @name : getSignSucUser
     * @description 用户成功签到1/2.....30的用户数
     */
    public function getSignSucUser($month)
    {
        $list = UserSign::getUserSignCountStatisc($month)->toArray();


        $times = [];

//        dd($list);

        for ($i = 0; $i <= 31; $i++) {
            array_push($times, (['times' => $i, 'count' => 0]));
        }

        foreach ($list as $userSign) {
            foreach ($times as &$time) {
                if ($time['times'] == $userSign['count']) {
                    $time['count']=$time['count']+1;
                    unset($time);
                    continue;
                }
            }
        }

        dd($times);

        return $times;


    }

}