@extends('layouts.website')

@if ($message = Session::get('success'))
    <div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@section('content')

    <div class="vip-landing">

        <!-- Modal -->
        <div class="modal fade modalnav" id="myModalhed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>

                    </div>
                    <div class="modal-body">
                        <nav class="navbar navbar-default">
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="navbar-collapse" id="bs-example-navbar-collapse-1">
                                <form method="get" action="http://hypex.trantorinc.com/search-result"
                                      class="top-search navbar-form navbar-left">
                                    <input type="hidden" name="_token" value="TYJvG9ksEkmvvVG4LqUKhH4M3875gJHyufbGZQpm">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="search_keyword"
                                               placeholder="Search for brand or product... etc">
                                        <a href="javascript:void(0);" onclick="top_search(this)">
                                            <svg class="svg-inline--fa fa-search fa-w-16" aria-hidden="true"
                                                 data-prefix="fas" data-icon="search" role="img"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                                 data-fa-i2svg="">
                                                <path fill="currentColor"
                                                      d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                                            </svg><!-- <i class="fas fa-search"></i> --></a>
                                    </div>

                                </form>

                                <ul class="nav navbar-nav navbar-right">
                                    <li><a href="vip-sell.html">VIP Sell</a></li>
                                    <li><a href="vip-offer.html">VIP Store</a></li>
                                    <li><a href="#">FAQ</a></li>
                                    <li><a href="#" class="">Sign In</a></li>
                                    <li><a href="#" class="">Sign Up</a></li>
                                </ul>
                            </div><!-- /.navbar-collapse -->
                        </nav>


                    </div>

                </div>
            </div>
        </div>


        <!-- /navbar -->

        <div class="vip-main-banner vip-inner-banner">
            <div class="container">
                <div class="row">

                    <div class="col-md-12 text-center">

                        <div class="text-center vip-title">
                            <div class="vip-section-title">
                                <h1>Hurry !! Sale Start Soon</h1>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,<br/> sed do eiusmod tempor
                                </p>
                            </div>
                        </div>


                        <div class="vip-timer-inner">


                            <div class="countdown countdown-container container">
                                <div class="clock row">
                                    <!-- <div class="clock-item clock-days countdown-time-value col-xs-3 col-sm-3 col-md-3">
                                        <div class="wrap">
                                            <div class="inner">
                                                <div id="canvas-days" class="clock-canvas"></div>

                                                <div class="text">
                                                    <p class="val">0</p>
                                                    <p class="type-days type-time">DAYS</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- <div class="clock-item clock-hours countdown-time-value col-xs-3 col-sm-3 col-md-3">
                                        <div class="wrap">
                                            <div class="inner">
                                                <div id="canvas-hours" class="clock-canvas"></div>

                                                <div class="text">
                                                    <p class="val">0</p>
                                                    <p class="type-hours type-time">HOURS</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- <div class="clock-item clock-minutes countdown-time-value col-xs-3 col-sm-3 col-md-3">
                                        <div class="wrap">
                                            <div class="inner">
                                                <div id="canvas-minutes" class="clock-canvas"></div>

                                                <div class="text">
                                                    <p class="val">0</p>
                                                    <p class="type-minutes type-time">MINUTES</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- <div class="clock-item clock-seconds countdown-time-value col-xs-3 col-sm-3 col-md-3">
                                        <div class="wrap">
                                            <div class="inner">
                                                <div id="canvas-seconds" class="clock-canvas"></div>

                                                <div class="text">
                                                    <p class="val">0</p>
                                                    <p class="type-seconds type-time">SECONDS</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                </div>
                            </div>
                            <!-- <div id="clock"></div> -->


                            <div class="vip-banner-btn">
                                <a class="vip-btn" href="{{url('/vip/vip-sale')}}">@lang("home.VIP Sale")</a>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- / section ends ======================================  -->


        <div class="index-block vip-product-listing">
            <div class="container">

                <div class="row">

                    <div class="col-md-12 vip-inner-title">
                        <div class="vip-section-title">
                            <h1>VIP Sell</h1>


                            <div class="vip-sorting-form" style="display: none;">
                                <form>
                                    <div class="select-sort">
                                        <h3>Brand</h3>
                                        <select>
                                            <option>All</option>
                                            <option>Addidas</option>
                                            <option>Nike</option>
                                            <option>Jordan</option>

                                        </select>
                                    </div> <!-- sorting ends -->

                                    <div class="select-sort">
                                        <h3>Price</h3>
                                        <select>
                                            <option>Low to High</option>
                                            <option>High to Low</option>
                                            <option>Random</option>
                                        </select>
                                    </div> <!-- sorting ends -->

                                </form>
                            </div>

                        </div>
                    </div>


                    <div class="col-md-12 ">
                        <ul class="vip-tab1 vip-product-list">
                            @if(count($vipStore) > 0)
                                @foreach($vipStore as $key=> $singleProduct)
                                    <li>
                                        @php
                                            $file='';
                                            $mainImage = '';
                                            if(!empty($singleProduct['product_images'])) {
                                                $file = $singleProduct['product_images'];
                                                $prodImages = explode(',',$file);
                                                $mainImage = current($prodImages);
                                            }else{
                                                $mainImage = 'dummy.png';
                                            }
                                        @endphp
                                        <a href="{{ url('vip-product-detail').'/'.base64_encode($singleProduct['id']) }}">
                                            <div class="product-grid">
                                                <div class="product-thumb"><img src="{{ url('/').'/'.$mainImage }}" class="img-responsive"></div>
                                                <div class="product-thumb-info">
                                                    <h3 class="brand-title">{{ $singleProduct['product_brand']['brand_name'] }}</h3>
                                                    <h4 class="product-title">{{ str_limit($singleProduct['product_name'],20) }}</h4>
                                                    <div class="product-price"><span><strong>${{$singleProduct['retail_price']}}</strong></span><span>{{$singleProduct['start_counter']}} @lang('home.Sold')</span></div>
                                                </div>
                                            </div>
                                        </a>
                                    </li> <!-- product list ends -->
                                @endforeach
                            @else
                                No Record Found !!
                            @endif
                        </ul>
                    </div>
                    {{--<div class="load-more text-center"><a href="#">Load More</a></div>--}}
                    @if(count($vipStore) >= 10)
                        <div class="load-more text-center"><a href="{{ url('vip-products') }}">More Products</a></div>
                    @endif
                </div>
            </div>
        </div>


        <!-- / section ends ======================================  -->


        <div class="index-block howItWorks">
            <div class="container">

                <div class="col-md-12">
                    <div class="text-center vip-title">
                        <div class="vip-section-title">
                            <h1>Hot brands</h1>
                        </div>
                    </div>
                </div>

                <div class="innerdiv">

                    <div class="item">
                        <ul id="responsivebrands" class="f-collections content-slider">
                            <li>
                                <div class="brand-grid">
                                    <div class="brand-thumb"><img src="{{ url('public/v1/website/img/1.png') }}"
                                                                  alt=""/></div>
                                </div>
                            </li> <!-- product list ends -->
                            <li>
                                <div class="brand-grid">
                                    <div class="brand-thumb"><img src="{{ url('public/v1/website/img/2.png') }}"
                                                                  alt=""/></div>
                                </div>
                            </li> <!-- product list ends -->
                            <li>
                                <div class="brand-grid">
                                    <div class="brand-thumb"><img src="{{ url('public/v1/website/img/3.png') }}"
                                                                  alt=""/></div>
                                </div>
                            </li> <!-- product list ends -->
                            <li>
                                <div class="brand-grid">
                                    <div class="brand-thumb"><img src="{{ url('public/v1/website/img/4.png') }}"
                                                                  alt=""/></div>
                                </div>
                            </li> <!-- product list ends -->
                            <li>
                                <div class="brand-grid">
                                    <div class="brand-thumb"><img src="{{ url('public/v1/website/img/5.png') }}"
                                                                  alt=""/></div>
                                </div>
                            </li> <!-- product list ends -->

                        </ul>
                    </div>

                </div>
            </div><!-- container ends -->
        </div>

        <!-- / section ends ======================================  -->


        <div class="index-block noBar newsletterSection">
            <div class="container">
                <div class="innerdiv">
                    <div class="text-center vip-title">
                        <div class="vip-section-title">
                            <h1>Newsletter sign up</h1>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center newsletterBlock">
                            <div class="news form-group">
                                <form>
                                    <div class="input-field">
                                        <input type="text" placeholder="Enter Your Email">
                                    </div><!-- field ends -->
                                    <div class="form-btn">
                                        <button class="vip-btn" type="submit">Subscribe</button>
                                    </div>
                                </form>
                            </div><!-- form ends -->
                        </div>
                    </div>
                </div>
            </div><!-- container ends -->
        </div>

        <!-- / section ends ======================================  -->


        <!-- / section ends ======================================  -->


        <link rel="stylesheet" href="HYPEX_files/lightslider.css"/>

        <script src="HYPEX_files/lightslider.js"></script>
        <script>
            $(document).ready(function () {

                $("#homeSlider").lightSlider({
                    loop: true,
                    keyPress: true,
                    item: 1,
                    nav: false,
                    pager: true,
                    slideMargin: 0,

                });
                $(".f-collections").lightSlider({
                    loop: true,
                    keyPress: true,
                    item: 4,
                    nav: true,
                    pager: false,
                    slideMargin: 20,
                    responsive: [
                        {
                            breakpoint: 800,
                            settings: {
                                item: 3,
                                slideMove: 1,
                                slideMargin: 6,
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                item: 2,
                                slideMove: 1
                            },
                        }
                    ]
                });
                $("#responsive3").lightSlider({
                    loop: true,
                    keyPress: false,
                    item: 5,
                    nav: false,
                    pager: false,
                    slideMargin: 20,
                    responsive: [
                        {
                            breakpoint: 800,
                            settings: {
                                item: 3,
                                slideMove: 1,
                                slideMargin: 6,
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                item: 2,
                                slideMove: 1
                            },
                        }
                    ]
                });

                $("#responsive").lightSlider({
                    loop: true,
                    keyPress: true,
                    item: 4,
                    nav: true,
                    pager: false,
                    slideMargin: 20,
                    responsive: [
                        {
                            breakpoint: 800,
                            settings: {
                                item: 3,
                                slideMove: 1,
                                slideMargin: 6,
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                item: 2,
                                slideMove: 1
                            },
                        }
                    ]
                });
                $('#image-gallery').lightSlider({
                    gallery: true,
                    item: 1,
                    thumbItem: 4,
                    slideMargin: 0,
                    speed: 500,
                    auto: false,
                    loop: true,
                    onSliderLoad: function () {
                        $('#image-gallery').removeClass('cS-hidden');
                    }
                });
            });
        </script>


        <script type="text/javascript">
            $('document').ready(function () {
            'use strict';
            var today = new Date();
            // My target date is this month 30th 9.25pm
            var target = new Date(today);
            target.setDate(25);
            target.setHours(9,25,0,0);

            // Countdown start from yesterday
            var yesterday = new Date(today);
            yesterday.setDate(today.getDate() - 1);
            yesterday.setHours(0,0,0,0);
                $('.countdown').final_countdown({
                    'start': yesterday.getTime() / 1000,
                    'end': target.getTime() / 1000,
                    'now': today.getTime() / 1000,
                });

            });
        </script>

        <script type="text/javascript">
            // $('#clock').countdown('<?php echo $vipCountertime['start_date']; ?>', function(event) {
            // var $this = $(this).html(event.strftime(''
            //     + '<span>%d</span> days '
            //     + '<span>%H</span> hr '
            //     + '<span>%M</span> min '
            //     + '<span>%S</span> sec'));
            // });
            $('.countdown').countdown('<?php echo $vipCountertime['start_date']; ?>')
                .on('update.countdown', function(event) {
                    var $this = $(this).html(event.strftime(''
                        +'<div class="clock-item clock-days countdown-time-value col-xs-3 col-sm-3 col-md-3">'
                        +'<div class="wrap">'
                        +'<div class="inner">'
                        +'<div id="canvas-days" class="clock-canvas"></div>'
                        +'<div class="text">'
                        +' <p class="val">%d</p>'
                        +'<p class="type-days type-time">DAYS</p>'
                        +'</div>'
                        +'</div>'
                        +'</div>'
                        +'</div>'

                        +'<div class="clock-item clock-hours countdown-time-value col-xs-3 col-sm-3 col-md-3">'
                        +'<div class="wrap">'
                        +'<div class="inner">'
                        +'<div id="canvas-hours" class="clock-canvas"></div>'
                        +'<div class="text">'
                        +'<p class="val">%H</p>'
                        +'<p class="type-hours type-time">HOURS</p>'
                        +'</div>'
                        +'</div>'
                        +'</div>'
                        +'</div>'

                        +'<div class="clock-item clock-hours countdown-time-value col-xs-3 col-sm-3 col-md-3">'
                        +'<div class="wrap">'
                        +'<div class="inner">'
                        +'<div id="canvas-hours" class="clock-canvas"></div>'
                        +'<div class="text">'
                        +'<p class="val">%M</p>'
                        +'<p class="type-hours type-time">MINUTES</p>'
                        +'</div>'
                        +'</div>'
                        +'</div>'
                        +'</div>'

                        +'<div class="clock-item clock-hours countdown-time-value col-xs-3 col-sm-3 col-md-3">'
                        +'<div class="wrap">'
                        +'<div class="inner">'
                        +'<div id="canvas-hours" class="clock-canvas"></div>'
                        +'<div class="text">'
                        +'<p class="val">%S</p>'
                        +'<p class="type-hours type-time">SECONDS</p>'
                        +'</div>'
                        +'</div>'
                        +'</div>'
                        +'</div>'));
                });
            //     .on('finish.countdown', function(event) {
            //     $(this).html('This offer has expired!')
            //         .parent().addClass('disabled');

            // });
        </script>

    </div>
@endsection
