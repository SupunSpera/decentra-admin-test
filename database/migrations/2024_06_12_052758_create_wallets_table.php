<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->float('token_amount',25)->default(0);
            $table->decimal('usdt_amount')->default(0);
            $table->float('holding_tokens', 25)->default(0);
            $table->decimal('holding_usdt')->default(0);
            $table->string('eth_wallet_address');
            $table->text('eth_wallet_private_key');
            $table->float('deposited_token_amount',25)->default(0);
            $table->integer('daily_share_cap')->default(0);
            $table->float('max_income_quota',12)->default(0);
            $table->float('used_income_quota',12)->default(0);
            $table->string('withdrawal_address')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('row_key')->nullable();
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
        Schema::dropIfExists('wallets');
    }
}
