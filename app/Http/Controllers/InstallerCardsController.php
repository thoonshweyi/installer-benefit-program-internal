<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Amphur;
use App\Jobs\SyncRowJob;
use App\Models\Province;
use App\Models\HomeOwner;
use App\Models\BranchUser;
use Illuminate\Support\Str;
use App\Models\ReturnBanner;
use Illuminate\Http\Request;
use App\Models\InstallerCard;
use App\Models\CusSaleAmounts;
use App\Models\SaleAmountCheck;
use App\Models\InstallerCardFile;
use App\Models\HomeownerInstaller;
use App\Models\InstallerCardPoint;
use Illuminate\Support\Facades\DB;
use App\Models\ReturnProductRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionTransaction;
use App\Models\RedemptionTransaction;
use Illuminate\Support\Facades\Session;
use App\Models\InstallerCardTransferLog;
use App\Models\POS101\Pos101GbhCustomer;
use App\Models\POS102\Pos102GbhCustomer;
use App\Models\POS103\Pos103GbhCustomer;
use App\Models\POS104\Pos104GbhCustomer;
use App\Models\POS105\Pos105GbhCustomer;
use App\Models\POS106\Pos106GbhCustomer;
use App\Models\POS107\Pos107GbhCustomer;
use App\Models\POS108\Pos108GbhCustomer;
use App\Models\POS112\Pos112GbhCustomer;
use App\Models\POS113\Pos113GbhCustomer;
use App\Models\POS114\Pos114GbhCustomer;
use App\Models\HomeownerInstallerHistory;
use App\Models\InstallerCardTransferFile;

class InstallerCardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-installer-card', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-installer-card', ['only' => ['create']]);
        $this->middleware('permission:register-installer-card', ['only' => ['register']]);
        $this->middleware('permission:create-installer-card|register-installer-card', ['only' => ['store']]);
        $this->middleware('permission:edit-installer-card', ['only' => ['edit','refresh']]);
        $this->middleware('permission:delete-installer-card', ['only' => ['delete']]);
        $this->middleware('permission:check-installer-card', ['only' => ['checking','check']]);
        $this->middleware('permission:transfer-installer-card', ['only' => ['transfer']]);
    }
    public function index(){
        $installercards = InstallerCard::orderBy('id','desc')->paginate(10);


        // **Preparation for multiple branch deployment
        // Fetch related user UUIDs
        // $userUuids = $installercards->pluck('user_uuid')->filter();
        // // Fetch users from the local branch database
        // $users = DB::table('users')
        //     ->whereIn('uuid', $userUuids)
        //     ->get()
        //     ->keyBy('uuid'); // Index users by UUID for easy mapping
        // // Map user details to installer cards
        // $installercards->each(function ($installercard) use ($users) {
        //     $installercard->users = $users->get($installercard->user_uuid);
        // });
        // dd($installercards[0]);


         // Fetch all unique user UUIDs from installer cards
        // $userUuids = $installercards->pluck('user_uuid')->filter()->unique();
        // // Initialize an empty collection to store users
        // $users = collect();
        // $LDConnections = ['pgsql_lanthit', 'pgsql_ayetharyar', 'pgsql_eastdagon','pgsql_hlaingtharyar','pgsql_mawlamyine','pgsql_satsan','pgsql_tampawady','pgsql_terminalm','pgsql_theikpan','pgsql_southdagon','pgsql_bago','pgsql_shwepyithar']; // Add all branch DB names
        // // Loop through each branch connection to fetch users
        // foreach ($LDConnections as $connection) {
        //     $branchUsers = DB::connection($connection)
        //         ->table('users')
        //         ->whereIn('uuid', $userUuids)
        //         ->get();
        //     $users = $users->merge($branchUsers); // Merge users from each branch
        // }
        // // Map user details to the installer cards
        // $users = $users->keyBy('uuid'); // Index users by UUID for easy access
        // $installercards->each(function ($installercard) use ($users) {
        //     $installercard->users = $users->get($installercard->user_uuid);
        // });

        $previousUrl = url()->previous();
        $previousRoute = app('router')->getRoutes()->match(app('request')->create($previousUrl));
        $previousRouteName = $previousRoute->getName();
        if(!(strpos($previousRouteName, 'installercards.store') === 0)){
            // $request->session()->forget('ver_phone');
            Session::forget("ver_phone");
        }

        return view("installercards.index",compact("installercards"));
    }

    public function create(){
        $provinces = Province::orderBy('province_id')->get();
        $amphurs = Amphur::orderBy('amphur_id')->get();
        return view("installercards.create",compact('provinces','amphurs'));
    }
    public function store(Request $request){
        $request->merge([
            'prevmonths_sale_amount' => str_replace(',', '', $request->prevmonths_sale_amount)
        ]);
        // dd($request);

        $prevmonths_sale_amt_limit = $this->getInstallerCriterias()["prevmonths_sale_amt_limit"];
        $validatearrs = [
            // "card_number" => [
            //     "required",
            //     function ($attribute, $value, $fail) {
            //         // Check the centralized database for uniqueness
            //         $exists = DB::connection('centralpgsql')
            //             ->table('installer_cards')
            //             ->where('card_number', $value)
            //             ->exists();

            //         if ($exists) {
            //             $fail("The {$attribute} has already been taken in the centralized database.");
            //         }
            //     },
            //     "min:10",
            //     "max:10"
            // ],
            "card_number"=>"required|unique:installer_cards,card_number|min:10|max:10",

            "fullname"=>"required",
            "phone"=>"required",
            "address"=>"required",
            "gender"=>"required",
            "dob"=>"required",
            "nrc"=>"required",
            'member_active'=>"required",
            'customer_active'=>"required",
            'customer_rank_id'=>"required",
            "customer_barcode"=>"required",

            "gbh_customer_id"=>"required",

            // "prevmonths_sale_amount"=>"required|numeric|min:$prevmonths_sale_amt_limit",

            "images" => "required|array",
            "images.*"=>"mimes:jpg,jpeg,png,pdf|max:10240",
        ];

        $user = Auth::user();
        $user_id = $user->id;
        $user_uuid = $user->uuid;
        $branch_id = getCurrentBranch();

        if(!$user->can("create-installer-card")){
            $validatearrs["prevmonths_sale_amount"] = "required|numeric|min:$prevmonths_sale_amt_limit";
        }

        $request->validate($validatearrs);


        $installercard = new InstallerCard();
        $installercard->card_number = $request->card_number;
        $installercard->branch_id = $branch_id;

        $installercard->fullname = $request->fullname;
        $installercard->phone = $request->phone;
        $installercard->address = $request->address;
        $installercard->gender = $request->gender;
        $installercard->dob = $request->dob;
        $installercard->nrc = $request->nrc;
        $installercard->passport = $request->passport;
        $installercard->identification_card = $request->identification_card;
        $installercard->member_active = $request->member_active;
        $installercard->customer_active = $request->customer_active;
        $installercard->customer_rank_id = $request->customer_rank_id;

        $installercard->customer_barcode = $request->customer_barcode;

        $installercard->titlename = $request->titlename;
        $installercard->firstname = $request->firstname;
        $installercard->lastnanme = $request->lastnanme;
        $installercard->province_id = $request->province_id;
        $installercard->amphur_id = $request->amphur_id;
        $installercard->nrc_no = $request->nrc_no;
        $installercard->nrc_name = $request->nrc_name;
        $installercard->nrc_short = $request->nrc_short;
        $installercard->nrc_number = $request->nrc_number;
        $installercard->gbh_customer_id = $request->gbh_customer_id;


        $installercard->totalpoints = 0;
        $installercard->totalamount = 0;
        $installercard->credit_points = 0;
        $installercard->credit_amount = 0;
        $installercard->issued_at = Carbon::now();
        $installercard->user_uuid = $user_uuid;
        $installercard->status = 1;
        $installercard->save();
        dispatch(new SyncRowJob("installer_cards","insert",$installercard));


        // // Update Other Card Status as Inactive
        // $otherinstallercards = InstallerCard::where('gbh_customer_id',$installercard->gbh_customer_id)->where("card_number","!=",$installercard->card_number)->get();
        // foreach($otherinstallercards as $otherinstallercard){
        //     // Update all matching rows' status to inactive (status = 0) in one query
        //     $otherinstallercard->update([
        //         'status' => 0
        //     ]);
        //     dispatch(new SyncRowJob("installer_cards","update",$otherinstallercard));
        // }



        // Multi Images Upload
        if($request->hasFile('images')){
            foreach($request->file("images") as $image){
                 $installercardfile = new InstallerCardFile();
                 $installercardfile->installer_card_card_number = $installercard->card_number;

                 $file = $image;
                 $fname = $file->getClientOriginalName();
                 $imagenewname = $installercard['card_number'].$fname;
                 $file->move(public_path('assets/img/installercards/'),$imagenewname);


                 $filepath = 'assets/img/installercards/'.$imagenewname;
                 $installercardfile->image = $filepath;

                 $installercardfile->save();
            }
        }


        return redirect()->route('installercards.index')->with('success','New Installer Registered Successfully');
    }

    // public function verifycustomer(Request $request){

    //     $request->validate([
    //         "ver_phone"=>"required|numeric",
    //     ]);
    //     $verifyphone = $request['ver_phone'];
    //     // dd($verifyphone);

    //     $user = Auth::user();
    //     $user_uuid = $user->uuid;
    //     $userbranches = BranchUser::where('user_uuid',$user_uuid)->pluck('branch_id');

    //     $customermodels = [
    //         \App\Models\POS101\Pos101GbhCustomer::class,
    //         \App\Models\POS101\Pos101GbhCustomer::class,
    //         \App\Models\POS102\Pos102GbhCustomer::class,
    //         \App\Models\POS103\Pos103GbhCustomer::class,
    //         \App\Models\POS104\Pos104GbhCustomer::class,
    //         \App\Models\POS105\Pos105GbhCustomer::class,
    //         \App\Models\POS106\Pos106GbhCustomer::class,
    //         \App\Models\POS107\Pos107GbhCustomer::class,
    //         \App\Models\POS108\Pos108GbhCustomer::class,
    //         \App\Models\POS112\Pos112GbhCustomer::class,
    //         \App\Models\POS113\Pos113GbhCustomer::class,
    //         \App\Models\POS114\Pos114GbhCustomer::class,
    //     ];
    //     // dd($userbranches);

    //     // dd($customermodels[0]::where('mobile',$verifyphone)->first());

    //     foreach($userbranches as $userbranch){
    //                     // dynamically referencing modal class according to User Branch
    //         $customer = $customermodels[$userbranch - 1]::where('mobile',$verifyphone)->first();
    //         if($customer){
    //             $customershopbranchid = $userbranch;
    //             break;
    //         }
    //     }

    //     // $customer = Pos101GbhCustomer::where('mobile',$verifyphone)->first();
    //     // dd($customer);
    //     return response()->json(["customer"=>$customer,"customershopbranchid"=>$customershopbranchid]);
    // }

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

    public function edit($card_number){
        $installercard = InstallerCard::where('card_number',$card_number)->orderBy('id','asc')->first();

        $installercardcount = InstallerCard::where('customer_barcode',$installercard->customer_barcode)
                                ->whereIn("stage",["approved"])
                                ->where('card_number',"!=",$card_number)->count();
        // dd($installercardcount);

        $card_numbers = InstallerCard::where('customer_barcode',$installercard->customer_barcode)
                        ->whereIn("stage",["approved"])
                        ->where("card_number","!=",$card_number)->pluck('card_number');


        $homeower_uuids = HomeownerInstaller::pluck('home_owner_uuid');
        $homeowners = HomeOwner::whereNotIn("uuid",$homeower_uuids)->get();
        $homeownerinstallers = HomeownerInstaller::where('installer_card_card_number',$card_number)->get();
        $homeownerinstallerhistories = HomeownerInstallerHistory::where('installer_card_card_number',$card_number)->orderBy("id",'desc')->get();

        return view("installercards.edit",compact('installercard','installercardcount','card_numbers','homeowners','homeownerinstallers','homeownerinstallerhistories'));
    }

    public function refresh($card_number){
        $installercard = InstallerCard::where('card_number',$card_number)->orderBy('id','asc')->first();
        $customer_barcode = $installercard->customer_barcode;

        $branch_id = getCurrentBranch();
        $gbhcustomer = getCustomerInfoById($branch_id,$customer_barcode);

        // dd($gbhcustomer);
        $installercard->update([
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
        dispatch(new SyncRowJob("installer_cards","update",$installercard));


        return redirect()->route('installercards.edit',$card_number)->with("success",'Installer Card Updated');

    }

    // public function update($card_number,Request $request){
    //     $request->validate([
    //         "name"=>"required",
    //         "phone"=>"required",
    //     ]);

    //     $installercard = InstallerCard::where('card_number',$card_number)->first();
    //     $installercard->name = $request->name;
    //     $installercard->phone = $request->phone;
    //     $installercard->save();

    //     return response()->json(["message"=>"Installer Card Updated"],201);

    // }

    public function destroy($card_number){
        // InstallerCard::destroy($id);
        $installercard = InstallerCard::where('card_number',$card_number)->orderBy('id','asc')->first();
        // Save the ID before deletion
        $installercardId = $installercard->id;

        // Delete the installer card
        $installercard->delete();

        // Dispatch sync job with only the necessary data
        dispatch(new SyncRowJob("installer_cards", "delete", ['id' => $installercardId]));
        return redirect()->route('installercards.index')->with('success','Installer Deleted Successfully');
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

    public function checking(){
        return view('installercards.checking');
    }

    public function check(Request $request){
        $inscardnumber = $request->inscardnumber;

        $installercard = InstallerCard::where('card_number',$inscardnumber)->first();

        if($installercard){
            if($installercard->stage === 'pending'){
                return response()->json(["title"=>"Oops, Installer Card Invalid","message"=>"Installer Card is not approved yet"]);
            }
            else  if($installercard->status === 0){
                return response()->json(["title"=>"Oops, Installer Card Invalid","message"=>"Installer Card is deactivated"]);
            }else{
                return response()->json(["installercard"=>$installercard]);
            }
        }else{
            return response()->json(["title"=>"Oops, Installer Card Not Found","message"=>"Installer Card Number Incorrect!!"]);
        }
    }

    // public function changestatus(Request $request){
    //     $installercard = InstallerCard::where('card_number',$request->card_number)->orderBy('id','asc')->first();
    //     $installercard->status = $request["status"];
    //     $installercard->save();

    //     return response()->json(["success"=>"Status Change Successfully"]);
    // }

    public function track($card_number,Request $request){
        $installercard = InstallerCard::where('card_number',$card_number)->first();

        $allinstallercards = InstallerCard::where('customer_barcode',$installercard->customer_barcode)
                            ->whereIn("stage",["approved"])
                            ->orderBy('created_at','desc')->get();
        // dd($allinstallercards);


        return view("installercards.track",compact("allinstallercards"))->render();
    }

    public function storecardlock(Request $request){

        Session::put('cardlock',"open");

        return response()->json(["success"=>"Car Unlocked Successfully"]);
    }

    // public function transfer(Request $request,$old_installer_card_card_number){
    //     // dd($old_installer_card_card_number);
    //     $request->validate([
    //         "new_installer_card_card_number"=>"required",
    //         'transfer_type'=>"required"
    //     ]);

    //     \DB::beginTransaction();
    //     try{

    //         $user = Auth::user();
    //         $user_uuid = $user->uuid;


    //         $old_installer_card = InstallerCard::where('card_number',$old_installer_card_card_number)
    //                                 ->orderBy('id','asc')->first();

    //         $new_installer_card = InstallerCard::where('card_number',$request->new_installer_card_card_number)
    //                                 ->orderBy('id','asc')->first();


    //         // dd($old_installer_card,$new_installer_card);

    //         $old_collection_transactions = CollectionTransaction::where('installer_card_card_number',$old_installer_card_card_number);
    //         $old_collection_transactions->update([
    //             "installer_card_card_number"=>$new_installer_card->card_number
    //         ]);

    //         $old_installer_card_points = InstallerCardPoint::where('installer_card_card_number',$old_installer_card_card_number);
    //         $old_installer_card_points->update([
    //             "installer_card_card_number"=>$new_installer_card->card_number
    //         ]);

    //         $old_redemption_transactions = RedemptionTransaction::where('installer_card_card_number',$old_installer_card_card_number);
    //         $old_redemption_transactions->update([
    //             "installer_card_card_number"=>$new_installer_card->card_number
    //         ]);

    //         $old_return_banners = ReturnBanner::where('installer_card_card_number',$old_installer_card_card_number);
    //         $old_return_banners->update([
    //             "installer_card_card_number"=>$new_installer_card->card_number
    //         ]);

    //         $new_installer_card->update([
    //             'totalpoints'=>$old_installer_card->totalpoints,
    //             'totalamount'=>$old_installer_card->totalamount,
    //             'credit_points'=>$old_installer_card->credit_points,
    //             'credit_amount'=>$old_installer_card->credit_amount,
    //             'status'=>1
    //         ]);

    //         $old_installer_card->update([
    //             'totalpoints'=>0,
    //             'totalamount'=>0,
    //             'credit_points'=>0,
    //             'credit_amount'=>0,
    //             'status'=>0
    //         ]);

    //         InstallerCardTransferLog::create([
    //             'transfer_type'=>$request->transfer_type,
    //             'old_installer_card_card_number'=>$old_installer_card_card_number,
    //             'new_installer_card_card_number'=>$new_installer_card->card_number,
    //             'transferred_points'=>$old_installer_card->totalpoints,
    //             'transferred_amount'=>$old_installer_card->totalamount,
    //             'transferred_credit_points'=>$old_installer_card->credit_points,
    //             'transferred_credit_amount'=>$old_installer_card->credit_amount,
    //             'user_uuid'=>$user_uuid
    //         ]);
    //         \DB::commit();

    //         return redirect()->route('installercards.index')->with("success", "New installer card is successfully transferred.");;
    //     }catch(Exception $err){
    //         \DB::rollback();

    //         return redirect()->route('installercards.checking')->with("error","There is an error in transferring installer card");
    //     }
    // }


    public function transfer(Request $request,$old_installer_card_card_number){
        // dd($old_installer_card_card_number);
        $request->validate([
            "old_installer_card_card_number"=>"required",
            "new_installer_card_card_number"=>"required",
            'transfer_type'=>"required",
            "images" => "required|array",
            "images.*"=>"image|mimes:jpg,jpeg,png|max:1024",
        ]);

        \DB::beginTransaction();
        try{

            $user = Auth::user();
            $user_id = $user->id;
            $user_uuid = $user->uuid;

            $old_installer_card = InstallerCard::where('card_number',$old_installer_card_card_number)
                                    ->orderBy('id','asc')->first();

            $new_installer_card = InstallerCard::where('card_number',$request->new_installer_card_card_number)
                                    ->orderBy('id','asc')->first();


            // dd($old_installer_card,$new_installer_card);

            //  dd($old_installer_card,$new_installer_card);

            // Start Check Transfer Avaibility
                // Check if the old card is already disabled
                // if ($old_installer_card->status == 0) {
                //     return redirect()->back()->with('error', 'This old installer card is already disabled.');
                // }

                if(empty($new_installer_card)){
                    return redirect()->back()->with('error', "Firstly Cashier must register new card.");
                }
                if ($old_installer_card->gbh_customer_id != $new_installer_card->gbh_customer_id) {
                    return redirect()->back()->with('error', "This card is only transferable to its original owner.");
                }
            // End Check Transfer Avaibility



            $old_collection_transactions = CollectionTransaction::where('installer_card_card_number',$old_installer_card_card_number)
                                            ->orderBy('created_at','asc')
                                            ->orderBy('id','asc')
                                            ->get();
            foreach($old_collection_transactions as $old_collection_transaction){
                $new_collection_transaction = $old_collection_transaction->replicate()->fill([
                    "installer_card_card_number"=>$new_installer_card->card_number
                ]);
                $new_collection_transaction->save();
                dispatch(new SyncRowJob("collection_transactions","insert",$new_collection_transaction));

                $old_collection_transaction->delete();
                dispatch(new SyncRowJob("collection_transactions","update",$old_collection_transaction));
            }



            $old_installer_card_points = InstallerCardPoint::where('installer_card_card_number',$old_installer_card_card_number)
                                            ->orderBy('created_at','asc')
                                            ->orderBy('id','asc')
                                            ->get();
            foreach($old_installer_card_points as $old_installer_card_point){
                $new_installer_card_point = $old_installer_card_point->replicate()->fill([
                    "installer_card_card_number"=>$new_installer_card->card_number
                ]);
                $new_installer_card_point->save();
                dispatch(new SyncRowJob("installer_card_points","insert",$new_installer_card_point));

                $old_installer_card_point->delete();
                dispatch(new SyncRowJob("installer_card_points","update",$old_installer_card_point));
            }


            $old_redemption_transactions = RedemptionTransaction::where('installer_card_card_number',$old_installer_card_card_number)
                                            ->orderBy('created_at','asc')
                                            ->orderBy('id','asc')
                                            ->get();
            foreach($old_redemption_transactions as $old_redemption_transaction){
                $new_redemption_transaction = $old_redemption_transaction->replicate()->fill([
                    "installer_card_card_number"=>$new_installer_card->card_number
                ]);
                $new_redemption_transaction->save();
                dispatch(new SyncRowJob("redemption_transactions","insert",$new_redemption_transaction));


                $old_redemption_transaction->delete();
                dispatch(new SyncRowJob("redemption_transactions","update",$old_redemption_transaction));
            }

            $old_return_banners = ReturnBanner::where('installer_card_card_number',$old_installer_card_card_number)
                                            ->orderBy('created_at','asc')
                                            ->orderBy('id','asc')
                                            ->get();
            foreach($old_return_banners as $old_return_banner){
                $new_return_banner = $old_return_banner->replicate()->fill([
                    "installer_card_card_number"=>$new_installer_card->card_number
                ]);
                $new_return_banner->save();
                dispatch(new SyncRowJob("return_banners","insert",$new_return_banner));


                $old_return_banner->delete();
                dispatch(new SyncRowJob("return_banners","update",$old_return_banner));
            }



            $installercardtransferlog = InstallerCardTransferLog::create([
                'uuid' => (string) Str::uuid(),
                'transfer_type'=>$request->transfer_type,
                'old_installer_card_card_number'=>$old_installer_card_card_number,
                'new_installer_card_card_number'=>$new_installer_card->card_number,
                'transferred_points'=>$old_installer_card->totalpoints,
                'transferred_amount'=>$old_installer_card->totalamount,
                'transferred_credit_points'=>$old_installer_card->credit_points,
                'transferred_credit_amount'=>$old_installer_card->credit_amount,
                'user_uuid'=>$user_uuid
            ]);
            $new_installer_card->update([
                'totalpoints'=>$old_installer_card->totalpoints,
                'totalamount'=>$old_installer_card->totalamount,
                'credit_points'=>$old_installer_card->credit_points,
                'credit_amount'=>$old_installer_card->credit_amount,
                'status'=>1
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$new_installer_card));


            $old_installer_card->update([
                'totalpoints'=>0,
                'totalamount'=>0,
                'credit_points'=>0,
                'credit_amount'=>0,
                'status'=>0
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$old_installer_card));


            // Multi Images Upload
            if($request->hasFile('images')){
                foreach($request->file("images") as $image){
                     $installercardtransferfile = new InstallerCardTransferFile();
                     $installercardtransferfile->installer_card_transfer_log_uuid = $installercardtransferlog->uuid;

                     $file = $image;
                     $fname = $file->getClientOriginalName();
                     $imagenewname = uniqid($user_id).$installercardtransferlog['id'].$fname;
                     $file->move(public_path('assets/img/installercardtransfers/'),$imagenewname);


                     $filepath = 'assets/img/installercardtransfers/'.$imagenewname;
                     $installercardtransferfile->image = $filepath;

                     $installercardtransferfile->save();
                    // dispatch(new SyncRowJob("redemption_transaction_files","insert",$installercardtransferfile));
                }

           }

            \DB::commit();

            return redirect()->route('installercards.index')->with("success", "New installer card is successfully transferred.");;
        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('installercards.index')->with("error", "There is an error in transferring installer card");;
        }
    }

    public function register(){
        $prevmonths_sale_amt_limit = $this->getInstallerCriterias()["prevmonths_sale_amt_limit"];
        // dd($prevmonths_sale_amt_limit);
        return view("installercards.register",compact("prevmonths_sale_amt_limit"));
    }

    public function matchbysaleamount(Request $request){
        // dd($request->match_phone);

        $month_limit = 6;
        $match_phones = $request->match_phones;
        // dd($match_phones);
        $filtered_match_phones = array_filter($match_phones, function ($value) {
            return $value !== null && $value !== 0 && $value !== '';
        });
        $filtered_match_phones = array_unique($filtered_match_phones);
        // dd($filtered_match_phones);
        $prevmonths_sale_amount_arr = getPrevMonthsSaleAmounts($filtered_match_phones);
        // dd(empty($prevmonths_sale_amount_arr));
        $prevmonths_sale_amounts = !empty($prevmonths_sale_amount_arr) ? collect($prevmonths_sale_amount_arr) : collect();
        $is_sale = count($prevmonths_sale_amounts) > 0;

        $primary_result = 0;
        $secondary_result1 = 0;
        $secondary_result2 = 0;
        $response_arr= [];
        foreach($filtered_match_phones as $idx=>$filtered_match_phone){
            if($idx == 0){
                // dd(count($prevmonths_sale_amounts) > 0);
                if($is_sale){
                    // dd($prevmonths_sale_amounts);
                    $primary_result =  $prevmonths_sale_amounts->where('mobile',$filtered_match_phone)->first();
                }
                $response_arr['primary_result'] = $primary_result;
            }elseif($idx == 1){
                if($is_sale){
                    $secondary_result1 =  $prevmonths_sale_amounts->where('mobile',$filtered_match_phone)->first();
                }
                $response_arr['secondary_result1'] = $secondary_result1;
            }elseif($idx == 2){
                if($is_sale){
                    $secondary_result2 = $prevmonths_sale_amounts->where('mobile',$filtered_match_phone)->first();
                }
                $response_arr['secondary_result2'] = $secondary_result2;
            }
        }


        $totalsaleamount = $prevmonths_sale_amounts->sum('amnt');
        // dd($totalsaleamount);
        $branch_id = getCurrentBranch();
        $user = Auth::user();
        $user_uuid = $user->uuid;
        $saleamountcheck = SaleAmountCheck::create([
            'uuid' => (string) Str::uuid(),
            'primary_phone'=> $filtered_match_phones[0],
            'total_sale_amount'=> $totalsaleamount,
            'branch_id'=> $branch_id,
            'user_uuid'=> $user_uuid
        ]);

        foreach($prevmonths_sale_amounts as $prevmonths_sale_amount){
            CusSaleAmounts::create([
                'customer_barcode'=>  $prevmonths_sale_amount->customer_barcode,
                'phone'=> $prevmonths_sale_amount->mobile,
                'sale_amount'=> $prevmonths_sale_amount->amnt,
                'sale_amount_check_uuid'=> $saleamountcheck->uuid
            ]);
        }


        return response()->json(["data"=>$response_arr]);

    }

    public function getInstallerCriterias(){
        $installer_criterias = ["prevmonths_sale_amt_limit"=>1000000];
        return $installer_criterias;
    }

    public function approveCardRequest($card_number,Request $request){

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $installercard = InstallerCard::where("card_number",$card_number)->first();
        // dd($transaction);
        $installercard->update([
            "stage"=>"approved",
            "approved_by"=>$user_uuid,
            "approved_date"=>now(),
            "bm_remark"=>$request->remark
        ]);
        dispatch(new SyncRowJob("installer_cards","update",$installercard));

        // Update Other Card Status as Inactive
        $otherinstallercards = InstallerCard::where('gbh_customer_id',$installercard->gbh_customer_id)->where("card_number","!=",$installercard->card_number)->get();
        foreach($otherinstallercards as $otherinstallercard){
            // Update all matching rows' status to inactive (status = 0) in one query
            $otherinstallercard->update([
                'status' => 0
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$otherinstallercard));
        }

        return redirect()->back()->with('success','Installer Card Approved Successfully');

    }

    public function rejectCardRequest($card_number,Request $request){
        // dd('hay');

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $installercard = InstallerCard::where("card_number",$card_number)->first();
        // dd($transaction);

        $installercard->update([
            'status' => 0,
            "stage"=>"rejected",
            "approved_by"=>$user_uuid,
            "approved_date"=>now(),
            "bm_remark"=>$request->remark
        ]);
        dispatch(new SyncRowJob("installer_cards","update",$installercard));

        return redirect()->back()->with('success','Installer Card Rejected Successfully');

    }
}
