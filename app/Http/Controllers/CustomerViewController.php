<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CustomerIPs;
use App\Models\CustomerView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CustomerViewController extends Controller
{
    public function customer_view()
    {
        $currentURL = URL::current();
        if (str_contains($currentURL, '192.168.3.242') || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
            $branch = Branch::select('branch_id')->first();
        }
        $check_ip_addresses = CustomerIPs::where('branch_id', $branch->branch_id)->get();
        return view('create_tickets.customer-view', compact('check_ip_addresses'));
    }

    public function route_view(Request $request)
    {
        //find Current Branch
        $currentURL = URL::current();
        if (str_contains($currentURL, '192.168.3.242') || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
            $branch = Branch::select('branch_id')->first();
        }
        $check_ip_address = CustomerView::select('route_name')->where('ip', $request->ip_address)->where('branch_id', $branch->branch_id)
            ->first();
        return $check_ip_address;
    }

    public function store_customer_view()
    {
        $currentURL = URL::current();
        $check_customer_ip = CustomerView::where('ip', \Request::ip())->first();
        if ($check_customer_ip) {
            $store_customer_view_update['route_name'] = $currentURL;
            $check_customer_ip->update($store_customer_view_update);
        } else {
            if (str_contains($currentURL, '192.168.3.242') ||
                str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
                $branch = Branch::select('branch_id')->first();
            }
            //TODO find branch_id for all branch
            $store_customer_view['ip'] = \Request::ip();
            $store_customer_view['route_name'] = $currentURL;
            $store_customer_view['branch_id'] = $branch->branch_id;
            $store_customer_view['status'] = 1;
            CustomerView::create($store_customer_view);
        }

    }
}
