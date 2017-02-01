<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class Updatemasterfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:masterfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update masterfile';

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
        //

        Artisan::call('db:seed', [
    '--class' => 'UpdateMasterfile'
]);

    }
}
