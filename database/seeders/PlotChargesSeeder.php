<?php

namespace Database\Seeders;

use App\Models\PlotCharges;
use Illuminate\Database\Seeder;

class PlotChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plot_charges = array(
            array(
                "id" => 9,
                "size_id" => 1,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "250",
                "year" => "2023",
                "created_at" => "2023-07-26 08:02:20",
                "updated_at" => "2023-07-26 08:02:20",
            ),
            array(
                "id" => 10,
                "size_id" => 1,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "60",
                "year" => "2023",
                "created_at" => "2023-07-26 13:06:05",
                "updated_at" => "2023-07-26 13:06:06",
            ),
            array(
                "id" => 7,
                "size_id" => 2,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "300",
                "year" => "2023",
                "created_at" => "2023-07-26 08:02:20",
                "updated_at" => "2023-07-26 08:02:20",
            ),
            array(
                "id" => 8,
                "size_id" => 2,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "80",
                "year" => "2023",
                "created_at" => "2023-07-26 08:02:20",
                "updated_at" => "2023-07-26 08:02:20",
            ),
            array(
                "id" => 5,
                "size_id" => 4,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "400",
                "year" => "2023",
                "created_at" => "2023-07-26 08:02:20",
                "updated_at" => "2023-07-26 08:02:20",
            ),
            array(
                "id" => 6,
                "size_id" => 4,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "100",
                "year" => "2023",
                "created_at" => "2023-07-26 08:02:20",
                "updated_at" => "2023-07-26 08:02:20",
            ),
            array(
                "id" => 3,
                "size_id" => 5,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "500",
                "year" => "2023",
                "created_at" => "2023-07-26 08:01:51",
                "updated_at" => "2023-07-26 08:01:51",
            ),
            array(
                "id" => 4,
                "size_id" => 5,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "150",
                "year" => "2023",
                "created_at" => "2023-07-26 08:02:20",
                "updated_at" => "2023-07-26 08:02:20",
            ),
            array(
                "id" => 11,
                "size_id" => 6,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "460",
                "year" => "2023",
                "created_at" => "2023-07-26 13:07:17",
                "updated_at" => "2023-07-26 13:07:17",
            ),
            array(
                "id" => 12,
                "size_id" => 6,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "200",
                "year" => "2023",
                "created_at" => "2023-07-26 13:07:17",
                "updated_at" => "2023-07-26 13:07:17",
            ),
            array(
                "id" => 13,
                "size_id" => 7,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "500",
                "year" => "2023",
                "created_at" => "2023-07-26 13:09:11",
                "updated_at" => "2023-07-26 13:09:11",
            ),
            array(
                "id" => 14,
                "size_id" => 7,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "200",
                "year" => "2023",
                "created_at" => "2023-07-26 13:09:11",
                "updated_at" => "2023-07-26 13:09:11",
            ),
            array(
                "id" => 15,
                "size_id" => 8,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "500",
                "year" => "2023",
                "created_at" => "2023-07-26 13:09:11",
                "updated_at" => "2023-07-26 13:09:11",
            ),
            array(
                "id" => 16,
                "size_id" => 8,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "150",
                "year" => "2023",
                "created_at" => "2023-07-26 13:10:32",
                "updated_at" => "2023-07-26 13:10:32",
            ),
            array(
                "id" => 17,
                "size_id" => 9,
                "charge_id" => 1,
                "charge_type_id" => 2,
                "amount" => "200",
                "year" => "2023",
                "created_at" => "2023-07-26 13:10:32",
                "updated_at" => "2023-07-26 13:10:32",
            ),
            array(
                "id" => 18,
                "size_id" => 9,
                "charge_id" => 2,
                "charge_type_id" => 2,
                "amount" => "60",
                "year" => "2023",
                "created_at" => "2023-07-26 13:10:32",
                "updated_at" => "2023-07-26 13:10:32",
            ),
        );

        foreach ($plot_charges as $row) {
//            dd($row);
            PlotCharges::create([
                'size_id' => $row['size_id'],
                'charge_id' => $row['charge_id'],
                'charge_type_id' => $row['charge_type_id'],
                'amount' => $row['amount'],
                'year' => $row['year'],
            ]);
        }
    }
}
