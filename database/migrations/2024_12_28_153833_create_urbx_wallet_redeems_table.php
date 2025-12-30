<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrbxWalletRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urbx_wallet_redeems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urbx_wallet_id')->constrained('urbx_wallets')->onDelete('cascade');
            $table->text('metamask_wallet_address')->nullable();
            $table->decimal('amount')->default(0);
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
        Schema::dropIfExists('urbx_wallet_redeems');
    }
}
