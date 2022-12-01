<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default('active')->index(); //activo, inactivo
            $table->unsignedInteger('edad')->default(18)->index(); // 5, 11, 15, 18
            $table->unsignedInteger('nivel')->index(); //A1, A2, B1,
            $table->unsignedInteger('duracion')->default(48); //hrs
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
        Schema::dropIfExists('cursos');
    }
}
