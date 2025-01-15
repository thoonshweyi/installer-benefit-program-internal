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
            'for_branch_id' => 'required',
            'quantity' => "required",
            'random'=>"required"
        ]);


        $branch_id = getCurrentBranch();
        $for_branch_id = $request->for_branch_id;
        $quantity = $request->quantity;
        $random = $request->random;
        $user = Auth::user();
        $user_uuid = $user->uuid;
        $remark = $request->remark;

        $cardnumbergenerator = CardNumberGenerator::create([
            "uuid"=> (string) Str::uuid(),
            "branch_id"=> $branch_id,
            // 'document_no' => $this->generate_doc_no($branch_id),
            "for_branch_id"=> $for_branch_id,
            "quantity"=> $quantity,
            "random"=> $random,
            'status' => 'pending',
            'prepare_by' => $user_uuid,
            "remark"=> $remark
        ]);

        $cardnumbers = $this->generate_card_number($for_branch_id,$quantity);
        // dd($cardnumbers);
        foreach($cardnumbers as $cardnumber){
            $cardnumber = CardNumber::create([
                'card_number'=> $cardnumber,
                'card_number_generator_uuid'=> $cardnumbergenerator->uuid
            ]);
        }

        return redirect()->route('cardnumbergenerators.index')->with('success','New Cards Created Successfully');
    }

    public function edit($uuid){
        $cardnumbergenerator = CardNumberGenerator::where('uuid',$uuid)->orderBy('id','asc')->first();
        $branches = Branch::all();

        $cardnumbers = CardNumber::where('card_number_generator_uuid',$uuid)->get();

        $qrviews = $this->generateQrCodeView($cardnumbers);

        return view("cardnumbergenerators.edit",compact('cardnumbergenerator',"branches","qrviews"));
    }


    // public static function generate_doc_no($branch_id)
    // {
    //     $prefix = 'ICG';
    //     $branch_prefix = Branch::select('branch_short_name')->where('branch_id', $branch_id)->first()->branch_short_name;
    //     $todayDate = Carbon::now()->format('Ymd'); // Format as YYYYMMDD


    //     // Query the latest document for the branch from today
    //     $lasttransaction = RedemptionTransaction::where('branch_id', $branch_id)
    //                             ->whereDate('created_at', Carbon::today())
    //                             ->orderBy('document_no', 'desc')
    //                             ->first();
    //     // dd($lasttransaction);

    //     // Set initial suffix
    //     $newSuffix = '0001';

    //     if ($lasttransaction) {
    //         // Extract the numeric suffix from the last document number
    //         $lastSuffix = (int) substr($lasttransaction->document_no, -4);

    //         // Increment the suffix by 1 and pad with zeros
    //         $newSuffix = str_pad($lastSuffix + 1, 4, '0', STR_PAD_LEFT);
    //     }
    //     // Combine parts to create the new document number
    //     $documentNumber = $prefix . $branch_prefix . $todayDate . '-' . $newSuffix;
    //     // dd($documentNumber);

    //     return $documentNumber;
    // }

    public static function generate_card_number($for_branch_id,$quantity){
        $prefix = getCardPrefix($for_branch_id);
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



    protected static function generatestudentid(){
        return \DB::transaction(function(){
            $lateststudent = \DB::table("students")->orderBy("id","desc")->first();
            $latestid= $lateststudent ?  $lateststudent->id : 0;
            $newstudentid = "WDF".str_pad($latestid+1,5,"0",STR_PAD_LEFT);

            while(\DB::table("students")->where("regnumber",$newstudentid)->exists()){
                $latestid++;
                $newstudentid = "WDF".str_pad($latestid+1,5,"0",STR_PAD_LEFT);
            }
            return $newstudentid;
        });
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
        $qrViews = $this->generateQrCodeView($cardnumbers);


        return Excel::download(new CardNumbersExport($cardnumbers, $qrViews), 'card_numbers.xlsx');
    }


}
