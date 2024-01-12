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
        Schema::create('contraceptive_methods',static function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique()->comment('Nombre');
            $table->string('description')->nullable()->comment('Descripción');
            $table->string('observations')->nullable()->comment('Observaciones');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contraceptive_methods');
    }
};
