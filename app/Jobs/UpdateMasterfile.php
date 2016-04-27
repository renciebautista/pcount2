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

        $mailer->send('emails.masterfile', $data, function($message)
        {
          $message->to($this->setting->uploader_email,'Project SOS Admin')
                  ->subject('Masterfile successfully updated.')
                  ->from('admin@ulp-projectsos.com', 'Project SOS');
        });
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed(Mailer $mailer)
    {
        $data = [];
        $message = [];

        \Artisan::call('queue:flush');

        $mailer->send('emails.masterfile_failed', $data, function($message)
        {
          $message->to($this->setting->uploader_email,'Project SOS Admin')
                  ->subject('Masterfile update failed.')
                  ->from('admin@ulp-projectsos.com', 'Project SOS');
        });
    }
}
