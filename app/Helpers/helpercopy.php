<?php

use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\LuckyDrawBranch;
use App\Models\LuckyDrawBrand;
use App\Models\LuckyDrawCategory;
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
use App\Models\PromotionChangeLog;
use App\Models\TicketHeaderInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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

function create_promotion_log(String $old_info = null, String $new_info = null, String $reason = 'Default Reason', String $promotion_uuid)
{
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
    if (str_contains($currentURL, '192.168.3.242')
    // ||str_contains($currentURL, '192.168.2.23')
    || str_contains($currentURL, '192.168.2.41') || str_contains($currentURL, '192.168.2.221')) {
        return 1;
    }
    if (str_contains($currentURL, '192.168.21.242')) {
        return 2;
    }
    if (str_contains($currentURL, '192.168.25.242')) {
        return 11;
    }
    if (str_contains($currentURL, '192.168.11.242')) {
        return 3;
    }
    if (str_contains($currentURL, '192.168.16.242')
    ||str_contains($currentURL, '192.168.2.23')
    ) {
        return 9;
    }
    if (str_contains($currentURL, '192.168.36.242')) {
        return 19;
    }
    if (str_contains($currentURL, '192.168.31.242')) {
        return 10;
    }
    if (str_contains($currentURL, '192.168.41.242')) {
        return 21;
    }
    if (str_contains($currentURL, '192.168.46.242')) {
        return 27;
    }
    if (str_contains($currentURL, '192.168.51.243')) {
        return 28;
    }
    if (str_contains($currentURL, '192.168.56.242')) {
        return 30;
    }
    Log::debug($e->getMessage());
    return redirect()
        ->intended(route("home"))
        ->with('error', 'Branch is not found!');
}

