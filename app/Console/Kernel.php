<?php

namespace App\Console;

use App\Jobs\BinanceTrade;
use App\Jobs\BinanceWatch;
use App\Models\Option;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $mins = (int) Option::get('trade.run_mins', 5);
        // $schedule->command('inspire')->hourly();
        if (!$mins || $mins <= 1) {
            $schedule->job(new BinanceTrade)->everyMinute();
        } else {
            $schedule->job(new BinanceTrade)->cron("*/$mins * * * *");
        }
        
        //$schedule->job(new BinanceWatch)->everyThreeMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
