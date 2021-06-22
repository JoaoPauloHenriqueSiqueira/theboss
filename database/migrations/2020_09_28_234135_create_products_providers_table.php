<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('provider_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('products_providers', function ($table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');;
        });

        Schema::table('products_providers', function ($table) {
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_providers');
    }
}
