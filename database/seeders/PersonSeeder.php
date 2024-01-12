<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $people = [
            [
                'personable_type' => 'App\Models\PhysicalPerson',
                'personable_id' => 1,
                'person_type_id' => 1
            ]
        ];

        Person::insert($people);
    }
}
