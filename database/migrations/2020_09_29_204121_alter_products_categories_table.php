<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_categories', function (Blueprint $table) {
            $table->dropForeign('products_categories_product_id_foreign');
            $table->dropForeign('products_categories_category_id_foreign');
            $table->dropColumn('product_id');
            $table->dropColumn('category_id');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
