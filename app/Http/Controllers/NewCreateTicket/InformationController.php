<?php

namespace App\Http\Controllers\NewCreateTicket;

use App\Models\Amphur;
use App\Models\Customer;
use Illuminate\Support\Str;
use App\Models\TicketHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NRC;
use App\Models\POS101\Pos101GbhCustomer;
use App\Models\POS102\Pos102GbhCustomer;
use App\Models\POS103\Pos103GbhCustomer;
use App\Models\POS104\Pos104GbhCustomer;
use App\Models\POS105\Pos105GbhCustomer;
use App\Models\POS106\Pos106GbhCustomer;
use App\Models\POS107\Pos107GbhCustomer;
use App\Models\POS108\Pos108GbhCustomer;
use App\Models\POS110\Pos110GbhCustomer;
use App\Models\POS112\Pos112GbhCustomer;
use App\Models\POS113\Pos113GbhCustomer;
use App\Models\POS114\Pos114GbhCustomer;
use App\Models\{Province,NRCName};

class InformationController extends Controller
{
    public function update_customer_info(Request $request)
    {
        // dd($request->all());
        $nrc_name                           = NRCName::where('id',$request->region)->first() ? NRCName::where('id',$request->region)->first()->district:'';
        
        switch($request->nrc_type)
        {
            case '(N)':
                 $request->nrc_type = 1;
                break;
            case '(E)':
                 $request->nrc_type = 2;
                break;
            case '(P)':
                 $request->nrc_type = 3;
                break;
            case '(A)':
                 $request->nrc_type = 4;
                break;
            case '(F)':
                 $request->nrc_type = 5;
                break;
            case '(TH)':
                 $request->nrc_type = 6;
                break;
            case '(G)':
                 $request->nrc_type = 7;
                break;
        }
        
// dd($nrc_name,'hi');
        $ticket_header_uuid                 = $request->ticket_header_uuid;
        $update_customer['phone_no']        = $request->phone_no;
        $update_customer['firstname']       = $request->firstname;
        $update_customer['customer_no']     = $request->customer_no;
        $update_customer['customer_type']   = $request->customer_type;
        $update_customer['province_id']     = $request->province_id;
        $update_customer['amphur_id']       = $request->amphur_id;
        $update_customer['nrc_no']          = $request->state_id;
        $update_customer['nrc_name']        = $request->region;
        $update_customer['nrc_short']       = $request->nrc_type;
        $update_customer['nrc_number']      = $request->number;
        $update_customer['nrc']             = $request->full_nrc? $request->full_nrc:"$request->state_id/$nrc_name($request->nrc_type)$request->number";

        if( ($request->check_foreigner == "true" && $request->passport == null) || ($request->check_foreigner=="false" && $request->full_nrc==null && $request->customer_type !=='New'))
        {
            $update_customer['foreigner_id'] = true;
        }

        else
        {
            $update_customer['foreigner_id'] = false;
        }

        
        if( ($request->check_foreigner == "true" && $request->passport == null))
        {
            return response()->json('error', 500);
        }


        $customer = Customer::where('phone_no',$request->phone_no)->first();
        // dd($customer);
        if($customer)
        {
            $customer->update($update_customer);
        }
        else
        {
            $update_customer['uuid'] = (string) Str::uuid();
            $customer = Customer::create($update_customer);
        };
        // dd($customer);
        //Update in Ticket Header
        $update_ticket_header['customer_uuid'] = $customer->uuid;
        $ticket_header = TicketHeader::where('uuid',$ticket_header_uuid)->first();
        $ticket_header->update($update_ticket_header);
        return response()->json($customer, 200);
    }

    public static function get_customer_by_phone_no($branch_id, $phone_no)
    {

        $customer = Pos101GbhCustomer::where('mobile', $phone_no)->whereOr('house_no', $phone_no)->first();
       
        $ld_customer = Customer::orwhere('phone_no', $phone_no)->orwhere('phone_no_2', $phone_no)->first();
        if (!$customer) {
            if($ld_customer){
                $customer = $ld_customer;
                $customer['customer_type'] ='Old';
            }
        }
        else{
            // dd($customer->customer_barcode);
            if (isset($customer->identification_card) && $customer->member_active == true) {
                $member_type = 'Member';
            } else {
                $member_type = 'Old';
            }
            if($ld_customer){
                $customer->customer_type = $member_type;
                $customer->customer_no = $customer->customer_barcode;
                // $customer->
            }else{
                $customer->phone_no = $customer->mobile;
                $customer->customer_type = $member_type;
                $customer->customer_id = $customer->gbh_customer_id;
                $customer->customer_no = $customer->customer_barcode;

            }
            
            if($customer->nrc_short !=null && $customer->nrc_no !==null)
            {
                $customer_nrc = NRC::where('nrc_id',$customer->nrc_no)->first();
                
                $customer_nrc_name = $customer_nrc!=null?$customer_nrc->district:'';
                $customer['nrc_name']= $customer_nrc_name;
                if($customer->nrc_short==1){
                    $customer['nrc_short'] = '(N)';
                }
                elseif ($customer->nrc_short == 2) {
                    $customer['nrc_short'] = '(E)';
                }
                elseif ($customer->nrc_short == 3) {
                    $customer['nrc_short'] = '(P)';
                }
                elseif ($customer->nrc_short == 4) {
                    $customer['nrc_short'] = '(A)';
                }
                elseif ($customer->nrc_short == 5) {
                    $customer['nrc_short'] = '(F)';
                }
                elseif ($customer->nrc_short == 6) {
                    $customer['nrc_short'] = '(TH)';
                }
                elseif ($customer->nrc_short == 7) {
                    $customer['nrc_short'] = '(G)';
                }
            }

        }
        
        if (isset($customer)) {
            return response()->json(['data' => $customer], 200);
        } else {
            return response()->json(null, 200);
        }
    }

    public function customer_township_by_customer_division(Request $request)
    {
        $province_id = $request->province_id;

        $customer_townships = Amphur::where('province_id', $province_id)->get()->toarray();


        $output = [];
        foreach ($customer_townships as $r) {
            $output[$r['amphur_id']] = $r['amphur_name'];
        }

        return $output;
    }

    public function get_nrc_info(Request $request)
    {
        $province_id        = $request->province_id;
        $customer_province  = Province::where('region_id',$province_id)->first();
        // dd($customer_province);

        $state_id           = $customer_province->region_id;
        $data               = NRCName::where('nrc_number_id',$state_id)->get();
        // dd($data);
        return response()->json($data, 200);
    }
}


// $customer_province  = Province::where('province_id',$province_id)->first();
// $province_name      = $customer_province->province_name;
// $nrc_regions        = NRC::where('province_name',$province_name)->get();
