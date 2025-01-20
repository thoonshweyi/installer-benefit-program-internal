<?php

namespace App\Http\Controllers;

use App\Models\Amphur;
use App\Models\Province;
use App\Models\HomeOwner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeOwnersCntroller extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('permission:view-installer-card', ['only' => ['index', 'store']]);
        // $this->middleware('permission:create-installer-card', ['only' => ['create']]);
        // $this->middleware('permission:register-installer-card', ['only' => ['register']]);
        // $this->middleware('permission:create-installer-card|register-installer-card', ['only' => ['store']]);
        // $this->middleware('permission:edit-installer-card', ['only' => ['edit','refresh']]);
        // $this->middleware('permission:delete-installer-card', ['only' => ['delete']]);
        // $this->middleware('permission:check-installer-card', ['only' => ['checking','check']]);
        // $this->middleware('permission:transfer-installer-card', ['only' => ['transfer']]);
    }
    public function index(){
        $homeowners = HomeOwner::orderBy('id','desc')->paginate(10);

        return view("homeowners.index",compact("homeowners"));
    }

    public function create(){
        $provinces = Province::orderBy('province_id')->get();
        $amphurs = Amphur::orderBy('amphur_id')->get();
        return view("homeowners.create",compact('provinces','amphurs'));
    }
    public function store(Request $request){

        $validatearrs = [
            "fullname"=>"required",
            "phone"=>"required|unique:home_owners,phone",
            "address"=>"required",
            "gender"=>"required",
            "dob"=>"required",
            "nrc"=>"required",
            'member_active'=>"required",
            'customer_active'=>"required",
            'customer_rank_id'=>"required",
            "customer_barcode"=>"required",

            "gbh_customer_id"=>"required",
        ];

        $user = Auth::user();
        $user_id = $user->id;
        $user_uuid = $user->uuid;
        $branch_id = getCurrentBranch();


        $request->validate($validatearrs);


        $homeowner = new HomeOwner();
        $homeowner->uuid = (string) Str::uuid();
        $homeowner->branch_id = $branch_id;

        $homeowner->fullname = $request->fullname;
        $homeowner->phone = $request->phone;
        $homeowner->address = $request->address;
        $homeowner->gender = $request->gender;
        $homeowner->dob = $request->dob;
        $homeowner->nrc = $request->nrc;
        $homeowner->passport = $request->passport;
        $homeowner->identification_card = $request->identification_card;
        $homeowner->member_active = $request->member_active;
        $homeowner->customer_active = $request->customer_active;
        $homeowner->customer_rank_id = $request->customer_rank_id;

        $homeowner->customer_barcode = $request->customer_barcode;

        $homeowner->titlename = $request->titlename;
        $homeowner->firstname = $request->firstname;
        $homeowner->lastnanme = $request->lastnanme;
        $homeowner->province_id = $request->province_id;
        $homeowner->amphur_id = $request->amphur_id;
        $homeowner->nrc_no = $request->nrc_no;
        $homeowner->nrc_name = $request->nrc_name;
        $homeowner->nrc_short = $request->nrc_short;
        $homeowner->nrc_number = $request->nrc_number;
        $homeowner->gbh_customer_id = $request->gbh_customer_id;


        $homeowner->user_uuid = $user_uuid;
        $homeowner->save();


        return redirect()->back()->with('success','New Home Owner Registered Successfully');
    }

    public function edit($uuid){
        $homeowner = HomeOwner::where('uuid',$uuid)->orderBy('id','asc')->first();


        return view("homeowners.edit",compact('homeowner'));
    }

    public function refresh($uuid){
        $homeowner = HomeOwner::where('uuid',$uuid)->orderBy('id','asc')->first();
        $gbh_customer_id = $homeowner->gbh_customer_id;
        $customer_barcode = $homeowner->customer_barcode;

        $branch_id = getCurrentBranch();
        $gbhcustomer = getCustomerInfoById($branch_id,$gbh_customer_id,$customer_barcode);

        // dd($gbhcustomer);
        $homeowner->update([
            'fullname'=> $gbhcustomer->fullname,
            'phone'=> $gbhcustomer->mobile,
            'address'=> $gbhcustomer->full_address,
            'gender'=> $gbhcustomer->sex,
            'dob'=> $gbhcustomer->date_birthday,
            'nrc'=> $gbhcustomer->nrc_array_id,
            'passport'=> $gbhcustomer->passport,
            'identification_card'=> $gbhcustomer->identification_card,
            'member_active'=>$gbhcustomer->member_active,
            'customer_active'=>$gbhcustomer->customer_active,
            'customer_rank_id'=>$gbhcustomer->customer_rank_id,
            'customer_barcode'=> $gbhcustomer->customer_barcode,

            'titlename'=> $gbhcustomer->titlename,
            'firstname'=> $gbhcustomer->firstname,
            'lastnanme'=> $gbhcustomer->lastnanme,
            'province_id'=> $gbhcustomer->province_id,
            'amphur_id'=> $gbhcustomer->amphur_id,
            'nrc_no'=> $gbhcustomer->nrc_no,
            'nrc_name'=> $gbhcustomer->nrc_name,
            'nrc_short'=> $gbhcustomer->nrc_short,
            'nrc_number'=> $gbhcustomer->nrc_number,
            'gbh_customer_id'=> $gbhcustomer->gbh_customer_id,
        ]);

        return redirect()->route('homeowners.edit',$uuid)->with('Home Owner Updated');

    }

    public function destroy($card_number){
        // HomeOwner::destroy($id);
        $homeowner = HomeOwner::where('card_number',$card_number)->orderBy('id','asc')->first();
        $homeowner->delete();
        return redirect()->route('homeowners.index')->with('success','Installer Deleted Successfully');
    }


    public function verifycustomer(Request $request){

        $request->validate([
            "ver_phone"=>"required|numeric",
        ]);
        $verifyphone = $request['ver_phone'];
        // dd($verifyphone);
        $branch_id = getCurrentBranch();
        // dd($branch_id);
        $customer = getCustomerInfo($branch_id,$verifyphone);


        $ismembercustomer = $customer->identification_card != null && $customer->member_active && $customer->customer_active && $customer->customer_rank_id == 1013;
        return response()->json(["customer"=>$customer,'ismembercustomer'=>$ismembercustomer]);
    }

    public function search(Request $request){
        $querycard_number = $request->input("querycard_number");
        $querynrc = $request->input("querynrc");
        $queryphone = $request->input("queryphone");

        $results = InstallerCard::query();
        // dd($results);
        if($querycard_number){
            $results = $results->where("card_number",$querycard_number);
        }
        if($querynrc){
            $results = $results->where("nrc",$querynrc);
        }
        if($queryphone){
            $results = $results->where("phone",$queryphone);
        }
        $installercards = $results->orderBy('id','desc')->paginate(10);

        // Fetch related user UUIDs
        $userUuids = $installercards->pluck('user_uuid')->filter();
        // Fetch users from the local branch database
        $users = DB::table('users')
            ->whereIn('uuid', $userUuids)
            ->get()
            ->keyBy('uuid'); // Index users by UUID for easy mapping

        // Map user details to installer cards
        $installercards->each(function ($installercard) use ($users) {
            $installercard->users = $users->get($installercard->user_uuid);
        });

        return view('installercards.index',compact("installercards"));
    }

}
