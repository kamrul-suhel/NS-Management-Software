<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMissingProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missing_product', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('product_id')->index();
            $table->bigInteger('product_serial_id')->nullable()->index();
            $table->bigInteger('company_id')->index();
            $table->smallInteger('quantity');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('missing_product');
    }
}
