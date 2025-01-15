<?php

namespace App\Http\Controllers\CreateTicket;
use App\Models\Branch;
use App\Models\NRCName;
use App\Models\Customer;
use App\Models\NRCNaing;
use App\Models\NRCNumber;
use App\Models\CustomerView;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Models\POS101\Pos101Amphur;
use App\Models\POS102\Pos102Amphur;
use App\Models\POS103\Pos103Amphur;
use App\Models\POS104\Pos104Amphur;
use App\Models\POS105\Pos105Amphur;
use App\Models\POS106\Pos106Amphur;
use App\Models\POS107\Pos107Amphur;
use App\Models\POS108\Pos108Amphur;
use App\Models\POS112\Pos112Amphur;
use App\Models\POS113\Pos113Amphur;
use App\Models\POS110\Pos110Amphur;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\POS101\Pos101Province;
use App\Models\POS102\Pos102Province;
use App\Models\POS103\Pos103Province;
use App\Models\POS104\Pos104Province;
use App\Models\POS105\Pos105Province;
use App\Models\POS106\Pos106Province;
use App\Models\POS107\Pos107Province;
use App\Models\POS108\Pos108Province;
use App\Models\POS112\Pos112Province;
use App\Models\POS113\Pos113Province;
use App\Models\POS110\Pos110Province;
use App\Http\Controllers\CustomerViewController;

class CustomerInfoController extends Controller
{

    public function customer_info($ticket_header_uuid)
    {
        $customer_data = new CustomerViewController;
        $customer_data->store_customer_view();
        // $currentURL = URL::current();
        // $check_customer_ip = CustomerView::where('ip', \Request::ip())->first();
        // if($check_customer_ip){
        //    $store_customer_view_update['route_name'] = $currentURL;
        //    $check_customer_ip->update($store_customer_view_update);
        // }else{
        //    if (str_contains($currentURL, '192.168.3.242') || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
        //       $branch = Branch::select('branch_id')->first();
        //   }
        //    $store_customer_view['ip'] = \Request::ip();
        //    $store_customer_view['route_name'] = $currentURL;
        //    $store_customer_view['branch_id'] = $branch->branch_id;
        //    $store_customer_view['status'] = 1;
        //    CustomerView::create($store_customer_view);
        // }

        $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        $customer_info = Customer::where('uuid', $ticket_header->customer_uuid)->first();
        $titles = ['Mr.', 'Ms.', 'Mrs.', 'LTD .', 'Plc .'];
        $nrc_nos = NRCNumber::get()->take(14);
        $nrc_names = NRCName::select('id', 'district')->get();
        $nrc_naings = NRCNaing::select('id', 'shortname')->get();
        $currentURL = URL::current();
        if (str_contains($currentURL, '192.168.21.242')) {
            $provinces = Pos102Province::get();
            $amphurs = Pos102Amphur::get();
        }
        if (str_contains($currentURL, '192.168.25.242')) {
            $provinces = Pos106Province::get();
            $amphurs = Pos106Amphur::get();
        }
        if (str_contains($currentURL, '192.168.3.242')
        // ||str_contains($currentURL, '192.168.2.23')
         || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
            $provinces = Pos101Province::get();
            $amphurs = Pos101Amphur::get();
        }
        if (str_contains($currentURL, '192.168.11.242')
        // ||str_contains($currentURL, '192.168.2.23')
        ) {
            $provinces = Pos103Province::get();
            $amphurs = Pos103Amphur::get();
        }
        if (str_contains($currentURL, '192.168.16.242')
        ||str_contains($currentURL, '192.168.2.23')) {
            $provinces = Pos104Province::get();
            $amphurs = Pos104Amphur::get();
        }
        if (str_contains($currentURL, '192.168.36.242')) {
            $provinces = Pos107Province::get();
            $amphurs = Pos107Amphur::get();
        }
        if (str_contains($currentURL, '192.168.31.242')) {
            $provinces = Pos105Province::get();
            $amphurs = Pos105Amphur::get();
        }
        if (str_contains($currentURL, '192.168.41.242')) {
            $provinces = Pos108Province::get();
            $amphurs = Pos108Amphur::orderby('amphur_name', 'ASC')->get();
        }
        if (str_contains($currentURL, '192.168.46.242')) {
            $provinces = Pos112Province::get();
            $amphurs = Pos112Amphur::get();
        }
        if (str_contains($currentURL, '192.168.51.243')) {
            $provinces = Pos113Province::get();
            $amphurs = Pos113Amphur::get();
        }
        if (str_contains($currentURL, '192.168.61.242')) {
            $provinces = Pos110Province::get();
            $amphurs = Pos110Amphur::get();
        }
        return view('create_tickets.check-customer-info', compact('customer_info','nrc_nos','titles','nrc_names','nrc_naings','provinces','amphurs','ticket_header','ticket_header_uuid'));
    }

    public function update_customer_info(Request $request)
    {
        // dd('hi');
        $ticket_header = TicketHeader::where('uuid',$request->ticket_header_uuid)->first();
        // dd($ticket_header);
        if($ticket_header->printed_at == null){
            if ($request->phone_no == '09777777777' || $request->firstname == 'Cash') {
                return redirect()->route('tickets.customer_info',$request->ticket_header_uuid)->with('error','Please Use Correct User Info');
            }
            $customer_info = Customer::where('uuid', $request->customer_uuid)->first();
            $update_customer['phone_no'] =$request->phone_no;
            $update_customer['phone_no_2'] =$request->phone_no_2;
            $update_customer['titlename'] =$request->titlename;
            $update_customer['firstname'] =$request->firstname;
            $update_customer['lastname'] =$request->lastname;
            $update_customer['nrc_no'] =$request->nrc_no;
            $update_customer['nrc_name'] =$request->nrc_name;
            $update_customer['nrc_short'] =$request->nrc_short;
            $update_customer['nrc_number'] =$request->nrc_number;
            $update_customer['customer_type'] =$request->customer_type;
            $update_customer['passport'] =$request->passport;
            $update_customer['email'] =$request->email;
            $update_customer['customer_division'] =$request->customer_division;
            $update_customer['customer_township'] =$request->customer_township;

            $customer_info->update($update_customer);
        }
        return redirect()->route('tickets.choose_promotion',$request->ticket_header_uuid);
    }


}
