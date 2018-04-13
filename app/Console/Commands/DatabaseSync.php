<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        \Schema::table('users', function ($table) {
            dd($table);
        });
    }
}
