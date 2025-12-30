<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituteWithdrawalApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institute_withdrawal_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('withdrawal_id')->constrained('wallet_redeems')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('customers')->onDelete('cascade');
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
        Schema::dropIfExists('institute_withdrawal_approvals');
    }
}
