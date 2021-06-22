<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('bar_code')->nullable(true);
            $table->float('sale_value',10,2)->unsigned()->nullable(false)->default(0);
            $table->integer('company_id')->unsigned();
            $table->float('cost_value')->unsigned()->nullable(false)->default(0);
            $table->boolean('control_quantity')->unsigned()->nullable(true)->default('1');
            $table->integer('quantity')->nullable(true);
            $table->boolean('notifiable')->unsigned()->nullable(true)->default('0');
            $table->integer('days_notify')->nullable(true);
            $table->timestamps();
        });

        Schema::table('products', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
