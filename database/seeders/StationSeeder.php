<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        Station::create([
            'station_name' => 'Saldhigga Bartamaha',
            'location' => 'Waberi, Mogadishu',
        ]);

        Station::create([
            'station_name' => 'Saldhigga Hodan',
            'location' => 'Hodan, Mogadishu',
        ]);
    }
}
