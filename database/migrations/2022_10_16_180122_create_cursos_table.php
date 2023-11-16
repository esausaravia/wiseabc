<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->default('ACTIVE')->index();
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
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
