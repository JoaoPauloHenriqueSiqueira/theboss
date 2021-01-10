<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtrasFieldToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('password')->nullable(true);
            $table->string('cep')->nullable(true);
            $table->string('city')->nullable(true);
            $table->string('neighborhood')->nullable(true);
            $table->string('complement')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['password', 'cep', 'city', 'neighborhood', 'complement']);
        });
    }
}
