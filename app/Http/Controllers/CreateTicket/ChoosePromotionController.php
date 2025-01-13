<?php

namespace App\Http\Controllers\CreateTicket;

use App\Models\Category;
use App\Models\LuckyDraw;
use App\Models\AmountCheck;
use Illuminate\Support\Str;
use App\Models\ClaimHistory;
use App\Models\ProductCheck;
use App\Models\TicketHeader;
use App\Models\LuckyDrawType;
use App\Models\Bago\BagoBrand;
use App\Models\LuckyDrawBrand;
use App\Models\LuckyDrawBranch;
use App\Models\LuckyDrawCategory;
use App\Models\Satsan\SatsanBrand;
use Illuminate\Support\Facades\DB;
use App\Models\TicketHeaderInvoice;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Lanthit\LanthitBrand;
use App\Models\FixedPrizeAmountCheck;
use App\Models\PromotionSubPromotion;
use App\Models\TheikPan\TheikPanBrand;
use App\Models\EastDagon\EastDagonBrand;
use App\Models\Tampawady\TampawadyBrand;
use App\Models\TerminalM\TerminalMBrand;
use App\Models\AyeTharyar\AyeTharyarBrand;
use App\Models\Mawlamyine\MawlamyineBrand;
use App\Models\SouthDagon\SouthDagonBrand;
use App\Http\Controllers\CustomerViewController;
use App\Models\HlaingTharyar\HlaingTharyarBrand;

class ChoosePromotionController extends Controller
{
    public function choose_promotion($ticket_header_uuid)
    {
        ///store customer view route////
        $customer_data = new CustomerViewController;
        $customer_data->store_customer_view();

        $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        //Find Active Promotions
        $promotions = LuckyDraw::where('status', 1)->with('promotion_sub_promotions')->get();

        $valid_total_price = 0;
        //Check old claim history
        $claim_history = ClaimHistory::where('ticket_header_uuid', $ticket_header_uuid)->where('choose_status', 2)->first();

        if (!$claim_history) {
            Log::debug('Start');
            $i = 1;
            foreach ($promotions as $promotion) {

                Log::debug('P' . $i . 'start');
                $promotion_data = $this->findPromotionData($promotion);

                $invoices = TicketHeaderInvoice::select('invoice_no')->where('ticket_header_uuid', $ticket_header_uuid)->get()->toArray();

                //Find Sub Promoiton
                $promoiton_sub_promotions = PromotionSubPromotion::where('promotion_uuid', $promotion->uuid)->where('deleted_at', null)->get();
                $j = 1;

                foreach ($promoiton_sub_promotions as $promoiton_sub_promotion) {
                    $valid_total_qty = 0;
                    if ($promoiton_sub_promotion->invoice_check_type == 1) {
                        $amount_check = AmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
                            ->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->first();
                        $one_qty_amount = $amount_check->amount;
                    } else {
                        $one_qty_amount = 0;
                    }
                    $total_qty = $this->findValidTotalQtyOfProductsFromInvoice($invoices, $promotion, $promoiton_sub_promotion, $promotion_data, $one_qty_amount);
                    if (is_numeric($total_qty) && $total_qty > 0) {
                        $valid_total_qty += $total_qty;
                    }
                    // }
                    //check and update
                    $one_claim_history = ClaimHistory::where('ticket_header_uuid', $ticket_header_uuid)
                        ->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
                        ->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->first();

                    $claim_history['promotion_uuid'] = $promoiton_sub_promotion->promotion_uuid;
                    $claim_history['sub_promotion_uuid'] = $promoiton_sub_promotion->sub_promotion_uuid;
                    $claim_history['ticket_header_uuid'] = $ticket_header_uuid;
                    $claim_history['valid_qty'] = $valid_total_qty;
                    $claim_history['one_qty_amount'] = $one_qty_amount;
                    $claim_history['invoice_check_type'] = $promoiton_sub_promotion->invoice_check_type;
                    $claim_history['prize_check_type'] = $promoiton_sub_promotion->prize_check_type;
                    if ($one_claim_history) {
                        if ($one_claim_history->choose_status != 2 && $one_claim_history->remain_choose_qty == $one_claim_history->valid_qty) {
                            $aa[] = $one_claim_history;
                            $claim_history['remain_choose_qty'] = (int) $valid_total_qty;
                            $claim_history['choose_status'] = 1;
                        } else {
                            $claim_history['remain_choose_qty'] = (int) $one_claim_history->remain_choose_qty;
                            $claim_history['choose_status'] = 2;
                        }
                        //update

                        $one_claim_history->update($claim_history);
                    } else {
                        $claim_history['remain_choose_qty'] = (int) $valid_total_qty;
                        $claim_history['choose_status'] = 1;
                        //create
                        $claim_history['uuid'] = (string) Str::uuid();
                        ClaimHistory::create($claim_history);
                    }
                    Log::debug('SP' . $j . 'Finish');
                    $j++;
                }

                Log::debug('P' . $i . 'Finish');
                $i++;
            }
        }
        $promotion_types = LuckyDrawType::where('status', 1)
            ->with(['promotions' => function ($query) use ($ticket_header_uuid) {
                $query->with(['claim_histories' => function ($query2) use ($ticket_header_uuid) {
                    $query2->where('ticket_header_uuid', $ticket_header_uuid)
                        ->orderby('created_at', 'ASC');
                }])
                    ->where('status', 1);
            }])
            ->orderby('created_at', 'ASC')
            ->get();
        Log::debug('finish');
        $ticket_header = TicketHeader::where('uuid', $ticket_header_uuid)->first();
        return view('create_tickets.choose-promotions', compact('ticket_header','ticket_header_uuid', 'promotion_types'));
    }

