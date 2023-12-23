<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('non_pathological_histories',static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('room')->nullable(); // Habitación
            $table->string('hygiene_habits')->nullable(); // Hábitos higiénicos
            $table->string('feeding')->nullable(); // Alimentación
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_pathological_histories');
    }
};
