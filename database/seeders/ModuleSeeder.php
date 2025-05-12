<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array (
            'Allotee',
            'Bill',
            'Bill Non Period',
            'Charges',
            'PlotCharges',
            'Sector',
            'Setting',
            'Size',
            'Users',
            'Permissions',
            'Roles',
            'Bank',
            'Type',
            'Report',
            'Bill Violation',
            'Bill Non User',
            'Bill Stamp Duty',
            'Bill Yearly',
        );

        foreach ($modules as $row) {
            Module::firstOrCreate([
                'name' => $row,
            ]);
        }
    }
}