    public function findPromotionData($promotion)
    {
        $promotion_data = [];
        $luckydraw_branches = LuckyDrawBranch::where('promotion_uuid', $promotion->uuid)->with('branches')->get()->toarray();
        $promotion_data['luckydraw_branches'] = $luckydraw_branches;

        $luckydraw_categories = LuckyDrawCategory::select('category_id')->where('promotion_uuid', $promotion->uuid)->with('categories')->get()->toarray();
        $promotion_data['luckydraw_categories'] = $luckydraw_categories;

        $luckydraw_brands = LuckyDrawBrand::select('brand_id')->where('promotion_uuid', $promotion->uuid)->get()->pluck('brand_id')->toarray();
        $promotion_data['luckydraw_brands'] = $luckydraw_brands;
        return $promotion_data;
    }

    public function findValidTotalQtyOfProductsFromInvoice($invoices, $promotion, $promoiton_sub_promotion, $promotion_data, $one_qty_amount)
    {
        foreach ($invoices as $i) {
            $invoice_no[] = $i['invoice_no'];
        }
        //check used invoice
        $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)->where('promotion_uuid', $promotion->uuid)
            ->whereHas('ticket_headers', function ($q) {
                $q->where('status', '=', 1);
            })->first();

        if (isset($ticket_header_invoice)) {
            return response()->json(['error' => 'invoice_is_used'], 200);
        }
        $invoice_check_type = $promoiton_sub_promotion->invoice_check_type;

        $luckydraw_branches = $promotion_data['luckydraw_branches'];
        $luckydraw_categories = $promotion_data['luckydraw_categories'];
        $luckydraw_brands = $promotion_data['luckydraw_brands'];

        $checkInvoiceNo = null;
        $valid_productdata = [];
        $valid_total_price = 0;
        $valid_total_qty = 0;
        $valid = [];
        $totalprice = 0;
        $invoiceNormalData = null;
        $invoiceReturnData = null;

