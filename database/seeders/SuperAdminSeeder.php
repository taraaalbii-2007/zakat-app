<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengguna')->insert([
            'uuid' => Str::uuid(),
            'peran' => 'superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678'),
            'google_id' => null,
            'google_token' => null,
            'refresh_token' => null,
            'is_active' => true,
            'verification_token' => null,
            'verification_token_expires_at' => null,
            'password_reset_token' => null,
            'password_reset_token_expires_at' => null,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}