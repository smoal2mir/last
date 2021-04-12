<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
        $schedule->call(function() {

            // Truncate Tables
            DB::table('publicMsg')->delete();
            DB::table('private_messages')->delete();
            DB::table('wallReg')->delete();
            DB::table('notificationsReg')->delete();
            DB::table('ordersReg')->delete();
            DB::table('adsReg')->delete();

            // Delete Old reveal_names records
            $top = DB::table("reveal_names")->latest()->pluck('id');
            $deleteCount = 1000;
            if(count($top) > $deleteCount) {
                DB::table("reveal_names")->where('id', '<=', $top[0] - $deleteCount)->delete();
            }

            // Delete Old wall records
            $top = DB::table("wall")->latest()->pluck('id');
            $deleteCount = 50;
            if(count($top) > $deleteCount) {
                DB::table("wall")->where('id', '<=', $top[0] - $deleteCount)->delete();
            }

        })->cron('0 0 * * 1,3,5,7'); // At 00:00 on Monday, Wednesday, Friday, and Sunday      ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
