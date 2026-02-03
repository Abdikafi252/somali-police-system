<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        $station = \App\Models\Station::first();

        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@police.gov.so',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => $adminRole->id,
            'station_id' => $station->id,
            'region_id' => 'Banaadir',
            'status' => 'active',
        ]);

        // Add an officer
        $officerRole = \App\Models\Role::where('slug', 'askari')->first();
        \App\Models\User::create([
            'name' => 'Askari Cali',
            'email' => 'officer@police.gov.so',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role_id' => $officerRole->id,
            'station_id' => $station->id,
            'region_id' => 'Banaadir',
            'status' => 'active',
        ]);
    }
}
