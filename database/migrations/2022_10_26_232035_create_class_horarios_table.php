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
        Schema::create('class_horarios', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classrooms');
            $table->unsignedInteger('dia');
            $table->unsignedInteger('hr');
            $table->primary(['class_id','dia','hr']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_horarios');
    }
};
