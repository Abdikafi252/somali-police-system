<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Askari Saldhig', 'slug' => 'askari'],
            ['name' => 'Taliyaha Saldhigga', 'slug' => 'taliye-saldhig'],
            ['name' => 'Sarkaalka CID', 'slug' => 'cid'],
            ['name' => 'Xeer-ilaaliye', 'slug' => 'prosecutor'],
            ['name' => 'Garsoore', 'slug' => 'judge'],
            ['name' => 'Taliyaha Gobolka', 'slug' => 'taliye-gobol'],
            ['name' => 'Taliyaha Qaranka', 'slug' => 'taliye-qaran'],
            ['name' => 'System Admin', 'slug' => 'admin'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
