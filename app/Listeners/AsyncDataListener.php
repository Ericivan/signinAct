<?php

namespace App\Listeners;

use App\Events\DatabaseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Schema;

class AsyncDataListener implements ShouldQueue
{

    public $connection = 'redis';

    public $queue = 'listeners';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DatabaseEvent  $event
     * @return void
     */
    public function handle(DatabaseEvent $event)
    {
        $data = $event->data;

        $table = $data->getTable();

        if (!Schema::connection('dev')->hasTable($table)) {
            $showTable = \DB::select("show create table {$table}")[0];

            $DbColect = [];

            foreach ($showTable as $item) {
                $DbColect[] = $item;
            }

            \DB::connection('dev')->select($DbColect[1]);
        }


        $isExist = \DB::connection('dev')->table($data->getTable())
            ->where('id',$data->id)->first();

        if (is_null($isExist)) {
            \DB::connection('dev')->table($data->getTable())->insert($data->toArray());
        }

    }

    public function failed(DatabaseEvent $event, $exception)
    {

    }
}
