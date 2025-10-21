<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'number' => '1234567890',
                'photo' => null,
                'company_name' => 'Admin Company',
                'address' => 'Admin Address',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('asdasd'),
                'role_id' => 1, // Admin role
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'House Owner',
                'email' => 'owner@gmail.com',
                'number' => '1234567891',
                'photo' => null,
                'company_name' => 'Owner Company',
                'address' => 'Owner Address',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('asdasd'),
                'role_id' => 2, // House Owner role
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tenant User',
                'email' => 'tenant@gmail.com',
                'number' => '1234567892',
                'photo' => null,
                'company_name' => null,
                'address' => 'Tenant Address',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('asdasd'),
                'role_id' => 3, // Tenant role
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}