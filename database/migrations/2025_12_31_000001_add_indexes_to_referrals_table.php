<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Critical indexes for 10,000+ user performance
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Index for finding children (most critical)
            $table->index('parent_referral_id', 'idx_parent_referral');
            $table->index('left_child_id', 'idx_left_child');
            $table->index('right_child_id', 'idx_right_child');

            // Index for customer lookup
            $table->index('customer_id', 'idx_customer');

            // Index for level-based queries
            $table->index('level', 'idx_level');
            $table->index(['level', 'level_index'], 'idx_level_index');

            // Index for direct referral queries
            $table->index('direct_referral_id', 'idx_direct_referral');

            // Composite index for finding empty slots
            $table->index(['level', 'left_child_id', 'right_child_id'], 'idx_level_children');
        });

        Schema::table('product_purchases', function (Blueprint $table) {
            // Index for active customer calculation (2-month window)
            $table->index(['customer_id', 'created_at'], 'idx_customer_created');
            $table->index('created_at', 'idx_created_at');
        });

        Schema::table('customer_supporting_bonuses', function (Blueprint $table) {
            // Index for bonus queries
            $table->index(['referral_id', 'customer_id'], 'idx_referral_customer');
            $table->index('created_at', 'idx_bonus_created');
        });

        Schema::table('customers', function (Blueprint $table) {
            // Index for referral code lookup
            if (!Schema::hasColumn('customers', 'referral_code')) {
                // Index may already exist
            } else {
                try {
                    $table->index('referral_code', 'idx_referral_code');
                } catch (\Exception $e) {
                    // Index might already exist
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropIndex('idx_parent_referral');
            $table->dropIndex('idx_left_child');
            $table->dropIndex('idx_right_child');
            $table->dropIndex('idx_customer');
            $table->dropIndex('idx_level');
            $table->dropIndex('idx_level_index');
            $table->dropIndex('idx_direct_referral');
            $table->dropIndex('idx_level_children');
        });

        Schema::table('product_purchases', function (Blueprint $table) {
            $table->dropIndex('idx_customer_created');
            $table->dropIndex('idx_created_at');
        });

        Schema::table('customer_supporting_bonuses', function (Blueprint $table) {
            $table->dropIndex('idx_referral_customer');
            $table->dropIndex('idx_bonus_created');
        });

        Schema::table('customers', function (Blueprint $table) {
            try {
                $table->dropIndex('idx_referral_code');
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
        });
    }
}
