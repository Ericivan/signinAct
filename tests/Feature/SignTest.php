<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\SignController;
use App\Reward;
use App\Services\UserSignService;
use App\User;
use App\UserSign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }


    public function testUser()
    {
        $user = User::find(1);

        $this->be($user, 'api');

        $this->get('api/sign/list')->dump();
    }

    public function testSignActivity()
    {
        $month = 4;

        $timeInterval = $this->getMothTimeIntervel($month)->pluck('date')->toArray();

        $user=$this->setUser(1);


        $this->deleteUserSignData($user->id, $month);

        \Log::useDailyFiles(storage_path() . '/logs/sign_test.log');

        foreach ($timeInterval as $time) {
            $this->setTime($time);
            $this->sign( $time);
        }

    }

    public function testResign()
    {
        $month = 4;

        $timeInterval = monthdate($month)->pluck('date')->toArray();

        $lastKey = count($timeInterval) - 1;


        $user = $this->setUser(1);

        //删除测试数据
        UserSign::where('user_id', $user->id)->whereMonth('created_at', $month)->delete();

        foreach ($timeInterval as $key => $time) {

            $this->setTime($time);

            if ($key == 0 || $key == $lastKey) {
                $this->sign($time);
            } elseif ($key % 2 == 0) {
                $this->resign($timeInterval[$key - 1]);
                $this->sign($time);
            }
        }
    }

    public function testSignError()
    {

        $month = 4;

        $timeInterval = monthdate($month)->pluck('date')->toArray();


        $user = $this->setUser(1);

        //删除测试数据
        UserSign::where('user_id', $user->id)->whereMonth('created_at', $month)->delete();


        foreach ($timeInterval as $key => $time) {
            $do = mt_rand(1, 3);

            if (!in_array($key, [5, 6, 7])) {
                $this->setTime($time);
            }

            switch ($do) {
                case 1:
                    $this->sign($time);
                    break;
                case 2:
                    $resignTime = isset($timeInterval[$key - 1]) ? $timeInterval[$key-1] : $timeInterval[mt_rand(1, 30)];
                    $this->resign($resignTime);
                    break;
                case 3:
                    continue;
            }
        }


    }

    /**
     * @author :Ericivan
     * @name : testResignError
     * @description 重复补签
     */
    public function testResignError()
    {
        $month = 4;

        $user = $this->setUser(1);

        $timeInterval = ['2018-04-01', '2018-04-02'];

        $this->deleteUserSignData($user->id, $month);

        $this->setTime($timeInterval[1]);

        $this->resign($timeInterval[0]);

        $this->resign($timeInterval[0]);

    }

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

    public function testFinally()
    {
        dd(UserSign::getUserSignCount(1, 4));
    }

    protected function sign($date)
    {
        \Log::useFiles(storage_path('/logs/test_sign.log'));
        $result = $this->post('api/sign', [
            'date' => $date,
            'debug' => 1,
        ]);

        if ($result->getStatusCode() == 200) {
            echo "{$date} 签到成功" . PHP_EOL;
        } else {
            echo "{$date} 签到失败" . PHP_EOL;
            print_r($result->getOriginalContent());
            \Log::error('test_sign', ['content' => $result->getOriginalContent()]);
        }
    }

    protected function resign($date)
    {
        \Log::useFiles(storage_path('/logs/test_sign.log'));
        $result = $this->post('api/sign/re', [
            'date' => $date,
            'debug' => 1,
        ]);

        if ($result->getStatusCode() == 200) {
            echo "{$date} 补签成功" . PHP_EOL;
        } else {
            echo "{$date} 补签失败" . PHP_EOL;
            print_r($result->getOriginalContent());
            \Log::error('test_resign', ['content' => $result->getOriginalContent()]);
        }
    }

    public function testUserCountByMonth()
    {
        $list = (new UserSignService())->getEntireMonthSignCount(4);

        dd($list);
        dd(UserSign::getResignUserCountByDateInMonth(4)->toArray());
        dd(UserSign::getUserSignCountStatisc(4)->toArray());
        dd($list);
    }

    public function testCount()
    {
        dd((new UserSignService())->getEntireMonthResignCount(4));
        dd((new UserSignService())->getSignSucUser(4));

    }

    protected function setTime($date)
    {
        Carbon::setTestNow(Carbon::parse($date));
    }

    protected function deleteUserSignData($userId,$month)
    {
        UserSign::where('user_id', $userId)->whereMonth('created_at', $month)->delete();
    }

    protected function setUser($userId = 1)
    {
        $user = User::find(1);

        $this->be($user, 'api');

        return $user;
    }

    public function testRewardCount()
    {
        dd(Reward::countRewardByMonth(4));
    }


}
