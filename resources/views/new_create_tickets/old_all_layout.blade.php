<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <title>Lucky Draw</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="BreezyCV - Resume / CV / vCard Template" />
    <meta name="keywords" content="vcard, resposnive, retina, resume, jquery, css3, bootstrap, Material CV, portfolio" />
    <meta name="author" content="lmpixels" />
    <meta 
     name='viewport' 
     content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' 
/>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/animations.css')}}" type="text/css"> 
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/main.css')}}" type="text/css">
        
    <link rel="stylesheet" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts/linear-icons-font/style.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>

  <body>
    <!-- Animated Background -->
    <!-- <div class="lm-animated-bg" style="background-image: url(img/main_bg.png);"></div> -->
    <div class="lm-animated-bg"><img src="{{ asset('img/main_bg.png')}}" style="background-image"></div>
    <!-- /Animated Background -->

    <!-- Loading animation -->
    <div class="preloader">
      <div class="preloader-animation">
        <div class="preloader-spinner">
        </div>
      </div>
    </div>
    <!-- /Loading animation -->

    <div class="page">
      <div class="page-content">

          <header id="site_header" class="header mobile-menu-hide">
            <div class="header-content">
              <div class="">
                <img src="{{ asset('images/new_design/best-practices.png') }}" alt="Alex Smith">
              </div>
              <div class="header-titles">
                <h2>LUCKY DRAW</h2>
                <h4>ကံစမ်းကြမယ်</h4>
              </div>

            
            </div>

            <ul class="main-menu">
              <li class="active">
                <a href="#home" class="nav-anim">
                  <span class="menu-icon lnr lnr-home"></span>
                  <span class="link-text">Home</span>
                </a>
              </li>
              <li>
                <a href="#about-me" class="nav-anim">
                  <span class="menu-icon lnr lnr-file-add"></span>
                  <span class="link-text">Invoices</span>
                </a>
              </li>
              <li>
                <a href="#resume" class="nav-anim">
                  <span class="menu-icon lnr lnr-user"></span>
                  <span class="link-text">Information</span>
                </a>
              </li>
              <li>
                <a href="#portfolio" class="nav-anim">
                  <span class="menu-icon lnr lnr-select"></span>
                  <span class="link-text">Promotions</span>
                </a>
              </li>
              <li>
                <a href="#blog" class="nav-anim">
                  <span class="menu-icon lnr lnr-star"></span>
                  <span class="link-text">My Promotions</span>
                </a>
              </li>
              <li>
                <a href="#contact" class="nav-anim">
                  <span class="menu-icon lnr lnr-list"></span>
                  <span class="link-text">Summary</span>
                </a>
              </li>
            </ul>

            

            <div class="copyrights">© 2020 All rights reserved.</div>
          </header>

          <!-- Mobile Navigation -->
          <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
          </div>
          <!-- End Mobile Navigation -->

          <!-- Arrows Nav -->
          <div class="lmpixels-arrows-nav">
            <div class="lmpixels-arrow-right"><i class="lnr lnr-chevron-right"></i></div>
            <div class="lmpixels-arrow-left"><i class="lnr lnr-chevron-left"></i></div>
          </div>
          <!-- End Arrows Nav -->

          <div class="content-area">
            <div class="animated-sections">
              <!-- Home  -->
              <section data-id="home" class="animated-section start-page">
                <div class="section-content vcentered">

                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="title-block">
                          <div class="owl-carousel text-rotation">                                    
                            <div class="item">
                              <div class="">
                                <img src="{{ asset('img/34images/1.jpg')}}" alt="Alex Smith">
                              </div>
                            </div>
                            
                            <div class="item">
                              <div class="">
                                <img src="{{ asset('img/34images/2.jpg')}}" alt="Alex Smith">
                              </div>
                            </div>
                            <div class="item">
                              <div class="">
                                <img src="{{ asset('images/new_design/3.jpg')}}" alt="Alex Smith">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                </div>
              </section>
              <!-- End of Home Subpage -->

              <!-- Invoices Subpage -->
              <section data-id="about-me" class="animated-section ">
                <div class="section-content">
                  <div class="page-title">
                    <h2> Invoices</h2>
                    
                  </div>
                  <div class="row ">
                      <div class="form-group form-group-with-icon" style="
                      margin-right: 20px;
                      width: 600px;
                  ">
                        <input id="invoice_no" type="text" name="invoice_no" class="form-control" placeholder="" required="required" data-error="Valid Invoice No is required.">
                        <label>Invoice No Address</label>
                        <div class="form-control-border"></div>
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group form-group-with-icon">
                        <input type="submit" class="button btn-send" value="Add">
                      </div>
                  </div>

                  <!-- Services -->
                

                  <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        
                        <table class="card-table table mb-0 tbl-server-info" id="collect_invoice_list">
                          <thead class="bg-white ">
                              <tr class="ligth ligth-data" style="
                              width: 400px;">
                                  <th>Invoice No </th>
                                  <th> Action</th>
                              </tr>
                          </thead>
                          <tbody class="ligth-body">
                            <tr class="ligth ligth-data">
                              <th> LAN1TESTUSA-221015-0001</th>
                              <th><span class="lnr lnr-cross-circle red" style="
                                color: red;                                font-size: 25px;
                            "></span> </th>
                          </tr>
                          </tbody>
                      </table>
                      
                    </div>
                  </div>
                  <!-- End of Services -->

                </div>
              </section>


              <!-- End of About Me Subpage -->

              <!-- Infomation Subpage -->
              <section data-id="resume" class="animated-section">
                <div class="section-content">
                  <div class="page-title">
                    <h2>Information</h2>
                  </div>

                  <div class="row">
                    <div class="col-xs-12 col-sm-6">
                      <div class="row timeline timeline-second-style clearfix">
                        <div class="timeline-item clearfix">
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="09798392286" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Phone No</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Chan Myae Lwin" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>First Name</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="12/YPT(N)011111" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>NRC No</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="CUSSAT1-190807-0037" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Customer No</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="pro1@mail.com" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Email</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Yangon" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Division</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Insein" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Address</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                      </div>
                      <div class="white-space-50"></div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6">
                      <div class="row timeline timeline-second-style clearfix">
                        <div class="timeline-item clearfix">
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="09798392287" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Phone No 2</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix">
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Kevin" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Last Name</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="-" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Passport</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Member" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px;
                        ">
                            <label>Customer Type</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="white-space-50"></div>
                        <div class="timeline-item clearfix" >
                          <div class="form-group form-group-with-icon form-group-focus">
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Insein" required="required" data-error="Name is required." value="aaaa" style="
                            width: 350px; margin-top: 73px;
                        ">
                            <label>Township</label>
                            <div class="form-control-border"></div>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                      </div>
                      <div class="white-space-50"></div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- End of Resume Subpage -->

              <!-- Promotion Subpage -->
              <section data-id="portfolio" class="animated-section">
                <div class="section-content">
                  <div class="page-title">
                    <h2>Promotion</h2>
                  </div>

                  <div class="row">
                    <div class="col-xs-12 col-sm-12">
                      <!-- Portfolio Content -->
                      <div class="portfolio-content">

                        <ul class="portfolio-filters">
                          <li class="active">
                            <a class="filter btn btn-sm btn-link" data-group="category_all">All</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="main">Main Promotion</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="category">Category Promotion</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="event">Event Promotion</a>
                          </li>
                        </ul>

                        <!-- Portfolio Grid -->
                        <div class="portfolio-grid three-columns">
                          
                          <figure class="item lbaudio" data-groups='["category_all", "main"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 1 -  <span style="color: #01a2e6;">10 Tickets</span></h4>
                            <span class="category">Main</span>
                          </figure>

                          <figure class="item lbaudio" data-groups='["category_all", "category"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 2 -  <span style="color: #01a2e6;">10 Tickets</span></h4>
                            <span class="category">Category</span>
                          </figure>

                          <figure class="item lbaudio" data-groups='["category_all", "event"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 3 -  <span style="color: #01a2e6;">10 Times</span></h4>
                            <span class="category">Event</span>
                          </figure>
                        </div>
                      </div>
                      <!-- End of Portfolio Content -->
                    </div>
                  </div>
                </div>
              </section>
              <!-- End of Portfolio Subpage -->

              <!-- Blog Subpage -->
              <section data-id="blog" class="animated-section">
                <div class="section-content">
                  <div class="page-title">
                    <h2>My Promotion</h2>
                  </div>

                  <div class="row">
                    <div class="col-xs-12 col-sm-12">
                      <!-- Portfolio Content -->
                      <div class="portfolio-content">

                        <ul class="portfolio-filters">
                          <li class="active">
                            <a class="filter btn btn-sm btn-link" data-group="category_all">All</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="main">Main Promotion</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="category">Category Promotion</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="event">Event Promotion</a>
                          </li>
                        </ul>

                        <!-- Portfolio Grid -->
                        <div class="portfolio-grid three-columns">
                          
                          <figure class="item lbaudio" data-groups='["category_all", "main"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 1 -  <span style="color: #01a2e6;">10 Tickets</span></h4>
                            <span class="category">Main</span>
                          </figure>

                          <figure class="item lbaudio" data-groups='["category_all", "category"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 2 -  <span style="color: #01a2e6;">10 Tickets</span></h4>
                            <span class="category">Category</span>
                          </figure>

                          <figure class="item lbaudio" data-groups='["category_all", "event"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 3 -  <span style="color: #01a2e6;">10 Times</span></h4>
                            <span class="category">Event</span>
                          </figure>
                        </div>
                      </div>
                      <!-- End of Portfolio Content -->
                    </div>
                  </div>
                </div>
              </section>
              <!-- End of Blog Subpage -->

              <!-- Contact Subpage -->
              <section data-id="contact" class="animated-section">
                <div class="section-content">
                  <div class="page-title">
                    <h2>Summary</h2>
                  </div>

                  <div class="row">
                    <div class="col-xs-12 col-sm-12">
                      <!-- Portfolio Content -->
                      <div class="portfolio-content">

                        <ul class="portfolio-filters">
                          <li class="active">
                            <a class="filter btn btn-sm btn-link" data-group="category_all">All</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="main">Main Promotion</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="category">Category Promotion</a>
                          </li>
                          <li>
                            <a class="filter btn btn-sm btn-link" data-group="event">Event Promotion</a>
                          </li>
                        </ul>

                        <!-- Portfolio Grid -->
                        <div class="portfolio-grid three-columns">
                          
                          <figure class="item lbaudio" data-groups='["category_all", "main"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 1 -  <span style="color: #01a2e6;">10 Tickets</span></h4>
                            <span class="category">Main</span>
                          </figure>

                          <figure class="item lbaudio" data-groups='["category_all", "category"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 2 -  <span style="color: #01a2e6;">10 Tickets</span></h4>
                            <span class="category">Category</span>
                          </figure>

                          <figure class="item lbaudio" data-groups='["category_all", "event"]'>
                            <div class="portfolio-item-img">
                              <img src="{{ asset('img/portfolio/1.jpg')}}" alt="SoundCloud Audio" title="" />
                              <a href="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/221650664&#038;color=%23ff5500&#038;auto_play=false&#038;hide_related=false&#038;show_comments=true&#038;show_user=true&#038;show_reposts=false&#038;show_teaser=true&#038;visual=true" class="lightbox mfp-iframe" title="SoundCloud Audio"></a>
                            </div>
                            <i class="fa fa-info"></i>
                            <h4 class="name">Promotion 3 -  <span style="color: #01a2e6;">10 Times</span></h4>
                            <span class="category">Event</span>
                          </figure>
                        </div>
                      </div>
                      <!-- End of Portfolio Content -->
                    </div>
                  </div>
                </div>
              </section>
              <!-- End of Contact Subpage -->
            </div>
          </div>

      </div>
    </div>

    
<!-- /////New Desing/// -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/modernizr.custom.js') }}"></script>
    <script src="{{ asset('js/animating.js') }}"></script>

    <script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>


    <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/jquery.shuffle.min.js') }}"></script>
    <script src="{{ asset('js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>

    
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/old_main.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
    <script>
  
</script>
  </body>


</html>
