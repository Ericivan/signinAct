<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DatabaseSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbsync:begin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'database sync';

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
        $result=\DB::connection('mysql')->select(\DB::raw('show create table users'))[0];

        $db = [];
        foreach ($result as $item) {
            $db[]=$item;
        }

        try {
            \DB::connection('dev')->select(\DB::raw($db[1]));
        } catch (\Exception $exception) {

        }

    }
}
