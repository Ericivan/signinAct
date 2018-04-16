<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16
 * Time: 17:03
 */

namespace App\Services;


use App\UserSign;

class UserSignService
{
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

    public function getSignSucUser($month)
    {
        $list = UserSign::getSignUserCountByDateInMonth($month);

        $times = range(1, 31);


    }
}