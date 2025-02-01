<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Jobs\SyncRowJob;
use App\Models\InstallerCard;
use Illuminate\Console\Command;
use App\Models\InstallerCardPoint;

class DeductExpiredPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'points:deduct-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deduct total expired pints from installer card';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $installercards = InstallerCard::where('status','1')
                            ->orderBy("created_at", "asc")
                            ->orderBy('id','asc')
                            ->get();


        foreach($installercards as $installercard){
            $installercardpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                                    ->where("is_redeemed", "0")
                                    ->where("expiry_date", "<", Carbon::now())
                                    ->where('expire_deduction_date',NULL)
                                    ->orderBy("created_at", "asc")
                                    ->orderBy('id','asc')
                                    ->get();

            $totalDedeductPoints = 0;
            $totalDeductAmount = 0;
            foreach($installercardpoints as $installercardpoint){


                $totalDedeductPoints += $installercardpoint->points_balance;
                $totalDeductAmount += $installercardpoint->amount_balance;

                $installercardpoint->update([
                    'points_balance'=>0,
                    'amount_balance'=>0,
                    'is_redeemed'=>1,
                    'expire_deduction_date'=> now()
                ]);
            }

            $installercard->update([
                "totalpoints"=>  $installercard->totalpoints - $totalDedeductPoints,
                "totalamount"=> $installercard->totalamount - $totalDeductAmount,
                'expire_points'=> $installercard->expire_points + $totalDedeductPoints,
                'expire_amount'=> $installercard->expire_amount + $totalDeductAmount,
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$installercard));
        }


    }
}
