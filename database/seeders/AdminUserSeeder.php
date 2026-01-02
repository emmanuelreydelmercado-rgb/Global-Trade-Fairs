<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = User::where('email', 'admin@globaltrade.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@globaltrade.com',
                'password' => Hash::make('admin123'), // Change this password!
                'role' => 'admin', // Assuming you have a role field
            ]);
            
            echo "Admin user created successfully!\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
}
