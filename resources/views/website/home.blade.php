@extends('layouts.website')

{{-- Web site Title --}}

@section('title') {!! $title !!} :: @parent @endsection

{{-- Content --}}

@if ($message = Session::get('success'))
    <div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@section('content')
    {{-- Content1 --}}
    @include('website.partial.mainbanner')
    {{-- Content1 --}}

    {{-- Content2--}}
    @include('website.partial.trending')
    {{-- Content2 --}}

    {{-- Content3 --}}
    @include('website.partial.trending2')
    {{-- Content3 --}}

    {{-- Content4 --}}
    @include('website.partial.trending3')
    {{-- Content4 --}}

    {{-- Content5 --}}
    @include('website.partial.howitworks')
    {{-- Content --}}

    {{-- Content6 --}}

    <div class="index-block howItWorks"> 
    <div class="container">
        <div class="innerdiv">
            <div class="section-title title-center"><h2>@lang('home.HOW IT WORKS')</h2></div>

            <div class="row">


                <div class="col-md-12 work-flow">
                    <ul>

                        <li><img src="{{ url('public/v1/website/img/w1-icon@3x.png') }}" alt=""/>
                            <span>@lang('home.Goods placed on the platform')</span>
                        </li>
                        <li><img src="{{ url('public/v1/website/img/w2-icon@3x.png') }}" alt=""/>
                            <span>@lang('home.Orders placed with payment')</span></li>
                        <li><img src="{{ url('public/v1/website/img/w3-icon@3x.png') }}" alt=""/>
                            <span>@lang('home.Goods delivered to the platform')</span></li>

                        <li><img src="{{ url('public/v1/website/img/w4-icon@3x.png') }}" alt=""/>
                            <span>@lang('home.Goods Authenticated')</span></li>
                        <li><img src="{{ url('public/v1/website/img/w5-icon@3x.png') }}" alt=""/>
                            <span>@lang('home.Payment released to the seller')</span></li>
                        <li><img src="{{ url('public/v1/website/img/w6-icon@3x.png') }}" alt=""/>
                            <span>@lang('home.Goods are shipped')</span></li>

                    </ul>
                </div>
            </div>


        </div>
    </div><!-- container ends -->
    </div>

    <div class="index-block countdowntimerNew">
        <div class="container">
            <div class="innerdiv">

                <div class="countTitleNew"><h1>@lang('home.VIP SALE')</h1></div>
                <div class="countdown countdown-container">
                    <div class="clock clock-row">

                        <div class="clock-item clock-days countdown-time-value ">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-days" class=" "></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-days type-time">@lang('home.DAYS')</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clock-item clock-hours countdown-time-value ">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-hours" class=" "></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-hours type-time">@lang('home.HOURS')</p>
                                    </div><!-- /.text -->
                                </div><!-- /.inner -->
                            </div><!-- /.wrap -->
                        </div><!-- /.clock-item -->

                        <div class="clock-item clock-minutes countdown-time-value ">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-minutes" class=" "></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-minutes type-time">@lang('home.MINUTES')</p>
                                    </div><!-- /.text -->
                                </div><!-- /.inner -->
                            </div><!-- /.wrap -->
                        </div><!-- /.clock-item -->

                        <div class="clock-item clock-seconds countdown-time-value ">
                            <div class="wrap">
                                <div class="inner">
                                    <div id="canvas-seconds" class=" "></div>

                                    <div class="text">
                                        <p class="val">0</p>
                                        <p class="type-seconds type-time">@lang('home.SECONDS')</p>
                                    </div><!-- /.text -->
                                </div><!-- /.inner -->
                            </div><!-- /.wrap -->
                        </div><!-- /.clock-item -->

                    </div><!-- /.clock -->
                </div><!-- /.countdown-wrapper -->


                <div class="center-btns">
                    <a class="btn1" href="{{url('/vip/vip-sale')}}">@lang('home.VIP SALE')</a>

                    <a class="btn1" href="{{url('/vip/vip-store')}}">@lang('home.VIP STORE')</a>
                </div>
            </div>
        </div><!-- container ends -->
    </div>

    <!-- / section ends ======================================  -->

    <div class="index-block howItWorks">
        <div class="container">
            <div class="innerdiv">
                <div class="section-title title-center"><h2>@lang('home.HOT BRANDS')</h2></div>


                <div class="item">
                    <ul id="responsive3" class="f-collections content-slider">
                        <li>
                            <div class="brand-grid">
                                <div class="brand-thumb"><img src="{{ url('public/v1/website/img/1.png') }}" alt=""/>
                                </div>
                            </div>
                        </li> <!-- product list ends -->
                        <li>
                            <div class="brand-grid">
                                <div class="brand-thumb"><img src="{{ url('public/v1/website/img/2.png') }}" alt=""/>
                                </div>
                            </div>
                        </li> <!-- product list ends -->
                        <li>
                            <div class="brand-grid">
                                <div class="brand-thumb"><img src="{{ url('public/v1/website/img/3.png') }}" alt=""/>
                                </div>
                            </div>
                        </li> <!-- product list ends -->
                        <li>
                            <div class="brand-grid">
                                <div class="brand-thumb"><img src="{{ url('public/v1/website/img/4.png') }}" alt=""/>
                                </div>
                            </div>
                        </li> <!-- product list ends -->
                        <li>
                            <div class="brand-grid">
                                <div class="brand-thumb"><img src="{{ url('public/v1/website/img/5.png') }}" alt=""/>
                                </div>
                            </div>
                        </li> <!-- product list ends -->

                    </ul>
                </div>

            </div>
        </div><!-- container ends -->
    </div>

    <!-- / section ends ======================================  -->





    <div class="index-block homeBlogs">
        <div class="container">

            <div class="row">
                <div class="innerdiv">
                    <div class="col-md-12">
                        <div class="section-title title-center"><h2>@lang("home.What's Trending")</h2></div>
                    </div>

                    <div class="col-md-4">
                        <div class="blogCol">
                            <a href="javascript:void(0);"><img src="{{ url('public/v1/website/img/blog1@3x.png') }}"
                                                               alt=""/></a>
                            <h2>@lang('home.NIKE UNVEILS 2017 MCDONALD ALL AMERICAN GAME FOOTWEAR & UNIFORMS')</h2>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="blogCol">
                            <a href="javascript:void(0);"><img src="{{ url('public/v1/website/img/blog2@3x.png') }}"
                                                               alt=""/></a>
                            <h2>@lang('home.NIKE UNVEILS 2017 MCDONALD ALL AMERICAN GAME FOOTWEAR & UNIFORMS')</h2>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="blogCol">
                            <a href="javascript:void(0);"><img src="{{ url('public/v1/website/img/blog3@3x.png') }}"
                                                               alt=""/></a>
                            <h2>@lang('home.NIKE UNVEILS 2017 MCDONALD ALL AMERICAN GAME FOOTWEAR & UNIFORMS')</h2>
                        </div>
                    </div>


                </div>
            </div><!-- row ends -->
        </div>
    </div>



    <!-- / section ends ======================================  -->


    <div class="index-block noBar newsletterSection">
        <div class="container">
            <div class="innerdiv">
                <div class="section-title title-center"><h2>@lang('home.NEWSLETTER SIGN UP')</h2></div>

                <div class="row">

                    <div class="col-md-6 col-md-offset-3 text-center newsletterBlock">
                        <div class="news form-group">
                            <form>
                                <div class="input-field">
                                    <input type="text" placeholder="@lang('home.Enter Your Email')">
                                </div><!-- field ends -->
                                <div class="form-btn">
                                    <button type="button">@lang('home.SUBSCRIBE')</button>
                                </div>

                            </form>
                        </div><!-- form ends -->
                    </div>


                </div>


            </div>
        </div><!-- container ends -->
    </div>

    <!-- / section ends ======================================  -->

    {{-- Content6 --}}
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            $("#homeSlider").lightSlider({
                loop: true,
                keyPress: true,
                item: 1,
                nav: true,
                pager: true,
                slideMargin: 0,
                auto: true,
                pauseOnHover: true,
                speed: 1100,
                pause: 4500,


            });
            $("#responsive2").lightSlider({
                loop: true,
                keyPress: true,
                item: 4,
                nav: true,
                autoplay: false,
                pager: false,
                slideMargin: 0,
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

            $('.countdown').final_countdown({
                'start': 1362139200,
                'end': 1388461320,
                'now': 1387461319
            });
        });
    </script>


@endsection