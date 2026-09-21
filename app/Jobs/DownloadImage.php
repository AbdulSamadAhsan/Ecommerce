<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DownloadImage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
        public $tries =4;

    /**
     * Seconds before retry.
     */
    public $backoff = 60*2;
    public function __construct()
    {
        //
        dd($thy);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}