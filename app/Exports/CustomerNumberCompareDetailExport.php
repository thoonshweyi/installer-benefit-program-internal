<?php

namespace App\Exports;

use App\Models\LuckyDraw;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Http\Controllers\ReportController;

class CustomerNumberCompareDetailExport implements FromView
{

    public function __construct($promotion_uuid,$compare_name)
    {
        $this->promotion_uuid = $promotion_uuid;
        $this->compare_name = $compare_name;
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
        $compare_name = $this->compare_name;
        $promotion_uuid = $this->promotion_uuid;

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
        
        $used_promotions = $this->promotion_connection()->wherein('uuid',$promotion_uuid)->get();

        if($compare_name == 1){
            foreach($used_promotions as $u_promotion){
                array_push($header, $u_promotion->name);
                $lanthit_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,1);
                array_push($lanthit, $lanthit_customer_total);
                $satsan_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,3);
                array_push($satsan, $satsan_customer_total);
                $eastdagon_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,9);
                array_push($eastdagon, $eastdagon_customer_total);
                $hlaingthaya_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,19);
                array_push($hlaingthaya, $hlaingthaya_customer_total);
                $terminal_m_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,27);
                array_push($terminal_m, $terminal_m_customer_total);
                $theikpan_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,2);
                array_push($theikpan, $theikpan_customer_total);
                $tampawady_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,11);
                array_push($tampawady, $tampawady_customer_total);
                $aye_thayar_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,21);
                array_push($aye_thayar, $aye_thayar_customer_total);
                $mawlamyine_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,10);
                array_push($mawlamyine, $mawlamyine_customer_total);
                $southdagon_customer_total = $this->report_connection()->customer_total_by_branch_id($u_promotion->id,28);
                array_push($southdagon, $southdagon_customer_total);
            }
            $title_name = 'Customer Number';
        }
        else{
            foreach($used_promotions as $u_promotion){
                array_push($header, $u_promotion->name);
                $lanthit_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,1);
                array_push($lanthit, $lanthit_customer_total);
                $satsan_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,3);
                array_push($satsan, $satsan_customer_total);
                $eastdagon_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,9);
                array_push($eastdagon, $eastdagon_customer_total);
                $hlaingthaya_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,19);
                array_push($hlaingthaya, $hlaingthaya_customer_total);
                $terminal_m_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,27);
                array_push($terminal_m, $terminal_m_customer_total);
                $theikpan_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,2);
                array_push($theikpan, $theikpan_customer_total);
                $tampawady_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,11);
                array_push($tampawady, $tampawady_customer_total);
                $aye_thayar_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,21);
                array_push($aye_thayar, $aye_thayar_customer_total);
                $mawlamyine_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,10);
                array_push($mawlamyine, $mawlamyine_customer_total);
                $southdagon_customer_total = $this->report_connection()->coupon_total_by_branch_id($u_promotion->id,28);
                array_push($southdagon, $southdagon_customer_total);
            }
            $title_name = 'Coupon Number';
        }

        array_push($header, 'Gr');
        $lanthit_gr = $lanthit['2'] == 0 ? 0 : ($lanthit['1']-$lanthit['2'])/$lanthit['2'] * 100;
        array_push($lanthit, $lanthit_gr);
        $satsan_gr = $satsan['2'] == 0 ? 0 : ($satsan['1']-$satsan['2'])/$satsan['2'] * 100;
        array_push($satsan, $satsan_gr);
        $eastdagon_gr = $eastdagon['2'] == 0 ? 0 : ($eastdagon['1']-$eastdagon['2'])/$eastdagon['2'] * 100;
        array_push($eastdagon, $eastdagon_gr);
        $hlaingthaya_gr = $hlaingthaya['2'] == 0 ? 0 : ($hlaingthaya['1']-$hlaingthaya['2'])/$hlaingthaya['2'] * 100;
        array_push($hlaingthaya, $hlaingthaya_gr);
        $terminal_m_gr = $terminal_m['2'] == 0 ? 0 : ($terminal_m['1']-$terminal_m['2'])/$terminal_m['2'] * 100;
        array_push($terminal_m, $terminal_m_gr);
        $theikpan_gr = $theikpan['2'] == 0 ? 0 : ($theikpan['1']-$theikpan['2'])/$theikpan['2'] * 100;
        array_push($theikpan, $theikpan_gr);
        $tampawady_gr = $tampawady['2'] == 0 ? 0 : ($tampawady['1']-$tampawady['2'])/$tampawady['2'] * 100;
        array_push($tampawady, $tampawady_gr);
        $aye_thayar_gr = $aye_thayar['2'] == 0 ? 0 : ($aye_thayar['1']-$aye_thayar['2'])/$aye_thayar['2'] * 100;
        array_push($aye_thayar, $aye_thayar_gr);
        $mawlamyine_gr = $mawlamyine['2'] == 0 ? 0 : ($mawlamyine['1']-$mawlamyine['2'])/$mawlamyine['2'] * 100;
        array_push($mawlamyine, $mawlamyine_gr);
        $southdagon_gr = $southdagon['2'] == 0 ? 0 : ($southdagon['1']-$southdagon['2'])/$southdagon['2'] * 100;
        array_push($southdagon, $southdagon_gr);
        $result = [
            $lanthit, $satsan,$eastdagon,$hlaingthaya,$terminal_m,$theikpan,$tampawady,$aye_thayar,$mawlamyine,$southdagon
        ];

        return view('reports.customer_number_compare.customer_number_compare_export', compact('title_name','header','result'));
    }
    
}
