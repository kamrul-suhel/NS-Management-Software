<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyForExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade');

//            $table->foreign('expense_categories_id')
//                ->references('id')
//                ->on('expense_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function(Blueprint $table){
           $table->dropForeign(['store_id']);
//           $table->dropForeign('expense_categories_id');
        });
    }
}