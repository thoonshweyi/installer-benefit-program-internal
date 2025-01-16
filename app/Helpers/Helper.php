<?php

// use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Ticket;
use App\Models\NRCName;
use App\Jobs\SyncRowJob;
use App\Models\Category;
use App\Models\Customer;
use App\Models\PointPay;
use App\Models\AllCategory;
use App\Models\PreusedSlip;
use Illuminate\Support\Str;
use App\Models\ClaimHistory;
use App\Models\ProductCheck;
use App\Models\TicketHeader;
use App\Models\InstallerCard;
use App\Models\LuckyDrawBrand;
use App\Models\LuckyDrawBranch;
use App\Models\DoubleProfitSlip;
use App\Models\PointsRedemption;
use App\Models\LuckyDrawCategory;
use App\Models\InstallerCardPoint;
use App\Models\PromotionChangeLog;
use Illuminate\Support\Facades\DB;
use App\Models\TicketHeaderInvoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Models\RedemptionTransaction;
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
use Illuminate\Support\Facades\Notification;
use App\Notifications\RedemptionTransactionApprovalNoti;


function getTodayTicket($promotion_uuid, $branch_id)
{
    $tickets = Ticket::where(['promotion_uuid' => $promotion_uuid, 'created_at' => Carbon::today()])->get()->count();
    return $tickets;
}

function getTotalTicket($promotion_uuid, $branch_id)
{
    $tickets = Ticket::where(['promotion_uuid' => $promotion_uuid])->get()->count();
    return $tickets;
}

function number_convert($string)
{
    $mm = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];
    $lang = config('app.locale');
    $num = range(0, 9);
    switch ($lang) {
        case 'mm':
            return str_replace($num, $mm, $string);
            break;

        case 'en':
            return str_replace($mm, $num, $string);
            break;

        default:
            return $string;
            break;
    }
}

function create_promotion_log($old_info, $new_info, $reason, $promotion_uuid)
{
    $old_info = 'null';
    $new_info = 'null';
    $reason   = 'Default Reason';
    $promotion_change_log['uuid'] = (string) Str::uuid();
    $promotion_change_log['date'] = date('Y-m-d H:i:s');
    $promotion_change_log['user_uuid'] = auth()->user()->uuid;
    $promotion_change_log['old_info'] = $old_info;
    $promotion_change_log['new_info'] = $new_info;
    $promotion_change_log['reason'] = $reason;
    $promotion_change_log['promotion_uuid'] = $promotion_uuid;
    PromotionChangeLog::create($promotion_change_log);
}

function associated_array_diff(array $old_info, array $new_info, String $promotion_uuid)
{
    foreach ($old_info as $key => $value) {
        if (array_key_exists($key, $new_info)) {
            if ($old_info[$key] != $new_info[$key]) {
                create_promotion_log($old_info[$key], $new_info[$key], $key . ' is changed', $promotion_uuid);
            }
        }
    }
}

function branch_array_diff(array $old_info, array $new_info, String $promotion_uuid)
{
    foreach ($new_info as $n_info) {
        $n_info = (int) $n_info;
        $found = array_search($n_info, $old_info);

        if ($found === false) {
            $branch_name = Branch::where('branch_id', $n_info)->first()->branch_name_eng;
            create_promotion_log('', $n_info, $branch_name . ' Branch is added', $promotion_uuid);
        }
    }
    foreach ($old_info as $o_info) {
        $o_info = (string) $o_info;
        $found = array_search($o_info, $new_info);

        if ($found === false) {
            $branch_name = Branch::where('branch_id', $n_info)->first()->name;
            create_promotion_log($o_info, '', $branch_name . ' Branch is removed', $promotion_uuid);
        }
    }
}

function category_array_diff(array $old_info, array $new_info, String $promotion_uuid)
{
    foreach ($new_info as $n_info) {
        $n_info = (int) $n_info;
        $found = array_search($n_info, $old_info);

        if ($found === false) {
            $category_name = Category::where('id', $n_info)->first()->name;
            create_promotion_log('', $n_info, $category_name . ' Category is added', $promotion_uuid);
        }
    }
    foreach ($old_info as $o_info) {
        $o_info = (string) $o_info;
        $found = array_search($o_info, $new_info);

        if ($found === false) {
            $category_name = Category::where('id', $n_info)->first()->name;
            create_promotion_log($o_info, '', $category_name . ' Category is removed', $promotion_uuid);
        }
    }
}

function brand_array_diff(array $old_info, array $new_info, String $promotion_uuid)
{
    foreach ($new_info as $n_info) {
        $n_info = (int) $n_info;
        $found = array_search($n_info, $old_info);

        if ($found === false) {
            $product_brand_name = Brand::where('product_brand_id', $n_info)->first()->product_brand_name;
            create_promotion_log('', $n_info, $product_brand_name . ' Brand is added', $promotion_uuid);
        }
    }
    foreach ($old_info as $o_info) {
        $o_info = (string) $o_info;
        $found = array_search($o_info, $new_info);

        if ($found === false) {
            $product_brand_name = Brand::where('product_brand_id', $n_info)->first()->product_brand_name;
            create_promotion_log($o_info, '', $product_brand_name . ' Brand is removed', $promotion_uuid);
        }
    }
}
function get_current_branch_id()
{
    $currentURL = URL::current();
    if (
        str_contains($currentURL, '192.168.3.242')
        || str_contains($currentURL, '192.168.2.23')
        || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221') || str_contains($currentURL, 'ltluckydraw')
    ) {
        return 1;
    }
    if (str_contains($currentURL, '192.168.21.242') || str_contains($currentURL, 'tpluckydraw')) {
        return 2;
    }
    if (str_contains($currentURL, '192.168.25.242') || str_contains($currentURL, 'tpwluckydraw')) {
        return 11;
    }
    if (
        str_contains($currentURL, '192.168.11.242') || str_contains($currentURL, 'ssluckydraw')
        // ||str_contains($currentURL, '192.168.2.23')
    ) {
        return 3;
    }
    if (
        str_contains($currentURL, '192.168.16.242')
        || str_contains($currentURL, '192.168.2.23') || str_contains($currentURL, 'edluckydraw')
    ) {
        return 9;
    }
    if (str_contains($currentURL, '192.168.36.242') || str_contains($currentURL, 'htyluckydraw')) {
        return 19;
    }
    if (str_contains($currentURL, '192.168.31.242') || str_contains($currentURL, 'mlmluckydraw')) {
        return 10;
    }
    if (str_contains($currentURL, '192.168.41.242') || str_contains($currentURL, 'atyluckydraw')) {
        return 21;
    }
    if (str_contains($currentURL, '192.168.46.242') || str_contains($currentURL, 'tmluckydraw')) {
        return 27;
    }
    if (str_contains($currentURL, '192.168.51.242') || str_contains($currentURL, 'sdgluckydraw')) {
        return 28;
    }
    if (str_contains($currentURL, '192.168.56.242') || str_contains($currentURL, 'dngluckydraw')) {
        return 30;
    }
    if (str_contains($currentURL, '192.168.61.242') || str_contains($currentURL, 'bagoluckydraw')) {
        return 23;
    }
    Log::debug($e->getMessage());
    return redirect()
        ->intended(route("home"))
        ->with('error', 'Branch is not found!');
}

function findBrandId($invoice_no)
{
    // dd(mb_substr($invoice_no, 0, 4) == 'LAN1');
    if (mb_substr($invoice_no, 0, 4) == 'LAN1') {

        $branch_id = 1;
    } else if (mb_substr($invoice_no, 0, 4) == 'MDY1') {
        $branch_id = 2;
    } else if (mb_substr($invoice_no, 0, 4) == 'SAT1') {
        $branch_id = 3;
    } else if (mb_substr($invoice_no, 0, 4) == 'EDG1') {
        $branch_id = 9;
    } else if (mb_substr($invoice_no, 0, 4) == 'MLM1') {
        $branch_id = 10;
    } else if (mb_substr($invoice_no, 0, 4) == 'MDY2') {
        $branch_id = 11;
    } else if (mb_substr($invoice_no, 0, 4) == 'HTY1') {
        $branch_id = 19;
    } else if (mb_substr($invoice_no, 0, 4) == 'ATY1') {
        $branch_id = 21;
    } else if (mb_substr($invoice_no, 0, 4) == 'BGO1') {
        $branch_id = 23;
    } else if (mb_substr($invoice_no, 0, 4) == 'PTMN') {
        $branch_id = 27;
    } else if (mb_substr($invoice_no, 0, 4) == 'SDG1') {
        $branch_id = 28;
    } else if (mb_substr($invoice_no, 0, 4) == 'SPT1') {
        $branch_id = 30;
    } else {
        $branch_id = null;
    }
    // dd($branch_id);
    return $branch_id;
}

function findPromotionData($promotion)
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

