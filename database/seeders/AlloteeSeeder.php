<?php

namespace Database\Seeders;

use App\Models\Allotee;
use App\Models\PlotCharges;
use Illuminate\Database\Seeder;

class AlloteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


             Allotee::create([
                 'plot_no' => 123,
                 'name'=> 'Salman',
                 'email'=> 'salman@gmail.com.com',
                 'phone_no'=> 123,
                 'account_no'=> '123456789',
                 'contact_person_name'=> 'salman',
                 'address'=> 'test',
                 'sector_id'=> 1,
                 'size_id'=> 1,
                 'is_active'=> 1,
                 'arrears'=> 2560,

            ]);

    }
}
