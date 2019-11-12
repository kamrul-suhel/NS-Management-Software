<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyRelationBetweenSaleAssistantUserProductSerial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_assistants', function(Blueprint $table){
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('product_serial_id')
                ->references('id')
                ->on('product_serials');
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
