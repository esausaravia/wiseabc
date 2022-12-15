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
        Schema::create('pivots', function (Blueprint $table) {
            $table->unsignedBigInteger('receipt_id')->unsigned();
            $table->unsignedBigInteger('payment_concepts_id')->unsigned();
            $table->foreign('receipt_id')->references('id')->on('receipts');
            $table->foreign('payment_concepts_id')->references('id')->on('payment_concepts');
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
        Schema::dropIfExists('pivots', function (Blueprint $table) {
            $table->dropForeign('receipt_id');
            $table->dropForeign('payment_concepts_id');
        });
    }
};
