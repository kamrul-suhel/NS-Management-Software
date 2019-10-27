<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status')->index()->default(1);
            $table->unsignedBigInteger('bank_id')->notNull();
            $table->string('name','100')->index();
            $table->string('account_number', 20)->unique()->index()->notNull();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bank_id')
                ->references('id')
                ->on('banks')
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
        Schema::table('accounts', function(Blueprint $table){
            $table->dropIfExists('accounts');
        });
    }
}
