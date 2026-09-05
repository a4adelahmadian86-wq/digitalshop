<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['phone' => '09151234567'],
            [
                'first_name' => 'مدیر',
                'last_name' => 'اصلی',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'is_active' => true,
                'phone_verified_at' => now(),
            ]
        );

        $user->syncSystemRole();
    }
}
