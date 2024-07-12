<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Seed admin
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'phone' => '12345678901',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'otp' => null,
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed regular users
        DB::table('users')->insert([
            'name' => 'User 1',
            'email' => 'user1@test.com',
            'phone' => '987654321021',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'otp' => null,
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'User 2',
            'email' => 'user2@test.com',
            'phone' => '55555555555',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Replace with hashed password
            'otp' => null,
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
