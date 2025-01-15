<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\InstallerCardPoint;
use Illuminate\Database\Seeder;

class InstallerCardPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InstallerCardPoint::create([
            'installer_card_card_number'=>"303333331",
            'invoice_number'=>"INV12345",
            'point_promotion_uud'=>"85ae08ba-c5be-4cfd-878d-9fe5d15b20cf",
            'category_id'=>"1",
            'points_earned'=>20,
            'points_redeemed'=>0,
            'points_balance'=>20,
            'point_based'=>100,
            'amount_earned'=>2000,
            'amount_redeemed'=>0,
            'amount_balance'=>2000,
            'expiry_date'=>Carbon::now()->addDay(1),
            'is_redeemed'=>"0",
            'user_uuid'=>"7fe4da95-104d-4bc5-899b-0b6f777893ec"
        ]);


        InstallerCardPoint::create([
            'installer_card_card_number'=>"303333331",
            'invoice_number'=>"INV12345",
            'point_promotion_uud'=>"85ae08ba-c5be-4cfd-878d-9fe5d15b20cf",
            'category_id'=>"2",
            'points_earned'=>30,
            'points_redeemed'=>0,
            'points_balance'=>30,
            'point_based'=>200,
            'amount_earned'=>6000,
            'amount_redeemed'=>0,
            'amount_balance'=>6000,
            'expiry_date'=>Carbon::now()->addDay(1),
            'is_redeemed'=>"0",
            'user_uuid'=>"7fe4da95-104d-4bc5-899b-0b6f777893ec"
        ]);
    }
}
