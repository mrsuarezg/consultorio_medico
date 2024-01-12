<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'person_id' => 1,
                'specialty_id' => 15,
                'professional_card' => 1481132
            ]
        ];

        Doctor::insert($doctors);
    }
}
