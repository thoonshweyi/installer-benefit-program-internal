<?php

namespace App\Exports;

use App\Models\LuckyDraw;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Http\Controllers\ReportController;

class CouponNumberDetailExport implements FromView
{

    public function __construct($promotion_uuid)
    {
        $this->promotion_uuid = $promotion_uuid;
    }

    protected function promotion_connection()
    {
        return new LuckyDraw();
    }

    protected function report_connection()
    {
        return new ReportController();
    }

    public function view(): View
    {
        $uuid = $this->promotion_uuid;
        $promotion = $this->promotion_connection()->where('uuid',$uuid)->first();
        $header = ['Branches'];
        $lanthit = ['Lanthit'];
        $satsan = ['Satsan'];
        $eastdagon = ['Eastdagon'];
        $hlaingthaya = ['Hlaingthaya'];
        $terminal_m = ['Terminal M'];
        $theikpan = ['Theikpan'];
        $tampawady = ['Tampawady'];
        $aye_thayar = ['Aye Thayar'];
        $mawlamyine = ['Mawlamyine'];
        $southdagon = ['South Dagon'];

        //Add Total
        array_push($header, 'Total');

        $lanthit_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,1);
        array_push($lanthit, $lanthit_coupon_total);
        $lanthit_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,1);
        array_push($header, $lanthit_coupon_total);
        array_push($lanthit, $lanthit_coupon_total);

        $satsan_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,3);
        array_push($satsan, $satsan_coupon_total);
        $satsan_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,3);
        array_push($satsan, $satsan_coupon_total);

        $eastdagon_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,9);
        array_push($eastdagon, $eastdagon_coupon_total);
        $eastdagon_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,9);
        array_push($eastdagon, $eastdagon_coupon_total);

        $hlaingthaya_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,19);
        array_push($hlaingthaya, $hlaingthaya_coupon_total);
        $hlaingthaya_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,19);
        array_push($hlaingthaya, $hlaingthaya_coupon_total);

        $terminal_m_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,27);
        array_push($terminal_m, $terminal_m_coupon_total);
        $terminal_m_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,27);
        array_push($terminal_m, $terminal_m_coupon_total);

        $theikpan_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,2);
        array_push($theikpan, $theikpan_coupon_total);
        $theikpan_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,2);
        array_push($theikpan, $theikpan_coupon_total);

        $tampawady_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,11);
        array_push($tampawady, $tampawady_coupon_total);
        $tampawady_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,11);
        array_push($tampawady, $tampawady_coupon_total);

        $aye_thayar_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,21);
        array_push($aye_thayar, $aye_thayar_coupon_total);
        $aye_thayar_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,21);
        array_push($aye_thayar, $aye_thayar_coupon_total);

        $mawlamyine_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,10);
        array_push($mawlamyine, $mawlamyine_coupon_total);
        $mawlamyine_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,10);
        array_push($mawlamyine, $mawlamyine_coupon_total);

        $southdagon_coupon_total = $this->report_connection()->coupon_total_by_branch_id($uuid,28);
        array_push($southdagon, $southdagon_coupon_total);
        $southdagon_coupon_total = $this->report_connection()->coupon_total_detail_by_branch_id($promotion,28);
        array_push($southdagon, $southdagon_coupon_total);

        return view('reports.coupon_number.coupon_number_export', compact('promotion','header','lanthit','satsan','eastdagon','hlaingthaya','terminal_m','theikpan','tampawady','aye_thayar','mawlamyine','southdagon'));
    }

}
