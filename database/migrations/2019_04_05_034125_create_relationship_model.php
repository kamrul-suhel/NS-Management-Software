<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('sale_return_line', function(Blueprint $table){
            $table->foreign('sale_return_id')->references('id')
                ->on('sales_return')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->foreign('product_serial_id')
                ->references('id')
                ->on('product_serials');
        });

        Schema::table('sales_return', function(Blueprint $table){
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('seller_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_return', function (Blueprint $table) {
           $table->dropForeign(['transaction_id']);
           $table->dropForeign(['store_id']);
           $table->dropForeign(['seller_id']);
        });

        Schema::table('sale_return_line', function(Blueprint $table){
            $table->dropForeign(['sale_return_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_serial_id']);
        });
    }
}
