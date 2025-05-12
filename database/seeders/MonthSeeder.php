<?php
namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $months = [];

        $year = Carbon::now()->year; // Get the current year

        for ($month = 1; $month <= 12; $month++) {
            $months[] = [
                'name' => Carbon::create($year, $month, 1)->format('F'),
                'short' => Carbon::create($year, $month, 1)->format('M'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Month::insert($months);
    }
}
