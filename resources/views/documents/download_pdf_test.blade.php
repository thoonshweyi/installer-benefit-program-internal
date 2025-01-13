<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body{
            margin:10px;
        }
        .title img {
            width:25%;
        }
        table {
            width: 100%;
            text-align: left;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        table tr.top table td {
            padding-bottom: 2px;
            border-bottom: 1px solid #eee;
        }
        table tr.header {
            margin-top: 1px;
        }
        table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        table tr.heading td {
            border-bottom: 1px solid #ddd;
            border-top: 1px solid #ddd;
            font-weight: bold;
            font-size: 13px;
        }
        table tr.information td {
            font-size: 12px;
        }
        table tr.details td {
            font-size: 12px;
        }

        .text-center {
            text-align: center !important;
            border-bottom: 1px solid #eee;
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
        }

        h4 {
            font-size: 12px;
            font-weight: normal;
        }


        .tab {
            display: inline-block;
        }

        footer {
            font-size: 12px;
            position: fixed;
            bottom: 10;
            text-align: right;
            
        }
        .remark_place {
            font-size: 12px;
            border-top: 1px solid #eee;
            position: fixed; 
            bottom: 150;
        }
        .sign_place {
            font-size: 12px;
        
            border-top: 1px solid #eee;
            position: fixed;
            bottom: 100;
        }

        .sign_area {
            font-size: 14px;
            margin-top: 2px;
            line-height: 10px;
        }
    </style>
</head>
<body>
    <footer>
        <p>Print By : {{isset($document->rg_out)? $document->rg_out->name : ''}} | Print On : {{ now(); }}</p>
    </footer>
    <div>
        <table>
            <tr class="top">
                <table>
                    <tr>
                        <td class="title">
                            @php use App\Http\Controllers\DocumentController; @endphp
                            <img src="{{DocumentController::imagenABase64('images/PRO-1-Global-Logo.png')}}" >
                        </td>
                        <td style="text-align:right; width: 450px;">
                            <h4>PRO 1 GLOBAL COMPANY LIMITED ({{ isset($document->branch_id) ? $document->branches->branch_short_name: '-'}})<br>
                            {{ isset($document->branch_id) ? $document->branches->branch_address: '-' }}
                            <br>
                            Tel. 01-9640100, 9640110, 647730, 644832
                            </h4>
                        </td>
                    </tr>
                </table>
            </tr>
        </table>
        <table>
            <tr class="header">
                <td colspan="5" class="text-center">
                    <h2>{{ $title }}<h2>
                </td>
            </tr>
        </table>
        <table>
            <tr >
                <table>
                    <tr class="information">
                        <td style="text-align: left;  width: 17%;">
                            Supplier Name:
                        </td>
                        <td style="text-align: left; width: 30%;">
                            {{isset($document->suppliers) ? $document->suppliers->supplier_name : ''}}
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
                            {{isset($document->suppliers) ? $document->suppliers->supplier_code : ''}}
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
            </tr>
        </table>
        <table>
            <tr class="heading">
                <td style="width: 10px;">No</td>
                <td>Product Code</td>
                <td style="width: 350px;">Product Name</td>
                <td class="top">Unit</td>
                <td class="top">Qty</td>
                <td class="top" style="width: 100px;">PO/RG No</td>
                <!-- <td class="top">Remark</td> -->
            </tr>
            @php $i = 1;@endphp
            @foreach ($products as $product)
            <tr class="details">
                <td>{{ $i }}</td>
                <td>{{ $product->product_code_no }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_unit }}</td>
                <td >{{ $product->merchandising_actual_quantity }}</td>
                <td>{{ $product->rg_out_doc_no }}</td>
            </tr>
            @php $i++; @endphp
            @endforeach
        </table>

        <table class="remark_place">
            <tr>
                <td>Remark - {{ $document->merchandising_remark }}</td>
            </tr>

        </table>
        <table class="sign_place">
            <tr class="sign_area">
                <td colspan="2" style="text-align: center; ">
                    <strong>PRO 1 Global</strong>
                </td>
                <td style="width: 200px; text-align: center;">
                    <strong> Supplier </strong>
                </td>
            </tr>
            <tr>
                <td>
                    Sign
                </td>
                <td>
                    Sign
                </td>

                <td>
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
                    Received By :.......................
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
                    NRC :....................................
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td></td>
                </td>
                <td>
                    Phone No :............................
                </td>
            </tr>
        </table>
    </div>
    
</body>

</html>