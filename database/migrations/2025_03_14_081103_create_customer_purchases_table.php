<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->tinyInteger('type')->default(0);
            $table->decimal('amount')->default(0);
            $table->decimal('points')->default(0);
            $table->float('max_income_quota',12)->default(0);
            $table->float('remaining_income_quota',12)->default(0);
            $table->tinyInteger('income_quota_status')->default(1);
            $table->integer('project_id')->nullable(1);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('customer_purchases');
    }
}
