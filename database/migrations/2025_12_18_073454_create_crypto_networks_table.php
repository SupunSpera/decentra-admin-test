<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoNetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_networks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();               // "abstract-testnet"
            $table->unsignedBigInteger('chain_id')->unique();   // 11124
            
            $table->string('rpc_http')->nullable();             // "https://api.testnet.abs.xyz"
            $table->string('rpc_ws')->nullable();               // "wss://api.testnet.abs.xyz/ws"

            // We store tokens as JSON – super clean and exactly matches your object
            $table->json('tokens')->nullable();                 // {"TOKEN": "0xEb...", "USDC": "...", "CTT": "..."}

            $table->boolean('is_active')->default(true);

            // Quick index so your listener finds the network in 0.001 seconds
            $table->index('chain_id');
            $table->index('name');
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
        Schema::dropIfExists('crypto_networks');
    }
}
