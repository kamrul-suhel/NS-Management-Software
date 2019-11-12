<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeginKeyToProductSerial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product_serials', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions');
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
        Schema::table('product_serials', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['transaction_id']);
        });

    }
}
