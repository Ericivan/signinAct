<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\SignController;
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


        $user = User::find(1);

        $this->be($user, 'api');

        \Log::useDailyFiles(storage_path() . '/logs/sign_test.log');

        foreach ($timeInterval as $time) {
            Carbon::setTestNow(Carbon::parse($time));
//            $this->sign( $time);
        }

    }

    public function testResign()
    {
        $month = 4;

        $timeInterval = $this->getMothTimeIntervel($month)->pluck('date')->toArray();

        $lastKey = count($timeInterval) - 1;

        $user = User::find(1);

        //删除测试数据
        UserSign::where('user_id', $user->id)->whereMonth('created_at', $month)->delete();

        $this->be($user, 'api');

        foreach ($timeInterval as $key => $time) {

            Carbon::setTestNow(Carbon::parse($time));

            if ($key == 0 || $key==$lastKey) {
                $this->sign($time);
            }elseif($key % 2 == 0){
                $this->resign( $timeInterval[$key-1]);
                $this->sign($time);
            }
        }


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

    protected function sign( $date)
    {
        \Log::useFiles(storage_path('/logs/test_sign.log'));
        $result = $this->post('api/sign', [
            'date' => $date,
            'debug' => 1,
        ]);

        if ($result->getStatusCode() == 200) {
            echo "{$date} 签到成功" . PHP_EOL;
        }else{
            echo "{$date} 签到失败" . PHP_EOL;
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
        }else{
            echo "{$date} 补签失败" . PHP_EOL;
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
        (new UserSignService())->getSignSucUser(4);

    }


}
