<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyCompanyProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_product', function(Blueprint $table){
            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_product', function(Blueprint $table){
           $table->dropForeign(['company_id']);
           $table->dropForeign(['product_id']);
        });
    }
}
