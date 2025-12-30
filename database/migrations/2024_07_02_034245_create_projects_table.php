<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('total_value', 12, 2)->nullable();
            $table->decimal('invested_amount', 12, 2)->default(0);
            $table->decimal('minimum_investment', 12, 2)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('image_id')->nullable()->constrained('images')->onDelete('set null');
            $table->tinyInteger('type')->nullable();
            $table->integer('duration')->default(0);
            $table->tinyInteger('duration_type')->default(0);
            $table->decimal('harvest')->default(0);
            $table->decimal('direct_commission')->default(0);
            $table->decimal('points', 12, 2)->nullable();
            $table->tinyInteger('harvest_type')->default(0);
            $table->tinyInteger('bonus_generation')->default(0);
            $table->date('started_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('terms')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
