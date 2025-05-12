<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(

             '20M',
             '7M',
             '15M',
             '10M',
             '05M',
             '03M',
             '4K',
             '20K',
             '11K',
             '6K',
             '3.5 kanal',
             '8K',
             '10K',
             'Hospital/Clinic',
             'School',
             'Restaurant',
             'Shop',

        );

        foreach ($array as $row) {
            Size::create([
                'name' =>$row,
            ]);
        }

    }
}
