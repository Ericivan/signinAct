<?php

namespace Tests\Feature;

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
        $timeInterval = $this->getMothTimeIntervel(Carbon::now()->month)->pluck('date')->toArray();

        $api = 'api/sign';

        $user = User::find(1);

        $this->be($user, 'api');

        \Log::useDailyFiles(storage_path() . '/logs/sign_test.log');

        foreach ($timeInterval as $time) {
            $result = $this->post($api, [
                'date' => $time,
            ]);

            if ($result->getStatusCode() == 200) {
                echo "{$time} 签到成功" . PHP_EOL;
            }else{
                echo "{$time} 签到失败" . PHP_EOL;
                \Log::error('test_sign', ['content' => $result->getOriginalContent()]);
            }
        }

    }

    public function testResign()
    {
        $timeInterval = $this->getMothTimeIntervel(Carbon::now()->month)->pluck('date')->toArray();

        $api = 'api/sign';

        $user = User::find(1);

        $this->be($user, 'api');


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
}
