<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản trị viên',
                'phone' => '0900000000',
                'address' => 'Việt Nam',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );
    }
}