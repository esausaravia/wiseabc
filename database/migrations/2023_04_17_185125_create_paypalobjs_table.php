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
        Schema::create('paypalobjs', function (Blueprint $table) {
            $table->id();
            $table->string('paypal_id');
            $table->text('object');
            $table->unsignedBigInteger('paypalable_id')->index();
            $table->string('paypalable_type')->index();
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
        Schema::dropIfExists('paypalobjs');
    }
};
