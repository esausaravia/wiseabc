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
        Schema::create('attendance_pconcept', function (Blueprint $table) {
            $table->unsignedBigInteger('attendance_id');
            $table->foreign('attendance_id')->references('id')->on('attendances');

            $table->unsignedBigInteger('pconcept_id');
            $table->foreign('pconcept_id')->references('id')->on('payment_concepts');

            $table->unsignedInteger('amount')->default(0);

            $table->primary(['attendance_id', 'pconcept_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_pconcept');
    }
};
