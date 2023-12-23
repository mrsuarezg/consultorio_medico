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
        Schema::create('vital_signs',static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('consultation_id')->constrained()->cascadeOnDelete();
            $table->string('blood_pressure')->comment('Presión arterial (TA = Tensión arterial)');
            $table->unsignedTinyInteger('heart_rate')->comment('Frecuencia cardiaca (pulso)');
            $table->unsignedTinyInteger('respiratory_rate')->comment('Frecuencia respiratoria');
            $table->float('body_temperature')->comment('Temperatura corporal');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
