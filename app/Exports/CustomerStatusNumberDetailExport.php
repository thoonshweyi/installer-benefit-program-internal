<?php

namespace App\Exports;

use App\Models\LuckyDraw;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Http\Controllers\ReportController;

class CustomerStatusNumberDetailExport implements FromView
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
        $header = ['Branches','Member','Old Customer','New Customer'];
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

        //Find Member Total
        $lanthit_member_total = $this->report_connection()->member_total_by_branch_id($uuid,1);
        array_push($lanthit, $lanthit_member_total);
        $satsan_member_total = $this->report_connection()->member_total_by_branch_id($uuid,3);
        array_push($satsan, $satsan_member_total);
        $eastdagon_member_total = $this->report_connection()->member_total_by_branch_id($uuid,9);
        array_push($eastdagon, $eastdagon_member_total);
        $hlaingthaya_member_total = $this->report_connection()->member_total_by_branch_id($uuid,19);
        array_push($hlaingthaya, $hlaingthaya_member_total);
        $terminal_m_member_total = $this->report_connection()->member_total_by_branch_id($uuid,27);
        array_push($terminal_m, $terminal_m_member_total);
        $theikpan_member_total = $this->report_connection()->member_total_by_branch_id($uuid,2);
        array_push($theikpan, $theikpan_member_total);
        $tampawady_member_total = $this->report_connection()->member_total_by_branch_id($uuid,11);
        array_push($tampawady, $tampawady_member_total);
        $aye_thayar_member_total = $this->report_connection()->member_total_by_branch_id($uuid,21);
        array_push($aye_thayar, $aye_thayar_member_total);
        $mawlamyine_member_total = $this->report_connection()->member_total_by_branch_id($uuid,10);
        array_push($mawlamyine, $mawlamyine_member_total);
        $southdagon_member_total = $this->report_connection()->member_total_by_branch_id($uuid,28);
        array_push($southdagon, $southdagon_member_total);

        //Find Old Customer Total
        $lanthit_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,1);
        array_push($lanthit, $lanthit_old_customer_total);
        $satsan_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,3);
        array_push($satsan, $satsan_old_customer_total);
        $eastdagon_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,9);
        array_push($eastdagon, $eastdagon_old_customer_total);
        $hlaingthaya_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,19);
        array_push($hlaingthaya, $hlaingthaya_old_customer_total);
        $terminal_m_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,27);
        array_push($terminal_m, $terminal_m_old_customer_total);
        $theikpan_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,2);
        array_push($theikpan, $theikpan_old_customer_total);
        $tampawady_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,11);
        array_push($tampawady, $tampawady_old_customer_total);
        $aye_thayar_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,21);
        array_push($aye_thayar, $aye_thayar_old_customer_total);
        $mawlamyine_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,10);
        array_push($mawlamyine, $mawlamyine_old_customer_total);
        $southdagon_old_customer_total = $this->report_connection()->old_customer_total_by_branch_id($uuid,28);
        array_push($southdagon, $southdagon_old_customer_total);

        //Find New Customer Total
        $lanthit_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,1);
        array_push($lanthit, $lanthit_new_customer_total);
        $satsan_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,3);
        array_push($satsan, $satsan_new_customer_total);
        $eastdagon_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,9);
        array_push($eastdagon, $eastdagon_new_customer_total);
        $hlaingthaya_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,19);
        array_push($hlaingthaya, $hlaingthaya_new_customer_total);
        $terminal_m_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,27);
        array_push($terminal_m, $terminal_m_new_customer_total);
        $theikpan_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,2);
        array_push($theikpan, $theikpan_new_customer_total);
        $tampawady_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,11);
        array_push($tampawady, $tampawady_new_customer_total);
        $aye_thayar_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,21);
        array_push($aye_thayar, $aye_thayar_new_customer_total);
        $mawlamyine_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,10);
        array_push($mawlamyine, $mawlamyine_new_customer_total);
        $southdagon_new_customer_total = $this->report_connection()->new_customer_total_by_branch_id($uuid,28);
        array_push($southdagon, $southdagon_new_customer_total);

        $total = ['Total'];
        $total_member = $lanthit_member_total + $satsan_member_total + $eastdagon_member_total + $hlaingthaya_member_total + $terminal_m_member_total + $theikpan_member_total +
        $tampawady_member_total + $aye_thayar_member_total + $mawlamyine_member_total + $southdagon_member_total;
        array_push($total, $total_member);

        $total_old_customer = $lanthit_old_customer_total + $satsan_old_customer_total + $eastdagon_old_customer_total + $hlaingthaya_old_customer_total + $terminal_m_old_customer_total + $theikpan_old_customer_total +
        $tampawady_old_customer_total + $aye_thayar_old_customer_total + $mawlamyine_old_customer_total + $southdagon_old_customer_total;
        array_push($total, $total_old_customer);

        $total_new_customer = $lanthit_new_customer_total + $satsan_new_customer_total + $eastdagon_new_customer_total + $hlaingthaya_new_customer_total + $terminal_m_new_customer_total + $theikpan_new_customer_total +
        $tampawady_new_customer_total + $aye_thayar_new_customer_total + $mawlamyine_new_customer_total + $southdagon_new_customer_total;
        array_push($total, $total_new_customer);

        $result = [
            $lanthit, $satsan,$eastdagon,$hlaingthaya,$terminal_m,$theikpan,$tampawady,$aye_thayar,$mawlamyine,$southdagon,$total
        ];
        
        return view('reports.customer_status_number.customer_status_number_export', compact('promotion','header','result'));
    }
    
}
