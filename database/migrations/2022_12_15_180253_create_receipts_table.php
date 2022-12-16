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
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->string('status');
            $table->unsignedInteger('amount');
            $table->foreign('attendance_id')->references('id')->on('attendances');
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
            $table->dropForeign('attendance_id');
            $table->dropForeign('payment_id');
        });




    }
};
