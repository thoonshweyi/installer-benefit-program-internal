<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Tangerine&display=swap" rel="stylesheet" />
<style>
                .page {
                    page-break-after: always;
                }

                .last-page {
                    width: 105px;
                    height: 148px;
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

                .a {
                    /* text-align :center; */
                    text-align: center !important;
                }

                .desciption-mm {
                    font-size: 12px;
                    /* padding-bottom: 2px !important; */
                }

                .desciption-en {
                    font-size: 12px;
                }

                .customer_info {
                    font-size: 18px;
                }

                .column {
                    float: left;
                    /* width: 25.13%; */
                    width: 29.13%;
                    padding: 5px;

                }

                .date {
                    -webkit-clip-path: polygon(0 0, 100% 35%, 100% 65%, 0% 100%);
                    clip-path: polygon(0 0, 100% 35%, 100% 65%, 0% 100%);
                    width: 200px;
                    height: 200px;
                    background: #E0E6E5;
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

                .radious {
                    border-radius: 55px 5px 5px 55px;
                }
                .second_div{
                float: left;
                    /* width: 25.13%; */
                    width: 49.13%;
                    /* padding: 5px; */
                }
                @font-face {
            font-family: 'u4b00003';
            src: url('../fonts/u4b00003.ttf")}}') format('truetype');
            }
            .mm {
            font-family: 'u4b00003';
            }
</style>
</head>

<body>
    <div class="page">
        <div class="column" style="">
            <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" class="img-fluid image-right" alt=""
                style="width: 65%; margin-bottom: 10%;">
        </div>
        <div class="column">
        </div>
        <div class="container column" style="padding-left:40px;">
            <img src="{{ asset('images/date.png') }}" class="img-fluid image-right" alt=""
                style="width: 85%;">
        </div>
        <div class="second_div radious">
            {{-- <p style="font-size:23px;text-align:center;font-style: oblique;">Cash <br> Coupon</p> --}}
            <p class="mm">Cash Coupon1</p>

        </div>
        <div class="second_div radious" style="background-color:black;">
            <p style="color:white;text-align:center;font-size:30px;">3,000,000<br>MMK</p>
        </div>
        <p style="font-size:10px; padding-left:70px;padding-top:-50px;">"ကူပွန်၏ စည်းမျဉ်းစည်းကမ်းချက်များ"</p>
        <div class="column">
            <p style="font-size:6px;">(၁)Cash Coupon တစ်ခုလျှင် Invoice တစ်ခုတည်းအတွက်သာအသုံးပြုခွင့်ရှိပါသည်။</p>
            <p style="font-size:6px;">(၂)Coupon ကိုငွေသားဖြင့်လဲလှယ်ခွင့်မပြုပါ။</p>
            <p style="font-size:6px;">(၃)Coupon ကို Promotion Items များဝယ်ယူရာတွင်အသုံးပြုနိုင်ပါသည်။</p>
            <p style="font-size:6px;">(၄)Coupon သက်တမ်းမှာ(၁)လဖြစ်ပါသည်။</p>
            <p style="font-size:6px;">(၅)Coupon ဖြင့်ဝယ်ယူထားသော Items မှာ Return ပြန်ခွင့်မရှိပါ။</p>
        </div>
        <div class="column">
            <p style="font-size:6px;">(၆)Coupon တစ်စောင်လျှင်တစ်ကြိမ်သာ အသုံးပြုခွင့်ရှိပါသဖြင့် Coupon ၏
                ငွေပမာဏအနည်းဆုံးပြန်လည်အသုံးပြုရပါမည်။</p>
            <p style="font-size:6px;">(၇)ရေစိုစုတ်ပြဲမထင်ရှားသောကူပွန်နှင့်၊ ပျောက်ဆုံးပျက်စီးသွားသောကူပွန်များအတွက်
                အသုံးပြုခွင့်ပေးမည်မဟုတ်ပါ။</p>
            <p style="font-size:6px;">(၈)ကူပွန်ဖြင့်ဝယ်ယူထားသောပစ္စည်းအတွက် ကူပွန်နှင့်အခြား Promotion
                များအကျိုးခံစားခွင့်ထပ်မံမရရှိပါ။</p>
            <p style="font-size:6px;">(၉)Clearance Sale Item/Structure Item/Mobile Phone နှင့်မော်တော်ဆိုင်ကယ်များအတွက်
                အကျိုးခံစားခွင့်မရှိပါ။</p>
        </div>
        <div class="column" style="padding-top:20px;">
            <p style="font-size:5px;">
                @php
                $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                @endphp
                <img class="barcode"
                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode('hahahoehoe', $generatorPNG::TYPE_CODE_128)) }}">
            </p>
            <p style="font-size:7px; text-align:center;">GP171229-000004</p>
        </div>
        <!-- </div> -->
    </div>
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