function findValidTotalAmountOfProductsFromInvoice($invoice_no, $promoiton_sub_promotion, $promotion_data, $check_gold_ring)
{
    // dd('here');
    //check used invoice
    $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
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
    // dd($invoice_no[0]);
    $branch_id = findBrandId($invoice_no[0]);

    if (!$luckydraw_brands) {
        $l_brands[] = 0;
    } else {
        foreach ($luckydraw_brands as $luckydraw_brand) {
            $l_brands[] = $luckydraw_brand;
        }
    }
    // $db_ext = getConnection($branch_id);
    $db_ext = DB::connection('pos_pgsql');
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

    $promotion = $promoiton_sub_promotion->promotion;

    if ($promotion->diposit_type_id == 2) {
        $diposit_type_id = '2';
    } else if ($promotion->diposit_type_id == 3) {
        $diposit_type_id = '3';
    } else {
        $diposit_type_id = '2,3';
    }
    // dd($invoice_no);
    $invoice_no = "('" . implode("','", $invoice_no) . "')";
    // dd($invoice_no);

    //Check By Amount or By Product
    if ($invoice_check_type == 1)
    {
        //  for Others not today,
        //  dd($diposit_type_id);
        //Check All brand
        if ($l_brands[0] == 0)
        {
            //Check Invoice No for Normal Invoice
            // dd('true');
            $invoiceData = $db_ext->select("
                --update on 26Dec
                Select
                gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount,status
                from
                (
                Select *
                from
                (
                SELECT
                gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount,status from
                (
                    SELECT ------return
                    status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id, return_product_doc_branchcode,saleamount, voucher_value::numeric(19,2) as Coupon, CASE WHEN noreturnamnt<'0' then '0' ELSE noreturnamnt end as net_amount
                        FROM (
                            SELECT status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_branchcode, return_product_doc_branchname, sum(total) as noreturnamnt,return_product_doc_ref_doc_id,sum(voucher_value) as voucher_value
                                FROM
                                (SELECT  status,return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, Cate_type,total,return_product_doc_ref_doc_id,voucher_value
                        FROM
                        (SELECT distinct(return_product_doc_ref_docno)  as saleinvoice_no,status,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id,voucher_value::numeric(19,2)
                    FROM
                        (SELECT return_product_doc_datenow::date as return_date, CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                        ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                        FROM return_product.return_product_doc bb
                            LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                            LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                            INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                            WHERE return_product_doc_ref_docno in $invoice_no
                            AND return_product_item_barcode_code not in
                                ( SELECT return_product_item_barcode_code
                                        FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                        WHERE return_product_doc_ref_docno in $invoice_no
                                        AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                        AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                        AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                    )AND cc.return_product_item_amount::numeric(19,2) ='0'
                                        AND return_product_item_active <> 't'
                                )tt
                                LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                                LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                                LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                               where category_id in ($luckydraw_categories)
                               AND pro.diposit_type_id in ($diposit_type_id)
                               AND tt.return_product_item_barcode_codes not like '400502%'

                                group by return_product_doc_ref_docno,return_product_docno,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                                , return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name,return_product_doc_ref_doc_id,voucher_value,status
                )noreturn

                UNION ALL

                SELECT  status,return_date,saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total,return_product_doc_ref_doc_id,0::numeric(19) as voucher_value
                        FROM
                        (SELECT  status,return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.return_product_doc_gbh_customer_id as gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or, return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                            ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name, return_product_item_amount::numeric(19,2) as returnqty,return_product_item_sale_price::Numeric(19,2) as returnprice,return_product_doc_ref_doc_id
                            FROM (SELECT return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id , CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                    ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                                FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                                LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                                WHERE return_product_doc_ref_docno in $invoice_no
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                    )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                            WHERE category_id in ($luckydraw_categories)
                            AND pro.diposit_type_id in ($diposit_type_id)
                            AND tt.return_product_item_barcode_codes not like '400502%'

                        )return
                )returntable
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id,status)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in  $invoice_no
                    group by return_product_doc_ref_docno,balance_value)mx on saleinvoice_no=mx.sale AND return_date=mx.date
                    WHERE noreturnamnt::Numeric(19,0) <> '0'
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode,voucher_value,noreturnamnt,return_product_doc_ref_doc_id,saleamount,status

                UNION ALL------------------------sale

                SELECT status,bb.sale_cash_document_datenow::date as date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
                    ,(sum(sale_amount))::numeric(19,2) as saleamount
                    , voucher_value::numeric(19,2) as Coupon

                    , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                    FROM
                    (
                        SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                    else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                        ,(sale_amount)::numeric(19,2),cc.sale_cash_document_status_name as status

                        FROM sale_cash.sale_cash_document aa
                        LEFT JOIN sale_cash.sale_cash_items bb
                        on aa.sale_cash_document_id= bb.sale_cash_document_id
                        left join sale_cash.sale_cash_document_status cc
                        on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                        where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                        and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                        and aa.sale_cash_document_no in $invoice_no
                        and sale_cash_document_datenow::date >= '$promotion->start_date'
                        and sale_cash_document_datenow::date <= '$promotion->end_date'
                    )bb
                    INNER JOIN
                    (
                        SELECT * FROM   master_product_luckydraw
                    )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                    Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                    Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                    where  sale_cash_document_no1 in $invoice_no
                    and category_id in ($luckydraw_categories)
                    and pro.diposit_type_id in ($diposit_type_id)
                    group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code,status
                    )main group by gbh_customer_id, sale_cash_document_id,status
                )tab

                Union all

                Select * from
                    (
                    Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                    FROM return_product.return_product_doc aa
                    left join sale_cash.sale_cash_document bb on aa.return_product_doc_ref_docno=bb.sale_cash_document_no
                    left join sale_cash.sale_cash_document_status cc on bb.sale_cash_document_status_id=cc.sale_cash_document_status_id
                    where aa.return_product_doc_ref_docno in $invoice_no
                    Union all
                    SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                    FROM sale_cash.sale_cash_document aa
                    LEFT JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    left join sale_cash.sale_cash_document_status cc
                    on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in $invoice_no
                    )tab1
                    )main
                    group by gbh_customer_id, sale_cash_document_id,status
                ");
        }
        else
        {
            //Check Invoice No for Normal Invoice
            $invoiceData = $db_ext->select("
            --update on 26Dec
            Select
            gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount,status
            from
            (
            Select *
            from
            (
            SELECT
            gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount,status from
            (
                SELECT ------return
                status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id, return_product_doc_branchcode,saleamount, voucher_value::numeric(19,2) as Coupon, CASE WHEN noreturnamnt<'0' then '0' ELSE noreturnamnt end as net_amount
                    FROM (
                        SELECT status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_branchcode, return_product_doc_branchname, sum(total) as noreturnamnt,return_product_doc_ref_doc_id,sum(voucher_value) as voucher_value
                            FROM
                            (SELECT  status,return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, Cate_type,total,return_product_doc_ref_doc_id,voucher_value
                    FROM
                    (SELECT distinct(return_product_doc_ref_docno)  as saleinvoice_no,status,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                    ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id,voucher_value::numeric(19,2)
                FROM
                    (SELECT return_product_doc_datenow::date as return_date, CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                    ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                    FROM return_product.return_product_doc bb
                        LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                        LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                        INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                        WHERE return_product_doc_ref_docno in $invoice_no
                        AND return_product_item_barcode_code not in
                            ( SELECT return_product_item_barcode_code
                                    FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                    WHERE return_product_doc_ref_docno in $invoice_no
                                    AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                    AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                    AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                )AND cc.return_product_item_amount::numeric(19,2) ='0'
                                    AND return_product_item_active <> 't'
                            )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                           where category_id in ($luckydraw_categories)
                           AND good_brand_id in ($luckydraw_brands)
                           AND pro.diposit_type_id in ($diposit_type_id)
                           AND tt.return_product_item_barcode_codes not like '400502%'

                            group by return_product_doc_ref_docno,return_product_docno,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                            , return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name,return_product_doc_ref_doc_id,voucher_value,status
            )noreturn

            UNION ALL

            SELECT  status,return_date,saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total,return_product_doc_ref_doc_id,0::numeric(19) as voucher_value
                    FROM
                    (SELECT  status,return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.return_product_doc_gbh_customer_id as gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or, return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name, return_product_item_amount::numeric(19,2) as returnqty,return_product_item_sale_price::Numeric(19,2) as returnprice,return_product_doc_ref_doc_id
                        FROM (SELECT return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id , CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                            LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                            LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                            WHERE return_product_doc_ref_docno in $invoice_no
                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                            AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                            AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                )tt
                        LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                        LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                        LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        WHERE category_id in ($luckydraw_categories)
                        AND good_brand_id in ($luckydraw_brands)
                        AND pro.diposit_type_id in ($diposit_type_id)
                        AND tt.return_product_item_barcode_codes not like '400502%'

                    )return
            )returntable
                group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id,status)return
                right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                WHERE return_product_doc_ref_docno in  $invoice_no
                group by return_product_doc_ref_docno,balance_value)mx on saleinvoice_no=mx.sale AND return_date=mx.date
                WHERE noreturnamnt::Numeric(19,0) <> '0'
                group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode,voucher_value,noreturnamnt,return_product_doc_ref_doc_id,saleamount,status

            UNION ALL------------------------sale

            SELECT status,bb.sale_cash_document_datenow::date as date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
                ,(sum(sale_amount))::numeric(19,2) as saleamount
                , voucher_value::numeric(19,2) as Coupon

                , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                FROM
                (
                    SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                    ,(sale_amount)::numeric(19,2),cc.sale_cash_document_status_name as status

                    FROM sale_cash.sale_cash_document aa
                    LEFT JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    left join sale_cash.sale_cash_document_status cc
                    on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in $invoice_no
                    and sale_cash_document_datenow::date >= '$promotion->start_date'
                    and sale_cash_document_datenow::date <= '$promotion->end_date'
                )bb
                INNER JOIN
                (
                    SELECT * FROM   master_product_luckydraw
                )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                where  sale_cash_document_no1 in $invoice_no
                and category_id in ($luckydraw_categories)
                AND good_brand_id in ($luckydraw_brands)
                and pro.diposit_type_id in ($diposit_type_id)
                group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code,status
                )main group by gbh_customer_id, sale_cash_document_id,status
            )tab

            Union all

            Select * from
                (
                Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                FROM return_product.return_product_doc aa
                left join sale_cash.sale_cash_document bb on aa.return_product_doc_ref_docno=bb.sale_cash_document_no
                left join sale_cash.sale_cash_document_status cc on bb.sale_cash_document_status_id=cc.sale_cash_document_status_id
                where aa.return_product_doc_ref_docno in $invoice_no
                Union all
                SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                FROM sale_cash.sale_cash_document aa
                LEFT JOIN sale_cash.sale_cash_items bb
                on aa.sale_cash_document_id= bb.sale_cash_document_id
                left join sale_cash.sale_cash_document_status cc
                on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                and aa.sale_cash_document_no in $invoice_no
                )tab1
                )main
                group by gbh_customer_id, sale_cash_document_id,status


                ");
        }
        // }
        $new_total_price = 0;
        foreach ($invoiceData as $iData) {
            $new_total_price += $iData->total_net_amount;
        }
        if ($invoiceData) {
            $totalprice         = (int) $new_total_price;
            $gbh_customer_id    = $invoiceData[0]->gbh_customer_id;
            $invoice_id         = $invoiceData[0]->sale_cash_document_id;
            $status             = $invoiceData[0]->status;
        } else {
            $totalprice = null;
            $gbh_customer_id = null;
            $invoice_id = null;
            $status     = null;
        }
    } else {
        $product_checks = ProductCheck::select('check_product_code')->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->get()->toArray();
        foreach ($product_checks as $product_check) {
            $p_checks[] = $product_check['check_product_code'];
        }
        $p_checks = (string) implode("','", $p_checks);
        $p_checks = "'" . $p_checks . "'";

        //Check By Product
        // dd('checkbyprodcut');

    }
    return $data[] = [
        'totalprice' => $new_total_price,
        'gbh_customer_id' => $gbh_customer_id,
        'invoice_id' => $invoice_id,
    ];
}

