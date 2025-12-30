<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_redeems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->text('wallet_address')->nullable();
            $table->foreignId('wallet_transaction_id')->constrained('wallet_transactions')->onDelete('cascade');
            $table->foreignId('withdrawal_fee_transaction_id')->constrained('wallet_transactions')->onDelete('cascade');
            $table->foreignId('admin_fee_transaction_id')->constrained('wallet_transactions')->onDelete('cascade');
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
        Schema::dropIfExists('wallet_redeems');
    }
}
