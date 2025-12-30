<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('parent_referral_id')->nullable()->constrained('referrals')->onDelete('set null');
            $table->foreignId('direct_referral_id')->nullable()->constrained('referrals')->onDelete('set null');
            $table->foreignId('left_child_id')->nullable()->constrained('referrals')->onDelete('set null');
            $table->foreignId('right_child_id')->nullable()->constrained('referrals')->onDelete('set null');
            $table->decimal('left_points',12,1)->default(0);
            $table->decimal('right_points',12,1)->default(0);
            $table->integer('level')->default(0);
            $table->integer('level_index')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referrals');
    }
}
