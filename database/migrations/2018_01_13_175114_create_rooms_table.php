<?php

use App\Room;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_id')->index()->unsigned();
            $table->string('title');
            $table->string('description', 1000)->nullable();
            $table->decimal('price')->unsigned()->index();
            $table->decimal('additional_price')->unsigned()->nullable()->default(0.00);
            $table->string('status')->default(Room::UNAVAILABLE_PRODUCT);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('rooms');
    }
}
