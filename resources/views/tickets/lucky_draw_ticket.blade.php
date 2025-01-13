<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
    .page {
        page-break-after: always;
        padding: 35px 35px 35px 35px;
    }
    .last-page {
        padding: 35px 35px 35px 35px;
        overflow: hidden;
        font-family: Arial, Helvetica;
        position: relative;
        color: #545554;
    }

    .title {
        font-size: 15px;
        text-align: center;
        text-align: center !important;
        border-radius: 0px !important;
        border: 1px solid #000;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .table_header {
        font-size: 15px;
        text-align: center;
        text-align: center !important;
        border-radius: 0px !important;
        border: 1px solid #000;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .ticket_no {
        font-size: 12px;
        left: 1px;
        text-align: right;
        padding-right: 4px;
        position: absolute;
    }
    h6,
    h3 {
        size: 10px;
        font-weight: normal;
    }

    .text-center {
        font-size: 15px !important;
    }

    .desciption-en {
        font-size: 22px;
        float: left;
        width: 25.13%;
        padding: 10px;
        text-align: left;
    }
    .customer_info {
        font-size: 16px;
    }
    .text {
        font-size: 10px;
    }

    </style>
</head>

<body>
    <div class="page">
        <table style="padding-bottom:80px;">
            <tr>
                <td colspan="3" class="table_header">
                    <h3></h3>
                    <h3>{{$customers['promotion_name']}} Lucky Draw Ticket</h3>
                </td>
            </tr>
            <tr>
                <td style="">
                </td>
                <td style="width: 1px;">
                </td>
                <td style="text-align:right; width: 450px; height:50px">
                    <h6>Ticket No : {{ $customers['ticket_nos'] }} </h6>
                </td>
            </tr>
            <tr class="a">
                <td style="padding-right:10px;">
                </td>
                <td style="width: 150px; height:20px;">
                    <h6 class="customer_info">Name</h6>
                </td>
                <td>
                    <h6>: {{$customers['customer_name']}}</h6>
                </td>
                <td style="width: 1px">
                    <h6></h6>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td style="width:150px; height:20px;">
                    <h6 class="customer_info">NRC</h6>
                </td>
                <td style="width: 1px;">
                    <h6 style="font-size:16px;">: {{$customers['nrc']}}</h6>
                </td>
                <td>
                    <h6></h6>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td style="width: 150px; height:20px">
                    <h6 class="customer_info">Phone No</h6>
                </td>
                <td style="width: 1px;">
                    <h6 style="font-size:16px;">: {{$customers['phone_no']}}
                    <!-- @if(isset($customers['phone_no_2']))
                    , {{$customers['phone_no_2']}}
                    @endif -->
                    <!-- {{isset($customers['phone_no_2']) ? ', ' . $customers['phone_no_2'] : ''}} -->
                </h6>
                </td>
                <td>
                    <h6></h6>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td style="width: 50px; height:20px">
                    <h6 class="customer_info">Date</h6>
                </td>
                <td style="width: 1px;">
                    <h6 style="font-size:16px;">: {{$customers['date']}}</h6>
                </td>
                <!-- <td style="width: 1px;">
                    <h6 style="font-size:16px;">: 30-04-2023</h6>
                </td> -->
                <td>
                    <h6></h6>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td style="width: 50px; height:20px">
                    <h6 class="customer_info">Invoice No.</h6>
                </td>
                <td style="width: 1px;">
                    <h6 style="font-size:16px;">: {{$customers['invoice_no']}}</h6>
                </td>
                <td>
                    <h6></h6>
                </td>
            </tr>
        </table>
        <table class="title">
            <tr>
                <td colspan="2" class="text-center" style="text-align:center">
                    <h6 calss="mya-font" style="font-size:10px;">ကူပွန်၏ စည်းကမ်းချက်များ</h6>
                </td>
            </tr>
            <tr>
                <td class="desciption-en">
                    <p class="text">- ငွေသားဖြင့် လဲလှယ်၍ မရပါ။</p>
                    <p class="text">- This coupon is not redeemable for cash or cheque.</p>
                    <p class="text">- ရေစိုစုတ်ပြဲ၊ ပျောက်ဆုံး၊ ပျက်စီးသွားသော ကူပွန်များ လုံးဝအကျုံးမဝင်ပါ။</p>
                    <p class="text">- This coupon is not replaceable if lost or damaged.</p>
                </td>
                <td class="desciption-en">
                    <p class="text">- တစ်စုံတစ်ရာ အငြင်းပွါးမှုဖြစ်ပေါ်လာပါက PRO 1 Global Home Center ၏
                        ဆုံးဖြတ်ချက်သာအတည်ဖြစ်ပါသည်။</p>
                    <p class="text">- If have any dispute, the decision of PRO 1 Global Home Center is final.</p>
                    <p class="text">- ကံစမ်းမဲပေါက်လျှင် တင်ပြနိုင်ရန် ဤကူပွန်ကို သိမ်းထားပေးပါ။</p>
                    <p class="text">- Please keep this part to claim your prize.</p>
                </td>
            </tr>
        </table>
    </div>
    @php
        $numItems = count($data);
        $i = 0;
    @endphp
    @foreach($data as $d)
        <div class={{(++$i === $numItems) ? "last-page" : "page"}}>
            <table style="">
                <tr>
                    <td colspan="3" class="table_header">
                        <h3></h3>
                        <h3>{{$customers['promotion_name']}}  Lucky Draw Ticket</h3>
                    </td>
                </tr>
                <tr>
                    <td style="">
                    </td>
                    <td style="width: 1px;">
                    </td>
                    <td style="text-align:right; width: 450px; height:50px">
                        <h6>Ticket No : {{ $d['ticket_no'] }} </h6>
                    </td>
                </tr>
                <tr>
                    <td style="padding-right:10px;">
                    </td>
                    <td style="width: 150px; height:50px;">
                        <h6 class="customer_info">Name</h6>
                    </td>
                    <td>
                        <h6>: {{$customers['customer_name']}}</h6>
                    </td>
                    <td style="width: 1px">
                        <h6></h6>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td style="width:150px; height:50px;">
                        <h6 class="customer_info">NRC</h6>
                    </td>
                    <td style="width: 1px;">
                        <h6 style="font-size:16px;">: {{$customers['nrc']}}</h6>
                    </td>
                    <td>
                        <h6></h6>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td style="width: 150px; height:50px">
                        <h6 class="customer_info">Phone No</h6>
                    </td>
                    <td style="width: 1px;">
                        <h6 style="font-size:16px;">: {{$customers['phone_no']}}
                        <!-- {{isset($customers['phone_no_2']) ? ', ' . $customers['phone_no_2'] : ''}} -->
                    </h6>
                    </td>
                    <td>
                        <h6></h6>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td style="width: 50px; height:50px">
                        <h6 class="customer_info">Date</h6>
                    </td>
                    <td style="width: 1px;">
                        <h6 style="font-size:16px;">: {{$customers['date']}}</h6>
                    </td>
                    <!-- <td style="width: 1px;">
                        <h6 style="font-size:16px;">: 30-04-2023</h6>
                    </td> -->
                    <td>
                        <h6></h6>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td style="width: 150px; height:50px;">
                        <h6 class="customer_info">Invoice No.</h6>
                    </td>
                    @if(count($data) > 5)
                        <td style="width: 1px;">
                            <h6 style="font-size:14px;">: {{$customers['invoice_no']}}</h6>
                        </td>
                    @else
                        <td style="width: 1px;">
                            <h6 style="font-size:16px;">: {{$customers['invoice_no']}}</h6>
                        </td>
                    @endif

                    <td>
                        <h6></h6>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
  </body>
  </html>
