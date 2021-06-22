<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_companies', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->string('token')->unique()->nullable(false);
            $table->boolean('paid')->unsigned()->nullable(true)->default('0');
            $table->timestamps();
        });

        Schema::table('payment_companies', function ($table) {
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
        Schema::dropIfExists('payment_company');
    }
}
