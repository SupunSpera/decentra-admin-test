<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituteMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institute_members', function (Blueprint $table) {
            $table->id();
            $table->integer('institute_id');
            $table->integer('customer_id')->nullable();
            $table->string('email')->unique();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('type')->default(0);
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
        Schema::dropIfExists('institute_members');
    }
}
