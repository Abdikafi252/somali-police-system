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

        Facility::firstOrCreate(
            ['name' => 'Saldhigga Degmada Hodan'],
            [
                'type' => 'Station',
                'location' => 'Hodan, Mogadishu',
                'commander_id' => $admin->id,
            ]
        );

        Facility::firstOrCreate(
            ['name' => 'Garoonka Aadan Cadde'],
            [
                'type' => 'Checkpoint',
                'location' => 'Abdiaziz, Mogadishu',
                'commander_id' => $admin->id,
            ]
        );
    }
}
