<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\CardNumber;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\CardNumbersExport;
use App\Models\CardNumberGenerator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CardNumberGeneratorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-card-number-generator', ['only' => ['index']]);
        $this->middleware('permission:create-card-number-generator', ['only' => ['create','store']]);
    }
    public function index(Request $request){
        $cardnumbergenerators = CardNumberGenerator::orderBy('id','desc')->paginate(10);
        return view("cardnumbergenerators.index",compact("cardnumbergenerators"));
    }
    public function create(Request $request){
        $user = Auth::user();
        $user_uuid = $user->uuid;
        $userbranches = BranchUser::where("user_uuid",$user_uuid)->pluck("branch_id");
        $branches = Branch::whereIn("branch_id",$userbranches)->get();
        return view('cardnumbergenerators.create',compact("branches"));
    }

    public function store(Request $request){
        // dd($request);
        $request->validate([
            'to_branch_id' => 'required',
            'quantity' => "required",
            'random'=>"required",
            'remark'=>"required"
        ]);

        \DB::beginTransaction();
        try{

            $branch_id = getCurrentBranch();
            $to_branch_id = $request->to_branch_id;
            $quantity = $request->quantity;
            $random = $request->random;
            $user = Auth::user();
            $user_uuid = $user->uuid;
            $remark = $request->remark;

            $cardnumbergenerator = CardNumberGenerator::create([
                "uuid"=> (string) Str::uuid(),
                "branch_id"=> $branch_id,
                // 'document_no' => $this->generate_doc_no($branch_id),
                "to_branch_id"=> $to_branch_id,
                "quantity"=> $quantity,
                "random"=> $random,
                'status' => 'pending',
                'prepare_by' => $user_uuid,
                "remark"=> $remark
            ]);

            $cardnumbers = $this->generate_card_number($to_branch_id,$quantity);
            // dd($cardnumbers);
            foreach($cardnumbers as $cardnumber){
                $cardnumberObj = new CardNumber();
                $cardnumberObj->card_number = $cardnumber;
                $cardnumberObj->card_number_generator_uuid =  $cardnumbergenerator->uuid;


                $text = $cardnumber; // Replace with your specific text
                $qrCode = QrCode::format('png')->size(100)->generate($text);
                $qr_file_path = public_path("assets/img/cardnumbers/{$cardnumber}.png");
                $filepath = "assets/img/cardnumbers/{$cardnumber}.png";
                // Ensure the directory exists
                if (!file_exists(dirname($qr_file_path))) {
                    mkdir(dirname($qr_file_path), 0755, true);
                }
                // Save QR code image to the file path
                file_put_contents($qr_file_path, $qrCode);

                // Save cardnumberObj to the database
                $cardnumberObj->image = $filepath;
                $cardnumberObj->save();

            }

            \DB::commit();
            return redirect()->route('cardnumbergenerators.index')->with('success','New Cards Created Successfully');

        }catch(Exception $err){
            \DB::rollback();

            return redirect()->route('pointpromos.index')->with("error","There is an error in creation Point Promotion");
        }
    }


    public function edit($uuid){
        $cardnumbergenerator = CardNumberGenerator::where('uuid',$uuid)->orderBy('id','asc')->first();
        $branches = Branch::all();

        $cardnumbers = CardNumber::where('card_number_generator_uuid',$uuid)->get();

        // $qrviews = $this->generateQrCodeView($cardnumbers);

        return view("cardnumbergenerators.edit",compact('cardnumbergenerator',"branches","cardnumbers"));
    }


    // public static function generate_doc_no($branch_id)
    // {
    //     $prefix = 'ICG';
    //     $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
    //     $todayDate = Carbon::now()->format('Ymd'); // Format as YYYYMMDD


    //     // Query the latest document for the branch from today
    //     $lastcardnumbergenerator = Redemptioncardnumbergenerator::where('branch_id', $branch_id)
    //                             ->whereDate('created_at', Carbon::today())
    //                             ->orderBy('document_no', 'desc')
    //                             ->first();
    //     // dd($lastcardnumbergenerator);

    //     // Set initial suffix
    //     $newSuffix = '0001';

    //     if ($lastcardnumbergenerator) {
    //         // Extract the numeric suffix from the last document number
    //         $lastSuffix = (int) substr($lastcardnumbergenerator->document_no, -4);

    //         // Increment the suffix by 1 and pad with zeros
    //         $newSuffix = str_pad($lastSuffix + 1, 4, '0', STR_PAD_LEFT);
    //     }
    //     // Combine parts to create the new document number
    //     $documentNumber = $prefix . $branch_prefix . $todayDate . '-' . $newSuffix;
    //     // dd($documentNumber);

    //     return $documentNumber;
    // }

    public static function generate_card_number($to_branch_id,$quantity){
        $prefix = getCardPrefix($to_branch_id);
        $randomstring = randomstringgenerator(7);
        $card_numbers = [];

        for ($j = 0; $j < $quantity; $j++) {
            $newcardnumber =  $prefix.$randomstring;
            while(\DB::table("card_numbers")->where("card_number",$newcardnumber)->exists() || in_array($newcardnumber, $card_numbers)){
                $randomstring = randomstringgenerator(7);
                $newcardnumber =  $prefix.$randomstring;
            }
            // dd($newcardnumber);
            $card_numbers[] = $newcardnumber;
        }
        return $card_numbers;
    }


    public function generateQrCodeView($cardnumbers)
    {
        $qr_views = [];
        // dd($cardnumbers);
        foreach($cardnumbers as $cardnumber){
            $text = $cardnumber->card_number; // Replace with your specific text
            $qrCode = QrCode::format('png')->size(100)->generate($text);
            $base64QrCode = base64_encode($qrCode);
            $qr_views[] = $base64QrCode;
        }
        // dd($qr_views);
        return $qr_views;


    }

    public function export($uuid)
    {
        // dd("Excel Exported");
        $cardnumbergenerator = CardNumberGenerator::where('uuid',$uuid)->orderBy('id','asc')->first();
        $cardnumbers = CardNumber::where('card_number_generator_uuid',$uuid)->get();
        // $qrViews = $this->generateQrCodeView($cardnumbers);

        $cardnumbergenerator->update([
            "status"=>"exported",
        ]);


        return Excel::download(new CardNumbersExport($cardnumbers), "$cardnumbergenerator->document_no.xlsx");
    }


    public function approveCardNumberGenerator($uuid,Request $request){

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $cardnumbergenerator = CardNumberGenerator::where("uuid",$uuid)->first();
        // dd($cardnumbergenerator);
        $cardnumbergenerator->update([
            "status"=>"approved",
            "approved_by"=>$user_uuid,
            "approved_date"=>now(),
            "mkt_mgr_remark"=>$request->remark
        ]);

        // readIRENotification($uuid);
        // sendIRENotification('Finance',$cardnumbergenerator);


        return redirect()->back();

    }

    public function rejectCardNumberGenerator($uuid,$step,Request $request){
        // dd('hay');

        $user = Auth::user();
        $user_uuid = $user->uuid;
        $cardnumbergenerator = CardNumberGenerator::where("uuid",$uuid)->first();
        // dd($cardnumbergenerator);

        if($step == 'mkt-mgr'){
            $cardnumbergenerator->update([
                "status"=>"rejected",
                "approved_by"=>$user_uuid,
                "approved_date"=>now(),
                "mkt_mgr_remark"=>$request->remark
            ]);
        }

        $cardnumbers = CardNumber::where('card_number_generator_uuid',$uuid)->get();
        foreach($cardnumbers as $cardnumber){
            $cardnumber->update([
                'card_number'=> "REJ".$cardnumber->card_number
            ]);
        }

        // readIRENotification($uuid);
        // sendIRESingleUserNotification($cardnumbergenerator->prepare_by,$cardnumbergenerator);

        return redirect()->back();

    }

}
