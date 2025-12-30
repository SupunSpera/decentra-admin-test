<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletTransactionIdToUrbxWalletRedeemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('urbx_wallet_redeems', function (Blueprint $table) {
            $table->foreignId('wallet_transaction_id')->constrained('wallet_transactions')->onDelete('cascade')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('urbx_wallet_redeems', function (Blueprint $table) {
            $table->dropForeign(['wallet_transaction_id']); // Drop foreign key constraint
            $table->dropColumn('wallet_transaction_id'); // Drop the column
        });
    }
}
