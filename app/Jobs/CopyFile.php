<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
class CopyFile implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $source_path;
    public $destination_path;
    public function __construct($source, $destination)
    {
        //
        $this->source_path=$source;
        $this->destination_path=$destination;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
           $disk = Storage::disk('local');

        if (!$disk->exists($this->source)) {
            return;
        }

        // Create destination directory if necessary
        $directory = dirname($this->destination);

        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        // Copy file
        $disk->copy(
            $this->source_path,
            $this->destination_path
        );
    }
}