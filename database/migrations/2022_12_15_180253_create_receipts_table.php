<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            //id autoincremental
            $table->bigIncrements('id');
            $table->unsignedBigInteger('class_attendances_id')->unsigned();
            $table->unsignedBigInteger('payment_id')->unsigned();
            $table->string('estatus');
            $table->string('amount');
            $table->foreign('class_attendances_id')->references('id')->on('class_attendances');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts', function (Blueprint $table) {
            $table->dropForeign('class_attendances_id');
            $table->dropForeign('payment_id');
        });




    }
};
