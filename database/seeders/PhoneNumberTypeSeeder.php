<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class PhoneNumberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createdAt = now();
        $updatedAt = $createdAt;

        $phoneNumberTypes = [
            [
                'name' => 'Casa',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            [
                'name' => 'Celular',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            [
                'name' => 'Oficina',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            [
                'name' => 'Trabajo',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            [
                'name' => 'Otro',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
        ];

        \App\Models\PhoneNumberType::insert($phoneNumberTypes);
    }
}
