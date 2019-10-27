<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200)->nullable()->index();
            $table->string('address')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('reference_number', 100)->index();
            $table->string('phone')->nullable()->index();
            $table->string('mobile')->nullable()->index();
            $table->string('fax')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('websiteurl')->nullable();
            $table->string('city')->nullable();
            $table->tinyInteger('status')->nullable()->index();
            $table->softDeletes();

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
        Schema::dropIfExists('companies');
    }
}
