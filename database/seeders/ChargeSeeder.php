<?php

namespace Database\Seeders;

use App\Models\Charge;
use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            'Water Charges',
            'Conservancy Charges',
            'Non User Charges',
            'Possession Fee',
            'Legal Heir Fee',
            'Attorney Fee',
            'Duplicate Fee',
            'N.O.C Fee',
            'Water Connection Fee',
            'Sewerage Connection Fee',
            'Building Plan Approval Fee',
            'Malba Fee',
            'Violation Fee',
            'Completion Certificate Fee',
            'Boundary Wall Fee',
            'Mumty Fee',
            'Time Extension Charges',
            'Water Tank Charges',
            'Land Use Violation Charges',
        );

        foreach ($array as $row) {
            Charge::create([
                'name' => $row,
            ]);
        }

    }
}
