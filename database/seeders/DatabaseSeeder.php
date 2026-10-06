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
            $admin = User::firstOrNew([
                'email' => strtolower(trim($email)),
            ]);

            if (! $admin->exists) {
                $admin->password = $password;
            }

            $admin->name = env('ADMIN_NAME', 'System Administrator');
            $admin->role = 'admin';
            $admin->email_verified_at = $admin->email_verified_at ?? now();
            $admin->save();
        }
    }
}
