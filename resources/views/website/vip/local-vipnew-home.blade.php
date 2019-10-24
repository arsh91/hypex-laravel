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

        <div class="vip-main-banner">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 pull-sm-right small-up-height">
                        <div class="banner-imgs">
                            <i class="img1"><img src="{{ url('public/v1/website/img/banner-shoe2.png') }}" alt=""/></i>
                            <i class="img2"><img src="{{ url('public/v1/website/img/banner-shoe1.png') }}" alt=""/></i>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="banner-caption">
                            <h1>Hypex VIP <strong>Membership</strong></h1>
                            <p>CAD
                                @if(count($plans))
                                    {{$plans[0]['price']}}@lang("home./month")
                                @else
                                    $58 @lang("home./month")
                                @endif
                            </p>
                            <a class="vip-btn showplan" href="#vipplans">@lang("home.BECOME VIP")</a>
                        </div>
                    </div><!-- col ends-->
                </div>
            </div>
        </div>
        <!-- / section ends ======================================  -->


        <div class="index-block vip-features">
            <div class="container">

                <div class="row">

                    <div class="col-md-12 text-center vip-title">
                        <div class="vip-section-title">
                            <h1>VIP Features</h1>
                        </div>
                    </div>


                    <div class="col-md-4 feature-block">
                        <div class="icon-block">
                            <i><img src="{{ url('public/v1/website/img/feature-icon.png') }}" alt=""/></i>
                            <h4>@lang("home.Exclusive Sales")</h4>
                            <p>@lang("home.VIP membership grants you access to the most hyped items at extremely low prices.")</p>
                        </div>
                    </div> <!-- col ends -->

                    <div class="col-md-4 feature-block">
                        <div class="icon-block">
                            <i><img src="{{ url('public/v1/website/img/feature-icon.png') }}" alt=""/></i>
                            <h4>@lang("home.Timly Updates")</h4>
                            <p>@lang("home.VIPs will receive first hand sales, releases and restockings updates.")</p>
                        </div>
                    </div> <!-- col ends -->

                    <div class="col-md-4 feature-block">
                        <div class="icon-block">
                            <i><img src="{{ url('public/v1/website/img/feature-icon.png') }}" alt=""/></i>
                            <h4>@lang("home.Love it or Return it")</h4>
                            <p>@lang("home.VIPs can return any product within 7 days of purchase for any reasons.")</p>
                        </div>
                    </div> <!-- col ends -->

                    <div class="col-md-4 feature-block">
                        <div class="icon-block">
                            <i><img src="{{ url('public/v1/website/img/feature-icon.png') }}" alt=""/></i>
                            <h4>@lang("home.VIP Store")</h4>
                            <p>@lang("home.The VIP Store offers exclusives sales all year around.")</p>
                        </div>
                    </div> <!-- col ends -->

                    <div class="col-md-4 feature-block">
                        <div class="icon-block">
                            <i><img src="{{ url('public/v1/website/img/feature-icon.png') }}" alt=""/></i>
                            <h4>@lang("home.24/7 Email Q&A")</h4>
                            <p>@lang("home.We gurantee a response to all of your questions.")</p>
                        </div>
                    </div> <!-- col ends -->

                    <div class="col-md-4 feature-block">
                        <div class="icon-block">
                            <i><img src="{{ url('public/v1/website/img/feature-icon.png') }}" alt=""/></i>
                            <h4>@lang("home.Priority Shipping")</h4>
                            <p>@lang("home.All VIP purchases will be shipped using expedited shipping.")</p>
                        </div>
                    </div> <!-- col ends -->
                </div>
            </div>
        </div>


        <!-- / section ends ======================================  -->


        <div class="index-block vertical-line">
            <div class="line-break"></div>
        </div>


        <!-- / section ends ======================================  -->


        <div class="index-block vip-different">
            <div class="container">
                <div class="row">

                    <div class="col-xs-12 col-md-6">
                        <div class="vip-shoe-box">
                            <i class="box-hover"><img src="{{ url('public/v1/website/img/vip-box.png') }}" alt=""/></i>
                            <i class="hoverimg"><img src="{{ url('public/v1/website/img/vip-box-shoe.png') }}" alt=""/></i>
                        </div>
                    </div>


                    <div class="col-xs-12 col-md-6 small-center">

                        <div class="text-left vip-title">
                            <div class="vip-section-title">
                                <h1>@lang("home.Limited Spots Available")</h1>
                                <p>@lang("home.There are only 200 VIP spots available, signup today to gain all the
                                exclusive features.")</p>
                            </div>
                        </div>


                        <ul class="vip-counts">
                            <li>
                                <p>@lang("home.Number of spots")</p>
                                <h3>200</h3>
                            </li>

                            <li>
                                <p>@lang("home.Current Members")</p>
                                <h3>25</h3>
                            </li>
                        </ul>

                        <a href="#vipplans" class="vip-btn showplan">@lang("home.Join Today")</a>

                    </div>
                </div>
            </div>
        </div>


        <!-- / section ends ======================================  -->


        <div class="index-block vip-product-listing">
            <div class="container">

                <div class="row">

                    <div class="text-center vip-title">
                        <div class="vip-section-title">
                            <h1>@lang("home.Shop VIP Now")</h1>
                            <p>
                                <a class="active vip-btn" href="{{url('/vip/vip-store')}}">@lang("home.VIP Store")</a>
                                <a class="vip-btn" href="{{url('/vip/vip-sale')}}">@lang("home.VIP Sale")</a>
                            </p>
                        </div>
                    </div>


                <div class="col-md-12 ">
                    <ul class="vip-tab1 vip-product-list">

                        @if(count($vipData) > 0)
                            @foreach($vipData as $key=> $singleProduct)
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
                                                <h4 class="product-title">{{ str_limit($singleProduct['product_name'],30) }}</h4>
                                                <div class="product-price"><span><strong>{{$singleProduct['retail_price']}}</strong></span><span>{{$singleProduct['start_counter']}} @lang('home.Sold')</span></div>

                                            </div>

                                        </a>
                                    </li> <!-- product list ends -->
                                @endforeach
                            @else
                                No Record Found !!
                            @endif
                        </ul>
                    </div>
                    {{--@if(count($vipData) >= 10)--}}
                    @if(count($vipData) >= 10)
                        <div class="load-more text-center"><a href="{{ url('vip-products') }}">@lang("home.More Products")</a></div>
                    @endif
                </div>
            </div>
        </div>
        <!-- / section ends ======================================  -->


        <div class="index-block vip-plans" id="vipplans">
            <div class="container">
                <div class="row">

                    <div class="col-md-offset-1 col-xs-12 col-sm-6 text-xs-center text-sm-left">

                        <div class="vip-plan-terms">
                            <h1 class="">@lang("home.Plan Includes")</h1>
                            <p>@lang("home.Members are given the opportunity to purchase hyped items such as Supreme, Off-White or Air Jordan for retail price on our site every 15 days. One item per member per drop guaranteed. All items are sold on a FCFS basis.")</p>

                        </div> <!-- plan col ends -->
                    </div><!-- col 4 ends -->


                    <div class="col-xs-12 col-sm-4 text-center">

                        <div class="vip-plan-inner">
                            <div class="vip-plan-header">

                                <h2>@lang("home.Monthly")</h2>
                                <h3>@lang("home.Billed every month")</h3>
                                <span>@lang("home.Cancel anytime")</span>
                            </div>


                            <div class="vip-plan-body">
                                @if(count($plans))
                                    <h2>
                                        $<?php echo $plans[0]['price']; ?>
                                        <small>@lang("home./month")</small>
                                    </h2>
                                    <ul>
                                        <li>- @lang("home.Most popular").</li>
                                        <li>- @lang("home.All features")</li>
                                        <li>- @lang("home.24/7 Support")</li>
                                    <!-- <li>- {{ $plans[0]['feature_1']}}.</li>
												<li>- {{ $plans[0]['feature_2']}}.</li>
												<li>- {{ $plans[0]['feature_3']}}.</li>
                                                <li>- {{ $plans[0]['feature_4']}}.</li>  -->

                                </ul>
                            @else
                                <h2> $58.00
                                    <small>@lang("home./month")</small>
                                </h2><!---Initial case to handle if there is no plan--->
                                <ul>
                                    <li>- @lang("home.Most popular").</li>
                                    <li>- @lang("home.All features")</li>
                                    <li>- @lang("home.No binding")</li>
                                </ul>
                            @endif

                            <div class="text-center vip-plan-footer">
                                @if(count($plans))
                                    <!-- <a href="{{ route('buy_plan', ['id' => base64_encode($plans[0]['id'])]) }}"
                                       class="vip-btn">@lang("home.BUY NOW")</a> -->
                                       <a href="" id="buynow" class="vip-btn">@lang("home.BUY NOW")</a>

                                    </ul>

                                @else
                                    <h2> $58.00
                                        <small>@lang("home./month")</small>
                                    </h2><!---Initial case to handle if there is no plan--->
                                    <ul>
                                        <li>- @lang("home.Most popular").</li>
                                        <li>- @lang("home.All features")</li>
                                        <li>- @lang("home.No binding")</li>
                                    </ul>
                                @endif
                            </div>
                            <span id="successmessage">@lang('home.Alredy have Subscription')</span>
                        </div> <!-- plan col ends -->
                    </div><!-- col 4 ends -->

                </div>
            </div>
        </div>
        <!-- / section ends ======================================  -->

        <div class="index-block noBar newsletterSection">
            <div class="container">
                <div class="innerdiv">
                    <div class="section-title title-center"><h2>@lang("home.newsletter sign up")</h2></div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center newsletterBlock">
                            <div class="news form-group">
                                <form>
                                    <div class="input-field">
                                        <input type="text" placeholder="@lang('home.Enter Your Email')">
                                    </div><!-- field ends -->
                                    <div class="form-btn">
                                        <button class="vip-btn" type="submit">@lang("home.SUBSCRIBE")</button>
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


        <script type="text/javascript">
            $('document').ready(function () {
                'use strict';

                $('.countdown').final_countdown({
                    'start': 1362139200,
                    'end': 1388461320,
                    'now': 1387461319
                });
            });

            $(document).ready(function () {
                var speed = 1000;

                // check for hash and if div exist... scroll to div
                var hash = window.location.hash;
                if ($(hash).length) scrollToID(hash, speed);

                // scroll to div on nav click
                $('.showplan').click(function (e) {
                    e.preventDefault();
                    var id = $(this).attr('href');
                    if ($(id).length) scrollToID(id, speed);
                });
            })

            function scrollToID(id, speed) {
                var offSet = 70;
                var obj = $(id).offset();
                var targetOffset = obj.top - offSet;
                $('html,body').animate({scrollTop: targetOffset}, speed);
            }


            
            $('#successmessage').hide();
            $('#buynow').on('click', function (e) {
            e.preventDefault();
                $.ajax({
                  url: '{{ url('/') }}/subscriptioncheck/',
                  type: 'GET',
                  success: function(response){
                      if(response == 0){
                        window.location.href = '<?php echo route('buy_plan', ['id' => base64_encode($plans[0]['id'])]) ?>'; 
                      }else{
                        $('#successmessage').show();
                            setTimeout(function() {
                        $('#successmessage').fadeOut('slow');
                        }, 2000); // <-- time in milliseconds
                      }
                      
                  },
                  error: function(response){
                      if(response.responseJSON.errorCode == 401)
                      {
                        window.location.href = '/signin'; 
                      }
                    
                  }
                });
            
            });  
            


        </script>
    </div>
@endsection