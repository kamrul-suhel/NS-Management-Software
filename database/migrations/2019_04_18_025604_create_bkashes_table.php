<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBkashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bkashes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unsigned()->index()->notNull();
            $table->string('phone_number', '20')->index();
            $table->float('amount')->unsigned()->notNull();
            $table->tinyInteger('status')->unsigned()->default(0)->index();
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bkashes');
    }
}
