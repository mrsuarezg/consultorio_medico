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
        Schema::create('somatometries',static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('weight')->comment('Peso');
            $table->float('height')->comment('Talla');
            $table->float('IMC')->comment('IMC (Índice de masa corporal)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('somatometries');
    }
};
