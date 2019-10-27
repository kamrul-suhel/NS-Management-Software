<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_serials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('color', 50)->nullable()->index();
            $table->string('barcode',120)->nullable()->index();
            $table->string('imei',120)->nullable()->index();
            $table->enum('product_warranty',['No warranty','3 Month','6 Month','1 Year', '1.5 Year', '2 Year'])->nullable()->index();
            $table->integer('is_sold')->nullable()->unsigned()->index();
            $table->softDeletes();
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
        Schema::dropIfExists('product_serials');
    }
}
