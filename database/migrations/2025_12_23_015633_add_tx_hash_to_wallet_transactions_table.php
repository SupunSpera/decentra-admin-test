<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTxHashToWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('tx_hash', 100)
                    ->nullable()
                    ->unique()
                    ->after('id'); 
            $table->string('network', 255)
                    ->nullable()
                    ->after('tx_hash');

            $table->tinyInteger('confirmed')
                    ->default(0)
                    ->comment('0 = pending, 1 = confirmed')
                    ->after('network');

            $table->tinyInteger('isLocked')
                    ->default(0)
                    ->comment('0 = unlocked, 1 = locked')
                    ->after('confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropUnique(['tx_hash']);
            $table->dropColumn([
                'tx_hash',
                'network',
                'confirmed',
                'isLocked'
            ]);
        });
    }
}
