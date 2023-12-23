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
        Schema::create('pathological_histories',static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('infectious_diseases')->nullable()->comment('Enfermedades infecto contagiosas');
            $table->string('chronic_degenerative_diseases')->nullable()->comment('Enfermedades crónico degenerativas');
            $table->string('allergies')->nullable()->comment('Alergias');
            $table->string('surgical_interventions')->nullable()->comment('Intervenciones quirúrgicas');
            $table->string('traumatism')->nullable()->comment('Traumatismos');
            $table->string('transfusions')->nullable()->comment('Transfusiones');
            $table->string('seizures')->nullable()->comment('Convulsiones');
            $table->string('addictions')->nullable()->comment('Adicciones');
            $table->string('hospitalizations')->nullable()->comment('Hospitalizaciones');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathological_histories');
    }
};
