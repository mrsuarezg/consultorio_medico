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
        Schema::create('patients',static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('person_id')->unique()->constrained('people')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('blood_type_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('occupation')->nullable(); // Ocupación
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
