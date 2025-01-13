@extends('layouts.app')


@section('content')
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">FAQ Lists</h4>
                    </div>
                </div>
            </div>
            {{-- {{ dd(request()->query() ) }} --}}
            <div class="col-lg-12 mb-2">
                {{-- <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Accordion Item #1
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Accordion Item #2
                        </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Accordion Item #3
                        </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                        </div>
                        </div>
                    </div>
                </div> --}}



                {{-- <div id="accordion">
                    <!-- First Card -->
                    <div class="card">
                      <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                          <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Accordion Item #1
                          </button>
                        </h5>
                      </div>
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                          This is the content of the first accordion item.
                        </div>
                      </div>
                    </div>
                    <!-- Second Card -->
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Accordion Item #2
                          </button>
                        </h5>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                          This is the content of the second accordion item.
                        </div>
                      </div>
                    </div>
                    <!-- Third Card -->
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Accordion Item #3
                          </button>
                        </h5>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                          This is the content of the third accordion item.
                        </div>
                      </div>
                    </div>
                  </div>
                </div> --}}


                <div id="accordion">
                    <!-- Accordion Item #1 -->
                    <div class="card">
                      <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5 class="mb-0">
                            Point သက်တမ်းဘယ်လောက်ရှိပါသလဲ။
                        </h5>
                        <i class="fas fa-chevron-down"></i>
                      </div>
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                          <strong>Point စုခဲ့သည့်နေ့မှ ၆လ အတွင်း အသုံးပြုနိုင်ပါသည်။ </strong> သက်တမ်းကျော်လွန်နေသည့်point များကို အသုံးပြု၍ မရနိုင်တော့ပါ။
                        </div>
                      </div>
                    </div>

                    <div class="card">
                        <div class="card-header collapsed" id="headingThree" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseThree">
                          <h5 class="mb-0">
                                Member point ရော Installer pointရောရမှာလား။
                          </h5>
                          <i class="fas fa-chevron-down"></i>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                          <div class="card-body">
                            <strong>ဖောက်သည်သည် member/installer အကျိုးခံစားခွင့်နှစ်ခုလုံးရရှိပါမည်။</strong>
                          </div>
                        </div>
                    </div>

                    <!-- Accordion Item #2 -->
                    <div class="card">
                      <div class="card-header collapsed" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h5 class="mb-0">
                            Sale Invoiceအားလုံး စကင် ဖတ်၍ရနိုင်ပါသလား။
                        </h5>
                        <i class="fas fa-chevron-down"></i>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                          <strong>ဖောက်သည်သည် တစ်ပတ်အတွင်းဝယ်ယူခဲ့သည့် ဘောင်ချာများကိုသာ point စု၍ရနိုင်ပါသည်။</strong>
                        </div>
                      </div>
                    </div>
                    <!-- Accordion Item #3 -->
                    <div class="card">
                      <div class="card-header collapsed" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h5 class="mb-0">
                            ပွိုင့်စုသည့်အခါ (သို့) ပွိုင့်ထုတ်သည့်အခါ ဘာတွေလိုအပ်မလဲ။
                        </h5>
                        <i class="fas fa-chevron-down"></i>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                          <strong>ဖောက်သည်သည် ကတ်ပြနိုင်မှသာ ရှေ့လုပ်ငန်းစဉ်များ လုပ်ဆောင်၍ ရပါမည်။</strong> ပွိုင့်ထုတ်သည့်အခါ မှတ်ပုံတင်ပြရန်လိုအပ်ပါသည်။
                        </div>
                      </div>
                    </div>

                    <div class="card">
                        <div class="card-header collapsed" id="headingThree" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                          <h5 class="mb-0">
                                Returnပြန်သည့် အခါ point ဘယ်လို ပြန်နုတ်ပါသလဲ။
                          </h5>
                          <i class="fas fa-chevron-down"></i>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                          <div class="card-body">
                            <ol>
                                <li>No use (အသုံးမပြုရသေး)</li>
                                <li>Pre Use (ကြိုသုံး)
                                    <ul>
                                        <li>လက်ကျန်ရှိနေလျှင် ထိုလက်ကျန်ထဲမှနုတ်ပါမည်</li>
                                        <li>လက်ကျန်မရှိလျှင်နောက်တစ်ခါ ပွိုင့်လာစုရင်နုတ်ပါမည်</li>
                                    </ul>
                                </li>
                            </ol>
                          </div>
                        </div>
                      </div>
                </div>




        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('.delete-btns').click(function(e){
            e.stopPropagation();
            {{-- console.log('hay'); --}}

            Swal.fire({
                title: "Are you sure you want to delete collection transaction?",
                text: "All the collected will be removed recursively.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, send it!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $(this).closest('form').submit();
                }
            });
        });

    });
    function changeHandler(input){
        if(input.value){
            input.type = 'date'
        }else{
            input.type = 'text'
            input.blur();
        }
    }

    // Start Clear btn
    document.getElementById("btn-clear").addEventListener("click",function(){
        window.location.href = window.location.href.split("?")[0];
   });
   // End Clear btn
</script>
@stop
