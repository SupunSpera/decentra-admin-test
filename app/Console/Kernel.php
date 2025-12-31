<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('run:removeExpiredDeposits')->everyMinute();
        $schedule->command('run:confirmPendingDeposits')->everyMinute();
        $schedule->command('run:makeSupportingBonusAvailable')->dailyAt('23:55');
        $schedule->command('run:archiveMilestones')->everySixHours();
        $schedule->command('run:updateGeneratedSupportingBonuses')->dailyAt('00:10');
        $schedule->command('run:completeProjects')->dailyAt('23:45');
        // $schedule->command('run:makeNfcCardCustomer')->dailyAt('00:00');

        // Update referral counter caches hourly for 10k+ users performance
        $schedule->command('referrals:update-metrics')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
