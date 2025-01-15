

--Return Ticket > Search by Saleno with Return status
Select return_date, saleinvoice_no, gbh_customer_id, return_product_doc_ref_doc_id as sale_cash_document_id
            , return_product_doc_branchcode, return_product_doc_branchname,noreturnamnt
            from
            (
                select return_date, saleinvoice_no, gbh_customer_id
                , return_product_doc_branchcode, return_product_doc_branchname
                , sum(total) as noreturnamnt,return_product_doc_ref_doc_id
                from
                (
                    Select  return_date, saleinvoice_no, gbh_customer_id, return_product_item_barcode_codes, return_product_item_barcode_bill_name
                    , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, Cate_type,total,return_product_doc_ref_doc_id
                    from
                    (
                        Select
                        distinct(return_product_doc_ref_docno)  as saleinvoice_no
                        ,return_date,  return_product_doc_ref_docno, tt.return_product_doc_gbh_customer_id as gbh_customer_id
                        , return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name as Cate_type

                        ,sum(return_product_item_sale_price::Numeric(19,2)*return_product_item_sale_amount::Numeric(19,2)) as total,return_product_doc_ref_doc_id
                        from
                        (
                            select return_product_doc_datenow::date as return_date,
                            Case when return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                            else return_product_item_barcode_code end as return_product_item_barcode_codes, *
                            from return_product.return_product_doc bb
                            inner join return_product.return_product_item cc
                            on bb.return_product_doc_id= cc.return_product_doc_id
                            where return_product_doc_ref_docno in ('$invoice_no')
                            and return_product_item_barcode_code not in
                            (
                                select return_product_item_barcode_code
                                from return_product.return_product_doc bb
                                inner join return_product.return_product_item cc
                                on bb.return_product_doc_id= cc.return_product_doc_id
                                where return_product_doc_ref_docno in ('$invoice_no')
                                and cc.return_product_item_amount::numeric(19,2) <> '0'
                                and return_product_item_active = 't'
                            )and cc.return_product_item_amount::numeric(19,2) ='0'
                            and return_product_item_active <> 't'
                        )tt
                        Left Outer Join  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes and return_product_doc_branchcode=pro.branch_code
                        Left Outer Join public.master_goods_category as cate			on pro.category_id=cate.product_category_id
                        Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where
                        pro.diposit_type_id in ($diposit_type_id)
                        --and tt.return_product_item_barcode_codes not like '400502%'
                        group by return_product_doc_ref_docno,return_product_docno

                        ,return_date,  return_product_doc_ref_docno, return_product_doc_gbh_customer_id
                        , return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name
                        ,return_product_doc_ref_doc_id
                    )noreturn
                    Union All
                    Select return_date, saleinvoice_no,  gbh_customer_id,return_product_item_barcode_codes, return_product_item_barcode_bill_name
                    , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name as Cate_type, diff*returnprice as  total
                    ,return_product_doc_ref_doc_id
                    from
                    (
                        Select  return_date,  return_product_doc_ref_docno as saleinvoice_no, tt.gbh_customer_id,  return_product_item_sale_amount::numeric(19,2) as or
                        , return_product_item_sale_amount::numeric(19,2)-return_product_item_amount::numeric(19,2) as diff
                        , return_product_item_barcode_codes, tt.return_product_item_barcode_bill_name
                        , return_product_doc_branchcode, return_product_doc_branchname, good_brand_name, remark, diposit_type_name
                        , return_product_item_amount::numeric(19,2) as returnqty
                        ,return_product_item_sale_price::Numeric(19,2) as returnprice
                        ,return_product_doc_ref_doc_id
                        from
                        (
                            select return_product_doc_datenow::date as return_date, return_product_doc_gbh_customer_id as gbh_customer_id, Case when return_product_item_barcode_code ilike '%PRO%' then  substr(return_product_item_barcode_code,4, length(return_product_item_barcode_code) )
                                else return_product_item_barcode_code end as return_product_item_barcode_codes, *
                            from return_product.return_product_doc bb
                            inner join return_product.return_product_item cc
                            on bb.return_product_doc_id= cc.return_product_doc_id
                            where return_product_doc_ref_docno in ('$invoice_no')
                            and cc.return_product_item_amount::numeric(19,2) <> '0'
                            and return_product_item_active = 't'
                        )tt
                        Left Outer Join  public.temp_master_product  as pro on pro.barcode_code =tt.return_product_item_barcode_codes and return_product_doc_branchcode=pro.branch_code
                        Left Outer Join public.master_goods_category as cate			on pro.category_id=cate.product_category_id
                        Left JOin  public.diposit_type as dip on dip.diposit_type_id=pro.diposit_type_id
                        where pro.diposit_type_id in ($diposit_type_id)
			            --and tt.return_product_item_barcode_codes not like '400502%'
                    )return
                )returntable
                group by return_date, gbh_customer_id, saleinvoice_no
                , return_product_doc_branchcode, return_product_doc_branchname,return_product_doc_ref_doc_id
            )return
            where noreturnamnt::Numeric(19,0) <> '0'
