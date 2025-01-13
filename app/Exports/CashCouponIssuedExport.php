<?php

namespace App\Exports;

use App\Models\LuckyDraw;
use App\Models\IssuedPrize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CashCouponIssuedExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($uuid,$start_date,$end_date)
    {
        $this->uuid = $uuid;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    public function view(): View
    {
        $uuid = $this->uuid;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $promotion_name = LuckyDraw::where('uuid', $uuid)->first();
        $result = IssuedPrize::where('issued_prizes.promotion_uuid', $uuid)
                            ->where('promotions.start_date', $start_date)->where('promotions.end_date', $end_date)
                            ->join('promotions','promotions.uuid','issued_prizes.promotion_uuid')
                            ->get();
        return view('reports.cash_coupon_issued_export', compact('result','promotion_name'));
    }
}



