<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeyRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function($table){
            $table->foreign('hotel_id')->references('id')->on('hotels');
        });

        Schema::table('rents', function($table){
            $table->foreign('hotel_id')->references('id')->on('hotels');
            $table->foreign('room_id')->references('id')->on('rooms');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
