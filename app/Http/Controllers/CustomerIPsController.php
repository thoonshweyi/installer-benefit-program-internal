<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\CustomerIPs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerIPsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function customer_ip_address()
    {
        return new CustomerIPs();
    }
    public function index(Request $request)
    {

        // try {
            if ($request->ajax()) {
                $no = (!empty($_GET["no"])) ? ($_GET["no"]) : ('');
                $ip_address = (!empty($_GET["ip_address"])) ? ($_GET["ip_address"]) : ('');
                $branch_id = (!empty($_GET["branch_id"])) ? ($_GET["branch_id"]) : ('');
                $result = $this->customer_ip_address();
                if ($no != "") {
                    $result = $result->where('no', 'ilike', '%' . $no . '%');
                }
                if ($branch_id != "") {
                    $result = $result->where('branch_id', 'ilike', '%' . $branch_id . '%');
                }
                if ($ip_address != "") {
                    $result = $result->whereOr('ip_address', 'ilike', '%' . $ip_address . '%');
                }

                $result = $result->get();
                return DataTables::of($result)
                    ->addColumn('action', function ($data) {
                        return 'action';
                    })
                    ->editColumn('branch_id', function ($data) {
                        if (isset($data->branch_id)) {
                            return $data->branches->branch_name_eng;
                        }
                        return '';
                    })
                    ->make(true);
            }
            $branches = Branch::all();

            return view('customer_ips.index',compact('branches'));
        // } catch (\Exception $e) {
        //     Log::debug($e->getMessage());
        //     return redirect()
        //         ->intended(route("home"))
        //         ->with('error', 'Fail to load Data!');
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::all();
        return view('customer_ips.create',compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uuid = (string) Str::uuid();
        $customer_ip['uuid'] =(string) Str::uuid();
        $customer_ip['no'] = $request->no;
        $customer_ip['branch_id'] = $request->branch_id;
        $customer_ip['ip_address'] = $request->ip_address;
        CustomerIPs::create($customer_ip);

        return redirect()->route('customer_ips.index')
        ->with('success', 'Customer IP address created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerIPs  $customerIPs
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerIPs $customerIPs, $id)
    {

        $customer_ip = CustomerIPs::where('id',$id)->first();
        return view('customer_ips.show', compact('customer_ip'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerIPs  $customerIPs
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerIPs $customerIPs, $id)
    {
        $customer_ip = CustomerIPs::where('id',$id)->first();
        $branches = Branch::all();
        return view('customer_ips.edit', compact('customer_ip', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerIPs  $customerIPs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerIPs $customerIPs, $id)
    {
        $branches = Branch::all();
        $customer_ips = CustomerIPs::where('id',$id)->first();

        $customer_ip['no'] = $request->no;
        $customer_ip['branch_id'] = $request->branch_id;
        $customer_ip['ip_address'] = $request->ip_address;
       $customer_ips->update($customer_ip);
       return view('customer_ips.index',compact('branches'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerIPs  $customerIPs
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerIPs $customerIPs, $id)
    {
        $customer_ips = CustomerIPs::where('id',$id)->delete();
        return view('customer_ips.index');
    }
}