function findBrandId($invoice_no)
{
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
    } else if (mb_substr($invoice_no, 0, 5) == 'PTMN1') {
        $branch_id = 27;
    } else if (mb_substr($invoice_no, 0, 4) == 'SDG1') {
        $branch_id = 28;
    } else if (mb_substr($invoice_no, 0, 4) == 'SPT1') {
        $branch_id = 30;
    } else {
        $branch_id = null;
    }
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
    $db_ext = getConnection($branch_id);

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

    if ($promotion->diposit_type == 2) {
        $diposit_type_id = '2';
    } else if ($promotion->diposit_type == 3) {
        $diposit_type_id = '3';
    } else {
        $diposit_type_id = '2,3';
    }
    //Check By Amount or By Product
    if ($invoice_check_type == 1) {
        if ($check_gold_ring == 1) {
            // 1 for Gold Ring today,
            //Check All brand
            if ($l_brands[0] == 0) {
                $invoiceData = $db_ext->select("
                Select
                    gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount
                    from
                    (
                    Select *
                    from
                    (
                    SELECT
                    gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount from
                    (SELECT ------return
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND return_product_item_barcode_code not in
                                    ( SELECT return_product_item_barcode_code
                                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                            WHERE return_product_doc_ref_docno in ('$invoice_no')
                                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                            --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                            --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                            AND sc.sale_cash_document_datenow::date = now()::date
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                AND sc.sale_cash_document_datenow::date = now()::date
                                    )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                            WHERE category_id in ($luckydraw_categories)
                            AND pro.diposit_type_id in ($diposit_type_id)
                            AND tt.return_product_item_barcode_codes not like '400502%'

                        )return
                    )returntable
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in  ('$invoice_no')
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
                            and aa.sale_cash_document_no in ('$invoice_no')
                            --and sale_cash_document_datenow::date >= '$promotion->start_date'
                            --and sale_cash_document_datenow::date <= '$promotion->end_date'
                            and sale_cash_document_datenow::date = now()::date
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
                        group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code
                        )main group by gbh_customer_id, sale_cash_document_id
                    )tab

                    Union all

                    Select * from
                    (
                    Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount
                    FROM return_product.return_product_doc
                    where return_product_doc_ref_docno in ('$invoice_no')
                    Union all
                    SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount
                    FROM sale_cash.sale_cash_document aa
                    INNER JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in ('$invoice_no')
                    )tab1
                    )main
                group by gbh_customer_id, sale_cash_document_id
                ");
            }
            else {

                $invoiceData = $db_ext->select("
                Select gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount
                    from
                    (
                    Select *
                    from
                    (
                    SELECT
                    gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount from
                    (SELECT ------return
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND return_product_item_barcode_code not in
                                    ( SELECT return_product_item_barcode_code
                                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                            WHERE return_product_doc_ref_docno in ('$invoice_no')
                                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                            --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                            --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                            AND sc.sale_cash_document_datenow::date = now()::date
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                AND sc.sale_cash_document_datenow::date = now()::date
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
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in  ('$invoice_no')
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
                            and aa.sale_cash_document_no in ('$invoice_no')
                            --and sale_cash_document_datenow::date >= '$promotion->start_date'
                            --and sale_cash_document_datenow::date <= '$promotion->end_date'
                            and sale_cash_document_datenow::date = now()::date
                        )bb
                        INNER JOIN
                        (
                            SELECT * FROM   master_product_luckydraw
                        )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                        Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                        Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where  sale_cash_document_no1 in ('$invoice_no')
                        and category_id in ($luckydraw_categories)
                        and good_brand_id in ($luckydraw_brands)
                        and pro.diposit_type_id in ($diposit_type_id)
                        group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code
                        )main group by gbh_customer_id, sale_cash_document_id
                    )tab

                    Union all

                    Select * from
                    (
                    Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount
                    FROM return_product.return_product_doc
                    where return_product_doc_ref_docno in ('$invoice_no')
                    Union all
                    SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount
                    FROM sale_cash.sale_cash_document aa
                    INNER JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in ('$invoice_no')
                    )tab1
                )main
                group by gbh_customer_id, sale_cash_document_id
                ");
            }
        } else {
            //  for Others not today,
            //Check All brand
            if ($l_brands[0] == 0) {
                //Check Invoice No for Normal Invoice
                $invoiceData = $db_ext->select("
                Select
                    gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount
                    from
                    (
                    Select *
                    from
                    (
                    SELECT
                    gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount from
                    (SELECT ------return
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND return_product_item_barcode_code not in
                                    ( SELECT return_product_item_barcode_code
                                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                            WHERE return_product_doc_ref_docno in ('$invoice_no')
                                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                            --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                            --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
                                    )tt
                            LEFT OUTER JOIN  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes AND return_product_doc_branchcode=pro.branch_code
                            LEFT OUTER JOIN public.master_goods_category as cate on pro.category_id=cate.product_category_id
                            LEFT JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                            WHERE category_id in ($luckydraw_categories)
                            AND pro.diposit_type_id in ($diposit_type_id)
                            AND tt.return_product_item_barcode_codes not like '400502%'

                        )return
                    )returntable
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in  ('$invoice_no')
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
                            and aa.sale_cash_document_no in ('$invoice_no')
                            --and sale_cash_document_datenow::date >= '$promotion->start_date'
                            --and sale_cash_document_datenow::date <= '$promotion->end_date'
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
                        group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code
                        )main group by gbh_customer_id, sale_cash_document_id
                    )tab

                    Union all

                    Select * from
                    (
                    Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount
                    FROM return_product.return_product_doc
                    where return_product_doc_ref_docno in ('$invoice_no')
                    Union all
                    SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount
                    FROM sale_cash.sale_cash_document aa
                    INNER JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in ('$invoice_no')
                    )tab1
                    )main
                group by gbh_customer_id, sale_cash_document_id
                ");
            } else {
                //Check Invoice No for Normal Invoice
                $invoiceData = $db_ext->select("
                Select
                    gbh_customer_id, sale_cash_document_id,sum(total_net_amount) as total_net_amount
                    from
                    (
                    Select *
                    from
                    (
                    SELECT
                    gbh_customer_id, sale_cash_document_id,sum(net_amount) as total_net_amount from
                    (SELECT ------return
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND return_product_item_barcode_code not in
                                    ( SELECT return_product_item_barcode_code
                                            FROM return_product.return_product_doc bb INNER JOIN return_product.return_product_item cc on bb.return_product_doc_id= cc.return_product_doc_id
                                            WHERE return_product_doc_ref_docno in ('$invoice_no')
                                            AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                            --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                            --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
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
                                WHERE return_product_doc_ref_docno in ('$invoice_no')
                                AND cc.return_product_item_amount::numeric(19,2) <> '0' AND return_product_item_active = 't'
                                --AND sc.sale_cash_document_datenow::date >= '$promotion->start_date'
                                --AND sc.sale_cash_document_datenow::date <= '$promotion->end_date'
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
                    group by return_date, gbh_customer_id, saleinvoice_no, return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id)return
                    right JOIN (SELECT  distinct(return_product_doc_ref_docno)  as sale,max(return_product_doc_datenow)::date as date,(balance_value*1.05)::numeric(19) as saleamount  FROM  return_product.return_product_doc
                    WHERE return_product_doc_ref_docno in  ('$invoice_no')
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
                            and aa.sale_cash_document_no in ('$invoice_no')
                            --and sale_cash_document_datenow::date >= '$promotion->start_date'
                            --and sale_cash_document_datenow::date <= '$promotion->end_date'
                        )bb
                        INNER JOIN
                        (
                            SELECT * FROM   master_product_luckydraw
                        )as pro on bb.barcode_codes=pro.barcode_code --and bb.category_id=pro.category_id
                        Left JOIN (SELECT distinct branch_code, branch_name FROM public.temp_master_product) as br on br.branch_code=bb.branch_code
                        Left JOIN  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where  sale_cash_document_no1 in ('$invoice_no')
                        and category_id in ($luckydraw_categories)
                        and good_brand_id in ($luckydraw_brands)
                        and pro.diposit_type_id in ($diposit_type_id)
                        group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id,bb.branch_code
                        )main group by gbh_customer_id, sale_cash_document_id
                    )tab

                    Union all

                    Select * from
                    (
                    Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount
                    FROM return_product.return_product_doc
                    where return_product_doc_ref_docno in ('$invoice_no')
                    Union all
                    SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount
                    FROM sale_cash.sale_cash_document aa
                    INNER JOIN sale_cash.sale_cash_items bb
                    on aa.sale_cash_document_id= bb.sale_cash_document_id
                    where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                    and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                    and aa.sale_cash_document_no in ('$invoice_no')
                    )tab1
                    )main
                group by gbh_customer_id, sale_cash_document_id
                ");
            }
        }
        if ($invoiceData) {
            $totalprice = (int) $invoiceData[0]->total_net_amount;
            $gbh_customer_id = $invoiceData[0]->gbh_customer_id;
            $invoice_id = $invoiceData[0]->sale_cash_document_id;

        } else {
            $totalprice = null;
            $gbh_customer_id = null;
            $invoice_id = null;
        }
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
    return $data[] = [
        'totalprice' => $totalprice,
        'gbh_customer_id' => $gbh_customer_id,
        'invoice_id' => $invoice_id,
    ];
}

