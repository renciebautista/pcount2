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

        $data = [];
        $message = [];

        $mailer->send('emails.masterfile', $this->setting, function($message)
        {
          $message->to($this->setting->uploader_email)
                  ->subject('Masterfile Updating!')
                  ->from('admin@ulp-projectsos.com', 'Project SOS');
        });
    }
}