function findValidTotalAmountOfProductsFromInvoice_old($invoice_no, $promoiton_sub_promotion, $promotion_data, $check_gold_ring)
{

    //check used invoice

    $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
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


    $branch_id = findBrandId($invoice_no);

    if (!$luckydraw_brands) {
        $l_brands[] = 0;
    } else {
        foreach ($luckydraw_brands as $luckydraw_brand) {
            $l_brands[] = $luckydraw_brand;
        }
    }
    // $db_ext = getConnection($branch_id);
    $db_ext = DB::connection('pos_pgsql');
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
    // dd($luckydraw_categories);
    $promotion = $promoiton_sub_promotion->promotion;

    if ($promotion->diposit_type_id == 2) {
        $diposit_type_id = '2';
    } else if ($promotion->diposit_type_id == 3) {
        $diposit_type_id = '3';
    } else {
        $diposit_type_id = '2,3';
    }



    //Check By Amount or By Product
    if ($invoice_check_type == 1)
    {

        //Check All brand
        if ($l_brands[0] == 0)
        {
            //Check Invoice No for Normal Invoice

            $invoiceData = $db_ext->select("
                --update on 26Dec
                Select
                gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount,status
                from
                (
                Select *
                from
                (
                SELECT
                gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount,status from
                (
                    SELECT ------return
                    status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id, return_product_doc_branchcode,saleamount, voucher_value::numeric(19,2) as Coupon, CASE WHEN noreturnamnt<'0' then '0' ELSE noreturnamnt end as net_amount
                        FROM (
                            SELECT status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_branchcode, return_product_doc_branchname, sum(total) as noreturnamnt,return_product_doc_ref_doc_id,sum(voucher_value) as voucher_value
                                FROM
                                (SELECT  status,return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, Cate_type,total,return_product_doc_ref_doc_id,voucher_value
                        FROM
                        (SELECT distinct(return_product_doc_ref_docno)  as saleinvoice_no,status,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id,voucher_value::numeric(19,2)
                    FROM
                        (SELECT return_product_doc_datenow::date as return_date, CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                        ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                        FROM return_product.return_product_doc bb
                            LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                            LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                            INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                            WHERE return_product_doc_ref_docno in ('$invoice_no')
                            AND return_product_item_barcode_code not in
                                ( SELECT return_product_item_barcode_code
                                        FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                        WHERE return_product_doc_ref_docno in ('$invoice_no')
                                        AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                        AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                        AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                    )AND cc.return_product_item_amount::numeric(19,2) ='0'
                                        AND return_product_item_active <> 't'
                                )tt
                                LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                                LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                                LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                               where category_id in ($luckydraw_categories)
                               AND pro.diposit_type_id in ($diposit_type_id)
                               AND tt.return_product_item_barcode_codes not like '400502%'

                                group by return_product_doc_ref_docno,return_product_docno,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                                , return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name,return_product_doc_ref_doc_id,voucher_value,status
                )noreturn

                UNION ALL

                SELECT  status,return_date,saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total,return_product_doc_ref_doc_id,0::numeric(19) as voucher_value
                        FROM
                        (SELECT  status,return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.return_product_doc_gbh_customer_id as gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or, return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                            ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name, return_product_item_amount::numeric(19,2) as returnqty,return_product_item_sale_price::Numeric(19,2) as returnprice,return_product_doc_ref_doc_id
                            FROM (SELECT return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id , CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                    ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                                FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                                LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                    )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                            WHERE category_id in ($luckydraw_categories)
                            AND pro.diposit_type_id in ($diposit_type_id)
                            AND tt.return_product_item_barcode_codes not like '400502%'

                        )return
                )returntable
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id,status)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in ('$invoice_no')
                    group by return_product_doc_ref_docno,balance_value)mx on saleinvoice_no=mx.sale AND return_date=mx.date
                    WHERE noreturnamnt::Numeric(19,0) <> '0'
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode,voucher_value,noreturnamnt,return_product_doc_ref_doc_id,saleamount,status

                UNION ALL------------------------sale

                SELECT status,bb.sale_cash_document_datenow::date as date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
                    ,(sum(sale_amount))::numeric(19,2) as saleamount
                    , voucher_value::numeric(19,2) as Coupon

                    , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                    FROM
                    (
                        SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                    else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                        ,(sale_amount)::numeric(19,2),cc.sale_cash_document_status_name as status

                        FROM sale_cash.sale_cash_document aa
                        LEFT JOIN sale_cash.sale_cash_items bb
                        on aa.sale_cash_document_id= bb.sale_cash_document_id
                        left join sale_cash.sale_cash_document_status cc
                        on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                        where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                        and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                        and aa.sale_cash_document_no in ('$invoice_no')
                        and sale_cash_document_datenow::date >= '$promotion->start_date'
                        and sale_cash_document_datenow::date <= '$promotion->end_date'
                    )bb
                    INNER JOIN
                    (
                        SELECT * FROM   master_product_luckydraw
                    )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                    Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                    Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                    where  sale_cash_document_no1 in ('$invoice_no')
                    and category_id in ($luckydraw_categories)
                    and pro.diposit_type_id in ($diposit_type_id)
                    group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code,status
                    )main group by gbh_customer_id, sale_cash_document_id,status
                )tab

                Union all

                Select * from
                    (
                    Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                    FROM return_product.return_product_doc aa
                    left join sale_cash.sale_cash_document bb on aa.return_product_doc_ref_docno=bb.sale_cash_document_no
                    left join sale_cash.sale_cash_document_status cc on bb.sale_cash_document_status_id=cc.sale_cash_document_status_id
                    where aa.return_product_doc_ref_docno in ('$invoice_no')
                    Union all
                    SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                    FROM sale_cash.sale_cash_document aa
                    LEFT JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    left join sale_cash.sale_cash_document_status cc
                    on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in ('$invoice_no')
                    )tab1
                    )main
                    group by gbh_customer_id, sale_cash_document_id,status
                ");
                dd($invoiceData);
        }
        else
        {
            //Check Invoice No for Normal Invoice

            $invoiceData = $db_ext->select("
            --update on 26Dec
            Select
            gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount,status
            from
            (
            Select *
            from
            (
            SELECT
            gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount,status from
            (
                SELECT ------return
                status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id, return_product_doc_branchcode,saleamount, voucher_value::numeric(19,2) as Coupon, CASE WHEN noreturnamnt<'0' then '0' ELSE noreturnamnt end as net_amount
                    FROM (
                        SELECT status,return_date, saleinvoice_no, gbh_customer_id, return_product_doc_branchcode, return_product_doc_branchname, sum(total) as noreturnamnt,return_product_doc_ref_doc_id,sum(voucher_value) as voucher_value
                            FROM
                            (SELECT  status,return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, Cate_type,total,return_product_doc_ref_doc_id,voucher_value
                    FROM
                    (SELECT distinct(return_product_doc_ref_docno)  as saleinvoice_no,status,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                    ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id,voucher_value::numeric(19,2)
                FROM
                    (SELECT return_product_doc_datenow::date as return_date, CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                    ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                    FROM return_product.return_product_doc bb
                        LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                        LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                        INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                        WHERE return_product_doc_ref_docno in ('$invoice_no')
                        AND return_product_item_barcode_code not in
                            ( SELECT return_product_item_barcode_code
                                    FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                    WHERE return_product_doc_ref_docno in ('$invoice_no')
                                    AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                    AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                    AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                )AND cc.return_product_item_amount::numeric(19,2) ='0'
                                    AND return_product_item_active <> 't'
                            )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                           where category_id in ($luckydraw_categories)
                           AND good_brand_id in ($luckydraw_brands)
                           AND pro.diposit_type_id in ($diposit_type_id)
                           AND tt.return_product_item_barcode_codes not like '400502%'

                            group by return_product_doc_ref_docno,return_product_docno,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                            , return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name,return_product_doc_ref_doc_id,voucher_value,status
            )noreturn

            UNION ALL

            SELECT  status,return_date,saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total,return_product_doc_ref_doc_id,0::numeric(19) as voucher_value
                    FROM
                    (SELECT  status,return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.return_product_doc_gbh_customer_id as gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or, return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name, return_product_item_amount::numeric(19,2) as returnqty,return_product_item_sale_price::Numeric(19,2) as returnprice,return_product_doc_ref_doc_id
                        FROM (SELECT return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id , CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                ELSE return_product_item_barcode_code end as return_product_item_barcode_codes,ak.sale_cash_document_status_name as status, *
                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                            LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                            LEFT JOIN sale_cash.sale_cash_document_status ak on sc.sale_cash_document_status_id=ak.sale_cash_document_status_id
                            WHERE return_product_doc_ref_docno in ('$invoice_no')
                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                            AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                            AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                )tt
                        LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                        LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                        LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        WHERE category_id in ($luckydraw_categories)
                        AND good_brand_id in ($luckydraw_brands)
                        AND pro.diposit_type_id in ($diposit_type_id)
                        AND tt.return_product_item_barcode_codes not like '400502%'

                    )return
            )returntable
                group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id,status)return
                right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                WHERE return_product_doc_ref_docno in ('$invoice_no')
                group by return_product_doc_ref_docno,balance_value)mx on saleinvoice_no=mx.sale AND return_date=mx.date
                WHERE noreturnamnt::Numeric(19,0) <> '0'
                group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode,voucher_value,noreturnamnt,return_product_doc_ref_doc_id,saleamount,status

            UNION ALL------------------------sale

            SELECT status,bb.sale_cash_document_datenow::date as date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
                ,(sum(sale_amount))::numeric(19,2) as saleamount
                , voucher_value::numeric(19,2) as Coupon

                , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                FROM
                (
                    SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                    ,(sale_amount)::numeric(19,2),cc.sale_cash_document_status_name as status

                    FROM sale_cash.sale_cash_document aa
                    LEFT JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    left join sale_cash.sale_cash_document_status cc
                    on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in ('$invoice_no')
                    and sale_cash_document_datenow::date >= '$promotion->start_date'
                    and sale_cash_document_datenow::date <= '$promotion->end_date'
                )bb
                INNER JOIN
                (
                    SELECT * FROM   master_product_luckydraw
                )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                where  sale_cash_document_no1 in ('$invoice_no')
                and category_id in ($luckydraw_categories)
                AND good_brand_id in ($luckydraw_brands)
                and pro.diposit_type_id in ($diposit_type_id)
                group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code,status
                )main group by gbh_customer_id, sale_cash_document_id,status
            )tab

            Union all

            Select * from
                (
                Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                FROM return_product.return_product_doc aa
                left join sale_cash.sale_cash_document bb on aa.return_product_doc_ref_docno=bb.sale_cash_document_no
                left join sale_cash.sale_cash_document_status cc on bb.sale_cash_document_status_id=cc.sale_cash_document_status_id
                where aa.return_product_doc_ref_docno in ('$invoice_no')
                Union all
                SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount,cc.sale_cash_document_status_name as status
                FROM sale_cash.sale_cash_document aa
                LEFT JOIN sale_cash.sale_cash_items bb
                on aa.sale_cash_document_id= bb.sale_cash_document_id
                left join sale_cash.sale_cash_document_status cc
                on aa.sale_cash_document_status_id=cc.sale_cash_document_status_id
                where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                and aa.sale_cash_document_no in ('$invoice_no')
                )tab1
                )main
                group by gbh_customer_id, sale_cash_document_id,status


                ");
        }

        // }
        // dd($invoiceData);
        if ($invoiceData[0]) {
            $totalprice         = (int) $invoiceData[0]->total_net_amount;
            $gbh_customer_id    = $invoiceData[0]->gbh_customer_id;
            $invoice_id         = $invoiceData[0]->sale_cash_document_id;
            // $status             = $invoiceData[0]->status;
        } else {
            $totalprice = 0;
            $gbh_customer_id = 0;
            $invoice_id = 0;
            $status     = 'none';
        }
    } else {
        $product_checks = ProductCheck::select('check_product_code')->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->get()->toArray();
        foreach ($product_checks as $product_check) {
            $p_checks[] = $product_check['check_product_code'];
        }
        $p_checks = (string) implode("','", $p_checks);
        $p_checks = "'" . $p_checks . "'";

        //Check By Product
        // dd('checkbyprodcut');

    }

    return $data[] = [
        'totalprice'        => $totalprice,
        'gbh_customer_id'   => $gbh_customer_id,
        'invoice_id'        => $invoice_id,
        'status'            => $status
    ];
}
function get_customer($gbh_customer_id, $branch_id)
{


    $customer = Pos101GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    // dd($customer);
    if ($customer->gbh_customer_id != '10547') {
        //check customer in ldcustomer
        $ldcustomer = Customer::where('phone_no', $customer->mobile)->first();
        if ($customer->identification_card != '') {
            $member_type = 'Member';
        } else {
            $member_type = 'Old';
        }


        if (!isset($ldcustomer)) {
            $ldcustomer         = Customer::create([
                'uuid'          => (string) Str::uuid(),
                'customer_id'   => $customer->gbh_customer_id,
                'titlename'     => $customer->titlename,
                'firstname'     => $customer->firstname,
                'lastname'      => $customer->lastname,
                'phone_no'      => $customer->mobile,
                'nrc_no'        => (int) $customer->nrc_no,
                'nrc_name'      => (int) $customer->nrc_name,
                'nrc_short'     => (int) $customer->nrc_short,
                'nrc_number'    => $customer->nrc_number,
                'passport'      => $customer->passport,
                'email'         => $customer->email,
                'phone_no_2'    => $customer->homephone,
                'national_id'   => $customer->nationlity_id,
                'member_no'     => $customer->identification_card,
                'amphur_id'     => $customer->amphur_id,
                'province_id'   => $customer->province_id,
                'address'       => $customer->full_address,
                'customer_no'   => $customer->customer_barcode,
                'customer_type' => $member_type
            ]);
        }
    } else {
        $ldcustomer = Customer::create([
            'uuid'              => (string) Str::uuid(),
            'customer_id'       => $customer->gbh_customer_id ?? '10547',
            'firstname'         => $customer->firstname,
            'phone_no'          => $customer->mobile,
            'customer_type'     => 'New',
            'customer_no'       => '99999',
        ]);
    }
    if($customer->nrc_array_id!=null)
        {
            $ldcustomer->update(['nrc'=>$customer->nrc_array_id]);
        }
    if($customer->foreigner ==true)
    {
        $ldcustomer->update(['foreigner'=>$customer->foreigner]);
    }
    // dd($ldcustomer,'hiii');
    return $ldcustomer;
}

