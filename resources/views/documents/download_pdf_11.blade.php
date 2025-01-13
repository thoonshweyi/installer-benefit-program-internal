<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        .title img {
            width:25%;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        table tr.top table td {
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        header {
            padding-top: 1px;
            padding-bottom: 1px;
        }
        table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        table tr.information table td {
            padding-bottom: 40px;
            border-bottom: 1px solid #eee;
        }

        table tr.heading td {
            border-bottom: 1px solid #ddd;
            border-top: 1px solid #ddd;
            font-weight: bold;
        }


        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .text-center {
            text-align: center !important;
            border-bottom: 1px solid #eee;
        }

        h2 {
            font-size: 18px;
            margin-top: 5px;
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

        .sign_place {
            border-top: 1px solid #eee;
        }

        .sign_area {
            line-height: 100px;
        }
    </style>
</head>
<body>
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
            <tr>
                <table>
                    <tr>
                        <td colspan="5" class="text-center header">
                            <h2>{{ $title }}<h2>
                        </td>
                    </tr>
                    <tr>
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
                    <tr>
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
                    <tr>
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
                <td style="width: 200px;">Product Name</td>
                <td class="top">Unit</td>
                <td class="top" style="width: 50px;">Qty</td>
                <td class="top" style="width: 150px; text-align:center">PO/RG No</td>
                <!-- <td class="top">Remark</td> -->
            </tr>
            @php $i = 1;@endphp
            @foreach ($products as $product)
            <tr class="details">
                <td>{{ $i }}</td>
                <td>{{ $product->product_code_no }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_unit }}</td>
                <td style="width: 50px;">{{ $product->merchandising_actual_quantity }}</td>
                <td style="width: 150px; text-align:center">{{ $product->rg_out_doc_no }}</td>
            </tr>
            @php $i++; @endphp
            @endforeach
        </table>

        <table class="sign_place" style="position: fixed; bottom: 200;">
            <tr>
                <td colspan="7">Remark - {{ $document->merchandising_remark }}</td>
            </tr>

        </table>
        <table class="sign_place" style="position: fixed; bottom: 200;">
            <tr class="sign_area">
                <td colspan="2" style="text-align: center; font-size: 20px;"><strong>PRO 1 Global</strong>
                </td>
                <td style="width: 200px; text-align: center; font-size: 20px;">
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
                    Send By : {{isset($document->rg_out)? $document->rg_out->name : ''}}
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
    <footer>
        <p>Print By : {{isset($document->rg_out)? $document->rg_out->name : ''}} | Print On : {{ now(); }}</p>
    </footer>
</body>

</html>