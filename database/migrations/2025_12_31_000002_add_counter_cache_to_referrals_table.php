<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCounterCacheToReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Add counter cache columns to avoid expensive recursive queries
     * Dramatically improves performance for 10,000+ users
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Total children count (all descendants)
            $table->integer('left_children_count')->default(0)->after('right_points');
            $table->integer('right_children_count')->default(0)->after('left_children_count');

            // Active children count (with purchases in last 2 months) - cached
            $table->integer('left_active_count')->default(0)->after('right_children_count');
            $table->integer('right_active_count')->default(0)->after('left_active_count');

            // Cache last update timestamp for invalidation
            $table->timestamp('metrics_updated_at')->nullable()->after('right_active_count');

            // Add index for counter cache queries
            $table->index(['left_children_count', 'right_children_count'], 'idx_children_counts');
        });

        // Note: Counter initialization is handled by the artisan command:
        // php artisan referrals:update-metrics
        // This avoids MySQL "can't specify target table for update" error
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropIndex('idx_children_counts');
            $table->dropColumn([
                'left_children_count',
                'right_children_count',
                'left_active_count',
                'right_active_count',
                'metrics_updated_at'
            ]);
        });
    }
}
