<?php

namespace App\Jobs;

use App\Models\Referral;
use domain\Services\ReferralPlacementService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Background job to update referral counter caches
 *
 * Run this hourly or daily to keep metrics fresh
 * Prevents slow queries during customer creation
 */
class UpdateReferralMetrics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes
    public $tries = 3;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Starting referral metrics update job');

        $service = new ReferralPlacementService();

        try {
            $service->updateCounterCaches();

            Log::info('Referral metrics updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update referral metrics: ' . $e->getMessage());
            throw $e;
        }
    }
}
