<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 0. Disable FK checks
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // 1. Delete existing courts
        \App\Models\Facility::where('type', 'Court')
            ->orWhere('name', 'LIKE', '%Maxkamad%')
            ->delete();

        // 2. New List
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

            \App\Models\Facility::create([
                'name' => $name,
                'type' => 'Court',
                'location' => $location,
                'security_level' => 'High',
                'status' => 'Active'
            ]);
        }

        // Re-enable FK checks
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
