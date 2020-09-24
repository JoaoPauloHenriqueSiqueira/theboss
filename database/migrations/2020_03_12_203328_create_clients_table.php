<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('cpf_cnpj');
            $table->integer('type_id')->unsigned();
            $table->string('phone')->nullable(true);;
            $table->string('cell_phone');
            $table->string('address')->unsigened()->nullable(true);
            $table->string('email')->unsigened()->nullable(true);
            $table->integer('company_id')->unsigned();
            $table->boolean('notifiable')->unsigned()->nullable(true)->default('1');
            $table->timestamps();
        });

        Schema::table('clients', function ($table) {
            $table->foreign('type_id')->references('id')->on('client_types');
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
        Schema::dropIfExists('clients');
    }
}
