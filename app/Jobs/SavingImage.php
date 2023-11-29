<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Alboms;

class SavingImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $user_id;
    public $link;

    public function __construct($user_id, $link)
    {
        $this->user_id = $user_id;
        $this->link = $link;
    }

    /**
     * Execute the job.
     */
    public function handle(): array
    {
        $alboms = new Alboms();
        $save_image = $alboms->insert($this->user_id, $this->link);

        $status = $save_image['status'];
        $message = $save_image['message'];

        return ['status' => $status, 'message' => $message];
    }
}
