
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    <style>
       .page {
          page-break-after:always;
        }
        .last-page{
          width: 105px;
          height: 148px;
          overflow: hidden;
          font-family: Arial, Helvetica;
          position: relative;
          color: #545554;
        }
        .title{
          font-size : 15px;
          text-align :center;
          text-align: center !important;
          border-radius: 0px !important;
          border: 1px solid #000;
          padding-top:10px;
          padding-bottom :10px;
        }
        .ticket_no{
          font-size : 12px;
          left:1px;
          text-align :right;
          padding-right: 4px;
          position: absolute;
        }
        h6,h3{
          size: 10px;
          font-weight: normal;
        }
        .text-center{
          font-size: 15px !important;
        }
        .a {
          /* text-align :center; */
          text-align: center !important;
        }
        .desciption-mm {
          font-size: 12px;
          /* padding-bottom: 2px !important; */
        }
        .desciption-en{
          font-size: 12px;
        }
        .customer_info{
          font-size: 18px;
        }
        .column {
          
          float: left;
          /* width: 25.13%; */
          width: 29.13%;
          padding: 5px;
        
        }
        .date {
          -webkit-clip-path:polygon(0 0, 100% 35%, 100% 65%, 0% 100%);
          clip-path:polygon(0 0, 100% 35%, 100% 65%, 0% 100%);
          width:200px;
          height:200px;
          background:#E0E6E5;
          }
          .container {
            position: relative;
            text-align: center;
            /* color: white; */
          }
          .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
          }
          .barcode {
            text-align: right;
            width: 150px;
            height: 20px;
    }
    </style>
  </head>
  <body>
    <div class="page">
        <!-- <table style="">
           <tr>
              <td style="width: 150px;">
              <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" class="img-fluid image-right" alt="" style="width: 25%; margin-bottom: 10%;">
              </td>
                  <td style="width: 1px;">
              </td>
              <td style="text-align:right; width: 450px; height:50px;">
                  <h6><img src="{{ asset('images/date.png') }}" class="img-fluid image-right" alt="" style="width: 25%; margin-bottom: 10%;"></h6>
                <div class="centered">Centered</div> -->
                  <!-- <h6 style="ctx.strokeStyle = #b668ff;ctx.lineWidth = 4;">Date : 29/07/2022 </h6>
                  <h6>No : HBGLDPLAN1 </h6> -->
              <!-- </td>
           </tr>
            <tr class="a">
              <td style="width: 150px;">
              </td>
              <td style="">
                <h6 class=""></h6>
              </td>
              <td style="width: 1px;">
                  <h6 style="font-size:60px; font-style: oblique;">Gold</h6>
              </td>
              <td style="width: 1px">
              </td>
            </tr> -->
        <!-- </table> --> 
        <!-- <div class="row"> -->
        <div class="column" style="">
              <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" class="img-fluid image-right" alt="" style="width: 65%; margin-bottom: 10%;">
            </div>
            <div class="column">
            </div>
            <div class="container column">
            <img src="{{ asset('images/date.png') }}" class="img-fluid image-right" alt="" style="width: 65%;padding-left:50px;">
              <!-- <div class="centered">29/07/2022</div>   -->
            </div>
            <div class="column">
              </div>
              <div class="column">
              <p style="font-size:20px;">Gold</p>
            </div>
            <div class="column">
            </div>
            <p style="font-size:10px; padding-left:70px;">"ကူပွန်၏ စည်းမျဉ်းစည်းကမ်းချက်များ"</p>
            
            <!-- <div class="column">
              <p style="font-size:10px; padding-left:80px;">ကူပွန်၏</p>
            </div>
            <div class="column">
            <p style="font-size:10px; padding-right:70px;">စည်းကမ်းချက်များ</p>

            </div>
            <div class="column">
            </div> -->
            <div class="column">
              <p style="font-size:6px;">(၁)Cash Coupon တစ်ခုလျှင် Invoice တစ်ခုတည်းအတွက်သာအသုံးပြုခွင့်ရှိပါသည်။</p>
              <p style="font-size:6px;">(၂)Coupon ကိုငွေသားဖြင့်လဲလှယ်ခွင့်မပြုပါ။</p>
              <p style="font-size:6px;">(၃)Coupon ကို Promotion Items များဝယ်ယူရာတွင်အသုံးပြုနိုင်ပါသည်။</p>
              <p style="font-size:6px;">(၄)Coupon သက်တမ်းမှာ(၁)လဖြစ်ပါသည်။</p>
              <p style="font-size:6px;">(၅)Coupon ဖြင့်ဝယ်ယူထားသော Items မှာ Return ပြန်ခွင့်မရှိပါ။</p>
            </div>
            <div class="column">
              <p style="font-size:6px;">(၆)Coupon တစ်စောင်လျှင်တစ်ကြိမ်သာ အသုံးပြုခွင့်ရှိပါသဖြင့် Coupon ၏ ငွေပမာဏအနည်းဆုံးပြန်လည်အသုံးပြုရပါမည်။</p>
              <p style="font-size:6px;">(၇)ရေစိုစုတ်ပြဲမထင်ရှားသောကူပွန်နှင့်၊ ပျောက်ဆုံးပျက်စီးသွားသောကူပွန်များအတွက် အသုံးပြုခွင့်ပေးမည်မဟုတ်ပါ။</p>
              <p style="font-size:6px;">(၈)ကူပွန်ဖြင့်ဝယ်ယူထားသောပစ္စည်းအတွက် ကူပွန်နှင့်အခြား Promotion များအကျိုးခံစားခွင့်ထပ်မံမရရှိပါ။</p>
              <p style="font-size:6px;">(၉)Clearance Sale Item/Structure Item/Mobile Phone နှင့်မော်တော်ဆိုင်ကယ်များအတွက် အကျိုးခံစားခွင့်မရှိပါ။</p>
            </div>
            <div class="column" style="padding-top:100px;">
              <p style="font-size:5px;">
              @php
              $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
              @endphp
                    <img class="barcode"
                       src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('haha', $generatorPNG::TYPE_CODE_128)) }}"></p>
              <p style="font-size:7px; text-align:center;">GP171229-000004</p>
            </div>
          <!-- </div> -->
          </div>
          <!-- <p style="font-size:10px; text-align:left; padding-left:80px;">ကူပွန်၏ စည်းကမ်းချက်များ</p> -->
          <!-- <div class="column">
              <p style="font-size:6px;">(၁)Cash Coupon တစ်ခုလျှင် Invoice တစ်ခုတည်းအတွက်သာအသုံးပြုခွင့်ရှိပါသည်။</p>
              <p style="font-size:6px;">(၂)Coupon ကိုငွေသားဖြင့်လဲလှယ်ခွင့်မပြုပါ။</p>
              <p style="font-size:6px;">(၃)Coupon ကို Promotion Items များဝယ်ယူရာတွင်အသုံးပြုနိုင်ပါသည်။</p>
              <p style="font-size:6px;">(၄)Coupon သက်တမ်းမှာ(၁)လဖြစ်ပါသည်။</p>
              <p style="font-size:6px;">(၅)Coupon ဖြင့်ဝယ်ယူထားသော Items မှာ Return ပြန်ခွင့်မရှိပါ။</p>
            </div>
            <div class="column">
              <p style="font-size:6px;">(၆)Coupon တစ်စောင်လျှင်တစ်ကြိမ်သာ အသုံးပြုခွင့်ရှိပါသဖြင့် Coupon ၏ ငွေပမာဏအနည်းဆုံးပြန်လည်အသုံးပြုရပါမည်။</p>
              <p style="font-size:6px;">(၇)ရေစိုစုတ်ပြဲမထင်ရှားသောကူပွန်နှင့်၊ ပျောက်ဆုံးပျက်စီးသွားသောကူပွန်များအတွက် အသုံးပြုခွင့်ပေးမည်မဟုတ်ပါ။</p>
              <p style="font-size:6px;">(၈)ကူပွန်ဖြင့်ဝယ်ယူထားသောပစ္စည်းအတွက် ကူပွန်နှင့်အခြား Promotion များအကျိုးခံစားခွင့်ထပ်မံမရရှိပါ။</p>
              <p style="font-size:6px;">(၉)Clearance Sale Item/Structure Item/Mobile Phone နှင့်မော်တော်ဆိုင်ကယ်များအတွက် အကျိုးခံစားခွင့်မရှိပါ။</p>
            </div>
            <div class="column" style="padding-top:100px;">
              <p style="font-size:5px;">ABCDEFGHIJKLMNOABCDEFGHIJKLMNO</p>
              <p style="font-size:5px;">GP171229-000004</p>
            </div>
          </div> -->
    <div class="last-page">
    </div>
    <script>
      let canvas = document.getElementById('date');
      let ctx = canvas.getContext('2d');

      ctx.moveTo(20, 0);
      ctx.lineTo(40, 30);
      ctx.lineTo(0, 30);
      ctx.lineTo(20, 0);
      ctx.fillStyle = '#b668ff';
      ctx.fill();
    </script>
    </body>
    </html>
