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
        Schema::create('physical_people', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('surname');
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro']);
            $table->date('birth_date');
            $table->string('civil_status');
            $table->string('religion');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'last_name', 'surname', 'gender', 'birth_date', 'civil_status', 'religion'], 'physical_people_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_people');
    }
};
