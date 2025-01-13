<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PointsRedemption;

class PointsRedemptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PointsRedemption::create([
            'invoice_number'=>'INV12345',
            "category_id"=>"1",
            "points_redeemed"=>10,
            "pointaccumulated"=>100,
            "redemption_amount"=>1000,
            "redemption_date"=>now()
        ]);

        PointsRedemption::create([
            'invoice_number'=>'INV12345',
            "category_id"=>"2",
            "points_redeemed"=>20,
            "pointaccumulated"=>200,
            "redemption_amount"=>4000,
            "redemption_date"=>now()
        ]);

    }
}
