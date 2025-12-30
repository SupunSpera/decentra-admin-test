<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('daily_income_cap')->default(0);
            $table->decimal('usd_to_bte_fee')->default(0);
            $table->decimal('bte_to_usd_fee')->default(0);
            $table->decimal('withdrawal_fee')->default(0);
            $table->decimal('share_value')->default(0);
            $table->date('unhold_date')->nullable();
            $table->integer('unhold_period')->nullable();
            $table->date('unfreeze_date')->nullable();
            $table->integer('unfreeze_period')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