function getConnection($branch_id)
{
    if ($branch_id == 1) {
        $db_ext = DB::connection('pos101_pgsql');
    }
    if ($branch_id == 2) {
        $db_ext = DB::connection('pos102_pgsql');
    }
    if ($branch_id == 3) {
        $db_ext = DB::connection('pos103_pgsql');
    }
    if ($branch_id == 9) {
        $db_ext = DB::connection('pos104_pgsql');
    }
    if ($branch_id == 10) {
        $db_ext = DB::connection('pos105_pgsql');
    }
    if ($branch_id == 11) {
        $db_ext = DB::connection('pos106_pgsql');
    }
    if ($branch_id == 19) {
        $db_ext = DB::connection('pos107_pgsql');
    }
    if ($branch_id == 21) {
        $db_ext = DB::connection('pos108_pgsql');
    }
    if ($branch_id == 27) {
        $db_ext = DB::connection('pos112_pgsql');
    }
    if ($branch_id == 28) {
        $db_ext = DB::connection('pos113_pgsql');
    }
    if ($branch_id == 30) {
        $db_ext = DB::connection('pos114_pgsql');
    }
    if ($branch_id == 23) {
        $db_ext = DB::connection('pos110_pgsql');
    }
    return $db_ext;
}

function findValidTotalAmountOfProductsFromInvoiceWithSingleQuery($invoice_no, $invoice_nos, $promoiton_sub_promotion, $promotion_data, $check_gold_ring)
{
    //check used invoice
    // dd('here');
    $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)
        ->whereHas('ticket_headers', function ($q) {
            $q->where('status', '=', 1);
        })->first();

    if (isset($ticket_header_invoice)) {
        return $total_amount['message'] = 'invoice_is_used';
    }
    $invoice_check_type = $promoiton_sub_promotion->invoice_check_type;

    $luckydraw_branches = $promotion_data['luckydraw_branches'];
    $luckydraw_categories = $promotion_data['luckydraw_categories'];
    $luckydraw_brands = $promotion_data['luckydraw_brands'];
    $branch_id = findBrandId($invoice_no);
    if (!$luckydraw_brands) {
        $l_brands[] = 0;
    } else {
        foreach ($luckydraw_brands as $luckydraw_brand) {
            $l_brands[] = $luckydraw_brand;
        }
    }
    // dd($luckydraw_brands,$l_brands);
    // $db_ext = getConnection($branch_id);
    $db_ext = DB::connection('pos_pgsql');

    $customerData = $db_ext->select("
        Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount
        FROM return_product.return_product_doc
        where return_product_doc_ref_docno in ('$invoice_no')
        Union all
        SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount
        FROM sale_cash.sale_cash_document aa
        LEFT JOIN sale_cash.sale_cash_items bb
        on aa.sale_cash_document_id= bb.sale_cash_document_id
        where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
        and  (bb.barcode_code not like 'GP%' or sale_price <='0')
        and aa.sale_cash_document_no in ('$invoice_no')
    ");

    if (!$customerData) {
        return $data[] = [
            'message' => 'invoice_is_not_found',
        ];
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

    $promotion = $promoiton_sub_promotion->promotion;

    if ($promotion->diposit_type_id == 2) {
        $diposit_type_id = '2';
    } else if ($promotion->diposit_type_id == 3) {
        $diposit_type_id = '3';
    } else {
        $diposit_type_id = '2,3';
    }

    // dd($invoice_check_type);
    $invoice_nos = implode("','", $invoice_nos);
    //Check By Amount or By Product
    if ($invoice_check_type == 1) {
        //  for Others not today,
        //Check All brand
        if ($l_brands[0] == 0) {
            //Check Invoice No for Normal Invoice
            $invoiceData = $db_ext->select("
                    select bb.sale_cash_document_datenow::date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id
                    ,(sum(sale_amount))::numeric(19,2) as saleamount
                    , voucher_value::numeric(19,2) as Coupon

                    , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                    from
                    (
                        Select aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                    else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                        ,(sale_amount)::numeric(19,2)

                        from sale_cash.sale_cash_document aa
                        inner join sale_cash.sale_cash_items bb 			on aa.sale_cash_document_id= bb.sale_cash_document_id
                        inner join sale_cash.sale_cash_document_status cc		on aa. sale_cash_document_status_id = cc. sale_cash_document_status_id
                        inner join sale_cash.sale_cash_document_type dd			on aa.sale_cash_document_type_id= dd.sale_cash_document_type_id

                    -- left join pledge.pledge_document_ref_log as deposalereftable	on  aa.sale_cash_document_no= deposalereftable.sale_cash_document_no
                        where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1') --,'5')  --Remove Return Status requested by Chan Myae
                            and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                        and aa.sale_cash_document_no in ('$invoice_nos')
                        and sale_cash_document_datenow::date >= '$promotion->start_date'
                        and sale_cash_document_datenow::date <= '$promotion->end_date'
                    )bb
                    Inner join
                    (
                        Select *
                        from
                        (
                            Select barcode_code, barcode_bill_name, Case when good_brand_id is null then 0 else good_brand_id end as good_brand_id, Case when good_brand_name='' or  good_brand_name is null then 'No Brand' else good_brand_name end as  good_brand_name
                            , category_id, diposit_type_id , remark
                            from public.temp_master_product pro
                            Left Join public.master_goods_category as cate	on pro.category_id=cate.product_category_id

                            Union All

                            Select product_code as barcode_code, product_name1 as barcode_bill_name, 0 as good_brand_id, 'No Brand' as good_brand_name, cc.product_category_id  as category_id
                            , Case when product_category_code in ('01-CB', '02-ST', '03-RT') then 2
                            else 3 end as diposit_type_id , remark
                            from public.cancelled_code cc
                            Left Join public.master_goods_category as cate	on cc.product_category_id=cate.product_category_id
                        )tab
                    )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                    Left join (Select distinct branch_code, branch_name from public.temp_master_product) as br on br.branch_code=bb.branch_code
                    Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                    where  sale_cash_document_no1 in ('$invoice_nos')
                    and category_id in ($luckydraw_categories)
                    and pro.diposit_type_id in ($diposit_type_id)
                    group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id
                ");
            // dd($invoiceData==null);
            if (!$invoiceData) {
                //check Return
                $invoiceData = $db_ext->select("
                    select bb.sale_cash_document_datenow::date,depositdate,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id
                        ,(sum(sale_amount))::numeric(19,2) as saleamount
                        , voucher_value::numeric(19,2) as Coupon
                        , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                        from
                        (
                            Select depositdate,aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                        else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                            ,(sale_amount)::numeric(19,2)

                            from sale_cash.sale_cash_document aa
                            inner join sale_cash.sale_cash_items bb 			on aa.sale_cash_document_id= bb.sale_cash_document_id
                            inner join sale_cash.sale_cash_document_status cc		on aa. sale_cash_document_status_id = cc. sale_cash_document_status_id
                            inner join sale_cash.sale_cash_document_type dd			on aa.sale_cash_document_type_id= dd.sale_cash_document_type_id

                        inner join (select distinct(sale_cash_document_no)
                                            ,min(pledge_document_datenow::date) as depositdate
                                    from pledge.pledge_document_ref_log
                                    group by sale_cash_document_no) as deposalereftable	on  aa.sale_cash_document_no= deposalereftable.sale_cash_document_no
                            where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                            and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                            and aa.sale_cash_document_no in ('$invoice_nos')
                        and depositdate::date between '$promotion->start_date' and '$promotion->end_date'
                        and sale_cash_document_datenow::date-14 >= '$promotion->start_date'
                        and sale_cash_document_datenow::date-14 <= '$promotion->end_date'
                        )bb
                        Inner join
                        (
                            Select *
                            from
                            (
                                Select barcode_code, barcode_bill_name, Case when good_brand_id is null then 0 else good_brand_id end as good_brand_id, Case when good_brand_name='' or  good_brand_name is null then 'No Brand' else good_brand_name end as  good_brand_name
                                , category_id, diposit_type_id , remark
                                from public.temp_master_product pro
                                Left Join public.master_goods_category as cate	on pro.category_id=cate.product_category_id

                                Union All

                                Select product_code as barcode_code, product_name1 as barcode_bill_name, 0 as good_brand_id, 'No Brand' as good_brand_name, cc.product_category_id  as category_id
                                , Case when product_category_code in ('01-CB', '02-ST', '03-RT') then 2
                                else 3 end as diposit_type_id , remark
                                from public.cancelled_code cc
                                Left Join public.master_goods_category as cate	on cc.product_category_id=cate.product_category_id
                            )tab
                        )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                        Left join (Select distinct branch_code, branch_name from public.temp_master_product) as br on br.branch_code=bb.branch_code
                        Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where  sale_cash_document_no1 in ('$invoice_nos')
                        and category_id in ($luckydraw_categories)
                        and pro.diposit_type_id in ($diposit_type_id)
                        group by depositdate,bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id
                    ");
            }
        } else {

            //Check Invoice No for Normal Invoice
            $invoiceData = $db_ext->select("
                    select bb.sale_cash_document_datenow::date,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id
                    ,(sum(sale_amount))::numeric(19,2) as saleamount
                    , voucher_value::numeric(19,2) as Coupon

                    , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                    from
                    (
                        Select aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                    else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                        ,(sale_amount)::numeric(19,2)

                        from sale_cash.sale_cash_document aa
                        inner join sale_cash.sale_cash_items bb 			on aa.sale_cash_document_id= bb.sale_cash_document_id
                        inner join sale_cash.sale_cash_document_status cc		on aa. sale_cash_document_status_id = cc. sale_cash_document_status_id
                        inner join sale_cash.sale_cash_document_type dd			on aa.sale_cash_document_type_id= dd.sale_cash_document_type_id

                    -- left join pledge.pledge_document_ref_log as deposalereftable	on  aa.sale_cash_document_no= deposalereftable.sale_cash_document_no
                        where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1') --,'5')  --Remove Return Status requested by Chan Myae
                            and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                        and aa.sale_cash_document_no in ('$invoice_nos')
                        and sale_cash_document_datenow::date >= '$promotion->start_date'
                        and sale_cash_document_datenow::date <= '$promotion->end_date'
                    )bb
                    Inner join
                    (
                        Select *
                        from
                        (
                            Select barcode_code, barcode_bill_name, Case when good_brand_id is null then 0 else good_brand_id end as good_brand_id, Case when good_brand_name='' or  good_brand_name is null then 'No Brand' else good_brand_name end as  good_brand_name
                            , category_id, diposit_type_id , remark
                            from public.temp_master_product pro
                            Left Join public.master_goods_category as cate	on pro.category_id=cate.product_category_id

                            Union All

                            Select product_code as barcode_code, product_name1 as barcode_bill_name, 0 as good_brand_id, 'No Brand' as good_brand_name, cc.product_category_id  as category_id
                            , Case when product_category_code in ('01-CB', '02-ST', '03-RT') then 2
                            else 3 end as diposit_type_id , remark
                            from public.cancelled_code cc
                            Left Join public.master_goods_category as cate	on cc.product_category_id=cate.product_category_id
                        )tab
                    )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                    Left join (Select distinct branch_code, branch_name from public.temp_master_product) as br on br.branch_code=bb.branch_code
                    Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                    where  sale_cash_document_no1 in ('$invoice_nos')
                    and category_id in ($luckydraw_categories)
                    and good_brand_id in ($luckydraw_brands)
                    and pro.diposit_type_id in ($diposit_type_id)
                    group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id
                ");
            if (!$invoiceData) {
                //check Return
                $invoiceData = $db_ext->select("
                    select bb.sale_cash_document_datenow::date,depositdate,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id
                        ,(sum(sale_amount))::numeric(19,2) as saleamount
                        , voucher_value::numeric(19,2) as Coupon
                        , (((sum(sale_amount))::numeric(19,2)-voucher_value::numeric(19,2)))::numeric as net_amount
                        from
                        (
                            Select depositdate,aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                                        else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                            ,(sale_amount)::numeric(19,2)

                            from sale_cash.sale_cash_document aa
                            LEFT join sale_cash.sale_cash_items bb 			on aa.sale_cash_document_id= bb.sale_cash_document_id
                            inner join sale_cash.sale_cash_document_status cc		on aa. sale_cash_document_status_id = cc. sale_cash_document_status_id
                            inner join sale_cash.sale_cash_document_type dd			on aa.sale_cash_document_type_id= dd.sale_cash_document_type_id

                        inner join (select distinct(sale_cash_document_no)
                                            ,min(pledge_document_datenow::date) as depositdate
                                    from pledge.pledge_document_ref_log
                                    group by sale_cash_document_no) as deposalereftable	on  aa.sale_cash_document_no= deposalereftable.sale_cash_document_no
                            where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                            and  (bb.barcode_code not like 'GP%' or sale_price <='0')
                            and aa.sale_cash_document_no in ('$invoice_nos')
                        and depositdate::date between '$promotion->start_date' and '$promotion->end_date'
                        and sale_cash_document_datenow::date-14 >= '$promotion->start_date'
                        and sale_cash_document_datenow::date-14 <= '$promotion->end_date'
                        )bb
                        Inner join
                        (
                            Select *
                            from
                            (
                                Select barcode_code, barcode_bill_name, Case when good_brand_id is null then 0 else good_brand_id end as good_brand_id, Case when good_brand_name='' or  good_brand_name is null then 'No Brand' else good_brand_name end as  good_brand_name
                                , category_id, diposit_type_id , remark
                                from public.temp_master_product pro
                                Left Join public.master_goods_category as cate	on pro.category_id=cate.product_category_id

                                Union All

                                Select product_code as barcode_code, product_name1 as barcode_bill_name, 0 as good_brand_id, 'No Brand' as good_brand_name, cc.product_category_id  as category_id
                                , Case when product_category_code in ('01-CB', '02-ST', '03-RT') then 2
                                else 3 end as diposit_type_id , remark
                                from public.cancelled_code cc
                                Left Join public.master_goods_category as cate	on cc.product_category_id=cate.product_category_id
                            )tab
                        )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                        Left join (Select distinct branch_code, branch_name from public.temp_master_product) as br on br.branch_code=bb.branch_code
                        Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where  sale_cash_document_no1 in ('$invoice_nos')
                        and category_id in ($luckydraw_categories)
                        and good_brand_id in ($luckydraw_brands)
                        and pro.diposit_type_id in ($diposit_type_id)
                        group by depositdate,bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id
                    ");
            }
        }
        // dd($invoiceData);
        if ($invoiceData) {
            $totalprice = 0;
            foreach ($invoiceData as $iData) {
                $totalprice += (int) $iData->net_amount;
            }
        } else {
            $totalprice = 0;
        }
    } else {
        $product_checks = ProductCheck::select('check_product_code')->where('promotion_uuid', $promoiton_sub_promotion->promotion_uuid)->where('sub_promotion_uuid', $promoiton_sub_promotion->sub_promotion_uuid)->get()->toArray();
        foreach ($product_checks as $product_check) {
            $p_checks[] = $product_check['check_product_code'];
        }
        $p_checks = (string) implode("','", $p_checks);
        $p_checks = "'" . $p_checks . "'";

        //Check By Product
        // dd('checkbyprodcut');

    }
    return $data[] = [
        'totalprice' => $totalprice,
        'gbh_customer_id' => $customerData[0]->gbh_cust_id,
        'invoice_id' => $customerData[0]->sale_cash_document_id,
        'message' => null,
    ];
}

function getTotalValidAmount($ticket_header_uuid)
{
    $total_amount = 0;
    $total_invoices         = TicketHeaderInvoice::where('ticket_header_uuid',$ticket_header_uuid)->get();
    foreach($total_invoices as $invoice)
    {
        $total_amount       += $invoice->valid_amount;
    }
    return $total_amount;
}

function updateValidAmtAndQty($ticket_header_uuid)
{
    $result                             = TicketHeaderInvoice::where('ticket_header_uuid', $ticket_header_uuid)->get();

            $total_valid_amount         = 0;
            foreach($result as $data)
            {
                $total_valid_amount +=$data->valid_amount;
            }
            TicketHeader::where('uuid',$ticket_header_uuid)->update(['total_valid_amount'=>$total_valid_amount,'total_remain_amount'=>$total_valid_amount]);
            $claim_histories            = ClaimHistory::where('ticket_header_uuid',$ticket_header_uuid)->get();
            foreach($claim_histories as $claim)
            {
                $valid_qty          = (int) ($total_valid_amount /$claim->one_qty_amount);

                $claim->update(['valid_qty'=>$valid_qty,'remain_choose_qty'=>$valid_qty,'remain_claim_qty' =>$valid_qty]);
            }
}

function check_used_invoice($invoice_no)
{
    $ticket_header_invoice = TicketHeaderInvoice::where('invoice_no', $invoice_no)
            ->whereHas('ticket_headers', function ($q) {
                $q->where('status', '=', 1);
            })->first();
    return $ticket_header_invoice;
}

function get_promotion_sub_promotions()
{

}

function getAllCategory()
{
    return Category::distinct('maincatid')->get();
}


function sendIRENotification($role_name,$transaction){

    $notiusers = User::whereHas('branches', function($query) use ($transaction) {
        $query->where('branch_users.branch_id', $transaction->branch_id);
    })->whereHas('roles', function($query) use ($role_name) {
        $query->where('name', $role_name);
    })->get();
    if ($notiusers) {
        Notification::send($notiusers,new RedemptionTransactionApprovalNoti($transaction));
    }
}

function readIRENotification($uuid){
    $user = Auth::user();
    $user_uuid = $user->uuid;
     // Notification Disappear
     $type = "App\Notifications\RedemptionTransactionApprovalNoti";
     $getnotis = $user->unreadNotifications;
     // dd($getnoti);
     foreach($getnotis as $getnoti){
         if($getnoti->type == $type && $getnoti->data['redemption_transaction_uuid'] == $uuid){
             $getnoti->markAsRead();
         }
     }
}

function sendIRESingleUserNotification($user_uuid,$transaction){
    $notiuser = User::where("uuid",$user_uuid)->first();
    // dd($notiuser);
    $notiuser->notify(new RedemptionTransactionApprovalNoti($transaction));
}

function sendInstallerCardNotification($role_name,$installercard){

    $notiusers = User::whereHas('branches', function($query) use ($installercard) {
        $query->where('branch_users.branch_id', $installercard->branch_id);
    })->whereHas('roles', function($query) use ($role_name) {
        $query->where('name', $role_name);
    })->get();
    if ($notiusers) {
        Notification::send($notiusers,new RedemptionTransactionApprovalNoti($installercard));
    }
}



// function getCurrentBranch()
// {
//     $currentURL = URL::current();
//     // dd($currentURL);

//     if (str_contains($currentURL, 'http://ltluckydraw.pro1global.com:8888/') || str_contains($currentURL, 'http://127.0.0.1:8000/') || str_contains($currentURL, 'http://192.168.2.25:8888/')) {
//         return 1;
//     }

//     if (str_contains($currentURL, 'http://tploading.pro1global.com:56/theikpan_pickup_view') || str_contains($currentURL, 'http://pickup.test/theikpan_pickup_view')) {
//         return 2;
//     }

//     if (str_contains($currentURL, 'http://ssloading.pro1global.com:56/satsan_pickup_view') || str_contains($currentURL, 'http://pickup.test/satsan_pickup_view')
//         // ||str_contains($currentURL, '192.168.2.23')
//     ) {
//         return 3;
//     }

//     if (str_contains($currentURL, 'http://edgloading.pro1global.com:56/edg_pickup_view') || str_contains($currentURL, 'http://pickup.test/edg_pickup_view')) {
//         return 9;
//     }

//     if (str_contains($currentURL, 'http://mlmloading.pro1global.com:56/mlm_pickup_view')|| str_contains($currentURL, 'http://pickup.test/mlm_pickup_view')) {
//         return 10;
//     }

//     if (str_contains($currentURL, 'http://tpwloading.pro1global.com:56/tpwd_pickup_view')|| str_contains($currentURL, 'http://pickup.test/tpwd_pickup_view')) {
//         return 11;
//     }

//     if (str_contains($currentURL, 'http://htyloading.pro1global.com:56/hty_pickup_view')|| str_contains($currentURL, 'http://pickup.test/hty_pickup_view')) {
//         return 19;
//     }

//     if (str_contains($currentURL, 'http://atyloading.pro1global.com:56/ayetharyar_pickup_view')|| str_contains($currentURL, 'http://pickup.test/ayetharyar_pickup_view')) {
//         return 21;
//     }

//     if (str_contains($currentURL, 'http://tmloading.pro1global.com:56/terminalm_pickup_view')|| str_contains($currentURL, 'http://pickup.test/terminalm_pickup_view')) {
//         return 27;
//     }

//     if (str_contains($currentURL, 'http://sdgloading.pro1global.com:56/sdg_pickup_view')|| str_contains($currentURL, 'http://pickup.test/sdg_pickup_view')) {
//         return 28;
//     }


//     if (str_contains($currentURL, 'http://dngloading.pro1global.com:56/shwepyithar_pickup_view')|| str_contains($currentURL, 'http://pickup.test/shwepyithar_pickup_view')) {
//         return 30;
//     }

//     if (str_contains($currentURL, 'http://bgoloading.pro1global.com:56/bago_pickup_view')|| str_contains($currentURL, 'http://pickup.test/bago_pickup_view')) {
//         return 23;
//     }



// }

function getCurrentBranch(){
    $user = Auth::user();
    $user_uuid = $user->uuid;

    $branch_id = $user->branch_id;
    return $branch_id;
}

function getCustomerInfo($branch_id,$verifyphone){
    $customermodel;
    switch($branch_id){
        case 1:
            $customermodel = \App\Models\POS101\Pos101GbhCustomer::class;
            break;
        case 2:
            $customermodel = \App\Models\POS102\Pos102GbhCustomer::class;
            break;
        case 3:
            $customermodel = \App\Models\POS103\Pos103GbhCustomer::class;
            break;
        case 9:
            $customermodel = \App\Models\POS104\Pos104GbhCustomer::class;
            break;
        case 10:
            $customermodel = \App\Models\POS105\Pos105GbhCustomer::class;
            break;
        case 11:
            $customermodel = \App\Models\POS106\Pos106GbhCustomer::class;
            break;
        case 19:
            $customermodel = \App\Models\POS107\Pos107GbhCustomer::class;
            break;
        case 21:
            $customermodel = \App\Models\POS108\Pos108GbhCustomer::class;
            break;
        case 27:
            $customermodel = \App\Models\POS112\Pos112GbhCustomer::class;
            break;
        case 28:
            $customermodel = \App\Models\POS113\Pos113GbhCustomer::class;
            break;
        case 30:
            $customermodel = \App\Models\POS114\Pos114GbhCustomer::class;
            break;
        case 23:
            $customermodel = \App\Models\POS110\Pos110GbhCustomer::class;
            break;
    }

    // dd($customermodel::where('mobile',$verifyphone)->first());

    $customer = $customermodel::where('mobile',$verifyphone)->first();
    return $customer;
}

function getCustomerInfoById($branch_id,$gbh_customer_id,$customer_barcode){
    $customermodel;
    switch($branch_id){
        case 1:
            $customermodel = \App\Models\POS101\Pos101GbhCustomer::class;
            break;
        case 2:
            $customermodel = \App\Models\POS101\Pos102GbhCustomer::class;
            break;
        case 3:
            $customermodel = \App\Models\POS101\Pos103GbhCustomer::class;
            break;
        case 9:
            $customermodel = \App\Models\POS101\Pos104GbhCustomer::class;
            break;
        case 10:
            $customermodel = \App\Models\POS101\Pos105GbhCustomer::class;
            break;
        case 11:
            $customermodel = \App\Models\POS101\Pos106GbhCustomer::class;
            break;
        case 19:
            $customermodel = \App\Models\POS101\Pos107GbhCustomer::class;
            break;
        case 21:
            $customermodel = \App\Models\POS101\Pos108GbhCustomer::class;
            break;
        case 27:
            $customermodel = \App\Models\POS101\Pos112GbhCustomer::class;
            break;
        case 28:
            $customermodel = \App\Models\POS101\Pos113GbhCustomer::class;
            break;
        case 30:
            $customermodel = \App\Models\POS101\Pos114GbhCustomer::class;
            break;
        case 23:
            $customermodel = \App\Models\POS101\Pos110GbhCustomer::class;
            break;
    }

    // dd($customermodel::where('mobile',$verifyphone)->first());

    $customer = $customermodel::where('gbh_customer_id',$gbh_customer_id)->where('customer_barcode',$customer_barcode)->first();
    return $customer;
}

// function getCategoryGroupTotal($invoice_number){
//     $db_ext = DB::connection('pos101_pgsql');
//     $inv_cat_grp_totals = $db_ext->select("
//         SELECT public.master_goods_category.remark AS category_name,
//             public.temp_master_product.group_id,
//             public.temp_master_product.group_name,
//             SUM(sale_cash.sale_cash_items.sale_amount)::NUMERIC(15, 2)
//         FROM sale_cash.sale_cash_items
//         INNER JOIN public.temp_master_product
//             ON sale_cash.sale_cash_items.barcode_code = public.temp_master_product.barcode_code
//         INNER JOIN public.master_goods_category
//             ON public.temp_master_product.category_id = public.master_goods_category.product_category_id
//         WHERE sale_cash.sale_cash_items.sale_cash_document_no = '$invoice_number'
//         GROUP BY public.master_goods_category.remark,
//                 public.temp_master_product.group_id,
//                 public.temp_master_product.group_name;
//     ");
//     return $inv_cat_grp_totals;
// }

function getCategoryGroupPointTotal($branch_id,$invoice_number,$pointperamount){

    $branch_id = getInvoiceBranch($invoice_number);
    // dd($branch_id);
    $db_ext = getConnection($branch_id);
    $inv_cat_grp_totals = $db_ext->select("
         SELECT bb.sale_cash_document_datenow::date as date,category_id,category_name,group_id,group_name,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
            ,(sum(sale_amount))::numeric(19,2) as saleamount
            , voucher_value::numeric(19,2) as Coupon
            ,(sum(sale_amount)::integer/$pointperamount) as net_point
            --, case when (sum(sale_amount)::numeric(19,2))<'$pointperamount' then '0' else ((sum(sale_amount)::integer/$pointperamount))  end as net_point
            FROM
            (
                SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                            else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                ,(sale_amount)::numeric(19,2)

                FROM sale_cash.sale_cash_document aa
                LEFT JOIN sale_cash.sale_cash_items bb
                on aa.sale_cash_document_id= bb.sale_cash_document_id
                where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                and  (bb.barcode_code not like 'GP%' or sale_price <='0' and diposit_type_id <> '3')
                and aa.sale_cash_document_no in ('$invoice_number')
                )bb
            LEFT JOIN temp_master_product pro on bb.barcode_codes=pro.barcode_code
            Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
            Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
            where  sale_cash_document_no1 in ('$invoice_number')
            and pro.diposit_type_id in ('2','3')
            group by bb.sale_cash_document_datenow::date,category_id,category_name,group_id,group_name,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code
    ");
    return $inv_cat_grp_totals;
}
// function getCategoryGroupPointTotalReturned($branch_id,$invoice_number,$pointperamount){

//     $branch_id = getInvoiceBranch($invoice_number);
//     // dd($branch_id);
//     $db_ext = getConnection($branch_id);
//     $inv_cat_grp_totals = $db_ext->select("
//          SELECT bb.sale_cash_document_datenow::date as date,category_id,category_name,group_id,group_name,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
//             ,(sum(sale_amount))::numeric(19,2) as saleamount
//             , voucher_value::numeric(19,2) as Coupon
//             ,(sum(sale_amount)::integer/$pointperamount) as net_point
//             --, case when (sum(sale_amount)::numeric(19,2))<'$pointperamount' then '0' else ((sum(sale_amount)::integer/$pointperamount))  end as net_point
//             FROM
//             (
//                 SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
//                             else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
//                 ,(sale_amount)::numeric(19,2)

//                 FROM sale_cash.sale_cash_document aa
//                 LEFT JOIN sale_cash.sale_cash_items bb
//                 on aa.sale_cash_document_id= bb.sale_cash_document_id
//                 where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1','5')
//                 and  (bb.barcode_code not like 'GP%' or sale_price <='0' and diposit_type_id <> '3')
//                 and aa.sale_cash_document_no in ('$invoice_number')
//                 )bb
//             LEFT JOIN temp_master_product pro on bb.barcode_codes=pro.barcode_code
//             Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
//             Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
//             where  sale_cash_document_no1 in ('$invoice_number')
//             and pro.diposit_type_id in ('2','3')
//             group by bb.sale_cash_document_datenow::date,category_id,category_name,group_id,group_name,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code

//     ");
//     return $inv_cat_grp_totals;
// }

function getCategoryGroupPointTotalReturned($branch_id,$invoice_number,$pointperamount){

    $branch_id = getInvoiceBranch($invoice_number);
    // dd($branch_id);
    $db_ext = getConnection($branch_id);
    $inv_cat_grp_totals = $db_ext->select("
         SELECT bb.sale_cash_document_datenow::date as date,category_id,category_name,group_id,group_name,sale_cash_document_no1 as saleinvoiceno,gbh_customer_id,sale_id as sale_cash_document_id,bb.branch_code
            ,(sum(sale_amount))::numeric(19,2) as saleamount
            , voucher_value::numeric(19,2) as Coupon
            ,(sum(sale_amount)::integer/$pointperamount) as net_point
            --, case when (sum(sale_amount)::numeric(19,2))<'$pointperamount' then '0' else ((sum(sale_amount)::integer/$pointperamount))  end as net_point
            FROM
            (
                SELECT aa.sale_cash_document_no as sale_cash_document_no1, aa.sale_cash_document_id as sale_id,Case when barcode_code ilike '%PRO%' then  substr(barcode_code,4, length(barcode_code) )
                            else barcode_code end as barcode_codes,  aa.branch_code as  branch_code,  sale_cash_document_datenow::date,gbh_customer_id, voucher_value::numeric(19,2)
                ,(sale_amount)::numeric(19,2)

                FROM sale_cash.sale_cash_document aa
                LEFT JOIN sale_cash.sale_cash_items bb
                on aa.sale_cash_document_id= bb.sale_cash_document_id
                where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1','5')
                and  (bb.barcode_code not like 'GP%' or sale_price <='0' and diposit_type_id <> '3')
                and aa.sale_cash_document_no in ('$invoice_number')
                )bb
            LEFT JOIN temp_master_product pro on bb.barcode_codes=pro.barcode_code
            Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
            Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
            where  sale_cash_document_no1 in ('$invoice_number')
            and pro.diposit_type_id in ('2','3')
            group by bb.sale_cash_document_datenow::date,category_id,category_name,group_id,group_name,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code

    ");
    return $inv_cat_grp_totals;
}
function getInvoiceBranch($invoice_number){
    $branches = Branch::all();

    foreach($branches as $branch){
        if(strpos($invoice_number, $branch->branch_short_name) !== false){
            return $branch->branch_id;
        }
    }

}

// function getCategoryIdByName($category_name){
//     $maincatid = AllCategory::where('remark',$category_name)->first()->maincatid;
//     // dd($maincatid);
//     return $maincatid;
// }

function getMaincatidByName($category_name){
    $maincatid = AllCategory::where('product_category_name',$category_name)->first()->maincatid;
    // dd($maincatid);
    return $maincatid;
}

function getCategoryRemarkByName($category_name){
    $maincatid = AllCategory::where('product_category_name',$category_name)->first()->remark;
    return $maincatid;
}


// function getRetCategoryGroupTotal($return_product_docno,$branch_id){
//     $db_ext = getConnection($branch_id);
//     $inv_cat_grp_totals = $db_ext->select("
//         SELECT
//             public.temp_master_product.category_id,
//             public.temp_master_product.category_name,
//             public.temp_master_product.group_id,
//             public.temp_master_product.group_name,
//             SUM(return_product.return_product_item.return_product_item_sale_price_amount)::NUMERIC(15, 2) AS sale_price_amount
//         FROM
//             return_product.return_product_item
//         INNER JOIN
//             public.temp_master_product
//         ON
//             (CASE
//                 WHEN return_product.return_product_item.return_product_item_barcode_code ILIKE 'PRO%'
//                 THEN SUBSTR(return_product.return_product_item.return_product_item_barcode_code, 4, LENGTH(return_product.return_product_item.return_product_item_barcode_code) - 3)
//                 ELSE return_product.return_product_item.return_product_item_barcode_code
//             END) = public.temp_master_product.barcode_code
//         INNER JOIN
//             return_product.return_product_doc
//         ON
//             return_product.return_product_item.return_product_doc_id = return_product.return_product_doc.return_product_doc_id
//         WHERE
//             return_product.return_product_item.return_product_item_active = 'true'
//             AND return_product.return_product_doc.return_product_docno = '$return_product_docno'
//         GROUP BY
//             public.temp_master_product.category_id,
//             public.temp_master_product.category_name,
//             public.temp_master_product.group_id,
//             public.temp_master_product.group_name;
//     ");
//     return $inv_cat_grp_totals;
// }


function getRetCategoryGroupPointTotal($return_product_docno,$branch_id){
    $branch_id = getInvoiceBranch($return_product_docno);
    $db_ext = getConnection($branch_id);
    $inv_cat_grp_totals = $db_ext->select("
        SELECT ------return
return_date,category_id,category_name,group_id,group_name, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id, return_product_doc_branchcode,returnamnt, voucher_value::numeric(19,2) as Coupon, (returnamnt::integer*(-1)/10000) as return_point
	FROM (
	SELECT return_date, category_id,category_name,group_id,group_name, saleinvoice_no, gbh_customer_id, return_product_doc_branchcode, return_product_doc_branchname, sum(total) as returnamnt,return_product_doc_ref_doc_id,sum(voucher_value) as voucher_value
	FROM(

	SELECT  return_date,category_id,category_name,group_id,group_name,saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name, return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total,return_product_doc_ref_doc_id,0::numeric(19) as voucher_value
	FROM(
	SELECT  return_date, category_id,category_name,group_id,group_name, return_product_doc_ref_docno as saleinvoice_no, tt.return_product_doc_gbh_customer_id as gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or, return_product_item_amount::numeric(19,2) as diff, return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                            ,return_product_doc_branchcode, return_product_doc_branchname, good_brAND_name, remark, diposit_type_name, return_product_item_amount::numeric(19,2) as returnqty,return_product_item_sale_price::Numeric(19,2) as returnprice,return_product_doc_ref_doc_id
	FROM (SELECT return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id , CASE WHEN return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                    ELSE return_product_item_barcode_code end as return_product_item_barcode_codes, *
	FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                LEFT JOIN sale_cash.sale_cash_document sc on bb.return_product_doc_ref_docno=sc.sale_cash_document_no
                                WHERE return_product_docno in ('$return_product_docno')
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                    )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                            AND pro.diposit_type_id in ('2','3')
                            where tt.return_product_item_barcode_codes not like '400502%')return
                    )returntable
                    GROUP BY return_date, category_id,category_name,group_id,group_name, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_docno in  ('$return_product_docno')
                    GROUP BY return_product_doc_ref_docno, balance_value)mx on saleinvoice_no=mx.sale AND return_date=mx.date
                    where category_id is not null
                    -- WHERE returnamnt::Numeric(19,0) <> '0'
                    GROUP BY return_date, category_id,category_name,group_id,group_name, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode,voucher_value,returnamnt,return_product_doc_ref_doc_id,saleamount
    ");
    return $inv_cat_grp_totals;



}


function deductPreUsedPointsFromCard($card_number){

    $installercard = InstallerCard::where("card_number", $card_number)->first();
    $requestbranch_id = getCurrentBranch();

    $credit_points = $installercard->credit_points;
    $credit_amount = $installercard->credit_amount;
    if($credit_points < 0 && $credit_amount < 0){

        // Check for ongoing redemption transaction to block duplicates
        $curredemptiontransaction = RedemptionTransaction::where('installer_card_card_number',$card_number)->whereNotIn('status',['finished','rejected'])->exists();
        if($curredemptiontransaction){
            return redirect()->route('installercardpoints.detail',$card_number)->with("error","This installer has current redemption transaction and we can't deduct preused points now.");
        }

        $preusedinstallercardpoints = InstallerCardPoint::where("installer_card_card_number", $card_number)
                                        ->where('points_balance',"<",0)
                                        ->where('amount_balance',"<",0)
                                        ->orderBy("created_at", "asc")
                                        ->orderBy('id','asc')
                                        ->get();

        // dd($preusedinstallercardpoints);
        if(count($preusedinstallercardpoints) > 0){
            $user = Auth::user();
            $transaction = RedemptionTransaction::create([
                'uuid' => (string) Str::uuid(),
                'branch_id' => $requestbranch_id,
                'document_no' => RedemptionTransaction::generate_doc_no($requestbranch_id),
                'installer_card_card_number' => $card_number,
                'total_points_redeemed' => 0,
                'total_cash_value' => 0,
                'status' => 'finished',
                'redemption_date' => now(),
                'requester' => $installercard->fullname,
                'prepare_by' => $user->uuid,
                'nature'=>"return deduct"
            ]);
            dispatch(new SyncRowJob("redemption_transactions","insert",$transaction));

            $preusedslip = PreusedSlip::create([
                'uuid'=> (string) Str::uuid(),
                'branch_id'=> $requestbranch_id,
                'installer_card_card_number'=>$card_number,
                'before_pay_total_points'=> $installercard->totalpoints,
                'before_pay_total_amount'=> $installercard->totalamount,
                'before_pay_credit_points'=> $installercard->credit_points,
                'before_pay_credit_amount'=> $installercard->credit_amount,
                'total_points_paid'=> 0,
                'total_accept_value'=> 0,
                'user_uuid'=> $user->uuid,
                'redemption_transaction_uuid'=> $transaction->uuid,
            ]);
            dispatch(new SyncRowJob("preused_slips","insert",$preusedslip));


            $totalRedeemedPoints = 0;
            $totalRedeemedAmount = 0;
            foreach($preusedinstallercardpoints as $preusedinstallercardpoint){
                // Instller Paid Evidence------------------------------------------------------------------------- //
                $pointpay = PointPay::create([
                    'installer_card_point_uuid'=> $preusedinstallercardpoint->uuid,
                    'before_pay_points_balance'=> $preusedinstallercardpoint->points_balance,
                    'before_pay_amount_balance'=> $preusedinstallercardpoint->amount_balance,
                    'points_paid'=> 0,
                    'accept_value'=> 0,
                    'preused_slip_uuid'=> $preusedslip->uuid,
                ]);
                dispatch(new SyncRowJob("point_pays","insert",$pointpay));

                // Instller Paid Evidence------------------------------------------------------------------------- //

                $deductpoints = 0;
                $deductamount = 0;

                $installercardpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                        ->where("is_redeemed", "0")
                        ->where("expiry_date", ">", Carbon::now())
                        ->where("point_based",$preusedinstallercardpoint->point_based)
                        ->orderBy("created_at", "asc")
                        ->orderBy('id','asc')
                        ->get();

                $remainingPointsToRedeem = abs($preusedinstallercardpoint->points_balance);
                foreach ($installercardpoints as $installercardpoint) {

                    $availablePoints = $installercardpoint->points_balance;
                    $availableAmount = $installercardpoint->amount_balance;

                    if($availableAmount <= 0){
                        $installercardpoint->update([
                            'is_redeemed' => 1, // Mark as redeemed
                        ]);
                        dispatch(new SyncRowJob("installer_card_points","update",$installercardpoint));
                        continue;
                    }

                    if ($remainingPointsToRedeem > 0) {
                        if ($remainingPointsToRedeem >= $availablePoints) {
                            // Redeem all points from this entry
                            $totalRedeemedPoints += $availablePoints;
                            $totalRedeemedAmount += $availableAmount;

                            $pointredemption = PointsRedemption::create([
                                'installer_card_point_uuid'=>$installercardpoint->uuid,
                                'points_redeemed' => $availablePoints,
                                'point_accumulated' => $installercardpoint->point_based,
                                'redemption_amount' => $availableAmount, // Full amount redeemed
                                'redemption_transaction_uuid' => $transaction->uuid,
                            ]);
                            dispatch(new SyncRowJob("points_redemptions","insert", $pointredemption));


                            // Update this row as fully redeemed
                            $installercardpoint->update([
                                'points_redeemed' => $installercardpoint->points_redeemed + $availablePoints,
                                'points_balance' => 0,
                                'amount_redeemed' => $installercardpoint->amount_redeemed + $availableAmount,
                                'amount_balance' => 0,
                                'is_redeemed' => 1, // Mark as redeemed
                            ]);
                            dispatch(new SyncRowJob("installer_card_points","update",$installercardpoint));


                            $preusedinstallercardpoint->update([
                                "points_balance"=> $preusedinstallercardpoint->points_balance + $availablePoints,
                                "amount_balance"=> $preusedinstallercardpoint->amount_balance + $availableAmount
                            ]);
                            dispatch(new SyncRowJob("installer_card_points","update",$preusedinstallercardpoint));


                            $remainingPointsToRedeem -= $availablePoints;
                        } else {
                            // Partially redeem points from this entry
                            $proportionalAmount = ($remainingPointsToRedeem / $availablePoints) * $availableAmount;

                            $totalRedeemedPoints += $remainingPointsToRedeem;
                            $totalRedeemedAmount += $proportionalAmount;

                            $pointredemption = PointsRedemption::create([
                                'installer_card_point_uuid'=>$installercardpoint->uuid,
                                'points_redeemed' => $remainingPointsToRedeem,
                                'point_accumulated' => $installercardpoint->point_based,
                                'redemption_amount' =>  $proportionalAmount,
                                'redemption_transaction_uuid' => $transaction->uuid,
                            ]);
                            dispatch(new SyncRowJob("points_redemptions","insert", $pointredemption));

                            // Update this row with partial redemption
                            $installercardpoint->update([
                                'points_redeemed' => $installercardpoint->points_redeemed + $remainingPointsToRedeem,
                                'points_balance' => $installercardpoint->points_balance - $remainingPointsToRedeem,
                                'amount_redeemed' => $installercardpoint->amount_redeemed + $proportionalAmount,
                                'amount_balance' => $installercardpoint->amount_balance - $proportionalAmount,
                            ]);
                            dispatch(new SyncRowJob("installer_card_points","update",$installercardpoint));



                            $preusedinstallercardpoint->update([
                                "points_balance"=> $preusedinstallercardpoint->points_balance + $remainingPointsToRedeem,
                                "amount_balance"=> $preusedinstallercardpoint->amount_balance + $proportionalAmount
                            ]);
                            dispatch(new SyncRowJob("installer_card_points","update",$preusedinstallercardpoint));


                            $remainingPointsToRedeem = 0; // Fully redeemed
                        }
                    } else {
                        break; // No more points needed for redemption
                    }
                }

                // Instller Paid Evidence------------------------------------------------------------------------- //
                $pointpay->update([
                    'points_paid'=> $totalRedeemedPoints,
                    'accept_value'=> $totalRedeemedAmount,
                ]);
                dispatch(new SyncRowJob("point_pays","update",$pointpay));
                // Instller Paid Evidence------------------------------------------------------------------------- //
            }
            $transaction->update([
                "total_points_redeemed" => $totalRedeemedPoints,
                "total_cash_value" => $totalRedeemedAmount
            ]);
            dispatch(new SyncRowJob("redemption_transactions","update",$transaction));

            $installercard->update([
                "totalpoints"=>  $installercard->totalpoints - $totalRedeemedPoints,
                "totalamount"=> $installercard->totalamount - $totalRedeemedAmount,
                'credit_points'=> $installercard->credit_points + $totalRedeemedPoints,
                'credit_amount'=> $installercard->credit_amount + $totalRedeemedAmount,
            ]);
            dispatch(new SyncRowJob("installer_cards","update",$installercard));

            $preusedslip->update([
                'total_points_paid'=> $totalRedeemedPoints,
                'total_accept_value'=> $totalRedeemedAmount,
            ]);
            dispatch(new SyncRowJob("preused_slips","update",$preusedslip));
        }
    }

}

function deductDoubleProfit($card_number,$collectiontransaction){

            // Retrieve the installer's card and points balance
            $installercard = InstallerCard::where("card_number", $card_number)->first();
            $requestbranch_id = getCurrentBranch();

            $current_installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                                        ->where('collection_transaction_uuid',$collectiontransaction->uuid)
                                        ->where('points_earned',"<",0)
                                        ->where('points_balance',"=",0)
                                        ->where('amount_balance',"=",0)
                                        ->where("is_redeemed", "0")
                                        ->where("expiry_date", ">", Carbon::now())
                                        ->orderBy("created_at", "asc")
                                        ->orderBy('id','asc');

            $double_profit_points = $current_installerpoints->sum('points_earned');
            $reqredeempoints =abs($double_profit_points);
            // dd($reqredeempoints);

            // Ensure the installer has enough points
            // if ($installercard->totalpoints >= $reqredeempoints) {
                // Retrieve available points (sorted by expiry date)
                $installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                    ->where('collection_transaction_uuid',$collectiontransaction->uuid)
                    ->where("is_redeemed", "0")
                    ->where("expiry_date", ">", Carbon::now())
                    ->orderBy("created_at", "asc")
                    ->orderBy('id','asc')
                    ->get();
                $user = Auth::user();
                $transaction = RedemptionTransaction::create([
                    'uuid' => (string) Str::uuid(),
                    'branch_id' => $requestbranch_id,
                    'document_no' => RedemptionTransaction::generate_doc_no($requestbranch_id),
                    'installer_card_card_number' => $card_number,
                    'total_points_redeemed' => 0,
                    'total_cash_value' => 0,
                    'status' => 'finished',
                    'redemption_date' => now(),
                    'requester' => $installercard->fullname,
                    'prepare_by' => $user->uuid,
                    'nature'=>"double profit deduct"
                ]);
                dispatch(new SyncRowJob("redemption_transactions","insert",$transaction));

                $doubleprofitslip = DoubleProfitSlip::create([
                    'uuid'=> (string) Str::uuid(),
                    'branch_id'=>  $requestbranch_id,
                    'installer_card_card_number'=> $card_number,
                    'collection_transaction_uuid'=> $collectiontransaction->uuid,
                    'user_uuid'=> $user->uuid,
                    'redemption_transaction_uuid'=> $transaction->uuid,
                ]);
                dispatch(new SyncRowJob("double_profit_slips","insert",$doubleprofitslip));

                $totalRedeemedPoints = 0;
                $totalRedeemedAmount = 0;
                $remainingPointsToRedeem = $reqredeempoints;

                // Deduct points from the oldest available entries first
                foreach ($installerpoints as $installerpoint) {
                    $availablePoints = $installerpoint->points_balance;
                    $availableAmount = $installerpoint->amount_balance;

                    if($availableAmount <= 0){
                        $installerpoint->update([
                            'is_redeemed' => 1, // Mark as redeemed
                        ]);
                        dispatch(new SyncRowJob("installer_card_points","update",$installerpoint));
                        continue;
                    }

                    if ($remainingPointsToRedeem > 0) {
                        if ($remainingPointsToRedeem >= $availablePoints) {
                            // Redeem all points from this entry
                            $totalRedeemedPoints += $availablePoints;
                            $totalRedeemedAmount += $availableAmount;

                            $pointredemption = PointsRedemption::create([
                                'installer_card_point_uuid'=>$installerpoint->uuid,
                                'points_redeemed' => $availablePoints,
                                'point_accumulated' => $installerpoint->point_based,
                                'redemption_amount' => $availableAmount, // Full amount redeemed
                                'redemption_transaction_uuid' => $transaction->uuid,
                            ]);
                            dispatch(new SyncRowJob("points_redemptions","insert",$pointredemption));


                            // Update this row as fully redeemed
                            $installerpoint->update([
                                'points_redeemed' => $installerpoint->points_redeemed + $availablePoints,
                                'points_balance' => 0,
                                'amount_redeemed' => $installerpoint->amount_redeemed + $availableAmount,
                                'amount_balance' => 0,
                                'is_redeemed' => 1, // Mark as redeemed
                            ]);
                            dispatch(new SyncRowJob("installer_card_points","update",$installerpoint));


                            $remainingPointsToRedeem -= $availablePoints;




                        } else {
                            // Partially redeem points from this entry
                            $proportionalAmount = ($remainingPointsToRedeem / $availablePoints) * $availableAmount;

                            $totalRedeemedPoints += $remainingPointsToRedeem;
                            $totalRedeemedAmount += $proportionalAmount;


                            $pointredemption = PointsRedemption::create([
                                'installer_card_point_uuid'=>$installerpoint->uuid,
                                'points_redeemed' => $remainingPointsToRedeem,
                                'point_accumulated' => $installerpoint->point_based,
                                'redemption_amount' =>  $proportionalAmount,
                                'redemption_transaction_uuid' => $transaction->uuid,
                            ]);
                            dispatch(new SyncRowJob("points_redemptions","insert",$pointredemption));


                             // Update this row with partial redemption
                             $installerpoint->update([
                                'points_redeemed' => $installerpoint->points_redeemed + $remainingPointsToRedeem,
                                'points_balance' => $installerpoint->points_balance - $remainingPointsToRedeem,
                                'amount_redeemed' => $installerpoint->amount_redeemed + $proportionalAmount,
                                'amount_balance' => $installerpoint->amount_balance - $proportionalAmount,
                            ]);
                            dispatch(new SyncRowJob("installer_card_points","update",$installerpoint));


                            $remainingPointsToRedeem = 0; // Fully redeemed



                        }
                    } else {
                        break; // No more points needed for redemption
                    }
                }

                // Update the transaction with the total redeemed points and equivalent cash value
                $transaction->update([
                    "total_points_redeemed" => $totalRedeemedPoints,
                    "total_cash_value" => $totalRedeemedAmount
                ]);
                dispatch(new SyncRowJob("redemption_transactions","update",$transaction));


                                    // $installercard->update([
                                    //     "totalpoints"=>  $installercard->totalpoints - $totalRedeemedPoints,
                                    //     "totalamount"=> $installercard->totalamount - $totalRedeemedAmount
                                    // ]);

                // \DB::commit();
                return redirect()->route('installercardpoints.detail',$card_number)
                    ->with("success", "Redemption request is waiting for Branch Manager approval.");
            // } else {
            //     // Not enough points to redeem
            //     return redirect()->route('installercards.checking')
            //         ->with("error", "Installer Card doesn't have sufficient points.");
            // }
}

function checkDoubleProfit($card_number,$collectiontransaction){
      // Retrieve the installer's card and points balance
        $installercard = InstallerCard::where("card_number", $card_number)->first();
        $requestbranch_id = getCurrentBranch();


        $cur_double_pro_installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                                            ->where('collection_transaction_uuid',$collectiontransaction->uuid)
                                            ->where('points_earned',"<",0)
                                            ->where('points_balance',"=",0)
                                            ->where('amount_balance',"=",0)
                                            ->where("is_redeemed", "0")
                                            ->where("expiry_date", ">", Carbon::now())
                                            ->orderBy("created_at", "asc")
                                            ->orderBy('id','asc')
                                            ->get();

        if(count($cur_double_pro_installerpoints) <= 0){
            return false;
        }


        foreach($cur_double_pro_installerpoints as $cur_double_pro_installerpoint){
            $redeempoints = abs($cur_double_pro_installerpoint->points_earned);

            // Ensure the installer has enough points
            // if ($installercard->totalpoints >= $redeempoints) {
                // Retrieve available points (sorted by expiry date)

                $last_redeemed_point_id = null;

                if(empty($last_redeemed_point_id)){
                    $installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                    ->where('collection_transaction_uuid',$collectiontransaction->uuid)
                    ->where("is_redeemed", "0")
                    ->where("expiry_date", ">", Carbon::now())
                    ->orderBy("created_at", "asc")
                    ->orderBy('id','asc')
                    ->get();
                }else{
                    $installerpoints = InstallerCardPoint::where("installer_card_card_number", $installercard->card_number)
                    ->where('collection_transaction_uuid',$collectiontransaction->uuid)
                    ->where('id',">",$last_redeemed_point_id)
                    ->where("is_redeemed", "0")
                    ->where("expiry_date", ">", Carbon::now())
                    ->orderBy("created_at", "asc")
                    ->orderBy('id','asc')
                    ->get();
                }


                $user = Auth::user();

                $totalRedeemedPoints = 0;
                $totalRedeemedAmount = 0;
                $remainingPointsToRedeem = $redeempoints;

                // Deduct points from the oldest available entries first
                foreach ($installerpoints as $installerpoint) {
                    $availablePoints = $installerpoint->points_balance;
                    $availableAmount = $installerpoint->amount_balance;

                    if($availableAmount <= 0){
                        // $installerpoint->update([
                        //     'is_redeemed' => 1, // Mark as redeemed
                        // ]);
                        continue;
                    }

                    if ($remainingPointsToRedeem > 0) {
                        if ($remainingPointsToRedeem >= $availablePoints) {
                            // Redeem all points from this entry
                            $totalRedeemedPoints += $availablePoints;
                            $totalRedeemedAmount += $availableAmount;

                            $remainingPointsToRedeem -= $availablePoints;


                        } else {
                            // Partially redeem points from this entry
                            $proportionalAmount = ($remainingPointsToRedeem / $availablePoints) * $availableAmount;

                            $totalRedeemedPoints += $remainingPointsToRedeem;
                            $totalRedeemedAmount += $proportionalAmount;


                            $remainingPointsToRedeem = 0; // Fully redeemed

                            $last_redeemed_point_id = $installerpoint->id;
                        }
                    } else {
                        break; // No more points needed for redemption
                    }
                }

                $cur_double_pro_installerpoint->update([
                    'amount_earned'=> $totalRedeemedAmount * -1,
                ]);
                dispatch(new SyncRowJob("installer_card_points","update",$cur_double_pro_installerpoint));


                $installercard->update([
                    "totalamount"=> $installercard->totalamount - $totalRedeemedAmount
                ]);
                dispatch(new SyncRowJob("installer_cards","update",$installercard));


                $collectiontransaction->update([
                    "total_save_value"=>$collectiontransaction->total_save_value - $totalRedeemedAmount
                ]);
                dispatch(new SyncRowJob("collection_transactions","update",$collectiontransaction));



                // return response()->json(['equivalentamount'=>$totalRedeemedAmount]);

            // } else {
            //     // Not enough points to redeem
            //     return redirect()->route('installercards.checking')
            //         ->with("error", "Installer Card doesn't have sufficient points.");
            // }
        }


        return true;
}

function getPrevMonthsSaleAmounts($match_phones){

    $db_ext = DB::connection('pro1208_pgsql');
    // $match_phones = (string) implode(", ", $match_phones);
    $match_phones = implode(", ", array_map(function ($phone) {
        return "'" . addslashes($phone) . "'";
    }, $match_phones));


    $cus_sale_amt = $db_ext->select(
        "SELECT customer_barcode,mobile,amnt FROM (
			SELECT custcode,sum(amnt) as amnt FROM (
			SELECT custcode,sum(sumgoodamnt) as amnt
			FROM saledata.saleorderhd sohd
			LEFT JOIN configure.setar_emcust cus on cus.custid=sohd.custid
			WHERE docudate between '2024-01-01' and '2024-10-01' and docustatus <> 'C'
			GROUP BY custcode

			UNION ALL

			SELECT custcode,sum(sumgoodamnt) as amnt
			FROM  saledata.salecredithd sohd
			LEFT JOIN configure.setar_emcust cus on cus.custid=sohd.custid
			WHERE docudate between '2024-01-01' and '2024-10-01' and docustatus <> 'C'
			GROUP BY custcode

			UNION ALL

			SELECT custcode,sum(-1*saleamnt) as amnt
			FROM   saledata.creditnotehd cnhd
			LEFT JOIN configure.setar_emcust cus on cus.custid=cnhd.custid
			INNER JOIN saledata.creditnotedt cndt on cnhd.saleid= cndt.saleid
			WHERE cnhd.docudate between '2024-01-01' and '2024-10-01' and docustatus <> 'C'
			GROUP BY custcode)aa
			GROUP BY custcode)bb LEFT JOIN customer.vw_customer cu on cu.customer_barcode=bb.custcode
			WHERE mobile in ($match_phones)

			order by amnt desc

		limit 10"
    );

    // dd($cus_sale_amt);
    return $cus_sale_amt;
}

function getCardPrefix($branch_id){
    $prefix = "3".str_pad($branch_id, 2, '0', STR_PAD_LEFT);
    // dd($prefix);
    return $prefix;
}

function randomstringgenerator($length){

    $characters = "0123456789"; // index 0 to 35
    $characterlengts = strlen($characters);
    // dd($characterlengts); // 36

    $randomstring = "";
    for($i=0 ; $i<$length; $i++){
        $randomstring .=  $characters[rand(0,$characterlengts-1)];
    }

    // dd($randomstring); // VJMD // 1XES
    return $randomstring;
}
