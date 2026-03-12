<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create the default admin account if it does not already exist.
        // Change the password immediately after first login.
        User::firstOrCreate(
            ['email' => 'admin@cogon.gov.ph'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Admin@1234'),
                'role' => 'admin',
            ]
        );
    }
}
