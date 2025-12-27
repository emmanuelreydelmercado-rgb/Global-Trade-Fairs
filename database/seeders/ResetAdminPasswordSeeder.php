<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'emmanuelreydelmercado@gmail.com')->first();
        
        if ($user) {
            $user->password = Hash::make('reydel18');
            $user->save();
            echo "Password updated for: " . $user->email . "\n";
        } else {
            echo "User not found!\n";
        }
    }
}
