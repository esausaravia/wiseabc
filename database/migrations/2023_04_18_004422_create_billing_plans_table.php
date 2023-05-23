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
        Schema::create('billing_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_region_id')->default(1)->nullable()->index();
            $table->foreign('bill_region_id')->references('id')->on('bill_regions')->onUpdate('cascade')->onDelete('set null');

            $table->unsignedSmallInteger('tipo')->index();
            $table->unsignedSmallInteger('ritmo')->index();
            $table->unsignedMediumInteger('price');
            $table->string('status')->default('ACTIVE')->index();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_plans');
    }
};
