@extends('layouts.website')

@if ($message = Session::get('success'))
    <div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
@endif

@section('content')
    <form id="bidData" name="bidData" method="POST" action="{{ route('saveBid') }}">
        @csrf
        <div class="newSection">
            @php
                $prodData = current($productDetails);
				if(isset($prodData['product_sizes']) && $prodData['product_sizes'] != ''){
					$product_sizes = $prodData['product_sizes'];
				}
				
				//echo "<pre>"; print_r(base64_encode($prodData['id'])); echo "</pre>"; die;
				
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
        <section class="details-section">
        <div class="container">
            <div class="row flexed-row">
                <div class="col-md-7 value-filed col">
                    <div class="heading-conatiner text-center">
                        <h1>{{ $prodData['product_name'] }} {{ $prodData['product_brand_type']['brand_type_name'] }}</h1>

                        <div class="purple-btn" data-toggle="modal" data-target="#buy">@lang('home.Selected Size') : <span>{{ $productDetails['size'] }}</span></div>
                        <input type="hidden" name="hiddenSizeId" value="{{ $productDetails['sizeID'] }}">
                        <input type="hidden" name="hiddenProdId" value="{{ $prodData['id'] }}">
						<input type="hidden" name="hiddenBidType" id="hiddenBidType" value="bid-offer">


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
											<div class="bid active" id="bid-offer-section">Make Offer</div>
											<div class="bid" id="buy-now-section">Buy Now</div>
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
										<!--
										<li id="zipCodeSection">
											<span class="shipping-error"></span>
                                            <div class="row zipcodeDiv">
                                                <div class="col-md-4">
                                                    <label>Zip Code: </label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input class="form-control text-box zip-field-1" id="shipping_api_zip_field" placeholder="@lang('home.Zip Code')" name="shipping_zip" type="text" value="{{old('shipping_zip',$shippingdata['zip_code'])}}" maxlength='8' minlength='4'>
                                                </div>
                                            </div>
                                        </li><!--##shippingCost-->
										
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

                                        <!--<li class="refundable-dep-cont">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>@lang('home.Refundable Deposit')[<a href="#" data-toggle="modal" data-target="#refundablePopup">?</a>]: 
                                                    </label>
                                                </div>
                                                <div class="col-md-6 value-filed commissionFee">
                                                    --
                                                </div>
                                            </div>
                                        </li>-->
                                    </ul>
									
									<div class="dotted-line"></div>
									
                                    <ul class="values-ul">
                                        <li>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Total:</label>
                                                </div>
                                                <div class="col-md-6 value-filed totalPrice">
                                                    --
                                                </div>
                                                <div class="col-md-12">
                                                    <p>I have read
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#sellingagrePopup" class="agreement-link">@lang('home.Buying Agreement')
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
											Billing / Shipping Address
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
                                               value="{{old('shipping_first_name', $shippingdata['first_name'])}}" onblur="copy(this);"
                                               maxlength='15'>
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
                                               value="{{old('shipping_last_name',$shippingdata['last_name'])}}" onblur="copy(this);"
                                               maxlength='15'>
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
                                                    required="" onchange="copy(this);">
													<option value="0">Select Province</option>
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
													
													<option class="provOption CN" value="AH"<?php if ($shippingdata['province'] == 'AH' ) echo 'selected' ; ?> >@lang('home.Anhui')</option>
													<option class="provOption CN" value="BJ"<?php if ($shippingdata['province'] == 'BJ' ) echo 'selected' ; ?> >@lang('home.Beijing')</option>
													<option class="provOption CN" value="CQ"<?php if ($shippingdata['province'] == 'CQ' ) echo 'selected' ; ?> >@lang('home.Chongqing')</option>
													<option class="provOption CN" value="FJ"<?php if ($shippingdata['province'] == 'FJ' ) echo 'selected' ; ?> >@lang('home.Fujian')</option>
													<option class="provOption CN" value="GS"<?php if ($shippingdata['province'] == 'GS' ) echo 'selected' ; ?> >@lang('home.Gansu')</option>
													<option class="provOption CN" value="GD"<?php if ($shippingdata['province'] == 'GD' ) echo 'selected' ; ?> >@lang('home.Guangdong')</option>
													<option class="provOption CN" value="GX"<?php if ($shippingdata['province'] == 'GX' ) echo 'selected' ; ?> >@lang('home.Guangxi')</option>
													<option class="provOption CN" value="GZ"<?php if ($shippingdata['province'] == 'GZ' ) echo 'selected' ; ?> >@lang('home.Guizhou')</option>
													<option class="provOption CN" value="HI"<?php if ($shippingdata['province'] == 'HI' ) echo 'selected' ; ?> >@lang('home.Hainan')</option>
													<option class="provOption CN" value="HE"<?php if ($shippingdata['province'] == 'HE' ) echo 'selected' ; ?> >@lang('home.Hebei')</option>
													<option class="provOption CN" value="HL"<?php if ($shippingdata['province'] == 'HL' ) echo 'selected' ; ?> >@lang('home.Heilongjiang')</option>
													<option class="provOption CN" value="HA"<?php if ($shippingdata['province'] == 'HA' ) echo 'selected' ; ?> >@lang('home.Henan')</option>
													<option class="provOption CN" value="HB"<?php if ($shippingdata['province'] == 'HB' ) echo 'selected' ; ?> >@lang('home.Hubei')</option>
													<option class="provOption CN" value="HN"<?php if ($shippingdata['province'] == 'HN' ) echo 'selected' ; ?> >@lang('home.Hunan')</option>
													<option class="provOption CN" value="NM"<?php if ($shippingdata['province'] == 'NM' ) echo 'selected' ; ?> >@lang('home.Inner Mongolia')</option>
													<option class="provOption CN" value="JS"<?php if ($shippingdata['province'] == 'JS' ) echo 'selected' ; ?> >@lang('home.Jiangsu')</option>
													<option class="provOption CN" value="JX"<?php if ($shippingdata['province'] == 'JX' ) echo 'selected' ; ?> >@lang('home.Jiangxi')</option>
													<option class="provOption CN" value="JL"<?php if ($shippingdata['province'] == 'JL' ) echo 'selected' ; ?> >@lang('home.Jilin')</option>
													<option class="provOption CN" value="LN"<?php if ($shippingdata['province'] == 'LN' ) echo 'selected' ; ?> >@lang('home.Liaoning')</option>
													<option class="provOption CN" value="NX"<?php if ($shippingdata['province'] == 'NX' ) echo 'selected' ; ?> >@lang('home.Ningxia')</option>
													<option class="provOption CN" value="QH"<?php if ($shippingdata['province'] == 'QH' ) echo 'selected' ; ?> >@lang('home.Qinghai')</option>
													<option class="provOption CN" value="SN"<?php if ($shippingdata['province'] == 'SN' ) echo 'selected' ; ?> >@lang('home.Shaanxi')</option>
													<option class="provOption CN" value="SD"<?php if ($shippingdata['province'] == 'SD' ) echo 'selected' ; ?> >@lang('home.Shandong')</option>
													<option class="provOption CN" value="SH"<?php if ($shippingdata['province'] == 'SH' ) echo 'selected' ; ?> >@lang('home.Shanghai')</option>
													<option class="provOption CN" value="SX"<?php if ($shippingdata['province'] == 'SX' ) echo 'selected' ; ?> >@lang('home.Shanxi')</option>
													<option class="provOption CN" value="SC"<?php if ($shippingdata['province'] == 'SC' ) echo 'selected' ; ?> >@lang('home.Sichuan')</option>
													<option class="provOption CN" value="TJ"<?php if ($shippingdata['province'] == 'TJ' ) echo 'selected' ; ?> >@lang('home.Tianjin')</option>
													<option class="provOption CN" value="XJ"<?php if ($shippingdata['province'] == 'XJ' ) echo 'selected' ; ?> >@lang('home.Xinjiang')</option>
													<option class="provOption CN" value="YZ"<?php if ($shippingdata['province'] == 'YZ' ) echo 'selected' ; ?> >@lang('home.Xizang')</option>
													<option class="provOption CN" value="YN"<?php if ($shippingdata['province'] == 'YN' ) echo 'selected' ; ?> >@lang('home.Yunnan')</option>
													<option class="provOption CN" value="ZJ"<?php if ($shippingdata['province'] == 'ZJ' ) echo 'selected' ; ?> >@lang('home.Zhejiang')</option>
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

                            <div id="step3">
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
								<font class="addressTabTitle">
									@if(count($shippingdata) > 0 && $shippingdata['full_address'] != '')
										{{$shippingdata['full_address']}}
									@else
										Billing / Shipping Address
									@endif
								</font>
								<div class="editShipping floatRight"><i class="fas fa-edit"></i></div>
							</li>
							<li class="payment-method-label">
								<i class="far fa-credit-card"></i> Payment Method
							</li>
						</ul>
					</div>
                </div>
            </div>
        </div>
    </section><!--##section-->
    </div><!--##newSection-->

		<!--#Popup's with content-->
		<div id="sellingagrePopup" class="modal fade modal-dialog-centered" role="dialog">
			<div class="modal-dialog modal-dialog-centered">

				<!-- Modal content-->
				<div class="modal-content size-poppup">
					<div class="modal-header">
						<a class="close" data-dismiss="modal">&times;</a>
						<h2 class="model-price"></h2>
					</div>
					<div class="modal-body">
						<h3><u>@lang("home.Buyers notice:")</u></h3><br/>
						<b>@lang("home.1.Is the good in stock all the time?")</b><br/><br/>

						@lang("home.The seller promises that all purchasable items are in stock.If the seller defaults or makes a false delivery, HYPEX will handle according to the platform standard. In severe cases, the sellers' account will be canceled.")<br/><br/>

						<b>@lang("home.2.How long does it take for the delivery?")</b><br/><br/>

						@lang("home.Under normal circumstances, the entire transaction and delivery process takes 7 working days, except for special circumstances such as weather, climate, holiday, express delivery and other reasons. If the seller does not ship the goods after 48 hours since the order time, the seller agrees to refund the payment to the buyer after the platform cancels the order.")<br/><br/>

						<b>@lang("home.3.Identification of authenticity")</b><br/><br/>

						@lang("home.Buyer understands that in order to protect the interests of buyers, if the goods appear, but are not limited to the following conditions, the buyer agrees to authorize the platform to close the order and return the payment to the buyer, so please pay attention to the platform notice:")<br/><br/>

						@lang("home.(1) The quality problems of obvious oxidation, cracking, stains, scratches, dyeing, damage, abrasion, etc.")
						@lang("home.(2) The name, model number, color, size, etc. of the product do not match with the order information;")
						@lang("home.(3) The goods are not matched with the outer box (outer packaging) and accessories;")
						@lang("home.(4) The goods lack accessories, including but not limited to insoles, box cover, tags, etc.;")
						@lang("home.(5) There are obvious traces of use;")
						@lang("home.(6) It is obviously suspected of counterfeit and shoddy goods.")<br/><br/>

						<b>@lang("home.4.Can I return and refund the product?")</b><br/><br/>

						@lang("home.a. The trading mode of HYPEX is the bidding and auction mode. Commodity prices will fluctuate according to market demand. Buyers should purchase at the price that they prefer. After the successful transaction, the platform does not advocate the buyer to ask the seller to refund the difference.")<br/><br/>

						@lang("home.b. Buyers should be aware that most of the goods sold at HYPEX are the seller's personal items. In most cases, the seller sells only one item of the same model and size. Therefore, in the case of non-commodity quality issues, HYPEX does not support return service, but buyers can sell again on the platform.")<br/><br/>

						@lang("home.c. Buyers should check the goods at the time of receiving care. If you find quality problems, you should contact customer service within 7 days after receiving the goods, and ensure that the buckle, identification certificate, and packaging are intact. If the buyer does not contact customer service within 7 days or the goods are returned due to quality problems yet they do not have the quality problems described by the buyer or the goods actually meet the normal quality conditions described in these rules, HYPEX will deem the buyer refuses the goods for no reason. The platform has the right to deduct the corresponding fees according to the buyer's rejection.")<br/><br/>
					</div>
					<div class="modal-footer popup-btns">
					</div>
				</div>
			</div>
		</div><!--#Selling agreement PopUp now-->

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
	
	<div id="buy" class="modal fade modal-dialog-centered newSizePopup" role="dialog">
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
						<li id="{{ $sizeList['size'] }}" <?php if ($sizeList['size'] == $productDetails['size']) {
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
	
@endsection

@section('scripts')
    <script type="text/javascript">
	
		$(document).ready(function(){
			console.log($(location).attr("href"));
			$('#enterBidPrice').val('');
			var selectedCountry = "<?php echo $shippingdata['country'];  ?>";
			//alert(selectedCountry);
			if(selectedCountry == 'CN'){
				$('.CN').show();
			}else if(selectedCountry == 'CA'){
				$('.CA').show();
			}else{
				$('.CA').show();
			}
			
			$('#shipping_full_address').on('blur',function(){
				$('.addressTabTitle').html('Billing / Shipping Address');
				if($.trim($(this).val()) != '' ){
					$('.addressTabTitle').html($(this).val());
				}
			});
			
			if($('#pageType').val() == 'buynow'){
				$('#buy-now-section').trigger('click');
			}
			
			//CLICK THE DEFAULT FILLED BOX IN POPUP OF SIZE WITH PRICE
			$(".default").click(); 
			
		});
		
		//SCRIPTS FOR POPUP
		
		$(".model-size-chart li").click(function () {

            $("#selected-size").html(this.id);
            var price = $(this).closest('li').find('span').html();
            $(".model-size-chart li").removeClass('shadow');
            $(this).closest('li').addClass('shadow');
            $("#sizeBidPrice").html(price);
            $('#sell').removeAttr("disabled");
            $('#direct-buy').removeAttr("disabled");

        });
		
		//CASE:- WHEN CUSTOMER CLICKS MAKE BUY OFFER
		$("#sell").click(function () {
			var productID = "<?php echo base64_encode($prodData['id']); ?>";            

            var size = $("#selected-size").html();
            window.location.href = '/product-bid/' + productID + '/' + size + '/bidoffer';
            return false;

        });
		
		$('#directbuymessage').hide();
		
		//CASE : WHEN CUSTOMER CLICKS `BUY NOW`
        $("#direct-buy").click(function () {
			var productID = "<?php echo base64_encode($prodData['id']); ?>";
            
            var size = $("#selected-size").html();
            var prices = $("#sizeBidPrice").html();
            if (prices == '--') {
                $('#directbuymessage').show();
                setTimeout(function () {
                    $('#directbuymessage').fadeOut('slow');
                }, 2000); // <-- time in milliseconds
                return false;
            } else {
				//$('#buy-now-section').trigger('click');
				window.location.href = '/product-bid/' + productID + '/' + size + '/buynow';				
            }
            return false;
        });
		
		//show and hide the province resp to countries
		$('#shipping_country').change(function(){			
			var optionSelected = $("option:selected", this);			
			var optionID = $(this).children(":selected").attr("id");
			$('.provOption').hide(); //hide the other option first
			$('#shipping_province').prop('selectedIndex',0);
			if(optionID == 'CN'){
				$('.'+optionID).show();
				$('#shipping_zip').val('00000');
			}else if(optionID == 'CA'){
				$('.'+optionID).show();
				$('#shipping_zip').val('');
			}
			var valueSelected = this.value;
		})
	
		//Buying Case Flag
		var buyTypeFlag = 'bid-offer';
		var formAction = "{{ url('/') }}/";
		
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
					return noError;
				}
			}
        });
		
		//button toggle 
		$('.switch-container .bid').click(function(e){
		
			var lowestSellOffer = $('#hiddenPrice').val();
			var containerID = $(this).attr('id');
			$('.bid').removeClass('active');
			$(this).addClass('active');
			
			//CASE : When Buy Offer
			//fill amount in enter an offer box
			$('#enterBidPrice').empty();
			$('#enterBidPrice').prop('readonly', false);
			$('#hiddenBidType').val('bid-offer');
			$('.refundable-dep-cont, .expiration-section-cont').show();
			$('#bidData').attr('action', formAction+'savebid');
			$('.price-message').html('');
			
			//CASE I :- When directly buy
			if(containerID == 'buy-now-section'){
				console.log('buy now');
				$('.refundable-dep-cont, .expiration-section-cont').hide();
				$('#enterBidPrice').val(lowestSellOffer);
				$('#enterBidPrice').trigger('change');
				$('#enterBidPrice').prop('readonly', true);
				$('#hiddenBidType').val('buy-now');
				$('#bidData').attr('action', formAction+'purchase-bid');
				$('.price-message').html('You are about to purchase this product at the lowest sellling price');
				getPriceCalculations();
				//$('#enterBidPrice').trigger('change');
			} else if( containerID == "bid-offer-section") {
				$('#enterBidPrice').val('');
				$('.priceP').html('--'); //add plus sign with shipping rate
				$('.commissionFee').html('--');
				$('.processingFeeCal').html('--');
				$('.totalPrice').html('--');
				$('.total-amount-display').html('--');
			}
			slider.reloadSlider();
		});
		
        var ShipZipcode = $('#shipping_zip').val();
        if(ShipZipcode == ''){
            //slider.goToSlide(1);
        }
		
		$('.editShipping').on('click', function(){
			slider.goToSlide(1);
		});
		
        //$("#shipping_first_name").prop("readonly", true);
        //$("#shipping_last_name").prop("readonly", true);
        //$("#shipping_full_address").prop("readonly", true);
        //$("#shipping_street_city").prop("readonly", true);
        //$("#shipping_phone").prop("readonly", true);
        // $("#shipping_country").prop("disabled", true);
        //$('#shipping_country').attr("style", "pointer-events: none;");
        // $('#shipping_province').prop('disabled',true);
        //$('#shipping_province').attr("style", "pointer-events: none;");
        //$("#shipping_zip").prop("readonly", true);

        $("#billing_first_name").prop("readonly", true);
        $("#billing_last_name").prop("readonly", true);
        $("#billing_full_address").prop("readonly", true);
        $("#billing_street_city").prop("readonly", true);
        $("#billing_phone").prop("readonly", true);
        $('#billing_country').attr("style", "pointer-events: none;");
        $('#billing_province').attr("style", "pointer-events: none;");
       // $("#billing_province").prop("disabled", true);
       // $('#billing_province').prop('disabled',true);
        $("#billing_zip").prop("readonly", true);

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

		$("#enterBidPrice").keyup(function (event) {
			var currentPrice = parseInt($(this).val());
			var LowestPrice = parseInt($('#hiddenPrice').val());
			var HighestPrice = parseInt($('#hiddenHighestPrice').val());
			
			if(currentPrice >= LowestPrice) {
				$('#buy-now-section').trigger('click');
			} else if(currentPrice < HighestPrice) {
				$('.price-message').html('You are not the highest Offer');
			} else if(currentPrice == HighestPrice) {
				$('.price-message').html('You are about to match the highest Offer. Their Offer will be accepted before yours');
			} else if(currentPrice > HighestPrice) {
				$('.price-message').html('You are about to be the highest bidder');
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


        var shoePrice = $('#hiddenPrice').val(); //show price
        $("#bidNowButton").click(function () {
            $("form#bidData :input").each(function (e) {
                if ($(this).val() == '') {
                    var id = $(this).parent().parent().attr('id');
                    if (id == 'collapseTwo') {
                        if ($("#headingTwo a").hasClass('collapsed')) {
                            $("#headingTwo a").click();
                        }
                    }
                    var submitButton = $(this).html();
                    $(this).focus();
                    if (submitButton != 'SUBMIT') {
                        $(this).css('border-color', 'red');
                        $("#error").html('Please fill the required fields !!');
                    }
                    return false;
                    e.preventDefault();
                }
            });
        });

        $("input").keypress(function () {
            $(this).css('border-color', '#e9e9e9');
            $("#error").html('');
        });


        $("#same_as_shipping").click(function () {

            var checked = $(this).is(':checked');
            if (checked) {

                if ($("#shipping_full_address").val() != "" && $("#shipping_street_city").val() != "" && $("#shipping_country").val() != "" && $("#shipping_zip").val() != "" && $("#shipping_first_name").val() != "" && $("#shipping_last_name").val() != "" && $("#shipping_phone").val() != "") {

                    $("#error").html('');
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

        function copy(data) {

            var checked = $("#same_as_shipping").is(':checked');
            if (checked) {

                $("#billing_first_name").val($("#shipping_first_name").val());
                $("#billing_last_name").val($("#shipping_last_name").val());
                $("#billing_full_address").val($("#shipping_full_address").val());
                $("#billing_street_city").val($("#shipping_street_city").val());
                $("#billing_phone").val($("#shipping_phone").val());
                $("#billing_country").val($("#shipping_country").val());
                $("#billing_province").val($("#shipping_province").val()).change();
                $("#billing_zip").val($("#shipping_zip").val());
            }

        }

        /*###########################################################################*
         * SOME FIXED VARIBALES
         *###########################################################################*/

        var fixedCadAmount = '01.00'; //Canadian Dollar
        var commissionPrice = '1';

        /****************************************
         * FLAT SHIPPING RATE IN CASE OF CHINA
         * CAD 20
         ********************************************/
        var flatShippingRate = '20'; //fixed to CAD 20


        /****************************************
         *
         * THE SHIPPING RATE LOGIC
         * (SELLING PRICE + (SHIPPING RATE + 1CAD)) + 3% of total (processing fee)
         ********************************************/

        // $(document).ready(function(){
        //     var toPostalCode = $('#shipping_zip').val();
        //     var toCountry = $('#shipping_country').find(":selected").val();
        //     var price = $('#hiddenPrice').val();

        //     //console.log(toPostalCode+'--------'+'--------tocountry'+toCountry+'-------price->'+price);
        //     //return false;
        //     var countryCode = document.getElementById("shipping_country").value;
        //     if (countryCode == 'CN') {
        //         getFlatShippingRate(price, flatShippingRate);
        //     }else {
        //         if (price == '') {
        //             console.log('Price is missinng to calculate the processing fee');
        //         } else {
        //             $('#shipping_zip').css('border-color', '#ccc');
        //             getShipmentRate(toCountry, toPostalCode, price);
        //         }
        //     }
        // }) 

        $('#enterBidPrice').on('keyup', function (e) {
            var price = $('#enterBidPrice').val();
            var toPostalCode = $('#shipping_zip').val();
            var countryCode = document.getElementById("shipping_country").value;

			getPriceCalculations(); //CALL API RATE
			
            //Currency check and call the shipping method acc to that
            // if (toPostalCode == '') {
                // $('#shipping_api_zip_field').css('border-color', 'red');
            // } else {
                //$('#shipping_zip').css('border-color', '#ccc');
                // if (countryCode == 'CN') {
                    // getFlatShippingRate(price, flatShippingRate); //CALL FLAT RATE
                // }else{
                    //getShipmentRate(); //CALL API RATE
                    // getPriceCalculations(); //CALL API RATE
                // }

            // }

        });

        /********************************************************************************
         * Call the Ajax on change of ZIP CODE and get the shipping rates before submission
         * *****************************************************************************/
        $('#shipping_zip, #shipping_api_zip_field').on('change blur', function (e) {
            var toPostalCode = $(this).val();
			var runAPI = true;
			if($(this).hasClass('zip-field-2')){
				if($('#shipping_api_zip_field').val() == toPostalCode){
					runAPI = false;
				} else{
					$('#shipping_api_zip_field').val(toPostalCode);
				}
			} else {
				$('#shipping_zip').val(toPostalCode);
			}
			
			
			if(runAPI){
				var toCountry = $('#shipping_country').find(":selected").val();
				var price = $('#enterBidPrice').val();
				var countryCode = document.getElementById("shipping_country").value;

				//currency check

				if (price == '') {
					$('#enterBidPrice').css('border-color', 'red');
				} else {
					
					getShipmentRate();
					// if (countryCode == 'CN') {
						// getFlatShippingRate(price, flatShippingRate); // call the method with any API
					// }else{
						// $('#enterBidPrice').css('border-color', '#ccc');
						// $('#shipping_zip').css('border-color', '#ccc');
						// getShipmentRate();
					// }

				}
			}
        });

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
			sellPriceShipRateWithCAD = parseFloat(shipRateWithCAD) + parseFloat(price);

			var percen = 3;
			if (isNaN(sellPriceShipRateWithCAD) || isNaN(percen)) {
				processingFee = " ";
			} else {
				processingFee = ((percen * sellPriceShipRateWithCAD) / 100).toFixed(2);
			}
			finalCalPrice = parseFloat(sellPriceShipRateWithCAD) + parseFloat(processingFee);
			finalCalPrice = finalCalPrice.toFixed(2); //wrap upto 2 float
			//FORMULA APPLICATIONS END'S HERE

			var comPrice = parseFloat(finalCalPrice / 100) * commissionPrice;
			comPrice = comPrice.toFixed(2);
			if (comPrice < 1) {
				comPrice = '1.00';
			}
			
			if($('#hiddenBidType').val() == 'buy-now'){
				finalCalPrice = finalCalPrice - comPrice;
				finalCalPrice = finalCalPrice.toFixed(2); //wrap upto 2 float
			}
			console.log('final price---' + finalCalPrice + '++++++after cal commissionPrice --->' + comPrice);

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
				$('.processingFeeCal').html('+$' + processingFee);
			}

			$('.priceP').html('+' + shipmentCostWithDollar); //add plus sign with shipping rate
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
            console.log('hits flat rate method');
            var shipRateWithCAD = parseFloat(flatShippingRate) + parseFloat(fixedCadAmount);
            var sellPriceShipRateWithCAD = parseFloat(shipRateWithCAD) + parseFloat(price);
            var percen = 3
            if (isNaN(sellPriceShipRateWithCAD) || isNaN(percen)) {
                processingFee = " ";
            } else {
                processingFee = ((percen * sellPriceShipRateWithCAD) / 100).toFixed(2);
            }
            finalCalPrice = parseFloat(sellPriceShipRateWithCAD) + parseFloat(processingFee);
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
                $('.totalPrice').html(finalCalPrice.replace(/[$-]/g, function ($1) {
                    return $1 === '-' ? '$' : '-'
                }));
            } else {
                $('.totalPrice').html('$' + finalCalPrice);
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
                $('.processingFeeCal').html('+$' + processingFee);
            }

            //return false;
            //$('.processingFeeCal').html('+$' + processingFee); //update the processing Fee
            //$('.totalPrice').html('$' + finalCalPrice);
            $('.priceP').html('+$' + flatShippingRate); //add plus sign with shipping rate
            $('#shippingError').html('');
            $('.commissionFee').html('-$' + comPrice);
        }

        /*********************************************
         *  Get Province List on changing the country
         *
         **********************************************/
        function getProvince() {
            var val = document.getElementById("shipping_country").value;
            console.log(val);
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
      // $('#shipping_country').on('change', getProvince);

        /*Call province method on load also*/        
    </script>
@endsection