        if (mb_substr($invoice_no[0], 0, 4) == 'LAN1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = LanthitBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos101_pgsql');
            $branch_id = 1;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'MDY1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = TheikPanBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos102_pgsql');
            $branch_id = 2;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'SAT1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = SatsanBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos103_pgsql');
            $branch_id = 3;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'EDG1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = EastDagonBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos104_pgsql');
            $branch_id = 9;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'MLM1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = MawlamyineBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos105_pgsql');
            $branch_id = 10;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'MDY2') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = TampawadyBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos106_pgsql');
            $branch_id = 11;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'HTY1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = HlaingTharyarBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos107_pgsql');
            $branch_id = 19;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'ATY1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = AyeTharyarBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos108_pgsql');
            $branch_id = 21;
        }
        if (mb_substr($invoice_no[0], 0, 5) == 'PTMN1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = TerminalMBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos112_pgsql');
            $branch_id = 27;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'SDG1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = SouthDagonBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos113_pgsql');
            $branch_id = 28;
        }
        if (mb_substr($invoice_no[0], 0, 4) == 'BGO1') {
            if (!$luckydraw_brands) {
                $luckydraw_brands = BagoBrand::get()->toarray();

                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand['good_brand_id'];
                }
                $l_brands[] = 0;
            } else {
                foreach ($luckydraw_brands as $luckydraw_brand) {
                    $l_brands[] = $luckydraw_brand;
                }
            }
            $db_ext = DB::connection('pos110_pgsql');
            $branch_id = 23;
        }
        $luckydraw_brands = implode(", ", $l_brands);

        if (!$luckydraw_categories) {
            $luckydraw_categories = Category::get()->toarray();
            foreach ($luckydraw_categories as $luckydraw_category) {
                $l_categories[] = $luckydraw_category['erp_category_id'];
            }
        } else {
            foreach ($luckydraw_categories as $luckydraw_category) {
                $l_categories[] = $luckydraw_category['categories']['erp_category_id'];
            }
        }
        $luckydraw_categories = (string) implode(", ", $l_categories);

        $invoice_no = (string) implode("','", $invoice_no);
        $invoice_no = "'" . $invoice_no . "'";

        if ($promotion->diposit_type == 2) {
            $diposit_type_id = '2';
        } else if ($promotion->diposit_type == 3) {
            $diposit_type_id = '3';
        } else {
            $diposit_type_id = '2,3';
        }
        //Check By Amount or By Product
        if ($invoice_check_type == 1) {
            if ($promoiton_sub_promotion->prize_check_type == 3) {
                //Check Gold Ring - Fixed Prize Type - 1 for Gold Ring, 2 fo Gold Coin
                $check_gold_ring = FixedPrizeAmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)
                    ->pluck('fixed_prize_type')->first();

            } else {
                $check_gold_ring = 2;
            }
            if ($check_gold_ring == 1) {
                // 1 for Gold Ring,
                //Check Invoice No for Normal Invoice
                $invoiceData = $db_ext->select("
                    SELECT sum(net_amount) as total_net_amount from (SELECT ------return
                    return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id, return_product_doc_branchcode,saleamount, voucher_value::numeric(19,2) as Coupon, CASE WHEN noreturnamnt<'0' then '0' ELSE noreturnamnt end as net_amount
                        FROM (
                            SELECT return_date, saleinvoice_no, gbh_customer_id, return_product_doc_branchcode, return_product_doc_branchname, sum(total) as noreturnamnt,return_product_doc_ref_doc_id,sum(voucher_value) as voucher_value
                                FROM
                                (SELECT  return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, Cate_type,total,return_product_doc_ref_doc_id,voucher_value
                        FROM
                        (SELECT distinct(return_product_doc_ref_docno)  as saleinvoice_no,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id,voucher_value::numeric(19,2)
                        FROM
                            (SELECT return_product_doc_datenow::date as return_date, CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                            ELSE return_product_item_barcode_code end as return_product_item_barcode_codes, *
                            FROM return_product.return_product_doc bb
                                LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                                INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                WHERE return_product_doc_ref_docno in ($invoice_no)
                                AND return_product_item_barcode_code not in
                                    ( SELECT return_product_item_barcode_code
                                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                            WHERE return_product_doc_ref_docno in ($invoice_no)
                                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                            AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                            AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                            AND sc.sale_cash_document_datenow::date = now()::date
                                        )AND cc.return_product_item_amount::numeric(19,2) ='0'
                                            AND return_product_item_active <> 't'
                                    )tt
                                    LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                                    LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                                    LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                                    where category_id in ($luckydraw_categories)
                                    AND pro.diposit_type_id in ($diposit_type_id)
                                    AND good_brand_id in ($luckydraw_brands)
                                    AND tt.return_product_item_barcode_codes not like '400502%'

                                    group by return_product_doc_ref_docno,return_product_docno,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                                    , return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name,return_product_doc_ref_doc_id,voucher_value
                        )noreturn

                    UNION ALL

                    SELECT  return_date,saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total,return_product_doc_ref_doc_id,0::numeric(19) as voucher_value
                        FROM
                        (SELECT  return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.return_product_doc_gbh_customer_id as gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or, return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                            ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name, return_product_item_amount::numeric(19,2) as returnqty,return_product_item_sale_price::Numeric(19,2) as returnprice,return_product_doc_ref_doc_id
                            FROM (SELECT return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id , CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                    ELSE return_product_item_barcode_code end as return_product_item_barcode_codes, *
                                FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                                WHERE return_product_doc_ref_docno in ($invoice_no)
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                AND sc.sale_cash_document_datenow::date = now()::date
                                    )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                            WHERE category_id in ($luckydraw_categories)
                            AND pro.diposit_type_id in ($diposit_type_id)
                            AND good_brand_id in ($luckydraw_brands)
                            AND tt.return_product_item_barcode_codes not like '400502%'

                        )return
                    )returntable
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in  ($invoice_no)
                    group by return_product_doc_ref_docno,balance_value)mx on saleinvoice_no=mx.sale AND return_date=mx.date
                    WHERE noreturnamnt::Numeric(19,0) <> '0'
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode,voucher_value,noreturnamnt,return_product_doc_ref_doc_id,saleamount

                    UNION ALL------------------------sale

                    SELECT bb.sale_cash_document_datenow::date as date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
                        ,(sum(sale_amount))::numeric(19,2) as saleamount
                        , voucher_value::numeric(19,2) as Coupon

                        , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                        FROM
                        (
                            SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                        else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                            ,(sale_amount)::numeric(19,2)

                            FROM sale_cash.sale_cash_document aa
                            INNER JOIN sale_cash.sale_cash_items bb
                            on aa.sale_cash_document_id= bb.sale_cash_document_id
                            where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                            and aa.sale_cash_document_no in ($invoice_no)
                            and sale_cash_document_datenow::date >= '$promotion->start_date'
                            and sale_cash_document_datenow::date <= '$promotion->end_date'
                            AND sale_cash_document_datenow::date = now()::date
                        )bb
                        INNER JOIN
                        (
                            SELECT * FROM   master_product_luckydraw
                        )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                        Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                        Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where  sale_cash_document_no1 in ($invoice_no)
                        and category_id in ($luckydraw_categories)
                        and good_brand_id in ($luckydraw_brands)
                        and pro.diposit_type_id in ($diposit_type_id)
                        group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code
                )main
                ");
            } else {
                //Check Invoice No for Normal Invoice
                // $invoiceNormalData = $db_ext->select("Select sale_cash_document_datenow, sum(saleamount) as saleamount, sum(net_amount) net_amount
                // from
                // (
                //     select bb.sale_cash_document_datenow::date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id
                //            , (sum(sale_amount))::numeric(19,2) as saleamount
                //            , voucher_value::numeric(19,2) as Coupon
                //            , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                //     from
                //     (
                //         Select aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,
                //         Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code))
                //          else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                //         ,(sale_amount)::numeric(19,2)
                //         from sale_cash.sale_cash_document aa
                //         inner join sale_cash.sale_cash_items bb on aa.sale_cash_document_id= bb.sale_cash_document_id
                //         inner join sale_cash.sale_cash_document_status cc on aa. sale_cash_document_status_id = cc. sale_cash_document_status_id
                //         inner join sale_cash.sale_cash_document_type dd	on aa.sale_cash_document_type_id= dd.sale_cash_document_type_id
                //         where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                //         and (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                //         and aa.sale_cash_document_no in ($invoice_no)
                //         --and (sale_cash_document_datenow::date >= '2022-01-01'	and sale_cash_document_datenow::date = now()::date)
                //     )bb
                //     Inner join
                //     (
                //         Select *
                //         from
                //         (
                //         Select barcode_code, barcode_bill_name,
                //             Case when good_brand_id is null then 0 else good_brand_id end as good_brand_id,
                //             Case when good_brand_name='' or good_brand_name is null then 'No Brand'
                //                  else good_brand_name end as good_brand_name, category_id, diposit_type_id , remark
                //         from public.temp_master_product pro
                //         Left Join public.master_goods_category as cate	on pro.category_id=cate.product_category_id

                //         Union All

                //         Select product_code as barcode_code, product_name1 as barcode_bill_name, 0 as good_brand_id, 'No Brand' as good_brand_name, cc.product_category_id  as category_id
                //         , Case when product_category_code in ('01-CB', '02-ST', '03-RT') then 2 else 3 end as diposit_type_id ,remark
                //         from public.cancelled_code cc
                //         Left Join public.master_goods_category as cate	on cc.product_category_id=cate.product_category_id
                //         )tab
                //     )as pro on bb.barcode_codes=pro.barcode_code
                //     Left join (Select distinct branch_code, branch_name from public.temp_master_product) as br on br.branch_code=bb.branch_code
                //     Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                //     where sale_cash_document_no1 in ($invoice_no)
                //     --and category_id in ($luckydraw_categories)
                //     --and good_brand_id in ($luckydraw_brands)
                //     --and pro.diposit_type_id in ($diposit_type_id)
                //     --and barcode_codes in ('')
                //     group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id
                // )t
                // group by sale_cash_document_datenow");


                // // Check Return Invoice
                // $invoiceReturnData = $db_ext->select("Select return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id
                // , return_product_doc_branchcode, return_product_doc_branchname,noreturnamnt
                // from
                // (
                //     select return_date, saleinvoice_no, gbh_customer_id
                //     , return_product_doc_branchcode, return_product_doc_branchname
                //     , sum(total) as noreturnamnt,return_product_doc_ref_doc_id
                //     from
                //     (
                //         Select  return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name
                //         , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, Cate_type,total,return_product_doc_ref_doc_id
                //         from
                //         (
                //             Select
                //             distinct(return_product_doc_ref_docno)  as saleinvoice_no
                //             ,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id
                //             , return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                //             , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name as Cate_type

                //             ,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id
                //             from
                //             (
                //                 select return_product_doc_datenow::date as return_date,
                //                 Case when return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                //                 else return_product_item_barcode_code end as return_product_item_barcode_codes, *
                //                 from return_product.return_product_doc bb
                //                 inner join return_product.return_product_item cc
                //                 on bb.return_product_doc_id= cc.return_product_doc_id
                //                 where return_product_doc_ref_docno in ($invoice_no)
                //                 and return_product_item_barcode_code not in
                //                 (
                //                     select return_product_item_barcode_code
                //                     from return_product.return_product_doc bb
                //                     inner join return_product.return_product_item cc
                //                     on bb.return_product_doc_id= cc.return_product_doc_id
                //                     where return_product_doc_ref_docno in ($invoice_no)
                //                     and cc.return_product_item_amount::numeric(19,2) <> '0'
                //                     and return_product_item_active = 't'
                //                 )and cc.return_product_item_amount::numeric(19,2) ='0'
                //                 and return_product_item_active <> 't'
                //             )tt
                //             Left Outer Join  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes and return_product_doc_branchcode=pro.branch_code
                //             Left Outer Join public.master_goods_category as cate			on pro.category_id=cate.product_category_id
                //             Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                //             where
                //             pro.diposit_type_id in ($diposit_type_id)
                //             --and tt.return_product_item_barcode_codes not like '400502%'
                //             group by return_product_doc_ref_docno,return_product_docno

                //             ,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id
                //             , return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                //             , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name
                //             ,return_product_doc_ref_doc_id
                //         )noreturn
                //         Union All
                //         Select return_date, saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name
                //         , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total
                //         ,return_product_doc_ref_doc_id
                //         from
                //         (
                //             Select  return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or
                //             , return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff
                //             , return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                //             , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name
                //             , return_product_item_amount::numeric(19,2) as returnqty
                //             ,return_product_item_sale_price::Numeric(19,2) as returnprice
                //             ,return_product_doc_ref_doc_id
                //             from
                //             (
                //                 select return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id, Case when return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                //                     else return_product_item_barcode_code end as return_product_item_barcode_codes, *
                //                 from return_product.return_product_doc bb
                //                 inner join return_product.return_product_item cc
                //                 on bb.return_product_doc_id= cc.return_product_doc_id
                //                 where return_product_doc_ref_docno in ($invoice_no)
                //                 and cc.return_product_item_amount::numeric(19,2) <> '0'
                //                 and return_product_item_active = 't'
                //             )tt
                //             Left Outer Join  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes and return_product_doc_branchcode=pro.branch_code
                //             Left Outer Join public.master_goods_category as cate			on pro.category_id=cate.product_category_id
                //             Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                //             where pro.diposit_type_id in ($diposit_type_id)
                //             --and tt.return_product_item_barcode_codes not like '400502%'
                //         )return
                //     )returntable
                //     group by return_date, gbh_customer_id, saleinvoice_no
                //     , return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id
                // )return
                // where noreturnamnt::Numeric(19,0) <> '0'
                // ");

                  // Check Return Invoice
                $invoiceData = $db_ext->select("
                    SELECT sum(net_amount) as total_net_amount from (SELECT ------return
                    return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id, return_product_doc_branchcode,saleamount, voucher_value::numeric(19,2) as Coupon, CASE WHEN noreturnamnt<'0' then '0' ELSE noreturnamnt end as net_amount
                        FROM (
                            SELECT return_date, saleinvoice_no, gbh_customer_id, return_product_doc_branchcode, return_product_doc_branchname, sum(total) as noreturnamnt,return_product_doc_ref_doc_id,sum(voucher_value) as voucher_value
                                FROM
                                (SELECT  return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, Cate_type,total,return_product_doc_ref_doc_id,voucher_value
                        FROM
                        (SELECT distinct(return_product_doc_ref_docno)  as saleinvoice_no,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id,voucher_value::numeric(19,2)
                        FROM
                            (SELECT return_product_doc_datenow::date as return_date, CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                            ELSE return_product_item_barcode_code end as return_product_item_barcode_codes, *
                            FROM return_product.return_product_doc bb
                                LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                                INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                WHERE return_product_doc_ref_docno in ($invoice_no)
                                AND return_product_item_barcode_code not in
                                    ( SELECT return_product_item_barcode_code
                                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                            WHERE return_product_doc_ref_docno in ($invoice_no)
                                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                            --and sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                            --and sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                        )AND cc.return_product_item_amount::numeric(19,2) ='0'
                                            AND return_product_item_active <> 't'
                                    )tt
                                    LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                                    LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                                    LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                                    where category_id in ($luckydraw_categories)
                                    AND pro.diposit_type_id in ($diposit_type_id)
                                    AND good_brand_id in ($luckydraw_brands)
                                    AND tt.return_product_item_barcode_codes not like '400502%'

                                    group by return_product_doc_ref_docno,return_product_docno,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                                    , return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name,return_product_doc_ref_doc_id,voucher_value
                        )noreturn

                    UNION ALL

                    SELECT  return_date,saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total,return_product_doc_ref_doc_id,0::numeric(19) as voucher_value
                        FROM
                        (SELECT  return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.return_product_doc_gbh_customer_id as gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or, return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                            ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name, return_product_item_amount::numeric(19,2) as returnqty,return_product_item_sale_price::Numeric(19,2) as returnprice,return_product_doc_ref_doc_id
                            FROM (SELECT return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id , CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                    ELSE return_product_item_barcode_code end as return_product_item_barcode_codes, *
                                FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                                WHERE return_product_doc_ref_docno in ($invoice_no)
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                    )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                            WHERE category_id in ($luckydraw_categories)
                            AND pro.diposit_type_id in ($diposit_type_id)
                            AND good_brand_id in ($luckydraw_brands)
                            AND tt.return_product_item_barcode_codes not like '400502%'

                        )return
                    )returntable
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in  ($invoice_no)
                    group by return_product_doc_ref_docno,balance_value)mx on saleinvoice_no=mx.sale AND return_date=mx.date
                    WHERE noreturnamnt::Numeric(19,0) <> '0'
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode,voucher_value,noreturnamnt,return_product_doc_ref_doc_id,saleamount

                    UNION ALL------------------------sale

                    SELECT bb.sale_cash_document_datenow::date as date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
                        ,(sum(sale_amount))::numeric(19,2) as saleamount
                        , voucher_value::numeric(19,2) as Coupon

                        , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                        FROM
                        (
                            SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                        else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                            ,(sale_amount)::numeric(19,2)

                            FROM sale_cash.sale_cash_document aa
                            INNER JOIN sale_cash.sale_cash_items bb
                            on aa.sale_cash_document_id= bb.sale_cash_document_id
                            where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                            and aa.sale_cash_document_no in ($invoice_no)
                            and sale_cash_document_datenow::date >= '$promotion->start_date'
                            and sale_cash_document_datenow::date <= '$promotion->end_date'
                        )bb
                        INNER JOIN
                        (
                            SELECT * FROM   master_product_luckydraw
                        )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                        Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                        Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where  sale_cash_document_no1 in ($invoice_no)
                        and category_id in ($luckydraw_categories)
                        and good_brand_id in ($luckydraw_brands)
                        and pro.diposit_type_id in ($diposit_type_id)
                        group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code
                )main
                ");

            }
            // if (count($invoiceNormalData) > 0) {
                //     $totalprice += (int) $invoiceNormalData[0]->net_amount;
                // }
                // if (count($invoiceReturnData) > 0) {
                    //     $totalprice += (int) $invoiceReturnData[0]->noreturnamnt;
                    // }

            if ($invoiceData[0]->total_net_amount != null) {
                $totalprice = (int) $invoiceData[0]->total_net_amount;
            }else{
                $totalprice = null;
            }
            // $invoiceData = true;
        } else {
            $product_checks = ProductCheck::select('check_product_code')->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->get()->toArray();
            foreach ($product_checks as $product_check) {
                $p_checks[] = $product_check['check_product_code'];
            }
            $p_checks = (string) implode("','", $p_checks);
            $p_checks = "'" . $p_checks . "'";

            //Check By Product
            dd('checkbyprodcut');

        }
        if($totalprice){
            $total_valid_ticket_qty = $totalprice / $one_qty_amount;
            $total_valid_ticket_qty = $total_valid_ticket_qty < 0 ? 0 : (int) $total_valid_ticket_qty;
        }else{
            $total_valid_ticket_qty = 0;
        }
        // if ($invoiceData) {
        // } else {
        //     return response()->json(['error' => 'invoice_is_not_found'], 200);
        // }
        return $total_valid_ticket_qty;
    }

    public function checkInvoiceTime($invoice_date_time, $promotion)
    {
        $lucky_draw_end_date = strtotime($promotion->end_date);
        $lucky_draw_start_date = strtotime($promotion->start_date);
        $today = strtotime(date('Y-m-d'));
        if ($invoice_date_time > $lucky_draw_end_date) {
            return response()->json(['error' => 'invoice_is_expired'], 200);
        };
        if ($lucky_draw_end_date < $today) {
            return response()->json(['error' => 'promotion_expired'], 200);
        };
        if ($lucky_draw_start_date > $today) {
            return response()->json(['error' => 'promotion_is_not_start'], 200);
        };
    }

    public function getPromotionProduct($promoiton_sub_promotion)
    {
        return ProductCheck::select('check_product_code', 'check_product_qty')->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->get()->toArray();
    }

    public function getCheckAmountQty($promoiton_sub_promotion, $valid_total_price)
    {
        $amount_check = AmountCheck::where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->first();
        if ($amount_check) {
            return intval($valid_total_price / $amount_check->amount);
        }
        return 0;
    }

}
