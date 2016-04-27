<?php

namespace App\Jobs;

use App\Setting;
use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMasterfile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $setting;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {   
        \Artisan::call('db:seed', ['--class' => 'UpdateMasterfile']);
        $message = [];
        $data = [];
        $sender = $this->setting->uploader_email;

        $mailer->send('emails.masterfile', $data , function($message)
        {
          $message->to('rbautista@chasetech.com', 'Admin')
                  ->subject('Masterfile Updated!')
                  ->from('admin@ulp-projectsos.com', 'Project SOS');
        });
    }
}
