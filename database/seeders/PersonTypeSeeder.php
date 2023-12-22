<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace Database\Seeders;

use App\Models\PersonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class PersonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createdAt = now();
        $updatedAt = $createdAt;

        $personTypes = [
            [
                'name' => 'Física',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            [
                'name' => 'Moral',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
        ];

        PersonType::insert($personTypes);
    }
}
