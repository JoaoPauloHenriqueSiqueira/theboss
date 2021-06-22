<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('client_id')->unsigned()->nullable(true);
            $table->integer('user_id')->unsigned()->nullable(false);
            $table->dateTime('date_sale');
            $table->float('amount_total')->unsigned()->nullable(false)->default(0);
            $table->float('amount_paid')->unsigned()->nullable(false)->default(0);
            $table->timestamps();
        });

        Schema::table('sales', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
