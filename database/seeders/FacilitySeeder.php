<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\User;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        Facility::create([
            'name' => 'Saldhigga Degmada Hodan',
            'type' => 'Station',
            'location' => 'Hodan, Mogadishu',
            'commander_id' => $admin->id,
        ]);

        Facility::create([
            'name' => 'Garoonka Aadan Cadde',
            'type' => 'Checkpoint',
            'location' => 'Abdiaziz, Mogadishu',
            'commander_id' => $admin->id,
        ]);
    }
}
