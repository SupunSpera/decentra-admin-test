<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPointsToItemPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_purchases', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->nullable()->after('item_id');
            $table->decimal('points')->default(0)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_purchases', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('points');
        });
    }
}
