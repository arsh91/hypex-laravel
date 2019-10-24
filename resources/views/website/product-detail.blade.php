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
                        $pass_value = $singleProduct['retail_price'];

                        $productName = $singleProduct['product_name'];
                        if(isset($singleProduct['style']) && $singleProduct['style'] != ''){
                        $style = $singleProduct['style'];
                        }

                        if(isset($singleProduct['retail_price']) && $singleProduct['retail_price'] != ''){
                        $retail_price = $singleProduct['retail_price'];
                        }

                        //PASS CODE
                        if(isset($singleProduct['pass_code']) && $singleProduct['pass_code'] != ''){
                        $pass_code = $singleProduct['pass_code'];
                        }

                        //PASS VALUE, the changed price after applying promo code
                        if(isset($singleProduct['pass_value']) && $singleProduct['pass_value'] != ''){
                        $pass_value = $singleProduct['pass_value'];
                        }

                        if(isset($singleProduct['color']) && $singleProduct['color'] != ''){
                        $color = $singleProduct['color'];
                        }

                        if(isset($singleProduct['product_brand_type']['brand_type_name']) &&
                        $singleProduct['product_brand_type']['brand_type_name'] != ''){
                        $brand = $singleProduct['product_brand_type']['brand_type_name'];
                        }

                        if(isset($singleProduct['product_brand']['brand_name']) &&
                        $singleProduct['product_brand']['brand_name'] != ''){
                        $prodBrand = $singleProduct['product_brand']['brand_name'];
                        }

                        if(isset($singleProduct['product_size_types']['size_type']) &&
                        $singleProduct['product_size_types']['size_type'] != ''){
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
                            <div class="product-info">
                                <ul class="pro-subinfo">
                                    <li class="promoCode">
                                        <div class="promoSection">
                                            <input type="text" name="pass_code" id="promocode"
                                                   placeholder="@lang('home.Enter Promo Code')">
                                            <input type="hidden" name="product_id" value="{{$productID}}"
                                                   id="hiddenProductId">
                                            <button type="button" onclick="calculatePromo(this)">@lang('home.Apply')
                                            </button>
                                        </div>
                                    </li>
                                    <li class="promoAlert"><span id="promoMsg"></span></li>
                                </ul>
                            </div>
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
                                {{--
                                <li id="actual_price"><strong>@lang('home.Retail Price')</strong><span>{{ $retail_price }}</span>
                                </li>
                                --}}
                                <li id="actual_price">
                                    <strong>@lang('home.Retail Price')</strong>
                                    <span>
                                                @if(Session::get('currencyCode') != '')
                                                    <strong>{{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $currency_code, $retail_price)}}</strong>
                                                @else
                                                    <strong>{{$currency_code}} {{$retail_price}}</strong>
                                                @endif
                                            </span>
                                </li>
                                <li id="promo_price_li" style="display: none;">
                                    <strong>@lang('home.Retail Price')</strong>
                                    {{--<span>{{$currency_code}}</span>--}}
                                    <span id="promo_price" style="padding-left:4px;">{{Session::get('currencyCode')}} <p id="promo_updated_price">{{ $retail_price }}</p></span>
                                    <span style="color: #00b636">(Promo Code Applied)</span>
                                </li>
                            </ul>

                            <div class="sell-buy-btns">
                                <button type="button" class="normalButton" data-toggle="modal" data-target="#buy">
                                    @lang('home.Buy / Make Offer')
                                </button>
                                <button type="button" class="normalButton" data-toggle="modal" data-target="#sellOffer">
                                    @lang('home.Sell / Make Offer')
                                </button>
                                <button type="button" class="promoCodeButton" data-toggle="modal"
                                        data-target="#promobuy">@lang('home.Buy Now')
                                </button>
                            </div>


                            <div id="buy" class="modal fade modal-dialog-centered" role="dialog">
                                <div class="modal-dialog modal-dialog-centered">

                                    <!--Bidder Modal content-->
                                    <div class="modal-content size-poppup">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="text-center model-product-img"><img src="{{ url($mainImage) }}"
                                                                                           alt="Product Image"/></h4>
                                            <h2 class="model-price"></h2>
                                            <h3>@lang('home.Selected Size') : <span id="selected-size">--</span></h3>
                                            <span id="sizeBidPrice">--</span>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="model-size-chart">
                                                @if(count($product_sizes) > 0)
                                                @foreach($product_sizes as $key=> $sizeList)
                                                <li id="{{ $sizeList['size'] }}" <?php if ($sizeList['size'] == $maxSellSize) {
													echo "class='default'";
												} ?>>
                                                    <div class="size-box"><a
                                                                href="javascript::void();">{{ $sizeList['size'] }}</a>
                                                        @if(isset($maxBidsData[$sizeList['size']]))
                                                            @if(Session::get('currencyCode') != '')
                                                                <span id="bidPrice"
                                                                      style="font-size:11px;">
                                                                    {{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), 'CAD', $maxBidsData[$sizeList['size']])}}
                                                                    {{--{{Session::get('currencyCode')}} {{ $maxBidsData[$sizeList['size']] }}--}}
                                                                </span>
                                                            @else
                                                                <span id="bidPrice"
                                                                      style="font-size:11px;">CAD {{ $maxBidsData[$sizeList['size']] }}</span>
                                                            @endif
                                                        @else
                                                            <span>--</span>
                                                        @endif
                                                    </div>
                                                </li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <span id="directbuymessage">@lang('home.No Offer Available')</span>
                                        <div class="modal-footer popup-btns">
                                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                            <button id="sell" disabled>@lang('home.Make Buy Offer')</button>
                                            <button id="direct-buy" disabled>@lang('home.Buy Now')</button>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div id="sellOffer" class="modal fade modal-dialog-centered" role="dialog">
                                <div class="modal-dialog modal-dialog-centered">

                                    <!-- Modal content-->
                                    <div class="modal-content size-poppup">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="text-center model-product-img"><img src="{{ url($mainImage) }}"
                                                                                           alt="Product Image"/></h4>
                                            <h2 class="model-price"></h2>
                                            <h3>@lang('home.Selected Size') : <span id="sell-selected-size">--</span>
                                            </h3>
                                            <span id="sizeSellPrice">--</span>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="sell-model-size-chart">
                                                @if(count($product_sizes) > 0)
                                                    @foreach($product_sizes as $key=> $sizeList)
                                                        <li id="{{ $sizeList['size'] }}" <?php if ($sizeList['size'] == $minBidSize) {
															echo "class='default'";
														} ?>>
                                                            <div class="size-box"><a
                                                                        href="javascript::void();">{{ $sizeList['size'] }}</a>
                                                                @if(isset($minSellData[$sizeList['size']]))
                                                                    @if(Session::get('currencyCode') != '')
                                                                        <span id="bidPrice"
                                                                              style="font-size:11px;">
                                                                            {{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), 'CAD', $minSellData[$sizeList['size']])}}
                                                                            {{--{{Session::get('currencyCode')}} {{ $minSellData[$sizeList['size']] }}--}}
                                                                        </span>
                                                                    @else
                                                                        <span id="bidPrice"
                                                                              style="font-size:11px;">CAD {{ $minSellData[$sizeList['size']] }}</span>
                                                                    @endif
                                                                @else
                                                                    <span>--</span>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <span id="directsellmessage">@lang('home.No Offer Available')</span>
                                        <div class="modal-footer popup-btns">
                                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                            <button id="sellNow" disabled>@lang('home.Make Sell Offer')</button>
                                            <button id="direct-sell" disabled>@lang('home.Sell Now')</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Buy now using promo code -->

                            <div id="promobuy" class="modal fade modal-dialog-centered" role="dialog">
                                <div class="modal-dialog modal-dialog-centered">

                                    <!--Bidder Modal content-->
                                    <div class="modal-content size-poppup">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="text-center model-product-img"><img src="{{ url($mainImage) }}"
                                                                                           alt="Product Image"/></h4>
                                            <h2 class="model-price"></h2>
                                            <h3>@lang('home.Selected Size') : <span id="offer-selected-size">--</span>
                                            </h3>
                                            <span id="afterpromoPrice">{{$pass_value}}</span>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="model-size-chart" id="offer-size-chart">
                                                @if(count($product_sizes) > 0)
                                                    @foreach($product_sizes as $key=> $sizeList)
                                                        <li id="{{ $sizeList['size'] }}" <?php if ($sizeList['size']) {
															echo "class='default'";
														} ?>>
                                                            <div class="size-box"><a
                                                                        href="javascript::void();">{{ $sizeList['size'] }}</a>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="modal-footer popup-btns">
                                            <button id="promo-direct-buy" disabled>@lang('home.Buy Now')</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- end buy now using promo code  -->

                        </div>
                    </div> <!-- product slider ends -->
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

