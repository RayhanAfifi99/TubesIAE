<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishOrderCreated implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $orderData;

    /**
     * Create a new job instance.
     */
    public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('🔥 Async job sedang dijalankan oleh queue worker', $this->orderData);
    }
}
