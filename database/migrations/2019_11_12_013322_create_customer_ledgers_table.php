<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('particular')->nullable();
            $table->string('reference')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('transition_id')->nullable();
            $table->unsignedBigInteger('customer_id')->index();
            $table->double('debit',12,2)->default(0.00);
            $table->double('credit', 12, 2)->default(0.00);
            $table->double('balance', 12, 2)->default(0.00);
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
        Schema::dropIfExists('customer_ledgers');
    }
}
