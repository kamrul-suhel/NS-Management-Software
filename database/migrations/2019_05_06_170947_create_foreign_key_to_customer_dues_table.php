<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyToCustomerDuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('customer_dues', function(Blueprint $table){
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->ondelete('cascade');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');
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
        Schema::table('customer_dues', function(Blueprint $table){
           $table->dropForeign(['customer_id']);
           $table->dropForeign(['transaction_id']);
        });
    }
}
