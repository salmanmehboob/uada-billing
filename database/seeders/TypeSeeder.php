<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(

             'Plot',
             'House',
             'Shop',

        );

        foreach ($array as $row) {
            Type::create([
                'name' =>$row,
            ]);
        }

    }
}