@if(count($relatedProducts) > 1)
    <div class="index-block product-carousel">
        <div class="container">

            <div class="row">
                <div class="innerdiv">
                    <div class="section-title"><h2>@lang('home.Related Products')</h2></div>
                    <div class="item">
                        <ul id="content-slider" class="content-slider">

                           
                                @foreach($relatedProducts as $key=> $otherProducts)
                                    @if($productID != $otherProducts['id'])
                                        @php
                                            $relatedImages = $otherProducts['product_images'];
                                            $otherImages = explode(',',$relatedImages);
                                            $relatedImage = current($otherImages);
                                        @endphp


                                        <li>
                                            <a href="{{ url('product-detail').'/'.base64_encode($otherProducts['id']) }}">
                                                <div class="product-grid">
                                                    <div class="product-thumb"><img src="{{ url($relatedImage) }}"
                                                                                    alt=""/></div>
                                                    <div class="product-thumb-info">
                                                        <h3 class="brand-title">{{$otherProducts['product_brand']['brand_name']}}</h3>
                                                        <h2 class="product-title">{{str_limit($otherProducts['product_name'],20)}}</h2>
                                                        {{--<div class="product-price"><span><strong>{{$otherProducts['retail_price']}}</strong></span><span>{{$otherProducts['start_counter']}} @lang('home.Sold')</span></div>--}}

                                                        <div class="product-price">
                                                            <span>
                                                                {{--<strong>{{$otherProducts['retail_price']}}</strong>--}}
                                                                <strong>
                                                                    @if(Session::get('currencyCode') != '')
                                                                        <strong>{{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $otherProducts['currency_code'], $otherProducts['retail_price'])}}</strong>
                                                                    @else
                                                                        <strong>{{$otherProducts['currency_code']}} {{$otherProducts['retail_price']}}</strong>
                                                                    @endif
                                                                </strong>
                                                            </span>
                                                            <span>{{$otherProducts['start_counter']}} @lang('home.Sold')</span>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endif

                                @endforeach
                           



                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endif
