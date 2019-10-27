<?php

use App\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->string('name')->index();
            $table->string('description', 1000)->nullable();
            $table->integer('quantity')->unsigned();
            $table->decimal('sale_price')->unsigned()->nullable();
            $table->decimal('purchase_price')->unsigned()->nullable();
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('image');
            $table->string('is_barcode', 20)->nullable()->index();
            $table->unsignedBigInteger('seller_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
