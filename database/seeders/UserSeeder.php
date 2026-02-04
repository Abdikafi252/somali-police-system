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

        \App\Models\User::updateOrCreate(
            ['email' => 'admin@police.gov.so'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $adminRole->id,
                'station_id' => $station->id,
                'region_id' => 'Banaadir',
                'status' => 'active',
            ]
        );

        // Add CID Officer (Baare)
        $cidRole = \App\Models\Role::where('slug', 'cid')->first();
        if($cidRole) {
            \App\Models\User::updateOrCreate(
                ['email' => 'cid@police.gov.so'],
                [
                    'name' => 'Baare CID',
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role_id' => $cidRole->id,
                    'station_id' => $station->id,
                    'region_id' => 'Banaadir',
                    'status' => 'active',
                ]
            );
        }
        $officerRole = \App\Models\Role::where('slug', 'askari')->first();
        \App\Models\User::updateOrCreate(
            ['email' => 'officer@police.gov.so'],
            [
                'name' => 'Askari Cali',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $officerRole->id,
                'station_id' => $station->id,
                'region_id' => 'Banaadir',
                'status' => 'active',
            ]
        );
    }
}
