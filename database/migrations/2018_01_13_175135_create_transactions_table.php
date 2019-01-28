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
            $table->integer('hotel_id')->index()->unsigned();
            $table->string('client_name')->unsigned()->index();
            $table->text('client_address')->unsigned();
            $table->string('client_phone', 30)->unsigned()->index();
            $table->integer('staff_id')->unsigned()->index();
            $table->float('paid')->unsigned()->nullable();
            $table->float('discount_amount')->unsigned()->nullable();
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
