<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyShareCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_share_calculations', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_sales',12,2)->default(0);
            $table->decimal('total_point',8,2)->default(0);
            $table->decimal('point_value_bte',12,2)->default(0);
            $table->decimal('binary_pool_bte',12,2)->default(0);
            $table->decimal('qualified_shares',8,2)->default(0);
            $table->decimal('real_share_value',8,2)->default(0);
            $table->decimal('system_share_value',8,2)->default(0);
            $table->decimal('payout',8,2)->default(0);
            $table->decimal('pool_balance',12,2)->default(0);
            $table->decimal('cumulative_pool_balance', 12, 2)->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('type')->default(0);
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
        Schema::dropIfExists('daily_share_calculations');
    }
}
