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

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h3 class="order-succes text-success">@lang('home.Thank you for Subscription')</h3>
                            </div>
                        </div>


                         <div class="row">
                             
                            <div class="col-md-6 productPic text-center">
                                <span><img src="summeryShoe.png" alt="" /></span>

                                <!-- <div class="shareOrder">
                                    <p>Share with friends</p>
                                    <a href="#"><img src="images/fb-icon.png" alt="" /></a>
                                    <a href="#"><img src="images/insta-icon.png" alt="" /></a>
                                    <a href="#"><img src="images/twitter-icon.png" alt="" /></a>
                                </div> -->
                            </div>

                            <div class="col-md-6 OrderSummary">
                                <div class="summaryInner text-center">
                                    <h2>@lang('home.Summary')</h2>
                                    <h4>@lang('home.date') : <span><?php echo date("d-m-Y", strtotime($subscriptiondetails['created_at'])); ?></span></h4>

                                    <ul>
                                        <li><p>{{$subscriptiondetails['plan']['duration']}}</p><span>${{$subscriptiondetails['price']}}</span></li>

                                        <li><p>@lang('home.Start Date')</p><span>${{$subscriptiondetails['start_date']}}</span></li>

                                        <li><p>@lang('home.End Date')</p><span>${{$subscriptiondetails['end_date']}}</span>
                                        </li>
                                        <li class="Orderresult">
                                            <p><strong>@lang('home.Total')</strong></p><span><strong>${{$subscriptiondetails['price']}}</strong></span>
                                        </li>
                                    </ul>


                                    <div class="helpText">
                                        <h4>@lang('home.Questions about Order') ?</h4>
                                        <p>@lang('home.Contact Us') : <a href="mailto:help@hypex.ca">help@hypex.ca</a></p>
                                    </div>

                                    <div class="orderSuccessBottom">
                                        <p>@lang('home.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua').</p>
                                    </div>

                                </div><!-- inner ends -->
                            </div>



                         </div><!-- row ends -->


                         <div class="row">
                            <!-- ============================= related products goes here ============================ -->
                         </div><!-- row ends -->



                    </div><!-- container ends -->
                </div>           

@endsection 

@section('scripts')

@endsection