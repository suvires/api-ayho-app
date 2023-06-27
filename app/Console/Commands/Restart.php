<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Restart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset, run migrations and seed the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:reset');
        $this->call('migrate');
        $this->call('db:seed');
    }
}
