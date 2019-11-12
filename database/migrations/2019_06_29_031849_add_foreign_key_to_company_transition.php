<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToCompanyTransition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_transactions', function(Blueprint $table){
            $table->foreign('store_id')
                ->references('id')
                ->on('stores');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_transactions', function(Blueprint $table){
            $table->dropForeign(['store_id']);
            $table->dropForeign(['company_id']);
        });
    }
}
