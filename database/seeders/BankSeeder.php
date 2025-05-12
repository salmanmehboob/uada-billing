<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = array(
            array(
                "id" => 1,
                "name" => "UBL",
                "branch" => "MINGORA",
                "account_no" => "142689878567767",
                "created_at" => "2023-07-26 12:25:57",
                "updated_at" => "2023-07-26 12:25:57",
                "deleted_at" => NULL,
                "is_active" => 1,
            ),
            array(
                "id" => 2,
                "name" => "Habib Metro",
                "branch" => "Consumer Detail ID",
                "account_no" => "388",
                "created_at" => "2023-07-26 12:25:57",
                "updated_at" => "2023-07-26 12:25:57",
                "deleted_at" => NULL,
                "is_active" => 1,
            ),
            array(
                "id" => 3,
                "name" => "Habib Metro",
                "branch" => "Consumer Detail ID",
                "account_no" => "400",
                "created_at" => "2023-07-26 12:25:57",
                "updated_at" => "2023-07-26 12:25:57",
                "deleted_at" => NULL,
                "is_active" => 1,
            ),
            array(
                "id" => 4,
                "name" => "MCB",
                "branch" => "Kanju",
                "account_no" => "129900",
                "created_at" => "2023-07-26 12:25:57",
                "updated_at" => "2023-07-26 12:25:57",
                "deleted_at" => NULL,
                "is_active" => 1,
            ),
        );
        foreach ($banks as $row) {
            Bank::create([
                'name' =>$row['name'],
                'branch' =>$row['branch'],
                'account_no' =>$row['account_no'],
            ]);
        }

    }
}
