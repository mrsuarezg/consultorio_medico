<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace Database\Seeders;

use App\Models\PhysicalPerson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class PhysicalPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $physicalPersons = [
            [
                'name' => 'José Luis',
                'first_surname' => 'Serrano',
                'surname' => 'Arellano',
                'gender' => 'Masculino',
                'birth_date' => '1979-12-01',
                'civil_status' => 'Casado',
                'religion' => 'Católico'
            ],
        ];

        PhysicalPerson::insert($physicalPersons);
    }
}
