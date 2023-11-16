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
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classrooms')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('teams_id')->nullable();
            $table->foreign('teams_id')->references('id')->on('teams_infos')->onUpdate('cascade')->onDelete('set null');

            $table->unsignedBigInteger('payout_id')->nullable();
            $table->foreign('payout_id')->references('id')->on('payouts')->onUpdate('cascade')->onDelete('set null');

            $table->dateTimeTz('fechahora')->index();
            $table->unsignedInteger('duracion')->default(0); //40 mins * 60 seg = 2400 seg
            $table->boolean('puntual')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
