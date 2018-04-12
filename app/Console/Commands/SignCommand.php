<?php

namespace App\Console\Commands;

use App\Reward;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SignCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reward:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate the rewards by input month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $month = $this->ask('please set the month rewards');

        $timeInterval = $this->getMothTimeIntervel($month);

        $list = $timeInterval->map(function ($item) {
            return [
                'date' => $item['date'],
                'name' => mt_rand(100, 1000),
            ];
        })->toArray();

        \DB::beginTransaction();
        try {
            Reward::whereMonth('date','=',4)->delete();

            Reward::insert($list);

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();

            $this->error($exception->getMessage());
        }


        $this->info('finish');

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
}
