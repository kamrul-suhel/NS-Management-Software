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
            $table->string('client_name')->index();
            $table->text('father_name');
            $table->text('client_address');
            $table->string('client_phone', 30)->nullable()->index();
            $table->integer('staff_id')->unsigned()->index();
            $table->float('discount_amount')->unsigned()->nullable();
            $table->float('total')->unsigned()->index();
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
