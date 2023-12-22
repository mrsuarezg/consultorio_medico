<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonData = [
            'email_verified_at' => now(),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => null,
            'profile_photo_path' => null,
            'current_team_id' => null,
        ];

        $users = [
            [
                // 'id' => 1,
                'name' => 'Cesar Suárez Granados',
                'password' => Hash::make('Admin1234'),
                'email' => 'devcsuarez@gmail.com',
                'role' => 'super_admin',
            ],
            [
                // 'id' => 2,
                'name' => 'José Luis Serrano Arellano',
                'password' => Hash::make('Admin1234'),
                'email' => 'joseluis@gmail.com',
                'role' => 'doctor',
            ]
        ];

        foreach ($users as $user) {
            $role = $user['role'];
            unset($user['role']);

            $createdUser = User::query()->create($user + $commonData);
            $createdUser->assignRole($role);
        }

    }
}