<!--#realted products slider-->



    <!--div class="index-block oreder-history">
                    <div class="container">

                        <div class="row">
                            <div class="innerdiv">
							<div class="section-title title-center"><h2>Order History</h2></div>

                                <ul class="oreder-list">
                                    <li>
                                       <div class="h-cell product-img">
                                           <span><img src="{{ url($mainImage) }}" alt="" /></span>
                                       </div>
                                       <div class="h-cell product-detail">
                                           <h3>Nike</h3>
                                           <h2>Acronym x Air Presto Mid 'Dynamic Yellow'</h2>
                                           <h4>201812180112416047696328</h4>

                                       </div>
                                       <div class="h-cell prodcut-size">(8.5)</div>
                                       <div class="h-cell prodcut-price">$852.00</div>
                                    </li>

                                    <li>
                                       <div class="h-cell product-img">
                                           <span><img src="{{ url($mainImage) }}" alt="" /></span>
                                       </div>
                                       <div class="h-cell product-detail">
                                           <h3>Nike</h3>
                                           <h2>Acronym x Air Presto Mid 'Dynamic Yellow'</h2>
                                           <h4>201812180112416047696328</h4>

                                       </div>
                                       <div class="h-cell prodcut-size">(8.5)</div>
                                       <div class="h-cell prodcut-price">$852.00</div>
                                    </li>

                                    <li>
                                       <div class="h-cell product-img">
                                           <span><img src="{{ url($mainImage) }}" alt="" /></span>
                                       </div>
                                       <div class="h-cell product-detail">
                                           <h3>Nike</h3>
                                           <h2>Acronym x Air Presto Mid 'Dynamic Yellow'</h2>
                                           <h4>201812180112416047696328</h4>

                                       </div>
                                       <div class="h-cell prodcut-size">(8.5)</div>
                                       <div class="h-cell prodcut-price">$852.00</div>
                                    </li>


                                </ul>

                            </div>
                        </div>
                    </div>
                </div>


				<div class="index-block outfit-ideas">
                    <div class="container">

                        <div class="row">
                            <div class="innerdiv">
                                <div class="section-title title-center"><h2>Outfit Ideas</h2></div>

                               <div class="idea-gallery gallery">
							   @if(count($prodImages) > 0)
        @foreach($prodImages as $key=> $value)
            <a href="{{ url($value) }}"><img src="{{ url($value) }}" alt="" title="" /></a>
									@endforeach
    @endif
            </div>

         </div>
     </div>
 </div>
</div>



