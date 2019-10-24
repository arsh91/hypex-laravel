@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')

<div class="index-block product-block">    
                    <div class="container">                        

                        <div class="row">
                         <div class="col-lg-5 col-xs-12">
                            <img src="images/productImage.png" alt="">
                         </div>
                         <div class="col-lg-7 col-xs-12">
                            <h2>Air Jordan 1 Retro High OG Premium
                            ‘Yin Yang’</h2>
                            <p class=" productSize">Selected Size : 36.5</p>
                            <p class="productPrice">$190 <span>Highest Offer</span></p>
                             <ul class="parallelFields">
                             <li><input type="text" placeholder='Enter Amount'><p>Enter An Offer</p></li>
                              <li><select placeholder="Select Date"><option>Select Date</option></select><p>Expiration Date</p></li>
                                 
                             </ul>
                      
                         </div>
                        </div>
                    </div>
                </div>
    <!-- / section ends ======================================  -->

   <div class="index-block productInfo">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xs-12">
                <ul>
                   <li>  
                   <strong>Shipping and Returning Address</strong> 
                     <p>#7779 Dooley Roads Apt. 668. Lake Melbaside. Cayman 
                        Islands  </p> 
                   </li> 
                   <li>  
                   <strong>Billing Information</strong> 
                     <p>#7779 Dooley Roads Apt. 668. Lake Melbaside. Cayman 
                        Islands  </p> 
                   </li> 
                   <li>  
                   <strong>Payout Information</strong> 
                     <p>#7779 Dooley Roads Apt. 668. Lake Melbaside. Cayman 
                        Islands  </p> 
                   </li>  
                </ul>  

                </div>
                 <div class="col-lg-4 col-md-4 col-xs-12">
                  <ul class="borderLeft">
                 
                   <li>  
                   <strong>Shipping Cost</strong> 
                     <p>$28.00</p> 
                   </li> 
                   <li>  
                   <strong>Processing Fee (+3%)</strong> 
                     <p>+$0.00</p> 
                   </li>  
              
                </ul> 
                </div>
                 <div class="col-lg-4 col-md-4 col-xs-12">
                  <ul class="borderLeft">
                  
                   <li>  
                   <span>Total Price</span> 
                   <strong class="boldPrice">+ $28.00</strong> 
                     
                   </li> 
                   <li><p>I have read <a href="#">Selling Agreement</a></p></li> 
                   <li><button type="submit">Submit</button></li>
                </ul> 
                </div>
            </div>
        </div>  


   </div>

















	PRODUCT NAME : {{ $productDetails['product_name'] }} <br/>
	SELCTED SIZE : {{ $productDetails['size'] }} <br/>
	SIZE TYPE    : {{ $productDetails['product_size_types']['size_type'] }} <br/>
	BRAND NAME   : {{ $productDetails['product_brand']['brand_name'] }} <br/>
	BRAND TYPE   : {{ $productDetails['product_brand_type']['brand_type_name'] }} <br/>
	
@endsection 
