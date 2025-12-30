<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->tinyInteger('payment_type')->default(0);
            $table->decimal('price', 12, 2)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreignId('image_id')->nullable()->constrained('images')->onDelete('set null');
            $table->decimal('points',12,2)->nullable();
            $table->integer('level')->default(0);
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
        Schema::dropIfExists('products');
    }
}
