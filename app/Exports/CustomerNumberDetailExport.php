<?php

namespace App\Exports;

use App\Models\LuckyDraw;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Http\Controllers\ReportController;

class CustomerNumberDetailExport implements FromView
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

        $lanthit_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,1);
        array_push($lanthit, $lanthit_customer_total);
        $lanthit_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,1)['customer_total'];
        array_push($header, $lanthit_customer_total);
        array_push($lanthit, $lanthit_customer_total);

        $satsan_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,3);
        array_push($satsan, $satsan_customer_total);
        $satsan_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,3)['customer_total'];
        array_push($satsan, $satsan_customer_total);

        $eastdagon_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,9);
        array_push($eastdagon, $eastdagon_customer_total);
        $eastdagon_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,9)['customer_total'];
        array_push($eastdagon, $eastdagon_customer_total);

        $hlaingthaya_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,19);
        array_push($hlaingthaya, $hlaingthaya_customer_total);
        $hlaingthaya_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,19)['customer_total'];
        array_push($hlaingthaya, $hlaingthaya_customer_total);

        $terminal_m_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,27);
        array_push($terminal_m, $terminal_m_customer_total);
        $terminal_m_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,27)['customer_total'];
        array_push($terminal_m, $terminal_m_customer_total);
        
        $theikpan_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,2);
        array_push($theikpan, $theikpan_customer_total);
        $theikpan_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,2)['customer_total'];
        array_push($theikpan, $theikpan_customer_total);

        $tampawady_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,11);
        array_push($tampawady, $tampawady_customer_total);
        $tampawady_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,11)['customer_total'];
        array_push($tampawady, $tampawady_customer_total);

        $aye_thayar_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,21);
        array_push($aye_thayar, $aye_thayar_customer_total);
        $aye_thayar_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,21)['customer_total'];
        array_push($aye_thayar, $aye_thayar_customer_total);

        $mawlamyine_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,10);
        array_push($mawlamyine, $mawlamyine_customer_total);
        $mawlamyine_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,10)['customer_total'];
        array_push($mawlamyine, $mawlamyine_customer_total);

        $southdagon_customer_total = $this->report_connection()->customer_total_by_branch_id($uuid,28);
        array_push($southdagon, $southdagon_customer_total);
        $southdagon_customer_total = $this->report_connection()->customer_total_detail_by_branch_id($promotion,28)['customer_total'];
        array_push($southdagon, $southdagon_customer_total);

        $total = $lanthit_customer_total[0] + $satsan_customer_total[0] + $eastdagon_customer_total[0] + $hlaingthaya_customer_total[0] + $terminal_m_customer_total[0] + $theikpan_customer_total[0] + $tampawady_customer_total[0] + $aye_thayar_customer_total[0] + $mawlamyine_customer_total[0] + $southdagon_customer_total[0];
        
        $days = $this->report_connection()->customer_total_detail_by_branch_id($promotion,10)['date_array'];
        return view('reports.customer_number.customer_number_export', compact('promotion','total','days','header',
        'lanthit','satsan','eastdagon','hlaingthaya','terminal_m','theikpan','tampawady','aye_thayar','mawlamyine','southdagon'));
    }
    
}
