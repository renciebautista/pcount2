<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMasterfile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {   
        \Artisan::call('db:seed', ['--class' => 'UpdateMasterfile']);
        
        $data = [];
        $message = [];

        $mailer->send('emails.welcome', $data, function($message)
        {
          $message->to('rbautista@chasetech.com', 'Philip Brown')
                  ->subject('Welcome to Cribbb!')
                  ->from('rbautista@chasetech.com', 'Philip Brown');
        });
    }
}