function get_customer($gbh_customer_id, $branch_id)
{
    if ($branch_id == 1) {
        $customer = Pos101GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 2) {
        $customer = Pos102GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 3) {
        $customer = Pos103GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 9) {
        $customer = Pos104GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 10) {
        $customer = Pos105GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 11) {
        $customer = Pos106GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 19) {
        $customer = Pos107GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 21) {
        $customer = Pos108GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 27) {
        $customer = Pos112GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 28) {
        $customer = Pos113GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($branch_id == 30) {
        $customer = Pos114GbhCustomer::where('gbh_customer_id', $gbh_customer_id)->first();
    }
    if ($customer->customer_barcode != '10547') {
        //check customer in ldcustomer
        $ldcustomer = Customer::where('phone_no', $customer->mobile)->first();
        if ($customer->identification_card != '') {
            $member_type = 'Member';
        } else {
            $member_type = 'Old';
        }

        if (!isset($ldcustomer)) {
            $ldcustomer = Customer::create([
                'uuid' => (string) Str::uuid(),
                'customer_id' => $customer->gbh_customer_id,
                'titlename' => $customer->titlename,
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'phone_no' => $customer->mobile,
                'nrc_no' => (int) $customer->nrc_no,
                'nrc_name' => (int) $customer->nrc_name,
                'nrc_short' => (int) $customer->nrc_short,
                'nrc_number' => (int) $customer->nrc_number,
                'passport' => $customer->passport,
                'email' => $customer->email,
                'phone_no_2' => $customer->homephone,
                'national_id' => $customer->nationlity_id,
                'member_no' => $customer->identification_card,
                'amphur_id' => $customer->amphur_id,
                'province_id' => $customer->province_id,
                'address' => $customer->full_address,
                'customer_no' => $customer->customer_barcode,
                'customer_type' => $member_type,
            ]);
        }
    } else {
        $ldcustomer = Customer::create([
            'uuid' => (string) Str::uuid(),
            'customer_id' => $customer->gbh_customer_id ?? '10547',
            'firstname' => $customer->firstname,
            'phone_no' => $customer->mobile,
            'customer_type' => 'New',
        ]);
    }
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
    $db_ext = getConnection($branch_id);

    $customerData = $db_ext->select("
        Select distinct return_product_doc_gbh_customer_id as gbh_cust_id, return_product_doc_ref_doc_id as sale_cash_document_id, 0 as total_net_amount
        FROM return_product.return_product_doc
        where return_product_doc_ref_docno in ('$invoice_no')
        Union all
        SELECT distinct gbh_customer_id as gbh_cust_id, aa.sale_cash_document_id, 0 as total_net_amount
        FROM sale_cash.sale_cash_document aa
        INNER JOIN sale_cash.sale_cash_items bb
        on aa.sale_cash_document_id= bb.sale_cash_document_id
        where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
        and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
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
    // dd($promotion);
    if ($promotion->diposit_type_id == 2) {
        $diposit_type_id = '2';
    } else if ($promotion->diposit_type_id == 3) {
        $diposit_type_id = '3';
    } else {
        $diposit_type_id = '2,3';
    }

    $invoice_nos = implode("','", $invoice_nos);
    //Check By Amount or By Product
    if ($invoice_check_type == 1) {
        if ($check_gold_ring == 1) {
            // 1 for Gold Ring today,
            //Check All brand
            if ($l_brands[0] == 0) {
                //check Normal
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
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                        and aa.sale_cash_document_no in ('$invoice_nos')
                        and sale_cash_document_datenow::date >= '$promotion->start_date'
                        and sale_cash_document_datenow::date <= '$promotion->end_date'
                        and sale_cash_document_datenow = now()::date
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
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                            and aa.sale_cash_document_no in ('$invoice_nos')
                        and depositdate::date between '$promotion->start_date' and '$promotion->end_date'
                        and  depositdate = now()::date
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

            }
            else {

                //check Normal
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
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                        and aa.sale_cash_document_no in ('$invoice_nos')
                        and sale_cash_document_datenow::date >= '$promotion->start_date'
                        and sale_cash_document_datenow::date <= '$promotion->end_date'
                        and  sale_cash_document_datenow = now()::date
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
                            inner join sale_cash.sale_cash_items bb 			on aa.sale_cash_document_id= bb.sale_cash_document_id
                            inner join sale_cash.sale_cash_document_status cc		on aa. sale_cash_document_status_id = cc. sale_cash_document_status_id
                            inner join sale_cash.sale_cash_document_type dd			on aa.sale_cash_document_type_id= dd.sale_cash_document_type_id

                           inner join (select distinct(sale_cash_document_no)
                                            ,min(pledge_document_datenow::date) as depositdate
                                       from pledge.pledge_document_ref_log
                                      group by sale_cash_document_no) as deposalereftable	on  aa.sale_cash_document_no= deposalereftable.sale_cash_document_no
                            where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
                            and aa.sale_cash_document_no in ('$invoice_nos')
                        and depositdate::date between '$promotion->start_date' and '$promotion->end_date'
                        and depositdate = now()::date
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
        }
        else {
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
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
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
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
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
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
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
                            inner join sale_cash.sale_cash_items bb 			on aa.sale_cash_document_id= bb.sale_cash_document_id
                            inner join sale_cash.sale_cash_document_status cc		on aa. sale_cash_document_status_id = cc. sale_cash_document_status_id
                            inner join sale_cash.sale_cash_document_type dd			on aa.sale_cash_document_type_id= dd.sale_cash_document_type_id

                        inner join (select distinct(sale_cash_document_no)
                                            ,min(pledge_document_datenow::date) as depositdate
                                    from pledge.pledge_document_ref_log
                                    group by sale_cash_document_no) as deposalereftable	on  aa.sale_cash_document_no= deposalereftable.sale_cash_document_no
                            where aa.sale_cash_document_type_id in (1,5) and aa.sale_cash_document_status_id in ('1')
                            and  (bb.barcode_code not ilike 'GP%' or sale_price <='0')
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

        }

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
