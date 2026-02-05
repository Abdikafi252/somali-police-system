<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courts = [
            ['name' => 'Maxkamadda Degmada Boondheere', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Cabdicasiis', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Dayniile', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Dharkenley', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Heliwaa', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Hodan', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Howlwadaag', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Kaaraan', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Kaxda', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Shangaani', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Shibis', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Wadajir', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Xamar Jajab', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Xamar Weyne', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Degmada Yaaqshiid', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Gobolka Banaadir', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Racfaanka Gobolka Banaadir', 'location' => 'Muqdisho'],
            ['name' => 'Maxkamadda Sare ee Dalka Soomaaliya', 'location' => 'Muqdisho'],
        ];

        foreach ($courts as $court) {
            \App\Models\Facility::updateOrCreate(
                ['name' => $court['name']],
                [
                    'type' => 'Court',
                    'location' => $court['location'],
                    'security_level' => str_contains($court['name'], 'Sare') || str_contains($court['name'], 'Racfaan') || str_contains($court['name'], 'Gobolka') ? 'high' : 'normal'
                ]
            );
        }
    }
}
