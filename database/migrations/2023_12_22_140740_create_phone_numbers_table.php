<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phone_numbers', static function (Blueprint $table): void {
            $table->id();
            $table->string('number', 10)->unique();
            $table->string('country_code', 4)->default('52');
            $table->foreignId('type_id')->constrained('phone_number_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_numbers');
    }
};
