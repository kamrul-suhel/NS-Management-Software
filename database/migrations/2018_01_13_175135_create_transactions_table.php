<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->index()->unsigned();
            $table->integer('customer_id')->unsigned()->index();
            $table->integer('seller_id')->unsigned()->index();
            $table->integer('payment_status')->unsigned()->nullable(); // paid : 1, due : 2, half paid = 3
            $table->float('payment_due')->unsigned()->nullable();
            $table->float('service_charge')->unsigned()->nullable()->index();
            $table->enum('type',['paid', 'due-paid'])->index();
            $table->float('paid')->unsigned()->nullable();
            $table->string('invoice_number')->index();
            $table->float('discount_amount')->unsigned()->nullable();
            $table->float('special_discount')->unsigned()->nullable();
            $table->float('total')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
