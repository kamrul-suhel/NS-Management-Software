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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_return_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_serial_id')->nullable();
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
