<?php

namespace Tests\Unit;

use App\Reward;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testMonthInterval()
    {
        $month = 4;
        $interval = collect();

        $start = Carbon::now()->month($month)->startOfMonth();

        $end = Carbon::now()->month($month)->endOfMonth();

        while ($start <= $end) {
            $interval->push([
                'date' => $start->toDateString(),
            ]);

            $start = $start->addDay();
        }

        dd($interval);
    }

    public function testCreateRewards()
    {
        $rewards = [100, 200, 300, 400,500, 600, 700];

        $insert = [];

        $time = Carbon::now();


        foreach ($rewards as $key => $value) {
            $insert[] = [
                'name' => $value,
                'date' => $time->toDateString(),
            ];

            $time->addDay();
        }

        Reward::insert($insert);


    }

    public function testTime()
    {
        dd(Carbon::parse('2018-01-01')->lt(Carbon::now()));
    }

}
