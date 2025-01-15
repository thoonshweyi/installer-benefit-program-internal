<?php

namespace Database\Seeders;

use App\Models\NRCNumber;
use Illuminate\Database\Seeder;

class NRCNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nrc_numbers = [
            ["id" => 1, "nrc_number_name" => "1/"],
            ["id" => 2, "nrc_number_name" => "2/"],
            ["id" => 3, "nrc_number_name" => "3/"],
            ["id" => 4, "nrc_number_name" => "4/"],
            ["id" => 5, "nrc_number_name" => "5/"],
            ["id" => 6, "nrc_number_name" => "6/"],
            ["id" => 7, "nrc_number_name" => "7/"],
            ["id" => 8, "nrc_number_name" => "8/"],
            ["id" => 9, "nrc_number_name" => "9/"],
            ["id" => 10, "nrc_number_name" => "10/"],
            ["id" => 11, "nrc_number_name" => "11/"],
            ["id" => 12, "nrc_number_name" => "12/"],
            ["id" => 13, "nrc_number_name" => "13/"],
            ["id" => 14, "nrc_number_name" => "14/"],
            ["id" => 15, "nrc_number_name" => "15/"],
            ["id" => 16, "nrc_number_name" => "16/"],
            ["id" => 17, "nrc_number_name" => "17/"],
            ["id" => 18, "nrc_number_name" => "18/"],
            ["id" => 19, "nrc_number_name" => "19/"],
            ["id" => 20, "nrc_number_name" => "20/"],
         
        ];

        foreach ($nrc_numbers as $nrc_number) {
            NRCNumber::create($nrc_number);
        }
    }
}
