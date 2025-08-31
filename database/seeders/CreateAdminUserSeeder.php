<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates or updates a single bootstrap user, using .env when available.
     */
    public function run(): void
    {
        // Read credentials from .env (with sane defaults for local dev)
        $name     = env('SEED_ADMIN_NAME', 'Super Admin');
        $email    = env('SEED_ADMIN_EMAIL', 'admin@gmail.com');
        $password = env('SEED_ADMIN_PASSWORD', 'password'); // change this in .env!

        // Build the payload for update/create
        $data = [
            'id'              => '9fbe5eae-9762-44db-b0c6-8d6493515f9f',
            'full_name'         => 'Super Admin',
            'email'             => $email,
            'email_verified_at' => now(),                // remove if you use email verification workflow strictly
            'password'          => Hash::make($password),
            'remember_token'    => Str::random(10),
        ];

        // If your users table has extra columns (e.g., role, is_super_admin), set them here:
        // $data['is_super_admin'] = true;      // uncomment if exists
        // $data['role'] = 'super_admin';       // or role_id if you use role IDs

        // Idempotent: updates the existing user (by email) or creates a new one.
        User::updateOrCreate(['email' => $email], $data);

        $this->command->info("Seeded admin user: {$email}");
    }
}
