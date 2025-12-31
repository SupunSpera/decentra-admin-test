<?php

namespace App\Console\Commands;

use App\Jobs\UpdateReferralMetrics;
use Illuminate\Console\Command;

/**
 * Command to update referral tree metrics
 *
 * Usage: php artisan referrals:update-metrics
 */
class UpdateReferralMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referrals:update-metrics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update referral tree counter caches and metrics';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Dispatching referral metrics update job...');

        UpdateReferralMetrics::dispatch();

        $this->info('Job dispatched successfully! Metrics will be updated in the background.');

        return 0;
    }
}
