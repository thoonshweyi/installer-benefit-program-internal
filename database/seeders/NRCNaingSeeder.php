<?php

namespace Database\Seeders;

use App\Models\NRCNaing;
use Illuminate\Database\Seeder;

class NRCNaingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nrc_naings = [
            ["id" => 1, "shortname" => "(N)"],
            ["id" => 2, "shortname" => "(E)"],
            ["id" => 3, "shortname" => "(P)"],
            ["id" => 4, "shortname" => "(A)"],
            ["id" => 5, "shortname" => "(F)"],
            ["id" => 6, "shortname" => "(TH)"],
            ["id" => 7, "shortname" => "(G)"],
        ];   
        foreach ($nrc_naings as $nrc_naing) {
            NRCNaing::create($nrc_naing);
        }
    }
}
