----////Normal///---
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
                and aa.sale_cash_document_no in ('$invoice_no')
                and sale_cash_document_datenow::date >= '$lucky_draw->start_date'
                and sale_cash_document_datenow::date <= '$lucky_draw->end_date'
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
            where  sale_cash_document_no1 in ('$invoice_no')
           and category_id in ($luckydraw_categories)
           and good_brand_id in ($luckydraw_brands)
           and pro.diposit_type_id in ($diposit_type_id)
           group by bb.sale_cash_document_datenow::date,voucher_value::numeric(19,2),sale_cash_document_no1,gbh_customer_id,sale_id
