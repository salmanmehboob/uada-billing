<?php

namespace Database\Seeders;

use App\Models\ChargeType;
use Illuminate\Database\Seeder;

class ChargeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            array(
                'id' => 1,
                'name' => 'Per Year',
            ),array(
                'id' => 2,
                'name' => 'Per Month',
            ),
            array(
                'id' => 3,
                'name' => 'Per Square Feet',
            ),
            array(
                'id' => 4,
                'name' => 'Per Marla',
            ),
            array(
                'id' => 5,
                'name' => 'Per Shop',
            ),
            array(
                'id' => 6,
                'name' => 'Lump Sum',
            ),
            array(
                'id' => 7,
                'name' => 'Per Trip',
            ),

        );

        foreach ($array as $row) {
            ChargeType::create([
                'id' => $row['id'],
                'name' => $row['name'],
            ]);
        }

    }
}
