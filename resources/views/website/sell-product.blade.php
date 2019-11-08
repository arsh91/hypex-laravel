@extends('layouts.website')

@if ($message = Session::get('success'))
    <div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@section('content')
    <form id="sellData" name="sellData" method="POST" action="{{ route('saveSell') }}">
        @csrf
        <div class="newSection">
            @php
                $prodData = current($productDetails);
                if(isset($prodData['product_sizes']) && $prodData['product_sizes'] != ''){
                    $product_sizes = $prodData['product_sizes'];
                }

                $shippingdata = current($productDetails['shippingAddress']);
                $returndata = current($productDetails['returnAddress']);
                $file = $prodData['product_image_link'];
                $mainImage = current($file);
                $currCode = Session::get('currencyCode');

                $highestBuyingOffer = '';
                if(isset($minSellData[$productDetails['size']])) {
                    if($currCode != '') {
                        $highestBuyingOffer = $currCode.''.$minSellData[$productDetails['size']];
                    }
                    else{
                        $highestBuyingOffer = '$'.$minSellData[$productDetails['size']];
                    }
                            
                }   

                $lowestSellingOffer = '';
                if(isset($maxBidsData[$productDetails['size']])) {
                    if($currCode != '') {
                        $lowestSellingOffer = $currCode.''.$maxBidsData[$productDetails['size']];
                    }
                    else{
                        $lowestSellingOffer = '$'.$maxBidsData[$productDetails['size']];
                    }
                            
                }

            @endphp
            <?php //echo "check value--->";  print_r($productDetails['sellerOffer']);  ?>
            <section class="details-section">
                <div class="container">
                    <div class="row flexed-row">
                        <div class="col-md-7 value-filed col">
                            <div class="heading-conatiner text-center">
                                <h1>{{ $prodData['product_name'] }} {{ $prodData['product_brand_type']['brand_type_name'] }}</h1>

                                <div class="purple-btn" data-toggle="modal" data-target="#sell">@lang('home.Selected Size') : <span>{{ $productDetails['size'] }}</span></div>
                                <input type="hidden" name="hiddenSizeId" value="{{ $productDetails['sizeID'] }}">
                                <input type="hidden" name="hiddenProdId" value="{{ $prodData['id'] }}">
                                <input type="hidden" name="hiddenBidType" id="hiddenBidType" value="sell-offer">


                                <!-- <div class="purple-btn">Selected Size: <span>6.5</span></div> -->
                                <div class="dotted-line"></div>
                                <ul>
                                    
                                    <li class="text-left">
                                        @if(isset($maxBidsData[$productDetails['size']]))
                                                @lang('home.Lowest Selling Offer'): 
                                                <span>{{$lowestSellingOffer}}</span>
                                                <input type="hidden" name="hiddenPrice" id="hiddenPrice" value="{{ $maxBidsData[$productDetails['size']] }}">
                                        @else
                                            <input type="hidden" name="hiddenPrice" value="0">
                                        @endif
                                    </li>
                                    <li class="text-right">
                                        @if(isset($minSellData[$productDetails['size']]))@lang('home.Highest Buying Offer'): <span>{{$highestBuyingOffer}} </span>
                                        <input type="hidden" name="hiddenHighestPrice" id="hiddenHighestPrice" value="{{ $minSellData[$productDetails['size']] }}">
                                        @endif
                                    </li>
                                    
                                </ul>
                                <!--##### FILL THE CURRENCY FROM SESSION IF PRESENT ELSE DEFAULT  ####-->
                                @if(Session::get('currencyCode') != '')
                                    <input type="hidden" name="hiddenCurrency" value="{{Session::get('currencyCode')}}">
                                @else
                                    <input type="hidden" name="hiddenCurrency" value="CAD">
                                @endif
                                <!--##### FILL THE CURRENCY FROM SESSION IF PRESENT ELSE DEFAULT  ####-->
                            </div>
                            <div class="slider-container">
                                <img src="{{ url($mainImage) }}" alt="" title="" />
                            </div>
                        </div>
                        <div class="col-md-5 col grey-bg" id="sliderContainer">
                                <div class="row shippingLoader" style="display: none;"></div>
                                <div class="slider">
                                    <div id="step1" class="step">
                                        @if($productDetails['sellerOffer'])
                                            <div class="switch-container">
                                                <div class="switch-container-switcher bid-switch">
                                                    <div class="bid active" id="sell-offer-section">Make Offer</div>
                                                    <div class="bid" id="sell-now-section">Sell Now</div>
                                                </div>
                                            </div> <!--##BID & BUY NOW Buttons-->
                                        @endif
                                        <div class="tile offer-tile">                                    
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('home.Enter An Offer'):</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input id="enterBidPrice" type="number" min="1" step="1" name="bid_price" required placeholder="@lang('home.Enter Amount')" class="form-control text-box" value=""/>
                                                        <span class="price-message"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group expiration-section-cont">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('home.Expiration Date'):</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select name="bid_days" class="form-control text-box" required placeholder="Select Date">
                                                            <option value="15">15 Days</option>
                                                            <option value="30">30 Days</option>
                                                            <option value="45">45 Days</option>
                                                            <option value="60">60 Days</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group expiration-section-cont refundable-dep-cont">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label style="color:#9013fe;">@lang('home.Refundable Deposit') <a href="#" data-toggle="modal" data-target="#refundablePopup"><i id="refundableFontIcon" class="fas fa-question-circle"></i></a></label>
                                                    </div>
                                                    <div class="col-md-6 value-filed commissionFee" style="color:#9013fe;">
                                                        --
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="tile add-padding offer-tile">
                                            <ul class="values-ul">
                                                <li id="shiipingCostSection">
                                                    <div class="row shippingDiv">
                                                        <div class="col-md-6">
                                                            <label>Shipping Cost: </label>
                                                        </div>
                                                        <div class="col-md-6 value-filed priceP">
                                                            --
                                                        </div>
                                                    </div>
                                                </li><!--##shippingCost-->

                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>@lang('home.Processing Fee') (+3%): </label>
                                                        </div>
                                                        <div class="col-md-6 value-filed processingFeeCal">
                                                            --
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            
                                            <div class="dotted-line"></div>
                                            
                                            <ul class="values-ul">
                                                <li>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Total Payout:</label>
                                                        </div>
                                                        <div class="col-md-6 value-filed totalPrice">
                                                            --
                                                        </div>
                                                        <div class="col-md-12">
                                                            <p>I have read
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#sellingagrePopup" class="agreement-link">@lang('home.Selling Agreement')
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="dotted-line"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div id="step2" class="step">
                                        <div class="tile add-padding">
                                            <div class="tile-heading" id="shipping-header">
                                                <h2 class="text-uppercase">
                                                    Shipping Address
                                                    <span class="total-amount-box">Total: <font class="total-amount-display">--</font></span>
                                                </h2>
                                                <div class="dotted-line"></div>
                                                <span class="shipping-error"></span>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>First Name:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input required class="form-control text-box" id="shipping_first_name" placeholder="@lang('home.First Name')"
                                                       name="shipping_first_name" type="text"
                                                       value="{{old('shipping_first_name', $shippingdata['first_name'])}}" maxlength='15'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Last Name:</label>
                                                    </div>
                                                    <div class="col-md-8">                         
                                                        <input required class="form-control text-box" id="shipping_last_name" placeholder="@lang('home.Last Name')"
                                                       name="shipping_last_name" type="text"
                                                       value="{{old('shipping_last_name',$shippingdata['last_name'])}}" maxlength='15'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Full Address:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input required class="form-control text-box" id="shipping_full_address"
                                                       placeholder="@lang('home.Full Address')"
                                                       name="shipping_full_address" type="text" value="{{old('full_address' , $shippingdata['full_address'])}}"
                                                       maxlength='150'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>City:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input required class="form-control text-box" id="shipping_street_city" placeholder="@lang('home.City')"
                                                       name="shipping_street_city" type="text"
                                                       value="{{old('shipping_street_city' , $shippingdata['street_city'])}}" maxlength='15'>
                                                    </div>
                                                </div>
                                            </div>                                    
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Country:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select name="shipping_country" id="shipping_country" class="form-control text-box">
                                                            <option id="CA" value="CA" <?php if ($shippingdata['country'] == 'CA' ) echo 'selected' ; ?>>@lang('home.Canada')</option>
                                                            <option id="CN" value="CN" <?php if ($shippingdata['country'] == 'CN' ) echo 'selected' ; ?>>@lang('China')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Province:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select class="form-control text-box" name="shipping_province" id="shipping_province"
                                                            required="" >
                                                            <option class="provOption CA" value="AB" <?php if ($shippingdata['province'] == 'AB' ) echo 'selected' ; ?>>@lang('home.Alberta')</option>
                                                            <option class="provOption CA" value="BC" <?php if ($shippingdata['province'] == 'BC' ) echo 'selected' ; ?>>@lang('home.British Columbia')</option>
                                                            <option class="provOption CA" value="MB" <?php if ($shippingdata['province'] == 'MB' ) echo 'selected' ; ?>>@lang('home.Manitoba')</option>
                                                            <option class="provOption CA" value="NB" <?php if ($shippingdata['province'] == 'NB' ) echo 'selected' ; ?>>@lang('home.New Brunswick')</option>
                                                            <option class="provOption CA" value="Newfoundland and Labrador" <?php if ($shippingdata['province'] == 'Newfoundland and Labrador' ) echo 'selected' ; ?>>@lang('home.Newfoundland and Labrador')</option>
                                                            <option class="provOption CA" value="NL" <?php if ($shippingdata['province'] == 'NL' ) echo 'selected' ; ?>>@lang('home.Northwest Territories')</option>
                                                            <option class="provOption CA" value="NS" <?php if ($shippingdata['province'] == 'NS' ) echo 'selected' ; ?>>@lang('home.Nova Scotia')</option>
                                                            <option class="provOption CA" value="NU" <?php if ($shippingdata['province'] == 'NU' ) echo 'selected' ; ?>>@lang('home.Nunavut')</option>
                                                            <option class="provOption CA" value="ON" <?php if ($shippingdata['province'] == 'ON' ) echo 'selected' ; ?>>@lang('home.Ontario')</option>
                                                            <option class="provOption CA" value="PE" <?php if ($shippingdata['province'] == 'PE' ) echo 'selected' ; ?>>@lang('home.Prince Edward Island')</option>
                                                            <option class="provOption CA" value="QC" <?php if ($shippingdata['province'] == 'QC' ) echo 'selected' ; ?>>@lang('home.Quebec')</option>
                                                            <option class="provOption CA" value="SK" <?php if ($shippingdata['province'] == 'SK' ) echo 'selected' ; ?>>@lang('home.Saskatchewan')</option>
                                                            <option class="provOption CA" value="YT" <?php if ($shippingdata['province'] == 'YT' ) echo 'selected' ; ?>>@lang('home.Yukon')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Zip Code:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input required class="form-control text-box zip-field-2" id="shipping_zip" placeholder="@lang('home.Zip Code')"
                                                       name="shipping_zip"
                                                       type="text" value="{{old('shipping_zip',$shippingdata['zip_code'])}}" maxlength='8' minlength='4'>
                                                    </div>
                                                </div>
                                            </div>                                  
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Phone Number:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input required class="form-control text-box" id="shipping_phone" placeholder="@lang('home.Phone Number')"
                                                       name="shipping_phone" type="text" value="{{old('shipping_phone',$shippingdata['phone_number'])}}"
                                                       maxlength='15'>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dotted-line"></div>
                                        </div>
                                    </div>

                                    <!--###### Billing and Return Address ########-->
                                    <div id="step3">
                                        <div class="tile add-padding">
                                            <div class="tile-heading" id="shipping-header">
                                                <h2 class="text-uppercase">
                                                    @lang('home.Return Address')
                                                    <span class="total-amount-box">Total: <font class="total-amount-display">--</font></span>
                                                </h2>
                                                <div class="dotted-line"></div>
                                                <span class="shipping-error"></span>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('home.First Name'):</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                       <input class="form-control text-box" required id="billing_first_name" placeholder="@lang('home.First Name')"
                                                        name="billing_first_name" type="text"
                                                        value=" {{old('billing_first_name',$returndata['first_name'])}}"  maxlength='15'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('home.Last Name'):</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                       <input required class="form-control text-box" id="billing_last_name" placeholder="@lang('home.Last Name')"
                                                        name="billing_last_name" type="text" value="{{old('billing_last_name',$returndata['last_name'])}}"
                                                         maxlength='15'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('home.Full Address'):</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input required class="form-control text-box" id="billing_full_address"
                                                       placeholder="@lang('home.Full Address')"
                                                       name="billing_full_address" type="text" value="{{old('full_address' , $returndata['full_address'])}}"
                                                       maxlength='150'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('home.City'):</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                       <input class="form-control text-box" required id="billing_street_city" placeholder="@lang('home.City')"
                                                        name="billing_street_city" type="text"
                                                        value="{{old('billing_street_city',$returndata['street_city'])}}" maxlength='15'>

                                                    </div>
                                                </div>
                                            </div>                                    
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Country:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select name="billing_country" id="billing_country" class="form-control">
                                                            <option value="CA" <?php if ($returndata['country'] == 'CA' ) echo 'selected' ; ?>>@lang('home.Canada')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Province:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <select class="form-control" name="billing_province" id="billing_province" required="">
                                                            <option class="provOption CA" value="AB" <?php if ($returndata['province'] == 'AB' ) echo 'selected' ; ?>>@lang('home.Alberta')</option>
                                                            <option class="provOption CA" value="BC" <?php if ($returndata['province'] == 'BC' ) echo 'selected' ; ?>>@lang('home.British Columbia')</option>
                                                            <option class="provOption CA" value="MB" <?php if ($returndata['province'] == 'MB' ) echo 'selected' ; ?>>@lang('home.Manitoba')</option>
                                                            <option class="provOption CA" value="NB" <?php if ($returndata['province'] == 'NB' ) echo 'selected' ; ?>>@lang('home.New Brunswick')</option>
                                                            <option class="provOption CA" value="Newfoundland and Labrador" <?php if ($returndata['province'] == 'Newfoundland and Labrador' ) echo 'selected' ; ?>>@lang('home.Newfoundland and Labrador')</option>
                                                            <option class="provOption CA" value="NL" <?php if ($returndata['province'] == 'NL' ) echo 'selected' ; ?>>@lang('home.Northwest Territories')</option>
                                                            <option class="provOption CA" value="NS" <?php if ($returndata['province'] == 'NS' ) echo 'selected' ; ?>>@lang('home.Nova Scotia')</option>
                                                            <option class="provOption CA" value="NU" <?php if ($returndata['province'] == 'NU' ) echo 'selected' ; ?>>@lang('home.Nunavut')</option>
                                                            <option class="provOption CA" value="ON" <?php if ($returndata['province'] == 'ON' ) echo 'selected' ; ?>>@lang('home.Ontario')</option>
                                                            <option class="provOption CA" value="PE" <?php if ($returndata['province'] == 'PE' ) echo 'selected' ; ?>>@lang('home.Prince Edward Island')</option>
                                                            <option class="provOption CA" value="QC" <?php if ($returndata['province'] == 'QC' ) echo 'selected' ; ?>>@lang('home.Quebec')</option>
                                                            <option class="provOption CA" value="SK" <?php if ($returndata['province'] == 'SK' ) echo 'selected' ; ?>>@lang('home.Saskatchewan')</option>
                                                            <option class="provOption CA" value="YT" <?php if ($returndata['province'] == 'YT' ) echo 'selected' ; ?>>@lang('home.Yukon')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Zip Code:</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                       <input required class="form-control text-box zip-field-2" id="billing_zip" placeholder="@lang('home.Zip Code')"
                                                        name="billing_zip"
                                                        type="text" value="{{old('billing_zip',$returndata['zip_code'])}}" maxlength='8' minlength='4'>
                                                    </div>
                                                </div>
                                            </div>                                  
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>@lang('home.Phone Number'):</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                       <input class="form-control text-box" required id="billing_phone" placeholder="@lang('home.Phone Number')"
                                                        name="billing_phone" type="text" value="{{old('billing_phone',$returndata['phone_number'])}}"
                                                        maxlength='15'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display:none;">
                                                <div class="panel-body">
                                                    <input required id="length" placeholder="@lang('home.Length (cm)')" name="length" type="text"
                                                           value="Length: 35.00 (cm)">
                                                    <input required id="width" placeholder="@lang('home.Width (cm)')" name="width" type="text"
                                                           value="Width: 23.50 (cm)">
                                                    <input required id="height" placeholder="@lang('home.Height (cm)')" name="height" type="text"
                                                           value="Height: 13.50 (cm)">
                                                    <input required id="weight" placeholder="@lang('home.Weight (lb)')" name="weight" type="text"
                                                           value="Weight: 3(lb)">
                                                </div>
                                            </div>

                                            <div class="dotted-line"></div>
                                        </div>
                                    </div><!--#Billing and Return Address-->
									
									<div id="step4">
                                        <div class="tile add-padding">
                                            <div class="tile-heading">
                                                <h2 class="text-uppercase">
                                                    Payment Method
                                                    <span class="total-amount-box">Total: <font class="total-amount-display">--</font></span>
                                                </h2>
                                                <div class="dotted-line"></div>
                                            </div>
                                            <input type="submit" class="purple-btn payment-button" id="bidNowButton" value="Add Credit Card" disabled="disabled" />
                                            
                                            <div class="dotted-line"></div>
                                            
                                        </div>
                                    </div>
									
                                    <div id="step5">
                                        <div class="tile add-padding">
                                            <div class="tile-heading">
                                                <h2 class="text-uppercase">
                                                    Payment Method
                                                    <span class="total-amount-box">Total: <font class="total-amount-display">--</font></span>
                                                </h2>
                                                <div class="dotted-line"></div>
                                            </div>
                                            <input type="submit" class="purple-btn payment-button" id="bidNowButton" value="Add Credit Card" disabled="disabled" />
                                            
                                            <div class="dotted-line"></div>
                                            
                                        </div>
                                    </div>
                            </div>
                            <div class="buying-payment-conatiner step-labels-cont">
                                <ul>
                                    <li class="shipping-address-label">
                                        <i class="fas fa-home"></i>
                                        <font id="addressTabShippingTitle">
                                            @if(count($shippingdata) > 0 && $shippingdata['full_address'] != '')
                                                {{$shippingdata['full_address']}}
                                            @else
                                                Shipping Address
                                            @endif
										</font>
										/
										<font id="addressTabReturnTitle">
                                            @if(count($returndata) > 0 && $returndata['full_address'] != '')
                                                {{$returndata['full_address']}}
                                            @else
                                                Return Address
                                            @endif
                                        </font>
                                        <div class="editShipping floatRight"><i class="fas fa-edit"></i></div>
                                    </li>
									<li class="shipping-address-label">
                                        <i class="fas fa-home"></i>

									</li>
                                    <!--<li class="return-address-label">
                                        <i class="fas fa-home"></i>
										<font class="addressTabTitle">
                                            @if(count($returndata) > 0 && $returndata['full_address'] != '')
                                                {{$returndata['full_address']}}
                                            @else
                                                Return Address
                                            @endif
                                        </font>
										<div class="editReturn floatRight"><i class="fas fa-edit"></i></div>
                                    </li>-->
                                    <li class="payment-method-label">
                                        <i class="far fa-credit-card"></i> Payout Info
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div><!--#newSection-->
        <!--##second Popup-->
        <div id="refundablePopup" class="modal fade modal-dialog-centered" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content size-poppup">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h2 class="model-price"></h2>
                    </div>
                    <div class="modal-body">
                        @lang('home.Refundable content will be updated soon').
                    </div>
                    <div class="modal-footer popup-btns">
                    </div>
                </div>

            </div>
        </div><!--#refundable PopUp now-->
		<input type="hidden" id="pageType" value="{{ $pageType }}">
    </form>

    <div id="sell" class="modal fade modal-dialog-centered newSizePopup" role="dialog">
        <div class="modal-dialog modal-dialog-centered">

            <!--Bidder Modal content-->
            <div class="modal-content size-poppup">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="text-center model-product-img"><img src="{{ url($mainImage) }}"
                                                                   alt="Product Image"/></h4>
                    <h2 class="model-price"></h2>
                    <h3>@lang('home.Selected Size') : <span id="selected-size">--</span></h3>
                    <span id="sizeSellPrice">--</span>
                </div>
                <div class="modal-body">
                    <ul class="model-size-chart">
                        @if(count($product_sizes) > 0)
                        @foreach($product_sizes as $key=> $sizeList)
                        <li id="{{ $sizeList['size'] }}" <?php if ($sizeList['size'] == $productDetails['size']) {
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

@endsection

@section('scripts')
    <script>

    /*
    * Script to run bxslider
    */

    var slider = $('.slider').bxSlider({
        adaptiveHeight: true,
        hideControlOnEnd: true,
        infiniteLoop: false,
        pager: false,
        prevText: 'PREVIOUS',
        nextText: 'NEXT',
        touchEnabled: false,
        onSlideAfter: function($slideElement, oldIndex, newIndex){
            $('.step-labels-cont li').show();
            switch(newIndex){
            case 0 : /*code for slide 1*/; break;
            case 1 :
                $('li.shipping-address-label').hide();
                break;
			case 2 :
                $('li.return-address-label').hide();
                break;
            case 3 :
                getShipmentRate(true);
                $('li.payment-method-label').hide();
                break;
				/*etc*/
            }
        },
        onSlideBefore: function($slideElement, oldIndex, newIndex){
            //alert(oldIndex +'--'+newIndex);
            if(newIndex == 1){
                var amt = $('#enterBidPrice').val();
                if(amt == '' || isNaN(amt)){
                    $('#enterBidPrice').css('border-color', 'red');
                    return false;
                }else{
                    $('#enterBidPrice').css('border-color', '#e9e9e9');
                }
            }else if(newIndex == 2){
                var noError = true;
				var amt = $('#enterBidPrice').val();
                if(amt == '' || isNaN(amt)){
                    $('#enterBidPrice').css('border-color', 'red');
					noError = false;
                } else {
					if($.trim($('#shipping_first_name').val()) == ''){
						$('#shipping_first_name').css('border-color', 'red');
						noError = false;
					}
					if($.trim($('#shipping_last_name').val()) == ''){
						$('#shipping_last_name').css('border-color', 'red');
						noError = false;
					}
					if($.trim($('#shipping_full_address').val()) == ''){
						$('#shipping_full_address').css('border-color', 'red');
						noError = false;
					}
					if($.trim($('#shipping_street_city').val()) == ''){
						$('#shipping_street_city').css('border-color', 'red');
						noError = false;
					}
					if($.trim($('#shipping_zip').val()) == ''){
						$('#shipping_zip').css('border-color', 'red');
						noError = false;
					}
					if($.trim($('#shipping_phone').val()) == ''){
						$('#shipping_phone').css('border-color', 'red');
						noError = false;
					}
					if(noError == false) {
						slider.goToSlide(1);
					}
				}
                return noError;
            } else if(newIndex == 3){
                var noError = true;
                if($.trim($('#billing_first_name').val()) == ''){
                    $('#billing_first_name').css('border-color', 'red');
                    noError = false;
                }
                if($.trim($('#billing_last_name').val()) == ''){
                    $('#billing_last_name').css('border-color', 'red');
                    noError = false;
                }
                if($.trim($('#billing_full_address').val()) == ''){
                    $('#billing_full_address').css('border-color', 'red');
                    noError = false;
                }
                if($.trim($('#billing_street_city').val()) == ''){
                    $('#billing_street_city').css('border-color', 'red');
                    noError = false;
                }
                if($.trim($('#billing_zip').val()) == ''){
                    $('#billing_zip').css('border-color', 'red');
                    noError = false;
                }
                if($.trim($('#billing_phone').val()) == ''){
                    $('#billing_phone').css('border-color', 'red');
                    noError = false;
                }
                return noError;
            }
        }
    });


    //SCRIPTS FOR POPUP

    $(document).ready(function(){
		var formAction = "{{ url('/') }}/";
		
		//CLICK THE DEFAULT FILLED BOX IN POPUP OF SIZE WITH PRICE
        $(".default").click(); 
		
		//button toggle 
		$('.switch-container .bid').click(function(e){
		
			var lowestSellOffer = $('#hiddenHighestPrice').val();
			var containerID = $(this).attr('id');
			$('.bid').removeClass('active');
			$(this).addClass('active');
			
			//CASE : When Buy Offer
			//fill amount in enter an offer box
			$('#enterBidPrice').empty();
			$('#enterBidPrice').prop('readonly', false);
			$('#hiddenBidType').val('sell-offer');
			$('.refundable-dep-cont, .expiration-section-cont').show();
			$('#sellData').attr('action', formAction+'savesell');
			$('.price-message').html('');
			
			//CASE I :- When directly buy
			if(containerID == 'sell-now-section'){
				//console.log('Sell now');
				$('.refundable-dep-cont, .expiration-section-cont').hide();
				$('#enterBidPrice').val(lowestSellOffer);
				$('#enterBidPrice').trigger('change');
				$('#enterBidPrice').prop('readonly', true);
				$('#hiddenBidType').val('sell-now');
				$('#sellData').attr('action', formAction+'sell-bid');
				//$('.price-message').html('You are about to purchase this product at the lowest sellling price');
				getPriceCalculations();
				//$('#enterBidPrice').trigger('change');
			} else if( containerID == "sell-offer-section") {
				$('#enterBidPrice').val('');
				$('.priceP').html('--'); //add plus sign with shipping rate
				$('.commissionFee').html('--');
				$('.processingFeeCal').html('--');
				$('.totalPrice').html('--');
				$('.total-amount-display').html('--');
			}
			slider.reloadSlider();
		});
		
		$('#shipping_full_address').on('blur',function(){
			$('#addressTabShippingTitle').html('Billing / Shipping Address');
			if($.trim($(this).val()) != '' ){
				$('#addressTabShippingTitle').html($(this).val());
			}
		});
		
		$('#billing_full_address').on('blur',function(){
			$('#addressTabReturnTitle').html('Return Address');
			if($.trim($(this).val()) != '' ){
				$('#addressTabReturnTitle').html($(this).val());
			}
		});
		
		$('.editShipping').on('click', function(){
			slider.goToSlide(1);
		});
		
		$('.editReturn').on('click', function(){
			slider.goToSlide(2);
		});
		
		if($('#pageType').val() == 'sellnow'){
			$('#sell-now-section').trigger('click');
		}
		
    });


    $(".model-size-chart li").click(function () {

        $("#selected-size").html(this.id);
        var price = $(this).closest('li').find('span').html();
        $(".model-size-chart li").removeClass('shadow');
        $(this).closest('li').addClass('shadow');
        $("#sizeSellPrice").html(price);
        $('#sellNow').removeAttr("disabled");
        $('#direct-sell').removeAttr("disabled");

    });


    $("#sellNow").click(function () {

        var productID = "<?php echo base64_encode($prodData['id']); ?>";

        var size = $("#selected-size").html();

        window.location.href = '/product-sell/' + productID + '/' + size + '/selloffer';
        return false;
    });
    
    $('#directsellmessage').hide();

    $("#direct-sell").click(function () {
        var productID = "<?php echo base64_encode($prodData['id']); ?>";

        var size = $("#selected-size").html();
        var sellprices = $("#sizeSellPrice").html();
        
        if (sellprices == '--') {
            //console.log(sellprices);
            $('#directsellmessage').show();
            setTimeout(function () {
                $('#directsellmessage').fadeOut('slow');
            }, 2000); // <-- time in milliseconds
            return false;
        } else {
            //window.location.href = '/sell-now/' + productID + '/' + size + '/sellnow';
            window.location.href = '/product-sell/' + productID + '/' + size + '/sellnow';
        }
        return false;


    });

       
    //$('#shipping_country').attr("style", "pointer-events: none;");
    //$('#shipping_province').attr("style", "pointer-events: none;");
    
    //$('#billing_country').attr("style", "pointer-events: none;");
    //$('#billing_province').attr("style", "pointer-events: none;");
       
    $("#shipping_phone,#billing_phone").keydown(function (event) {

        var number = $("#shipping_phone").val();
        var length = number.length;
        var num = event.keyCode;

        if (length > 12 && num != 8) {
            event.preventDefault();
        }


        if ((num > 95 && num < 106) || (num > 36 && num < 41) || num == 9) {
            return;
        }
        if (event.shiftKey || event.ctrlKey || event.altKey) {
            event.preventDefault();
        } else if (num != 46 && num != 8) {
            if (isNaN(parseInt(String.fromCharCode(event.which)))) {
                event.preventDefault();
            }
        }
    });
	
	//Script for messages
	$("#enterBidPrice").keyup(function (event) {
		var currentPrice = parseInt($(this).val());
		var LowestPrice = parseInt($('#hiddenPrice').val());
		var HighestPrice = parseInt($('#hiddenHighestPrice').val());
		var minimumAmt = parseInt('25');
		
		if(currentPrice <= minimumAmt){
			$('.price-message').html('You must meet the minimum selling offer of $25');
		} else if(currentPrice <= HighestPrice) {
			$('.price-message').html('You are about to sell at the highest Buying offer');
		}else if(currentPrice > HighestPrice && currentPrice < LowestPrice) {
			$('.price-message').html('You are about to be the lowest selling offer');
		} else if(currentPrice == LowestPrice) {
			$('.price-message').html('You are about to match the lowest Selling Offer. Their Offer will be accepted before yours');
		}  else if(currentPrice > LowestPrice) {
			$('.price-message').html('You are not the lowest selling offer');
		} else {
			$('.price-message').html('');
		}
		slider.reloadSlider();
		$("#enterBidPrice").focus();
	});

    $("#enterBidPrice").keydown(function (event) {

        var number = $("#enterBidPrice").val();
        var length = number.length;
        var num = event.keyCode;

        if (length > 4 && num != 8) {
            event.preventDefault();
        }


        if ((num > 95 && num < 106) || (num > 36 && num < 41) || num == 9) {
            return;
        }
        if (event.shiftKey || event.ctrlKey || event.altKey) {
            event.preventDefault();
        } else if (num != 46 && num != 8) {
            if (isNaN(parseInt(String.fromCharCode(event.which)))) {
                event.preventDefault();
            }
        }
    });


    $('#shipping_first_name,#shipping_last_name,#shipping_street_city,#billing_first_name,#billing_last_name,#billing_street_city').keypress(function (e) {

        var key = e.keyCode;
        var number = $(this).val();
        var length = number.length;

        if (length > 16 && key != 8) {
            event.preventDefault();
        }
        if (key >= 48 && key <= 57) {
            e.preventDefault();
        }

    });


    $('#shipping_first_name,#shipping_last_name,#shipping_street_city,#billing_first_name,#billing_last_name,#billing_street_city').on('keypress', function (event) {
        var regex = new RegExp("^[0-9a-zA-Z \b]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    });


    $("#bidNowButton").click(function () {
        var isError = 0; //set Flag
        $("form#sellData :input").each(function (e) {
            if ($(this).val() == '') {
                isError = 1; //update the flag
                var id = $(this).parent().parent().attr('id');
                if (id == 'collapseTwo') {
                    if ($("#headingTwo a").hasClass('collapsed')) {
                        $("#headingTwo a").click();
                    }
                }

                if (id == 'collapseThree') {
                    if ($("#headingThree a").hasClass('collapsed')) {
                        $("#headingThree a").click();
                    }
                }

                var submitButton = $(this).html();
                $(this).focus();
                if (submitButton != 'SUBMIT') {
                    $(this).css('border-color', 'red');
                    $("#error").html('Please fill the required fields !!');
                    $("#error-info").html('Please fill the required fields !!');
                }
                return false;
                //e.preventDefault();
            }
        });
        //console.log(isError);
        //$("#bidData").submit();
//return false;
    });


    $("input").keypress(function () {
        $(this).css('border-color', 'black');
        $("#error").html('');
    });

    $("#same_as_shipping").click(function () {

        var checked = $(this).is(':checked');
        if (checked) {

            if ($("#shipping_full_address").val() != "" && $("#shipping_street_city").val() != "" && $("#shipping_country").val() != "" && $("#shipping_zip").val() != "" && $("#shipping_first_name").val() != "" && $("#shipping_last_name").val() != "" && $("#shipping_phone").val() != "") {

                $("#billing_first_name").val($("#shipping_first_name").val());
                $("#billing_last_name").val($("#shipping_last_name").val());
                $("#billing_full_address").val($("#shipping_full_address").val());
                $("#billing_street_city").val($("#shipping_street_city").val());
                $("#billing_phone").val($("#shipping_phone").val());
                $("#billing_country").val($("#shipping_country").val());
                $("#billing_province").val($("#shipping_province").val()).change();
                $("#billing_zip").val($("#shipping_zip").val());

            } else {

                $("#error").html('Please fill the shipping address first !!');
                return false;
                e.preventDefault();
            }
        }
    });

    /***************************************************
     *
     * THE SHIPPING RATE LOGIC
     * (BUYING PRICE - (SHIPPING RATE + 1 CAD)) - 3% of total (processing fee)
     * Subtract the shipping rate from actual amount
     *****************************************************/

    var fixedCadAmount = '01.00'; //Canadian Dollar
    var commissionPrice = '1';
    $('#enterBidPrice').on('keyup', function (e) {
        var price = $('#enterBidPrice').val();
        var toPostalCode = $('#shipping_zip').val();
        var countryCode = document.getElementById("shipping_country").value;
        getPriceCalculations(); //CALL API RATE

    });

        

    /*Call the Ajax and get the shipping rates before submission*/
    $('#shipping_zip').on('change blur', function (e) {
        var toPostalCode = $(this).val();
        var toCountry = $('#shipping_country').find(":selected").val();
        var price = $('#enterBidPrice').val();
        var countryCode = document.getElementById("shipping_country").value;

        //Currency check
        if (countryCode == 'CN') {
            getFlatShippingRate(price, flatShippingRate);
        }else {
            if (price == '') {
                $('#enterBidPrice').css('border-color', 'red');
                /*$('html, body').animate({
                 scrollTop: $("#enterBidPrice").offset().top
                 }, 2000);*/
            } else {
                $('#enterBidPrice').css('border-color', '#ccc');
                $('#shipping_zip').css('border-color', '#ccc');
                getShipmentRate(toCountry, toPostalCode, price);
            }
        }
    });

    /*Get price calculation with fixed amount of Shipping Rate
    * Shipping Cost 25 
    */
    function getPriceCalculations(shipmentCost = 25){
        
        var price = parseFloat($('#enterBidPrice').val());
        if(price == '' || isNaN(price)){
            $('.priceP').html('--');
            $('.commissionFee').html('--');
            $('.processingFeeCal').html('--');
            $('.totalPrice').html('--');
            $('.total-amount-display').html('--');
            $('#bidNowButton').prop('disabled', true);

            return false;
        }
        //var shipmentCost = 25; // Fixed shipping cost for all Zipcodes
        var shipmentCostWithDollar = '$' + shipmentCost;
        $('.shippingDiv').show();
        $('.affectedByShiipingCost').show(); //show the dynmic rate here

        //FORMULA APPLICATIONS START'S HERE
        shipRateWithCAD = parseFloat(shipmentCost) + parseFloat(fixedCadAmount);
        sellPriceShipRateWithCAD = parseFloat(price) - parseFloat(shipRateWithCAD); //(BUYING PRICE - (SHIPPING RATE + 1 CAD))

        var percen = 3;
        if (isNaN(sellPriceShipRateWithCAD) || isNaN(percen)) {
            processingFee = " ";
        } else {
            processingFee = ((percen * sellPriceShipRateWithCAD) / 100).toFixed(2);
        }
        finalCalPrice = parseFloat(sellPriceShipRateWithCAD) - parseFloat(processingFee);
        finalCalPrice = finalCalPrice.toFixed(2); //wrap upto 2 float
        //FORMULA APPLICATIONS END'S HERE

        var comPrice = parseFloat(finalCalPrice / 100) * commissionPrice;
        comPrice = comPrice.toFixed(2);
        if (comPrice < 1) {
            comPrice = '1.00';
        }
        
       //console.log('final price---' + finalCalPrice + '++++++after cal commissionPrice --->' + comPrice);

        //Ajax value for processing fees
        var ajaxfinalCalPrice = finalCalPrice;

        //FINAL PRICE NEGATIVE CASE
        if (finalCalPrice < 1) {
            $('#bidNowButton').prop('disabled', true);
            finalCalPrice = '$' + finalCalPrice;
            finalCalPrice = finalCalPrice.replace(/[$-]/g, function ($1) {
                return $1 === '-' ? '$' : '-'
            });
            $('.totalPrice').html(finalCalPrice);
            $('.total-amount-display').html(finalCalPrice);
        } else {
            $('.totalPrice').html('$' + finalCalPrice);
            $('.total-amount-display').html('$' + finalCalPrice);
        }

        //Ajax value for processing fees
        var ajaxprocessingFee = processingFee;

        //PROSSESING FEE NEGATIVE CASE
        if (processingFee < 1) {
            processingFee = '$' + processingFee;
            $('.processingFeeCal').html(processingFee.replace(/[$-]/g, function ($1) {
                return $1 === '-' ? '$' : '-'
            }));
        } else {
            $('.processingFeeCal').html('-$' + processingFee);
        }

        $('.priceP').html('-' + shipmentCostWithDollar); //add plus sign with shipping rate
        $('.commissionFee').html('$' + comPrice);
        
        //STEP II:-
        //store the price values into session
        $.ajax({
            url: "{{ url('/') }}/savePriceToSession",
            type: "GET",
            //dataType: "jsonp",
            data: {
                shippingRate: shipmentCost,
                totalPrice: ajaxfinalCalPrice,
                processingFee: ajaxprocessingFee,
                commissionPrice: comPrice
            },
            success: function (response) { // What to do if we succeed
                $('#bidNowButton').attr("disabled", false);
            }
        });
        
    }

    /*The function to calculate the rate*/
    function getShipmentRate(doNotSlide = false) {
        var toCountry = $('#shipping_country').find(":selected").val();
        var toPostalCode = $('#shipping_zip').val();
        var price = $('#enterBidPrice').val();
        
        if($.trim(toPostalCode) == ''){
            $('span.shipping-error').html('Please fill correct zip code !').show();
            slider.goToSlide(1);
            $('#bidNowButton').prop('disabled', true);

        } else {
            $('span.shipping-error').empty().hide();
            $('#bidNowButton').prop('disabled', false);
        }
        
        return true;
        
        
        var shipRateWithCAD = '';
        var sellPriceShipRateWithCAD = '';
        var processingFee = '';
        var processingFeeCal = '';
        var clientHeadqauters = "V6V 1Z4"; //In case of buying this will be client's headqauters
       // var getRateURL = "https://private-anon-e9bf51dc6a-shipstation.apiary-mock.com/shipments/getrates";

        //This needs to be removed for TRANTOR SERVER
        var getRateURL = "https://ssapi.shipstation.com/shipments/getrates";

        //hit the shipstation API
        var request = new XMLHttpRequest();

        request.open('POST', getRateURL);

        request.setRequestHeader('Content-Type', 'application/json');
        request.setRequestHeader('Authorization', 'Basic MWE1ZDE2MDZkZDczNGExNmFlYmQ3ZmIyNzVjNDhjYjg6MWRkMjIzOTdkZjEwNDI0MzhjYmNhN2M2YzUxNDVkNWM=');
        request.setRequestHeader('Access-Control-Allow-Origin', '*');
        $('.shippingLoader').show();
        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                $('.shippingLoader').hide();

                if (this.status == 200) {
                    
                    // var shipmentData = this.responseText;
                    // var res = this.responseText;
                    // res = JSON.parse(res);
                    //var shipmentCost = res['0'].shipmentCost;
                    //getPriceCalculations(shipmentCost);
                    $('span.shipping-error').empty().hide();
                    $('#bidNowButton').attr("disabled", false);
                    
                } else {
                    //ELSE CASE WHEN DUE TO SOME REASON API IS NOT ABLE TO GET RESPONSE

                    //shipstationError = shipstationError + 1; //update if API throw any error
                    var errorResponse = JSON.parse(this.responseText);
                    var responseStack = errorResponse.StackTrace;
                    var n = responseStack.indexOf("fromZip");
                    var err = '';
                    if(n > 0){
                        err = 'Please fill correct zip code !';
                    }
                    $('span.shipping-error').html(err).show();
                    slider.goToSlide(1);
                    
                    //$('.priceP').html('--'); //add plus sign with shipping rate
                    //$('.commissionFee').html('--');
                    //$('.processingFeeCal').html('--');
                    //$('.totalPrice').html('--');
                    //$('.total-amount-display').html('--');
                    $('#bidNowButton').prop('disabled', true);
                    return false;
                }
            }
        };

        var body = {
            "carrierCode": "canada_post",
            "serviceCode": "xpresspost",
            "packageCode": null,
            "fromPostalCode": clientHeadqauters,
            "toState": "",
            "toCountry": toCountry,
            //"toPostalCode": "V6V 1Z4",
            "toPostalCode": toPostalCode,
            "toCity": "",
            "weight": {
                "value": "3",
                "units": "pounds"
            },
            "dimensions": {
                "units": "centimeters",
                "length": "35.00",
                "width": "23.00",
                "height": "13.50"
            },
            "confirmation": "delivery",
            "residential": false
        };
        request.send(JSON.stringify(body));
    }

    /*********************************************
     *  Shipping rate in case of FLAT case
     **********************************************/
    function getFlatShippingRate(price, flatShippingRate){
        //console.log('hits flat rate method');
        //FORMULA APPLICATIONS START'S HERE
        var shipRateWithCAD = parseFloat(flatShippingRate) + parseFloat(fixedCadAmount); //(SHIPPING RATE + 1 CAD)
        var sellPriceShipRateWithCAD = parseFloat(price) - parseFloat(shipRateWithCAD); //(BUYING PRICE - (SHIPPING RATE + 1 CAD))

        var percen = 3
        if (isNaN(sellPriceShipRateWithCAD) || isNaN(percen)) {
            processingFee = " ";
        } else {
            processingFee = ((percen * sellPriceShipRateWithCAD) / 100).toFixed(2);
        }
        finalCalPrice = parseFloat(sellPriceShipRateWithCAD) - parseFloat(processingFee);
        //FORMULA APPLICATIONS END'S HERE

        //1% case in BUY NOW
        var comPrice = parseFloat(finalCalPrice / 100) * commissionPrice;
        comPrice = comPrice.toFixed(2);
        if (comPrice < 1) {
            comPrice = '1.00';
        }
        //console.log('final price---' + finalCalPrice + '++++++after cal commissionPrice --->' + comPrice);
        finalCalPrice = finalCalPrice - comPrice;
        finalCalPrice = finalCalPrice.toFixed(2); //wrap upto 2 float

        //Ajax value for processing fees
        var ajaxfinalCalPrice = finalCalPrice;

        //FINAL PRICE NEGATIVE CASE
        if (finalCalPrice < 1) {
            $('#sellNowButton').prop('disabled', true);
            $('.payment_message').show();
            finalCalPrice = '$' + finalCalPrice;
            $('.totalPrice').html(finalCalPrice.replace(/[$-]/g, function ($1) {
                return $1 === '-' ? '$' : ' '
            }));
        } else {
            $('.payment_message').hide();
            $('.totalPrice').html('$' + finalCalPrice);
        }

        //Ajax value for processing fees
        var ajaxprocessingFee = processingFee;

        //PROSSESING FEE NEGATIVE CASE
        if (processingFee < 1) {
            processingFee = '$' + processingFee;
            $('.processingFeeCal').html(processingFee.replace(/[$-]/g, function($1) { return $1 === '-' ? '$' : '-' }));
        }else{
            $('.processingFeeCal').html('-$' + processingFee);
        }

        //$('.processingFeeCal').html('+$' + processingFee); //update the processing Fee
        //$('.totalPrice').html('$' + finalCalPrice);
        $('.priceP').html('-$'+flatShippingRate);
        $('.commissionFee').html('-$' + comPrice);
        $('#shippingError').html('');

        //STEP II:-
        //store the price values into session
        $.ajax({
            url: '/savePriceToSession',
            type: "GET",
            data: {
                shippingRate: flatShippingRate,
                totalPrice: ajaxfinalCalPrice,
                processingFee: ajaxprocessingFee,
                commissionPrice: comPrice
            },
            success: function (response) { // What to do if we succeed
                //if(data == "success")
            }
        });
    }

    /*********************************************
     *  Get Province List on changing the country
     *
     **********************************************/
    function getProvince() {
        var val = document.getElementById("shipping_country").value;
        //console.log(val);
        //e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "{{ url('/') }}/getProvince",
            data: 'country_id=' + val,
            dataType: 'json',
            success: function (data) {
                //console.log(data.length);
                var html = '';
                if (data.length > 0) {
                    //var res = JSON.parse(data);
                    $.each(data, function (key, value) {
                        html += '<option value="' + value.abbreviation + '">' + value.name + '</option>';
                    });
                    $('#shipping_province').html(html);
                } else {
                    html += 'No Record!';
                }
            }
        });
    }
	
	
    /*Call the province list on change */
    //$('#shipping_country').on('change', getProvince);

    /*Call province method on load also*/
   //$(document).ready(getProvince);

    </script>
@endsection