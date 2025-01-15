<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>{{ config('app.name', 'Laravel') }}</title>

      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
      <link rel="stylesheet" href="{{ asset('css/backend-plugin.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/backend.css?v=1.0.0') }}">
      <link rel="stylesheet" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/remixicon/fonts/remixicon.css') }}">  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->

      <div class="wrapper">
      <section class="login-content">
         <div class="container">
            <div class="row align-items-center justify-content-center height-self-center">
               <div class="col-lg-8">
                <a onclick="openCloseFullscreen();" class="mr-2"><i class="ri-fullscreen-line"></i></a>
                  <div class="card auth-card">
                     <div class="card-body p-0">
                        <div class="d-flex align-items-center auth-content">
                           <div class="col-lg-7 align-self-center">
                              <div class="p-3">
                                 <h4 class="mb-2">Installer Benefit Program</h4>
                                 {{-- <p>Version 2</p> --}}
                                 <form action="{{ route('login') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                                <input class="floating-input form-control keyboardType0 jQKeyboard"  name="login_value" value="{{ old('login_value') }}" placeholder="" autofocus required>
                                                <label>Employee ID</label>
                                                @if($errors->has('login_value'))
                                                <div class="invalid-feedback  d-block">
                                                    <strong>{{ $errors->first('login_value') }}</strong>
                                                </div>
                                                @endif
                                          </div>

                                       </div>

                                       <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                                <input class="floating-input form-control keyboardType0 jQKeyboard" name="password" type="password" placeholder="" required>
                                                <label>Password</label>
                                                @if($errors->has('password'))
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                       </div>

                                       <div class="col-lg-6">
                                          <div class="custom-control custom-checkbox mb-3">
                                             <input type="checkbox" name="remember_me" class="custom-control-input" id="remember_me">
                                             <label class="custom-control-label control-label-1" for="remember_me">Remember Me</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <a href="{{ route('password.request') }}" class="text-primary float-right">Forgot Password?</a>
                                       </div>
                                   </div>
                                    <button type="submit" class="btn btn-primary">Sign In</button>


                                 </form>
                              </div>
                           </div>
                           <div class="col-lg-5 content-right">
                              <div class="row">
                                  <img src="{{ asset('images/PRO-1-Global-Logo.png') }}" class="img-fluid image-right" alt="" style="width: 50%; margin-bottom: 10%;">
                                  <img src="{{ asset('images/logo.png') }}" class="img-fluid image-right" alt="" style="width: 50px; height: 50px; margin-bottom: 10%; position: absolute;right: 0;margin-right: 20px;">
                                </div>
                                {{-- <img src="{{ asset('images/login/login-image.gif') }}" class="img-fluid image-right" alt=""> --}}
                                <img src="{{ asset('images/ibplogo.jpg') }}" class="img-fluid image-right" style="border-radius: 50px 15px" alt="">

                           </div>
                        </div>

                     </div>

                  </div>
                </div>
                <div class="onScreen">
                               </div>
            </div>
         </div>
      </section>
      </div>

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('js/backend-bundle.min.js') }}"></script>

    <!-- Table Treeview JavaScript -->
    <script src="{{ asset('js/table-treeview.js') }}"></script>

    <!-- Chart Custom JavaScript -->
    <script src="{{ asset('js/customizer.js') }}"></script>

    <!-- Chart Custom JavaScript -->
    <script async src="{{ asset('js/chart-custom.js') }}"></script>


    <!-- app JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- <link rel="stylesheet" href="{{ asset('onScreen/jQKeyboard.css')}}" /> --}}
    {{-- <script src="{{ asset('onScreen/jQKeyboard.js') }}"></script> --}}

    <script type="text/javascript">
        $(document).on("click", ".keyboardType0", function () {
            $('input.keyboardType0').initKeypad({'keyboardType': '0'});
        })
        $(document).on("click", ".keyboardType1", function () {
            $('input.keyboardType1').initKeypad({'keyboardType': '1'});
        })

    </script>


    <script>
        function forceFullscreen()
        {
            top.resizeTo(window.screen.abailwidth, window.screen.availHeight);
            top.moveTo(0,0);
        }
        function openCloseFullscreen() {
            var isInFullScreen = (document.fullscreenElement && document.fullscreenElement !== null) ||
                (document.webkitFullscreenElement && document.webkitFullscreenElement !== null) ||
                (document.mozFullScreenElement && document.mozFullScreenElement !== null) ||
                (document.msFullscreenElement && document.msFullscreenElement !== null);

            var docElm = document.documentElement;
            if (!isInFullScreen) {
                if (docElm.requestFullscreen) {
                    docElm.requestFullscreen();
                } else if (docElm.mozRequestFullScreen) {
                    docElm.mozRequestFullScreen();
                } else if (docElm.webkitRequestFullScreen) {
                    docElm.webkitRequestFullScreen();
                } else if (docElm.msRequestFullscreen) {
                    docElm.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        }
    </script>
  </body>
</html>
