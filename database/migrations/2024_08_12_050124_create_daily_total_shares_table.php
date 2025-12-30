<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyTotalSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_total_shares', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->decimal('value', 12, 2)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
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
        Schema::dropIfExists('daily_total_shares');
    }
}
