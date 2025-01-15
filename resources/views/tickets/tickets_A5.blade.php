<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
       .page {
          page-break-after:always;
          margin: 115px 115px 200px 200px;
        }
        .last-page{
          width: 612px;
          height: 792px;
          overflow: hidden;
          font-family: Arial, Helvetica;
          position: relative;
          color: #545554;
        }
        .title {
          text-align: center !important;
          border-radius: 15px !important;
        	margin: 1em 20px;
          padding: 10px;
          border: 1px solid #000;
        }
        .text-center {
            text-align: center !important;
            padding-top:10px;
            font-weight: strong;
        }
        .text-center h6{
           color:red;
        }
        h6 {
          font-weight: normal;
        }
        h5 {
          font-weight: normal;
        }
        h4 {
          font-weight: normal;
        }
        h3{
          font-weight: normal;
        }
        .desciption-en {
          font-size: 15px;
        }
        .desciption-mm {
          font-size: 15px;
        }
        .promo-image{
          position: absolute;
          bottom: 0;
          left: 0;
          right: 0;
          height: 50px;
          padding-top:20px;
        }
        td {
          vertical-align: top;
          text-align: left;
        }
    </style>
  </head>
  <body>
    <div class="page">
        <table>
          <tr>
            <td  colspan="3" class="title">
                <h3>{{$customers['promotion_name']}}</h3>
                <h3> Lucky Draw Ticket</h3>
            </td>
          </tr>
          <tr>
            <td style="width: 150px;">
            </td>
            <td style="width: 1px;">
            </td>
            <td style="text-align:right; width: 450px; height:50px">
                <h6>Ticket No : {{ $customers['ticket_nos'] }}</h6>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>Name</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td style="width: 1px">
                 <h4>{{$customers['customer_name']}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>NRC</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
                <h4>{{$customers['nrc']}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
              <h4>Phone No</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
                <h4>{{$customers['phone_no']}}{{isset($customers['phone_no_2']) ? ', ' . $customers['phone_no_2'] : ''}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
                <h4>Date</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
              <h4>{{$customers['date']}}</h4>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:40px">
                <h4>Invoice No.</h4>
            </td>
            <td style="width: 1px;">
            <h4>:</h4>
              </td>
            <td>
                <h5>{{$customers['invoice_no']}}</h5>
            </td>
          </tr>
        </table>
        <table>
          <tr >
              <td colspan="2" class="text-center">
                  <h6>ကူပွန်၏ စည်းကမ်းချက်များ</h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-mm" style="height:25px">
                  <h6>- ငွေသားဖြင့် လဲလှယ်၍ မရပါ။<h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-en" style="height:25px">
                  <h6>&nbsp;&nbsp;This coupon is not redeemable for cash or cheque.<h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-mm" style="height:25px">
                  <h6>- ရေစိုစုတ်ပြဲ၊ ပျောက်ဆုံး၊ ပျက်စီးသွားသော ကူပွန်များ လုံးဝ အကျုံးမဝင်ပါ။<h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-en" style="height:25px">
                  <h6>&nbsp;&nbsp;This coupon is not replaceable if lost or damaged.<h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-mm" style="height:25px;line-height: 2.0;">
                  <h6>- တစ်စုံတစ်ရာ အငြင်းပွါးမှုဖြစ်ပေါ်လာပါက PRO 1 Global Home Center ၏ ဆုံးဖြတ်ချက်သာ &nbsp;&nbsp;အတည်ဖြစ်ပါသည်။<h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-en" style="height:25px">
                  <h6>&nbsp;&nbsp;If have any dispute, the decision of PRO 1 Global Home Center is final.<h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-mm" style="height:25px">
                  <h6>- ကံစမ်းမဲပေါက်လျှင် တင်ပြနိုင်ရန် ဤကူပွန်ကို သိမ်းထားပေးပါ။<h6>
              </td>
          </tr>
          <tr >
              <td colspan="2" class="desciption-en" style="height:25px">
                  <h6>&nbsp;&nbsp;Please keep this part to claim your prize.<h6>
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
        <table style="mergin-botton:100px">
          <tr>
              <td  colspan="3" class="title">
                <h3>{{$customers['promotion_name']}}</h3>
                <h3> Lucky Draw Ticket</h3>
              </td>
            </tr>
            <tr>
                <td style="width: 150px;">
                </td>
                <td style="width: 1px;">
                </td>
                <td style="text-align:right; width: 450px; height:50px">
                    <h5>Ticket No : {{$d['ticket_no']}}</h5>
                </td>
            </tr>
          <tr>
            <td style="width: 150px; height:90px">
              <h3>Name</h3>
            </td>
            <td style="width: 1px;">
              <h3>:</h3>
            </td>
            <td style="width: 1px">
              <h3>{{$d['customer_name']}}</h3>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:90px">
              <h3>NRC</h3>
            </td>
            <td style="width: 1px;">
              <h3>:</h3>
            </td>
            <td>
              <h3>{{$d['nrc']}}</h3>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:90px">
              <h3>Phone No</h3>
            </td>
            <td style="width: 1px;">
              <h3>:</h3>
            </td>
            <td>
              <h3>{{$d['phone_no']}}{{isset($d['phone_no_2']) ? ', ' . $d['phone_no_2'] : ''}}</h3>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:90px">
              <h3>Township</h3>
            </td>
            <td style="width: 1px;">
              <h3>:</h3>
            </td>
            <td>
              <h3>{{$d['township']}}</h3>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:90px">
              <h3>Region</h3>
            </td>
            <td style="width: 1px;">
              <h3>:</h3>
            </td>
            <td>
              <h3>{{$d['region']}}</h3>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:90px">
              <h3>Date</h3>
            </td>
            <td style="width: 1px;">
              <h3>:</h3>
            </td>
            <td>
              <h3>{{$d['date']}}</h3>
            </td>
          </tr>
          <tr>
            <td style="width: 150px; height:90px">
              <h3>Invoice No</h3>
            </td>
            <td style="width: 1px;">
              <h3>:</h3>
            </td>
            <td>
              @if(count($data) > 5)
              <h5>{{$d['invoice_no']}}</h5>
              @else
              <h4>{{$d['invoice_no']}}</h4>
              @endif
            </td>
          </tr>

        </table>
      </div>
        @endforeach
    </body></html>
