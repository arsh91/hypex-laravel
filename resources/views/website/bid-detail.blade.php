@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
<!-- <h1 style="text-align:center;"> ORDER DETAIL PAGE COMING SOON </h1> -->
<div class="index-block orderSummary-Container">    
                    <div class="container">  
						@php
							$type = $data['type'];
						@endphp 
						
                        <div class="row">
                            <div class="col-md-12 text-center">
								<h3 class="order-succes text-success">
								@if($type == 'bid')
									@lang('home.Your Buying Offer For') ${{$productbidderdetails['bid_price']}}  @lang('home.Is Live'). <!--<span>#{{$productbidderdetails['id']}}</span>-->
								@else
									@lang('home.Thank you for your') <?php echo ucfirst($data['type']); ?> @lang('home.Offer') <span>#{{$productbidderdetails['id']}}</span>
								@endif
                                    
                                    <!-- bidsell order detail page-->
								</h3>	
                            </div>
                        </div>


                         <div class="row">
                             
                            <div class="col-md-6 productPic text-center">
                                <!-- @php
                                $prodData = $productbidderdetails['product'];
                                $file = $prodData['product_image_link'];
                                $mainImage = $file;
                                @endphp -->
                                @php
                                $file = $productbidderdetails['product']['product_image_link'];
                                $mainImage = current($file);
                                @endphp 
                                <span><img src="{{ url($mainImage) }}" alt="" /></span>
                                <!-- <span><img src="" alt="" /></span> -->

                                <!-- <div class="shareOrder">
                                    <p>Share with friends</p>
                                    <a href="#"><img src="images/fb-icon.png" alt="" /></a>
                                    <a href="#"><img src="images/insta-icon.png" alt="" /></a>
                                    <a href="#"><img src="images/twitter-icon.png" alt="" /></a>
                                </div> -->
                            </div>

                            <div class="col-md-6 OrderSummary">
                                <div class="summaryInner text-center">
                                    <h2>
									<?php 
									if($data['type'] == 'bid'){
										echo "Offer"; 
										}else{
											echo ucfirst($data['type']) ;
										}
									?> @lang('home.Summary')</h2>
                                    <h4><span class="firstLetterCaps"> @lang('home.Date') </span>: <span><?php echo date("d-m-Y", strtotime($productbidderdetails['created_at'])); ?> </span></h4>

                                    <ul>
                                        <li><p>{{$productbidderdetails['product']['product_name']}}</p><span>${{$productbidderdetails['bid_price']}}</span></li>
                                        <li><p>@lang('home.Shipping and Handeling')</p><span>${{$productbidderdetails['shipping_price']}}</span></li>
                                        <li><p>@lang('home.Processing')</p><span>${{$productbidderdetails['processing_fee']}}</span>
                                        </li>
                                        <!--<li><p>@lang('home.Additional Price')</p><span>$1</span></li>-->
                                        <li class="Orderresult">
                                            <p><strong>@lang('home.Total')</strong></p><span><strong>${{$productbidderdetails['total_price']}}</strong></span>
                                        </li>
                                    </ul>


                                    <div class="helpText">
                                        <h4>@lang('home.Questions About The Order')</h4>
                                        <p>@lang('home.Contact Us') : <a href="mailto:info@hypex.ca">info@hypex.ca</a></p>
                                    </div>

                                </div><!-- inner ends -->
                            </div>

                         </div><!-- row ends -->

                         <div class="row">
                            <!-- ============================= related products goes here ============================ -->
                         </div><!-- row ends -->

                    </div><!-- container ends -->
                </div>                            
                <!-- search input ends -->

@endsection 

@section('scripts')

@endsection