<div class="index-block product-details">
 <div class="container">

     <div class="row">
         <div class="innerdiv">
             <div class="section-title title-center"><h2>Product Details</h2></div>

            <div class="more-details">
             <article>
                 <img src="{{ url($mainImage) }}" alt="" />
                                    <h1>A Complete Guide to This Weekend's Sneakers Releases    </h1>
                                    <p>This week of releases is packed with a ton of collaborations worth paying attention to. Virgil Abloh delivers another round of Nike sneakers, Clot put its own spin on the Air Jordan XIII Low, Aleali May and Maya Moore remixed some Air Jordans of their own, and more.</p><p>
Drops start on Wednesday with the release of two new pairs of the Off-White x Nike Air Force 1 Low. A new "Peace on Earth" colorway of the Puma Clyde Court Disrupt is hitting shelves on Thursday with partial proceeds from each sale being donated to the Trayvon Martin Foundation to fight gun violence. Nike is also dropping a basketball shoe of its own on Thursday, the "Black/Gold" retro of the Zoom LeBron III. Friday's major release is the eco-friendly A-Cold-Wall* x Nike Air Force 1 Low pack. </p><p>
The bulk of this week's big drops take place on Saturday. The OG "Hyper Blue" Nike Air Max Plus, Air Raid II-inspired "Tinker" Air Jordan VIII, Clot x Air Jordan XIII Low, Aleali May x Maya Moore x Air Jordan "Court Lux" pack, Pharrell x Adidas Crazy BYW X, and final installment of the Dragon Ball Z x Adidas collaboration inspired by the character Shenron all hit release this weekend.</p></article>
<article>
<img src="{{ url($mainImage) }}" alt="" />
                                    <h1>A Complete Guide to This Weekend's Sneakers Releases    </h1>
                                    <p>This week of releases is packed with a ton of collaborations worth paying attention to. Virgil Abloh delivers another round of Nike sneakers, Clot put its own spin on the Air Jordan XIII Low, Aleali May and Maya Moore remixed some Air Jordans of their own, and more.</p><p>
Drops start on Wednesday with the release of two new pairs of the Off-White x Nike Air Force 1 Low. A new "Peace on Earth" colorway of the Puma Clyde Court Disrupt is hitting shelves on Thursday with partial proceeds from each sale being donated to the Trayvon Martin Foundation to fight gun violence. Nike is also dropping a basketball shoe of its own on Thursday, the "Black/Gold" retro of the Zoom LeBron III. Friday's major release is the eco-friendly A-Cold-Wall* x Nike Air Force 1 Low pack. </p><p>
The bulk of this week's big drops take place on Saturday. The OG "Hyper Blue" Nike Air Max Plus, Air Raid II-inspired "Tinker" Air Jordan VIII, Clot x Air Jordan XIII Low, Aleali May x Maya Moore x Air Jordan "Court Lux" pack, Pharrell x Adidas Crazy BYW X, and final installment of the Dragon Ball Z x Adidas collaboration inspired by the character Shenron all hit release this weekend.</p>
</article>
                               </div>

                            </div>
                        </div>
                    </div>
                </div-->



