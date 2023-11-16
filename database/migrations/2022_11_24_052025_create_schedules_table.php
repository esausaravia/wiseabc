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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classrooms')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('teams_id')->nullable();
            $table->foreign('teams_id')->nullable()->references('id')->on('teams_infos')->onUpdate('cascade')->onDelete('set null');

            $table->dateTimeTz('fechahora')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
