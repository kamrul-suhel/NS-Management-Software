<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_id')->index()->unsigned();
            $table->integer('room_id')->index()->unsigned();
            $table->integer('staff_id')->unsigned()->index();
            $table->string('client_name')->index();
            $table->string('father_name', 100);
            $table->string('ni_number', 100)->index();
            $table->text('client_address');
            $table->string('client_phone', 30)->nullable()->index();
            $table->float('advance')->unsigned()->index()->nullable()->default(0);
            $table->float('total')->unsigned()->index()->nullable();
            $table->float('discount_amount')->unsigned()->nullable();
            $table->dateTime('check_in')->index()->nullable();
            $table->dateTime('check_out')->index()->nullable();
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
        Schema::dropIfExists('rents');
    }
}
