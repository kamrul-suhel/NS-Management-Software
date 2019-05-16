<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReturnLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_line', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_return_id')->index()->unsigned();
            $table->integer('product_id')->index()->unsigned();
            $table->integer('product_serial_id')->index()->unsigned()->nullable();
            $table->integer('quantity')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_return_line');
    }
}
