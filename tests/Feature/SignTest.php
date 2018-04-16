<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\SignController;
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

        $api = 'api/sign';

        $user = User::find(1);

        $this->be($user, 'api');

        \Log::useDailyFiles(storage_path() . '/logs/sign_test.log');

        foreach ($timeInterval as $time) {
            $this->sign($api, $time);
        }

    }

    public function testResign()
    {
        $month = 5;
        $timeInterval = $this->getMothTimeIntervel($month)->pluck('date')->toArray();

        $lastKey = count($timeInterval) - 1;

        $apiSign = 'api/sign';

        $apiResign = 'api/sign/re';

        $user = User::find(1);

        UserSign::where('user_id', $user->id)->whereMonth('created_at', $month)->delete();

        $this->be($user, 'api');

        foreach ($timeInterval as $key => $time) {

            if ($key % 2 == 0) {
                $this->sign($apiResign, $time);
            }elseif($key==$lastKey){
                $this->sign($apiSign, $time);
            }else{
                $this->resign($apiSign, $time);
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

    protected function sign($api, $date)
    {
        $result = $this->post($api, [
            'date' => $date,
            'deubg' => 1,
        ]);

        if ($result->getStatusCode() == 200) {
            echo "{$date} 签到成功" . PHP_EOL;
        }else{
            echo "{$date} 签到失败" . PHP_EOL;
            \Log::error('test_sign', ['content' => $result->getOriginalContent()]);
        }
    }

    protected function resign($api, $date)
    {
        $result = $this->post($api, [
            'date' => $date,
            'deubg' => 1,
        ]);

        if ($result->getStatusCode() == 200) {
            echo "{$date} 补签成功" . PHP_EOL;
        }else{
            echo "{$date} 补签失败" . PHP_EOL;
            \Log::error('test_resign', ['content' => $result->getOriginalContent()]);
        }
    }
}
