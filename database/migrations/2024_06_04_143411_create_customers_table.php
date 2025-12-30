<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('referral_code');
            $table->string('introduction')->nullable();
            $table->string('email')->unique();
            $table->string('direct_ref_code')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('active_status')->default(0);
            $table->tinyInteger('purchased_status')->default(0);
            $table->tinyInteger('type')->default(1);
            $table->integer('email_two_factory')->default(0);
            $table->text('email_code')->nullable();
            $table->integer('email_sent')->default(0);
            $table->dateTime('email_code_sent_at')->nullable();
            $table->float('frozen_shares',25)->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('customers');
    }
}
