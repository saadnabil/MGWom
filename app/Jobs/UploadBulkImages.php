<?php

namespace App\Jobs;

use App\Helpers\FileHelper;
use App\Models\ImagePulk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class UploadBulkImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $filePath;
    protected $originalName;
    public function __construct($filePath, $originalName)
    {
        $this->filePath = $filePath;
        $this->originalName = $originalName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ImagePulk::create([
            'image' => $this->filePath,
            'name' => $this->originalName,
        ]);
    }
}
