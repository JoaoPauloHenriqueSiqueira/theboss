<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('size_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('products_sizes', function ($table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');;
        });

        Schema::table('products_sizes', function ($table) {
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_sizes');
    }
}
