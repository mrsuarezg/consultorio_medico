<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DevDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PersonTypeSeeder::class,
            AddressTypeSeeder::class,
            PhoneNumberTypeSeeder::class,
            PhysicalPersonSeeder::class,
            PersonSeeder::class,
            BloodTypeSeeder::class,
            SpecialtySeeder::class,
            DoctorSeeder::class,
            // AddressSeeder::class,
            // PhoneNumberSeeder::class,
            ContraceptiveMethodSeeder::class,
        ]);
    }
}
