<?php

namespace App\Jobs;

use App\Models\Log;
use App\Models\Option;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class BinanceWatch extends BinanceTrade
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!Option::get('trade.status')) {
            return;
        }
        
        try {
            $this->watch();
        } catch (Throwable $e) {
            Log::log('error', $e->getMessage());
        }
    }
}
