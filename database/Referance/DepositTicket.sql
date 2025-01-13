--Deposit Ticket > If there had saleno, can't scan depositno.

select  distinct(depotable.pledge_document_docno) as DepositNo
            , deposalereftable.pledge_document_docno as Depositlogno, ref_docno as ReturnNo
            ,depotable.sale_command_document_no as SONo
            ,depotable.pledge_document_datenow::date
            ,sum(price_sale_amount)::numeric(19,2) as so_amount
            ,gbh_customer_id
            ,depotable.pledge_document_id
            from pledge.pledge_document as depotable
            left join pledge.pledge_document_ref_log as deposalereftable on depotable.pledge_document_docno= deposalereftable.pledge_document_docno
            Left join sale_command.sale_command_item as sale on depotable.sale_command_document_no=sale.sale_command_document_no
            left join --public.temp_master_product
            (
                Select *
                from
                (
                    Select barcode_code, barcode_bill_name, Case when good_brand_id is null then 0 else good_brand_id end as good_brand_id,
                    Case when good_brand_name = '' or good_brand_name is null then 'No Brand' else good_brand_name end as  good_brand_name
                    , category_id, diposit_type_id , remark
                    from public.temp_master_product  pro
                    Left Join public.master_goods_category as cate	on pro.category_id=cate.product_category_id

                    Union All

                    Select product_code as barcode_code, product_name1 as barcode_bill_name, 0 as good_brand_id, 'No Brand' as good_brand_name, cc.product_category_id  as category_id
                    , Case when product_category_code in ('01-CB', '02-ST', '03-RT') then 2
                    else 3 end as diposit_type_id , remark
                    from public.cancelled_code cc
                    Left Join public.master_goods_category as cate	on cc.product_category_id=cate.product_category_id
                )tab
            )as pro on sale.barcode_code=pro.barcode_code
            Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
            where pledge_document_status_id not in (1,6)
            and depotable.sale_command_document_no <> ''
            and depotable.pledge_document_datenow::date >'2022-01-01'
            and  (sale.barcode_code not ilike 'GP%' or price_sale ::numeric(19,2)<='0')
            and pro.diposit_type_id in ($diposit_type_id)
            and category_id in ($luckydraw_categories)
            and good_brand_id in ($luckydraw_brands)
            and depotable.pledge_document_docno in('$invoice_no')
            group by depotable.pledge_document_docno, deposalereftable.pledge_document_docno, ref_docno, depotable.sale_command_document_no
            ,depotable.pledge_document_datenow::date,gbh_customer_id, depotable.pledge_document_id

