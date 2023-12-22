<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

final class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view patients', 'guard_name' => 'web']);
        Permission::create(['name' => 'create patients', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit patients', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete patients', 'guard_name' => 'web']);
    }
}
