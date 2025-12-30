<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_logs', function (Blueprint $table) {
            $table->id();
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->float('token_amount',25)->default(0);
            $table->decimal('usdt_amount')->default(0);
            $table->float('holding_tokens', 25)->default(0);
            $table->decimal('holding_usdt')->default(0);
            $table->unsignedBigInteger('wallet_id');
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
        Schema::dropIfExists('wallet_logs');
    }
}
