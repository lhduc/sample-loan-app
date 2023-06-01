<?php

namespace Database\Seeders;

use App\Constants\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'type' => UserType::ADMIN,
            ]
        );

        User::firstOrCreate(
            ['email' => 'lehongduc87@gmail.com'],
            [
                'name' => 'Duc Le',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'type' => UserType::USER,
            ]
        );

        User::firstOrCreate(
            ['email' => 'elisa.doan@gmail.com'],
            [
                'name' => 'Elisa Doan',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'type' => UserType::USER,
            ]
        );
    }
}