@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            //Just hide the promo code button on load it will only show when promo code applied successfully
            $('.promoCodeButton').hide();

            $(".default").click();

            $("#content-slider").lightSlider({
                loop: true,
                keyPress: true,
                item: 4,
                nav: true,
                pager: false,
                adaptiveHeight: false,
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

    <script>
        $(".model-size-chart li").click(function () {

            $("#selected-size").html(this.id);
            var price = $(this).closest('li').find('span').html();
            $(".model-size-chart li").removeClass('shadow');
            $(this).closest('li').addClass('shadow');
            $("#sizeBidPrice").html(price);
            $('#sell').removeAttr("disabled");
            $('#direct-buy').removeAttr("disabled");

        });

        // promo offer

        $("#offer-size-chart li").click(function () {
            $("#offer-selected-size").html(this.id);
            var price = $(this).closest('li').find('span').html();
            $(".model-size-chart li").removeClass('shadow');
            $(this).closest('li').addClass('shadow');
            $("#afterpromoPrice").html(price);
            $('#promo-direct-buy').removeAttr("disabled");
        });

        // promo offer end

        $(".sell-model-size-chart li").click(function () {

            $("#sell-selected-size").html(this.id);
            var price = $(this).closest('li').find('span').html();
            $(".sell-model-size-chart li").removeClass('shadow');
            $(this).closest('li').addClass('shadow');
            $("#sizeSellPrice").html(price);
            $('#sellNow').removeAttr("disabled");
            $('#direct-sell').removeAttr("disabled");


        });

        $("#sell").click(function () {

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var size = $("#selected-size").html();

            window.location.href = '/product-bid/' + last_part + '/' + size + '/bidoffer';
            return false;


        });
        $('#directbuymessage').hide();
        $("#direct-buy").click(function () {

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var size = $("#selected-size").html();
            var prices = $("#sizeBidPrice").html();
            if (prices == '--') {
                //console.log(prices);
                $('#directbuymessage').show();
                setTimeout(function () {
                    $('#directbuymessage').fadeOut('slow');
                }, 2000); // <-- time in milliseconds
                return false;
            } else {
				
				window.location.href = '/product-bid/' + last_part + '/' + size + '/buynow';
				//window.location.href = '/direct-purchase/' + last_part + '/' + size + '/buynow';
				
            }
            return false;


        });


        // promo direct buy

        $("#promo-direct-buy").click(function () {
            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];
            var size = $("#offer-selected-size").html();
            var prices = $("#afterpromoPrice").html();
            window.location.href = '/promo-purchase/' + last_part + '/' + size + '/buypromo';
            return false;
        });
        // end promo direct buy

        $("#sellNow").click(function () {

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var size = $("#sell-selected-size").html();

            window.location.href = '/product-sell/' + last_part + '/' + size + '/selloffer';
            return false;


        });
        $('#directsellmessage').hide();
        $("#direct-sell").click(function () {

            var url = $(location).attr('href'),
                parts = url.split("/"),
                last_part = parts[parts.length - 1];

            var size = $("#sell-selected-size").html();
            var sellprices = $("#sizeSellPrice").html();
            if (sellprices == '--') {
                console.log(sellprices);
                $('#directsellmessage').show();
                setTimeout(function () {
                    $('#directsellmessage').fadeOut('slow');
                }, 2000); // <-- time in milliseconds
                return false;
            } else {
                window.location.href = '/sell-now/' + last_part + '/' + size + '/sellnow';
            }
            return false;


        });

        //Ajax method to calculate discounted price after applying PROMO CODE
        function calculatePromo(obj) {
            var promoCode = $('#promocode').val();
            var productID = $('#hiddenProductId').val();
            if (promoCode != '' && promoCode != undefined) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ url('/') }}/applyProductPromoCode',
                    type: "POST",
                    dataType: 'json',
                    data: {
                        promoCode: promoCode,
                        productID: productID
                    },
                    success: function (response) { // What to do if we succeed
                        //console.log(response);
                        $('#promoMsg').show();
                        if (response.isFound == 1) {
                            //Just remove those 2 buttons which are doing normal process
                            $('.normalButton').remove();
                            //show the direct buy button to continue with promo offer process
                            $('.promoCodeButton').show();
                            console.log(response.pass_value);
                            $('#promoMsg').html(response.message);
                            $('#promo_price_li').show();
                            $('#actual_price').remove();
                            $('#promo_updated_price').html(response.pass_value);

                        } else {
                            console.log('not found case');
                            $('#promoMsg').html(response.message);
                        }
                        setTimeout("$('#promoMsg').hide();", 3000); // hide promo code message
                    }
                });
            } else {
                alert('Please fill promo code!');
            }
        }

    </script>

    <script>
        $(function () {
            var $gallery = $('.gallery a').simpleLightbox();

            $gallery.on('show.simplelightbox', function () {
                console.log('Requested for showing');
            })
                .on('shown.simplelightbox', function () {
                    console.log('Shown');
                })
                .on('close.simplelightbox', function () {
                    console.log('Requested for closing');
                })
                .on('closed.simplelightbox', function () {
                    console.log('Closed');
                })
                .on('change.simplelightbox', function () {
                    console.log('Requested for change');
                })
                .on('next.simplelightbox', function () {
                    console.log('Requested for next');
                })
                .on('prev.simplelightbox', function () {
                    console.log('Requested for prev');
                })
                .on('nextImageLoaded.simplelightbox', function () {
                    console.log('Next image loaded');
                })
                .on('prevImageLoaded.simplelightbox', function () {
                    console.log('Prev image loaded');
                })
                .on('changed.simplelightbox', function () {
                    console.log('Image changed');
                })
                .on('nextDone.simplelightbox', function () {
                    console.log('Image changed to next');
                })
                .on('prevDone.simplelightbox', function () {
                    console.log('Image changed to prev');
                });
        });
    </script>
@endsection
