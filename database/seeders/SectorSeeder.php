<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'Utility',
        );

        foreach ($array as $row) {
            Sector::create([
                'name' => $row,
            ]);
        }

    }
}
