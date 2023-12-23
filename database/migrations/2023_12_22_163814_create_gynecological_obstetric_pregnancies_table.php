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
        Schema::create('gynecological_obstetric_pregnancies',static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained('patients')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('menarche')->nullable()->comment('Menarca');
            $table->string('IVSA')->nullable()->comment('IVSA (Inicio de vida sexual activa)');
            $table->string('number_of_partners')->nullable()->comment('Número de parejas');
            $table->string('pregnancies_G_P_C_A_O')->nullable()->comment('Embarazos G, P, C, A, O (Gestaciones, Partos, Cesáreas, Abortos, Obitos)');
            $table->string('contraceptive_method')->nullable()->comment('Método anticonceptivo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gynecological_obstetric_pregnancies');
    }
};
