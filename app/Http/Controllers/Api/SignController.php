<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BaseException;
use App\Http\Controllers\Controller;
use App\Reward;
use App\User;
use App\UserItem;
use App\UserSign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SignController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function sign()
    {

        //TODO 用户认证

        $userId = $this->user->id;
        $date = Carbon::now()->toDateString();

        //测试
        if (env('APP_ENV') == 'local') {
            $date = request('date',$date);
        }

        $isSign = UserSign::checkUserHasSign($userId, $date);

        if ($isSign) {
            $this->error(10000, 409);
        }
        \DB::beginTransaction();


        try {

            $reward = Reward::getRewardByDate($date);

            UserSign::create([
                'user_id' => $userId,
                'reward_id'=>$reward->id,
                'created_at' => $date,
            ]);

            User::where('id',$userId)->increment('gold', intval($reward->name));

            if ($date == Carbon::now()->endOfMonth()->toDateString()) {
                $this->getFinallyRewards($userId);
            }

            \DB::commit();
        } catch (\Exception $exception) {

            \DB::rollBack();

            \Log::error('sign', ['msg'=>$exception->getMessage(),'reward'=>$reward->toArray(),'line'=>$exception->getLine()]);

            $this->error(9002, 500);
        }


        return $this->success();

    }

    public function signList()
    {
        $userId = $this->user->id;

        $timeInterval = $this->getMothTimeIntervel(4);

        $userSign = UserSign::getUserSign($userId);


        $list = $timeInterval->map(function ($item) use ($userSign) {
            $isSign = $userSign->where('date', $item['date'])->first();

            $return = [
                'date' => $item['date'],
                'state' => is_null($isSign) ? 0 : 1,
            ];

            return $return;
        });

        return $this->success($list->toArray());
    }


    public function reSign()
    {
        //用户任务完成后进行补签的操作

        $this->validate(request(), [
            'date' => 'required|date',
        ]);
        $userId = $this->user->id;

        $resignDate = request('date');

        //检测传入时间准确性
        if (!$this->validRequestDate($resignDate)) {
            $this->error(10002,400);
        }

        $isReSign = UserSign::getUserHasReSign($userId,$resignDate);

        //检测用户当日是否已经进行补签操作
        if (isset($isReSign)) {
            return response()->json([
                'code' => 10001,
                'msg' => 'user has resign today',
            ]);
        }

        $hasSign = UserSign::checkUserHasSign($userId, $resignDate);
        if ($hasSign) {
            $this->error(10000, 409);
        }

        \DB::beginTransaction();

        try {
            $reward = Reward::getRewardByDate($resignDate);

            UserSign::create([
                'user_id' => $userId,
                'created_at' => $resignDate,
                'reward_id'=>$reward->id,
                'resign_at' => Carbon::now()->toDateTimeString(),
                'is_resign' => 1,
            ]);

            //用户增加金币
            User::where('id',$userId)->increment('gold', intval($reward->name));


            if ($resignDate == Carbon::now()->endOfMonth()->toDateString()) {
                $this->getFinallyRewards($userId);
            }

            \DB::commit();
        } catch (\Exception $exception) {

            \DB::rollBack();

            $this->error(9002, 500);
        }

        return $this->success();


    }

    /**
     * @param $month
     * @return \Illuminate\Support\Collection
     * @author :Ericivan
     * @name : getMothTimeIntervel
     * @description 获取月份时间区间
     */
    protected function getMothTimeIntervel($month)
    {
        $interval = collect();

        $start = Carbon::now()->month($month)->startOfMonth();

        $end = Carbon::now()->month($month)->endOfMonth();

        while ($start <= $end) {
            $interval->push([
                'date' => $start->toDateString(),
            ]);

            $start = $start->addDay();
        }

        return $interval;
    }

    /**
     * @param $date
     * @return bool
     * @author :Ericivan
     * @name : validRequestDate
     * @description 验证用户传入事件的正确性
     */
    public function validRequestDate($date)
    {
        return Carbon::parse($date)->lt(Carbon::now());
    }

    /**
     * @return int
     * @author :Ericivan
     * @name : getCurrentMonth
     * @description 获取当前月份天数
     */
    protected function getCurrentMonth()
    {
        return Carbon::now()->month;
    }

    /**
     * @param $userId
     * @author :Ericivan
     * @name : getFinallyRewards
     * @description 获取签到最终大奖
     */
    protected function getFinallyRewards($userId)
    {
        //TODO 最终大奖

        $month = Carbon::now()->month;
        $nameOfFinallyReward = config('sign.' . $month);

        $dayInMonth = $this->getDayInMonth();


        if ($dayInMonth == (UserSign::getUserSignCount($userId, $this->getCurrentMonth()))) {

            \DB::beginTransaction();
            try {
                UserItem::create([
                    'item_name' => $nameOfFinallyReward,
                    'user_id' => $userId,
                    'is_get' => 0,
                ]);

                \DB::commit();
            } catch (\Exception $exception) {
                \DB::rollBack();

                \Log::error('finally_reward', ['msg' => $exception->getMessage(), 'line' => $exception->getLine()]);
                $this->error(9002, 500);

            }

        }

    }

    protected function getDayInMonth()
    {
        return Carbon::now()->daysInMonth;
    }
}
