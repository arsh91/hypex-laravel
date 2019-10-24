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

                       


                         <div class="row OrderpageStyle">
                             
                            <div class="col-md-6 productPic">
                                @php
                                $file = $orderdetails['product']['product_image_link'];
                                $mainImage = current($file);
                                @endphp 
                                <span><img src="{{ url($mainImage) }}" alt="" /></span>
                                <!-- <span><img src="summeryShoe.png" alt="" /></span> -->
                                <!-- <div class="btn-center text-center">
                                	<a href="#">Track Order</a>

                                </div> -->

                                <!-- <div class="shareOrder">
                                    <p>Share with friends</p>
                                    <a href="#"><img src="images/fb-icon.png" alt="" /></a>
                                    <a href="#"><img src="images/insta-icon.png" alt="" /></a>
                                    <a href="#"><img src="images/twitter-icon.png" alt="" /></a>
                                </div> -->
                            </div>

                            <div class="col-md-6 OrderSummary">
                                <div class="summaryInner text-center">
                                    <h2>@lang('home.Your Purchase Is Confirmed!')</h2>
                                    <h3 class="productTitle">{{$orderdetails['product']['product_name']}}</h3>
                                    <h4 class="mt-4">@lang('home.Order Reference') : <strong>#{{$orderdetails['order_ref_number']}}</strong></h4>
                                    <h4>@lang('home.Order date') : <span><?php echo date("d-m-Y", strtotime($orderdetails['created_at'])); ?></span></h4>


                                    <h6 class="orderDetails">@lang('home.Summary')</h6>
                                    <ul>
                                        <li><p>{{$orderdetails['product']['product_name']}}</p><span>${{$orderdetails['price']}}</span></li>
                                        <li><p>@lang('home.Shipping')</p><span>${{$orderdetails['shipping_price']}}</span></li>
                                        <li><p>@lang('home.Processing')</p><span>${{$orderdetails['processing_fee']}}</span>
                                        </li>
                                        <li class="Orderresult">
                                            <p><strong>@lang('home.Total')</strong></p><span><strong>${{$orderdetails['total_price']}}</strong></span>
                                        </li>
                                    </ul>


                                    <div class="helpText">
                                        <h4>@lang('home.Questions About The Order') ?</h4>
                                        <p>@lang('home.Contact Us')  : <a href="mailto:info@hypex.ca">info@hypex.ca</a></p>
                                    </div>

                                    <!--<div class="orderSuccessBottom">
                                        <p>@lang('home.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua').</p>
                                    </div>-->

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