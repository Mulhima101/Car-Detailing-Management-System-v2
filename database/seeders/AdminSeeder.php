<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Check if admin exists before creating
        if (!User::where('email', 'admin@autox.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@autox.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]);
        }
    }
}