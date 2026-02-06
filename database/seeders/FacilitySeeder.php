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
        $adminId = $admin ? $admin->id : null;

        // Existing Default Station
        Facility::firstOrCreate(
            ['name' => 'Saldhigga Degmada Hodan'],
            [
                'type' => 'Station',
                'location' => 'Hodan, Mogadishu',
                'commander_id' => $adminId,
            ]
        );

        //  Courts List (User Request)
        $courts = [
            'Maxkamadda Degmada Hodan',
            'Maxkamadda Degmada Wadajir',
            'Maxkamadda Degmada Hamarweyne',
            'Maxkamadda Degmada Hamarjajab',
            'Maxkamadda Degmada Shibis',
            'Maxkamadda Degmada Cabdicasiis',
            'Maxkamadda Degmada Kaaraan',
            'Maxkamadda Degmada Yaaqshiid',
            'Maxkamadda Degmada Hiliwaa',
            'Maxkamadda Degmada Shangani',
            'Maxkamadda Degmada Waberi',
            'Maxkamadda Degmada Dharkenley',
            'Maxkamadda Degmada Kaxda',
            'Maxkamadda Degmada Dayniile',
            'Maxkamadda Degmada Bondheere',
            'Maxkamadda Degmada Wardhiigle',
            'Maxkamadda Gobolka Banaadir',
            'Maxkamadda Racfaanka',
            'Maxkamadda Sare - Somalia',
            'Maxkamadda Militariga',
        ];

        foreach ($courts as $name) {
            $location = 'Muqdisho';
            if (strpos($name, 'Degmada') !== false) {
                $parts = explode(' ', $name);
                $district = end($parts);
                $location = "Degmada {$district}, Muqdisho";
            }

            Facility::firstOrCreate(
                ['name' => $name],
                [
                    'type' => 'Court',
                    'location' => $location,
                    'security_level' => 'High',
                    'status' => 'Active',
                    'commander_id' => $adminId
                ]
            );
        }
    }
}
