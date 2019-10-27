<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('account_id')->notNull();
            $table->unsignedBigInteger('transaction_id')->nullable()->default(null);
            $table->unsignedBigInteger('company_id')->nullable()->default(null);
            $table->tinyInteger('status')->index()->default(1);
            $table->tinyInteger('payment_type')->index();
            $table->double('amount')->index();
            $table->string('reference')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
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
        Schema::table('account_transactions', function(Blueprint $table){
            $table->dropIfExists('account_transactions');
        });
    }
}
