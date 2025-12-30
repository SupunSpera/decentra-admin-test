<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrbxWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urbx_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->float('token_amount',25)->default(0);
            $table->decimal('usdt_amount')->default(0);
            $table->string('withdrawal_address')->nullable();
            $table->string('metamask_wallet_address')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('row_key')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urbx_wallets');
    }
}
