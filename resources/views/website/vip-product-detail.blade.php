@extends('layouts.website')

@if ($message = Session::get('success'))
    <div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@section('content')
    <div class="Trending">
        <div class="container">
<?php //print_r($productDetails); exit(); ?>
            <div class="row">
                @if(count($productDetails) > 0)
                    <div class="innerdiv">
                        @foreach($productDetails as $k=> $singleProduct)
                            <div class="col-xs-12 col-sm-6">
                                @php
                                    $file='';
                                    if($singleProduct['product_images']){
                                        $file = $singleProduct['product_images'];
                                        $prodImages = explode(',',$file);
                                        $mainImage = current($prodImages);
                                        $productID = $singleProduct['id'];
                                        $style = '--';
                                        $retail_price = '--';
                                        $color = '--';
                                        $brand = '--';
                                        $prodBrand = '--';
                                        $sizeType = '--';
                                        $releaseDate = '--';
                                        $product_sizes = '--';
                                        $currency_code = $singleProduct['currency_code'];

                                        $productName = $singleProduct['product_name'];
                                        if(isset($singleProduct['style']) && $singleProduct['style'] != ''){
                                            $style = $singleProduct['style'];
                                        }

                                        if(isset($singleProduct['retail_price']) && $singleProduct['retail_price'] != ''){
                                            $retail_price = $singleProduct['retail_price'];
                                        }

                                        if(isset($singleProduct['color']) && $singleProduct['color'] != ''){
                                            $color = $singleProduct['color'];
                                        }

                                        if(isset($singleProduct['product_brand_type']['brand_type_name']) && $singleProduct['product_brand_type']['brand_type_name'] != ''){
                                            $brand = $singleProduct['product_brand_type']['brand_type_name'];
                                        }

                                        if(isset($singleProduct['product_brand']['brand_name']) && $singleProduct['product_brand']['brand_name'] != ''){
                                            $prodBrand = $singleProduct['product_brand']['brand_name'];
                                        }

                                        if(isset($singleProduct['product_size_types']['size_type']) && $singleProduct['product_size_types']['size_type'] != ''){
                                            $sizeType = $singleProduct['product_size_types']['size_type'];
                                        }

                                        if(isset($singleProduct['product_sizes']) && $singleProduct['product_sizes'] != ''){
                                            $product_sizes = $singleProduct['product_sizes'];
                                        }

                                        if(isset($singleProduct['release_date']) && $singleProduct['release_date'] != ''){
                                            $releaseDate = $singleProduct['release_date'];
                                            $releaseDate = date('M d, Y', strtotime($releaseDate));
                                        }
                                    }
                                @endphp

                                <div class="product-slider">

                                    <!-- ============================== slider js ============================== -->


                                    <div class="item">
                                        <div class="clearfix" style="max-width:100%;">
                                            <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                                                @if(count($prodImages) > 0)
                                                    @foreach($prodImages as $key=> $images)

                                                        <li data-thumb="{{ url($images) }}">
                                                            <img src="{{ url($images) }}">
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        
                                    </div>
                                    <!-- ============================ slider js ends ======================== -->
                                </div>
                            </div> <!-- product slider ends -->

                            <div class="col-xs-12 col-sm-6">
                                <div class="product-info">
                                    <h1 class="brand-title">{{ $prodBrand }}</h1>
                                    <h2 class="pro-title">{{ $productName }}</h2>
                                    <ul class="pro-subinfo">
                                        <li><strong>@lang('home.Color')</strong><span>{{ $color }}</span></li>
                                        <li><strong>@lang('home.Style')</strong><span>{{ $style }}</span></li>
                                        <li><strong>@lang('home.Size Type')</strong><span>{{ __("home.$sizeType") }}</span></li>
                                        <li><strong>@lang('home.Release Date')</strong><span>{{$releaseDate}}</span></li>
                                        {{--<li id="actual_price"><strong>@lang('home.Retail Price')</strong><span>{{ $retail_price }}</span></li>--}}
                                        <li id="actual_price">
                                            <strong>@lang('home.Retail Price')</strong>
                                            <span>
                                                @if(Session::get('currencyCode') != '')
                                                <span>{{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $currency_code, $retail_price)}}</span>
                                            @else
                                                <span>{{$currency_code}} <span id="priceSpan">{{$retail_price}}</span></span>
                                            @endif()
                                            </span>
                                        </li>

                                        <li id="promo_price_li" style="display: none;"><strong>@lang('home.Retail Price')</strong>{{--<span>{{$currency_code}}</span>--}}<span id="promo_price" style="padding-left:4px;">{{ $retail_price }}</span><span style="color: #00b636"
                                            >(Promo Code Applied)</span>
                                        </li>
                                    </ul>

                                    <div class="sell-buy-btns">
                                    @if($subcriptioncheck == 1)
                                        <button type="button" class="normalButton" data-toggle="modal" data-target="#buy">@lang('home.Buy')</button>
                                    @elseif($subcriptioncheck == 0)
                                        <button type="button" id="buysubscription" class="normalButton vipBuyButton">@lang('home.Please buy Subscription')</button>
                                    @elseif($subcriptioncheck == 2)    
                                        <button type="button" id="pleaselogin" class="normalButton vipBuyButton">@lang('home.Please Login')</button>
                                    @elseif($subcriptioncheck == 3)    
                                        <button type="button" class="normalButton vipBuyButton">@lang('home.Not Available')</button>
                                    @elseif($subcriptioncheck == 4)    
                                        <button type="button" class="normalButton vipBuyButton">@lang('home.Sale is not active yet')</button>
                                    @endif
                                    </div>


                                    <div id="buy" class="modal fade modal-dialog-centered" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered">

                                            <!--Bidder Modal content-->
                                            <div class="modal-content size-poppup">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="text-center model-product-img"><img src="{{ url($mainImage) }}" alt="Product Image" /></h4>
                                                    <h2 class="model-price"></h2>
                                                    <h3>@lang('home.Selected Size') : <span id="selected-size">--</span></h3>
                                                    <span>

                                                        {{Session::get('currencyCode')}}
                                                        <span id="vipPrice">
                                                            {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $currency_code, $retail_price)}}
                                                           {{-- {{$retail_price}}--}}</span>
                                                    </span>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="model-size-chart" id="offer-size-chart">
                                                        @if(count($product_sizes) > 0)
                                                            @foreach($product_sizes as $key=> $sizeList)
                                                            <li id="{{ $sizeList['size'] }}">
                                                                <div class="size-box"><a href="javascript::void();">{{ $sizeList['size'] }}</a>
                                                                </div>
                                                            </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="modal-footer popup-btns">
                                                    <button id="direct-buy" disabled>@lang('home.Buy Now')</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div> <!-- product slider ends -->
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="index-block product-carousel">
        <div class="container">

            <div class="row">
                <div class="innerdiv">
                    <div class="section-title"><h2>@lang('home.Related Products')</h2></div>
                    <div class="item">
                        <ul id="content-slider" class="content-slider">

                            @if(count($relatedProducts) > 0)
                                @foreach($relatedProducts as $key=> $otherProducts)
                                    @if($productID != $otherProducts['id'])
                                        @php
                                            $relatedImages = $otherProducts['product_images'];
                                            $otherImages = explode(',',$relatedImages);
                                            $relatedImage = current($otherImages);
                                        @endphp

                                        
                                            <li>
                                                <a href="{{ url('vip-product-detail').'/'.base64_encode($otherProducts['id']) }}">
                                                <div class="product-grid">
                                                    <div class="product-thumb"><img src="{{ url($relatedImage) }}" alt="" /></div>
                                                    <div class="product-thumb-info">
                                                        <h3 class="brand-title">{{$otherProducts['product_brand']['brand_name']}}</h3>
                                                        <h2 class="product-title">{{str_limit($otherProducts['product_name'],20)}}</h2>
                                                        {{--<div class="product-price"><span><strong>{{$otherProducts['retail_price']}}</strong></span><span>{{$otherProducts['start_counter']}} @lang('home.Sold')</span></div>--}}
                                                        <div class="product-price">
                                                            <span>
                                                            @if(Session::get('currencyCode') != '')
                                                                    <strong>{{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $otherProducts['currency_code'], $otherProducts['retail_price'])}}</strong>
                                                                @else
                                                                    <strong>{{$otherProducts['currency_code']}} {{$otherProducts['retail_price']}}</strong>
                                                                @endif()
                                                            </span>
                                                            <span>{{$otherProducts['start_counter']}} @lang('home.Sold')</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                </a>
                                            </li>
                                    @endif

                                @endforeach
                            @endif

                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </div><!--#realted products slider-->

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $(".default").click();

            $("#content-slider").lightSlider({
                loop:true,
                keyPress:true,
                item:4,
                nav:true,
                pager:false,
                adaptiveHeight : false,
                slideMargin:20,
                responsive : [
                    {
                        breakpoint:800,
                        settings: {
                            item:3,
                            slideMove:1,
                            slideMargin:6,
                        }
                    },
                    {
                        breakpoint:480,
                        settings: {
                            item:2,
                            slideMove:1
                        },
                    }
                ]
            });

            $('#image-gallery').lightSlider({
                gallery:true,
                item:1,
                thumbItem:4,
                slideMargin:0,
                speed:500,
                auto:false,
                loop:true,
                onSliderLoad: function() {
                    $('#image-gallery').removeClass('cS-hidden');
                }
            });
        });
    </script>

    <script>
        $(".model-size-chart li").click(function(){

            $("#selected-size").html(this.id);
            var price = $(this).closest('li').find('span').html();
            $(".model-size-chart li").removeClass('shadow');
            $(this).closest('li').addClass('shadow');
            $("#vipPrice").html(price);
            $('#direct-buy').removeAttr("disabled");

        });


        $("#direct-buy").click(function(){
            var url = $(location).attr('href'),
            parts = url.split("/"),
            last_part = parts[parts.length-1];
            var size = $("#selected-size").html();
            var prices = $("#vipPrice").html();
            window.location.href = '/vip-purchase/'+last_part+'/'+size+'/buyvip';
            return false;
            });

        $('#pleaselogin').click(function() {
            window.location.href = '/signin';
            return false;
        });    

        $('#buysubscription').click(function() {
            window.location.href = '/vip-home';
            return false;
        });    

    </script>

    <script>
        $(function(){
            var $gallery = $('.gallery a').simpleLightbox();

            $gallery.on('show.simplelightbox', function(){
                console.log('Requested for showing');
            })
                .on('shown.simplelightbox', function(){
                    console.log('Shown');
                })
                .on('close.simplelightbox', function(){
                    console.log('Requested for closing');
                })
                .on('closed.simplelightbox', function(){
                    console.log('Closed');
                })
                .on('change.simplelightbox', function(){
                    console.log('Requested for change');
                })
                .on('next.simplelightbox', function(){
                    console.log('Requested for next');
                })
                .on('prev.simplelightbox', function(){
                    console.log('Requested for prev');
                })
                .on('nextImageLoaded.simplelightbox', function(){
                    console.log('Next image loaded');
                })
                .on('prevImageLoaded.simplelightbox', function(){
                    console.log('Prev image loaded');
                })
                .on('changed.simplelightbox', function(){
                    console.log('Image changed');
                })
                .on('nextDone.simplelightbox', function(){
                    console.log('Image changed to next');
                })
                .on('prevDone.simplelightbox', function(){
                    console.log('Image changed to prev');
                });
        });
    </script>
@endsection
