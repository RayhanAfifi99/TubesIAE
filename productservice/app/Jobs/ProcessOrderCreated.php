<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOrderCreated implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        Log::info('ProductService menerima order_created:', $this->order);

        // Di sini kamu bisa update stok
        // Contoh (pseudo-code):
        // Product::where('name', $this->order['product_name'])
        //        ->decrement('stock', $this->order['quantity']);
    }
}
