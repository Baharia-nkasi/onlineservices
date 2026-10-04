<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            ServiceDocumentSeeder::class,
        ]);

        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if ($email && $password) {
            User::updateOrCreate(
                ['email' => strtolower(trim($email))],
                [
                    'name' => env('ADMIN_NAME', 'System Administrator'),
                    'password' => $password,
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
