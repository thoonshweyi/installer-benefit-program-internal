<?php

namespace App\Http\Controllers;

use App\Models\ReturnCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionTransaction;

class ReturnProductDocumentsController extends Controller
{
    // public function checking(Request $request){
    //     $return_product_docno = $request->return_product_docno;
    //     // dd($return_product_docno);
    //     $collectiontransactions = [];
    //     if(isset($request->return_product_docno)){
    //         $branch_id = getCurrentBranch();


    //         $ret_cat_grp_totals = getRetCategoryGroupPointTotal($return_product_docno,$branch_id);
    //         // dd($ret_cat_grp_totals);
    //         if(empty($ret_cat_grp_totals)){
    //             return redirect()->route('returnproductdocuments.checking')->with("error","Invalid Return Prodoct Document");
    //         }
    //         $saleinvoice_no = $ret_cat_grp_totals[0]->saleinvoice_no;


    //         $collectiontransactions = CollectionTransaction::
    //                                 where('invoice_number',$saleinvoice_no)
    //                                 ->orderBy("created_at",'desc')
    //                                 ->get();
    //     }
    //     return view("returnproductdocuments.checking",compact("collectiontransactions"));
    // }


    public function checking(Request $request){
        $invoice_number = $request->invoice_number;
        $collectiontransactions = [];
        if(isset($request->invoice_number)){

            $collectiontransactions = CollectionTransaction::
                                    where('invoice_number',$invoice_number)
                                    ->orderBy("created_at",'desc')
                                    ->get();

            $user = Auth::user();
            $user_uuid = $user->uuid;
            $branch_id = getCurrentBranch();

            $collection_transaction_uuid = count($collectiontransactions) > 0 ? $collectiontransactions->first()->uuid : null;
            $flag = count($collectiontransactions) > 0 ? 'found' : 'not found';
            $returncheck = ReturnCheck::create([
                'branch_id' => $branch_id,
                'invoice_number'=> $invoice_number,
                'collection_transaction_uuid'=>$collection_transaction_uuid,
                'flag'=> $flag,
                'user_uuid'=> $user_uuid
            ]);
        }
        return view("returnproductdocuments.checking",compact("collectiontransactions"));
    }
}
