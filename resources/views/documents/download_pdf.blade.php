<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        h4{
            font-weight: normal;
        }
        h2{
            text-decoration: underline;
        }
        body{
            margin:10px;
        }
        .header {
            border-bottom: 1px solid #eee;
        }
        img {
            width:250px;
        }
        .header-text {
            text-align:right; 
            width: 450px;
            font-size: 12px;
        }
        .title {
            text-align: center !important;
            margin-top: 10px;
            font-size: 16px;
            margin-bottom:10px;
        }
        table tr{
            border-top: 1px solid #eee;
        }
        table tr.information td {
            font-size: 12px;
        }
        table tr.heading td {
            border-bottom: 1px solid #ddd;
            border-top: 1px solid #ddd;
            font-size: 13px;
        }
        table tr.details td {
            font-size: 13px;
            line-height: 1.6;
        }
        .remark_place {
            position: fixed; 
            font-size: 12px;
            width: 750px;
            border-top: 1px solid #eee;
            line-height: 2;
        }
        .sign_place {
            font-size: 13px;
            border-top: 1px solid #eee;
            position: fixed;
            width: 750px;
            padding: 20px;
        }
        @page {
            footer: page-footer;
        }
        footer {
            position: fixed;
            font-size: 16px;
            bottom: 10px;
        }
    </style>
</head>
<body>
    <footer>
        <p>Print By : {{$user_name}} | Print On : {{ now(); }}</p>
    </footer>
    <div>
        <table class="header">
            <tr>
                <td class="header-image">
                    @php use App\Http\Controllers\DocumentController; @endphp
                    <img src="{{DocumentController::imagenABase64('images/PRO-1-Global-Logo.png')}}" >
                </td>
                <td class="header-text">
                    <h4>PRO 1 GLOBAL COMPANY LIMITED ({{ isset($document->branch_id) ? $document->branches->branch_short_name: '-'}})<br>
                    {{ isset($document->branch_id) ? $document->branches->branch_address1: '-' }}
                    <br>
                    Tel. 01-9640100, 9640110, 647730, 644832
                    </h4>
                </td>
            </tr>
        </table>
        <h2 class="title">{{ $title }}<h2>
        <table>
            <tr class="information">
                <td style="text-align: left;  width: 17%;">
                    Supplier Name:
                </td>
                <td style="text-align: left; width: 50%;">
                    {{isset($document->suppliers) ? $document->suppliers->vendor_name : ''}}
                </td>
                <td style="text-align: justify; width: 13%;">
                    
                </td>
                <td style="text-align: right; width: 15%;">
                    Doc No :
                </td>
                <td style="text-align: left; width: 25%;">
                    {{$document->document_no}}
                </td>
            </tr>
            <tr class="information">
                <td style="text-align: left;">
                    Supplier Code&emsp;:
                </td>
                <td style="text-align: justify;">
                    {{isset($document->suppliers) ? $document->suppliers->vendor_code : ''}}
                </td>
                <td style="text-align: justify;">
                    
                </td>
                <td style="text-align: right;">
                    Pickup Date:
                </td>
                <td style="text-align: left;">
                    {{ date('d-m-Y'); }}
                </td>
            </tr>
            <tr class="information">
                <td style="text-align: left;">
                    
                </td>
                <td style="text-align: justify;">    
                </td>
                <td style="text-align: justify;">
                </td>
                <td style="text-align: right;">
                    Car No:
                </td>
                <td style="text-align: left;">
                </td>
            </tr>
        </table>
        <table>
            <tr class="heading">
                <td style="width: 30px;">No</td>
                <td style="width: 120px;">Product Code</td>
                <td style="width: 350px;">Product Name</td>
                <td style="width: 50px;" class="top">Unit</td>
                <td style="width: 50px;" class="top">Qty</td>
                <td class="top" style="width: 200px;">PO/RG No</td>
            </tr>
            @php $i = 1;@endphp
            @foreach ($products as $product)
            <tr class="details">
                <td>{{ $i }}</td>
                <td>{{ $product->product_code_no }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_unit }}</td>
                <td >{{ $product->operation_rg_out_actual_quantity }}</td>
                <td>{{ $product->rg_out_doc_no }}</td>
            </tr>
            @php $i++; @endphp
            @endforeach
        </table>
        <htmlpagefooter name="page-footer">
            <table class="remark_place">
                <tr>
                    <td>Remark -{{$document->merchandising_remark }}</td>
                </tr>
            </table>
            <table class="sign_place">
                <tr>
                    <td colspan="2" style="text-align: center; font-size: 16px; ">
                        PRO 1 Global
                    </td>
                    <td style="text-align: center; font-size: 16px;">
                        Supplier
                    </td>
                </tr>
                <tr >
                    <td style="padding-top:50px; width:35%;">
                        Sign
                    </td>
                    <td style="padding-top:50px; width:35%;">
                        Sign
                    </td>
                    <td style="padding-top:50px; width:20%;">
                        Sign
                    </td>
                </tr>
                <tr>
                    <td>
                        Send By : {{$user_name}}
                    </td>
                    <td>
                        Approved By :{{isset($document->branch_manager) ? $document->branch_manager->name : ''}}
                    </td>

                    <td>
                        Received By : ..................................
                    </td>
                </tr>
                <tr>
                    <td>
                        Position : Operation
                    </td>
                    <td>
                        Position : Branch Manager
                    </td>

                    <td>
                        NRC : ..............................................
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td></td>
                    </td>
                    <td>
                        Phone No : ......................................
                    </td>
                </tr>
            </table>
        </htmlpagefooter>
        
    </div>
    
</body>

</html>