@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
<form id="sellBid" name="sellBid" method="POST" action="{{ route('sellBid') }}">
@csrf
	<div class="index-block product-block">    
                    <div class="container">                        

                        <div class="row">
                         <div class="col-lg-6 col-xs-12">
						 @php
							$prodData = current($productDetails);
							$file = $prodData['product_image_link'];
							$mainImage = current($file);
						 @endphp
                            <img src="{{ url($mainImage) }}" alt="" width= "455px">
                         </div>
                         <div class="col-lg-6 col-xs-12">
                            <h2>{{ $prodData['product_name'] }} {{ $prodData['product_brand_type']['brand_type_name'] }}</h2>
                            <p class=" productSize">Selected Size : {{ $productDetails['size'] }}</p>
							<input type="hidden" name="hiddenSizeId" value="{{ $productDetails['sizeID'] }}">
							<input type="hidden" name="hiddenProdId" value="{{ $prodData['id'] }}">
							@if(isset($minSellData[$productDetails['size']]))
								<p class="productPrice">CA ${{ $minSellData[$productDetails['size']] }}<span>Highest Offer</span></p>
								<input type="hidden" name="hiddenPrice" id="hiddenPrice" value="{{ $minSellData[$productDetails['size']] }}">
							@else
								<input type="hidden" name="hiddenPrice" value="0">
							@endif
								

                         </div>
                        </div>
                    </div>
                </div>
    <!-- / section ends ======================================  -->

   <div class="index-block productInfo">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xs-12">
				<span id="error" style="color:red;"></span>
				<span id="shippingError" style="color:red;"></span>
                      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                 Shipping & Billing Address
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
					  <input required id="shipping_first_name" placeholder="First Name" name="shipping_first_name" type="text" value="{{old('shipping_first_name')}}" onblur="copy(this);" maxlength='15'>
					  <input required id="shipping_last_name" placeholder="Last name" name="shipping_last_name" type="text" value="{{old('shipping_last_name')}}" onblur="copy(this);" maxlength='15'>
					  
					<input required id="shipping_full_address" placeholder="Full Address" name="shipping_full_address" type="text" value="{{old('shipping_full_address')}}" onblur="copy(this);" maxlength='15'>
					<input required id="shipping_street_city" placeholder="Street / City" name="shipping_street_city" type="text" value="{{old('shipping_street_city')}}" onblur="copy(this);" maxlength='15'>
                    
                    <input required id="shipping_phone" placeholder="Phone Number" name="shipping_phone" type="text" value="{{old('shipping_phone')}}" maxlength='15'>
					
					
					<select name="shipping_country" id="shipping_country" class="form-control" >
								<option value="CA" selected>Canada</option>
					</select>
					
					<select class="form-control" name="shipping_province" id="shipping_province" required="" onchange="copy(this);">
							<option value="AB">Alberta</option>
              <option value="BC">British Columbia</option>
              <option value="MB">Manitoba</option>
              <option value="NB">New Brunswick</option>
              <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
              <option value="NL">Northwest Territories</option>
              <option value="NS">Nova Scotia</option>
              <option value="NU">Nunavut</option>
              <option value="ON">Ontario</option>
              <option value="PE">Prince Edward Island</option>
              <option value="QC">Quebec</option>
              <option value="SK">Saskatchewan</option>
              <option value="YT">Yukon</option>
					</select> 
					
					
					<input required id="shipping_zip" placeholder="Zip Code" name="shipping_zip" type="text" value="{{old('shipping_zip')}}" onblur="copy(this);" maxlength='8' minlength='4'>    					
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Return Address
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
			  
					<input required id="billing_first_name" placeholder="First Name" name="billing_first_name" type="text" value="{{old('billing_first_name')}}" onblur="copy(this);" maxlength='15'>
					  <input required id="billing_last_name" placeholder="Last name" name="billing_last_name" type="text" value="{{old('billing_last_name')}}" onblur="copy(this);" maxlength='15'>

					<input required id="billing_full_address" placeholder="Full Address" name="billing_full_address" type="text" value="{{old('billing_full_address')}}" maxlength='15'>
					<input required id="billing_street_city" placeholder="Street / City" name="billing_street_city" type="text" value="{{old('billing_street_city')}}" maxlength='15'>
                    
                    <input required id="billing_phone" placeholder="Phone Number" name="billing_phone" type="text" value="{{old('billing_phone')}}" maxlength='15'>
					
					<select name="billing_country" id="billing_country" class="form-control" >
								<option value="CA" selected>Canada</option>
					</select>

					<select class="form-control" name="billing_province" id="billing_province" required="">
							<option value="AB">Alberta</option>
              <option value="BC">British Columbia</option>
              <option value="MB">Manitoba</option>
              <option value="NB">New Brunswick</option>
              <option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
              <option value="NL">Northwest Territories</option>
              <option value="NS">Nova Scotia</option>
              <option value="NU">Nunavut</option>
              <option value="ON">Ontario</option>
              <option value="PE">Prince Edward Island</option>
              <option value="QC">Quebec</option>
              <option value="SK">Saskatchewan</option>
              <option value="YT">Yukon</option>
					</select>
					
					
    
					<input required id="billing_zip" placeholder="Zip Code" name="billing_zip" type="text" value="{{old('billing_zip')}}" maxlength='8' minlength='4'> 
              </div>
            </div>
          </div>
			<label>
				<input type="checkbox" id="same_as_shipping" /> Return Address Same as Shipping
			</label>
        </div>

                </div>
                 <div class="col-lg-4 col-md-4 col-xs-12">
				 <span id="error-info" style="color:red;visibility:hidden;"></span>
				 <div class="panel panel-default panel-right">
					<div class="panel-heading" role="tab" id="headingThree">
					  <h4 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseTwo">
						  Shipping Package
						</a>
					  </h4>
					</div>
					<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
					  <div class="panel-body">
							<input required id="length" placeholder="Length (cm)" name="length" type="text" value="35.00">
							<input required id="width" placeholder="Width (cm)" name="width" type="text" value="23.50">
							<input required id="height" placeholder="Height (cm)" name="height" type="text" value="13.50">    
							<input required id="weight" placeholder="Weight (lb)" name="weight" type="text" value="3"> 
					  </div>
					</div>
				  </div>
				 
                  <ul class="borderLeft">
                 
                   <li id="shiipingCostSection">
                      <div class="shippingLoader" style="display: none;">Loading...</div>
                     <div class="shippingDiv">  
                     <strong>Shipping Cost</strong> 
                       <p class="priceP"></p> 
                     </div>
                   </li> 
                   <li>  
                   <strong>Processing Fee (-3%)</strong> 
                     <p id="process_fee" name="process_fee" class="processingFeeCal">$0.00</p> 
                   </li>  
              
                </ul> 
                </div>
                 <div class="col-lg-4 col-md-4 col-xs-12">
                  <ul class="borderLeft">
                  
                  <div class="shippingLoader" style="display: none;">Loading...</div>
                   <li class="affectedByShiipingCost" style="display: none;">  
                   <span>Total Price</span> 
                   <strong class="boldPrice">+ <span class="totalPrice"></span> </strong>                    
                   </li>  

                   <li><p>I have read <a href="#">Selling Agreement</a></p></li> 
                   <li><button type="submit" value="submit" id="sellNowButton">Submit</button></li>
                </ul> 
				
                </div>
            </div>
        </div>  


   </div>

</form>	
@endsection 

@section('scripts')
<script>

$("#shipping_phone,#billing_phone").keydown(function (event) {
    
    var number = $("#shipping_phone").val();
    var length = number.length;
    var num = event.keyCode;
    
    if(length > 12 && num != 8){
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

$("#enterBidPrice").keydown(function (event) {
    
    var number = $("#enterBidPrice").val();
    var length = number.length;
    var num = event.keyCode;
    
    if(length > 4 && num != 8){
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


$('#shipping_first_name,#shipping_last_name,#shipping_street_city,#billing_first_name,#billing_last_name,#billing_street_city').keypress(function(e) {
    
        var key = e.keyCode;
        var number = $(this).val();
        var length = number.length;

        if(length > 16 && key != 8){
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






$("#sellNowButton").click(function(){
	var isError = 0; //set Flag
	$("form#sellBid :input").each(function(e){
		if($(this).val() == ''){
			isError = 1; //update the flag
			var id = $(this).parent().parent().attr('id');
			if(id == 'collapseTwo'){
				if($("#headingTwo a").hasClass('collapsed')){
					$("#headingTwo a").click();
				}
			}
			
			if(id == 'collapseThree'){
				if($("#headingThree a").hasClass('collapsed')){
					$("#headingThree a").click();
				}
			}
			
			var submitButton = $(this).html();
			$(this).focus();
			if(submitButton != 'Submit'){
				$(this).css('border-color','red');
				$("#error").html('Please fill the required fields !!');
				$("#error-info").html('Please fill the required fields !!');
			}
			return false;
			e.preventDefault();
		}
	});
}); //click method ends here

var fixedCadAmount = '01.00'; //Canadian Dollar

/*Below function hits the Shipstation API
* @params form_data
* @return Shipping Rate
*/
$('#sellBid').submit(function(e) {
  var shoePrice = $('#hiddenPrice').val(); //shoes price
  var shipRateWithCAD = '';
  var sellPriceShipRateWithCAD = '';
  var processingFee = '';
  var processingFeeCal = '';
  var error = 0;
  var shipstationError = 0;
  var formValues = {};
  //var clientHeadqauters = "V6V 1Z4";
  var form = this;
  e.preventDefault();
      
      $('#sellBid input').each(function(element){                   
            if($.trim($(this).val()) == "") {
                  error = error + 1;
            }
      }); // loop ends here

      //Get the form values and populate the API data
      $.each($('#sellBid').serializeArray(), function(i, field) {
          formValues[field.name] = field.value;
      });

      if(!error) {
         $('.shippingLoader').show(); //show the loader before request

            //hit the shipstation API              
            var request = new XMLHttpRequest();

            request.open('POST', 'https://private-anon-02d08c1d82-shipstation.apiary-proxy.com/shipments/getrates');

            request.setRequestHeader('Content-Type', 'application/json');
            request.setRequestHeader('Authorization', 'Basic MWE1ZDE2MDZkZDczNGExNmFlYmQ3ZmIyNzVjNDhjYjg6MWRkMjIzOTdkZjEwNDI0MzhjYmNhN2M2YzUxNDVkNWM=');

            request.onreadystatechange = function () {
              if (this.readyState === 4) {
                 $('.shippingLoader').hide();

                  console.log(this.responseText);
                if(this.status == 200){
                  var shipmentData = this.responseText;
                  var res = this.responseText;
                  res = JSON.parse(res);
                  var shipmentCost = res['0'].shipmentCost;
                  var shipmentCostWithDollar = '$'+shipmentCost;
                  console.log(shoePrice);

                  //STEP I:-
                  //FORMULA APPLICATIONS START'S HERE 
                  shipRateWithCAD = parseFloat(shipmentCost) + parseFloat(fixedCadAmount);
                  sellPriceShipRateWithCAD = parseFloat(shoePrice) - parseFloat(shipRateWithCAD);
                  console.log('before processing percentage');
                  console.log(sellPriceShipRateWithCAD);
                  //return false;
                  var percen = 3
                  if(isNaN(sellPriceShipRateWithCAD) || isNaN(percen)){
                    processingFee=" ";
                  }else{
                    processingFee = ((percen/sellPriceShipRateWithCAD) * 100).toFixed(2);
                  }
                  console.log('processingFee-------->'+processingFee);
                  finalCalPrice = parseFloat(sellPriceShipRateWithCAD) - parseFloat(processingFee);
                  finalCalPrice = finalCalPrice.toFixed(2); //wrap upto 2 float
                  console.log('finalprocessed');
                  console.log(finalCalPrice);

                  //FORMULA APPLICATIONS END'S HERE
                 // return false;

                  $('.shippingDiv').show();
                  $('.affectedByShiipingCost').show();
                  $('.processingFeeCal').html('+$'+processingFee); //update the processing Fee
                  $('.totalPrice').html('$'+finalCalPrice); //update the total price section
                  $('.priceP').html(shipmentCost);  
                  $('#shippingError').html('');

                  //STEP II:-
                  //store the price values into session
                  $.ajax({
                    url: '/savePriceToSession',
                    type: "GET",
                    data: {shippingRate:shipmentCost, totalPrice: finalCalPrice, processingFee:processingFee},
                     success: function(response){ // What to do if we succeed
                        //if(data == "success")
                      //alert(response); 
                      }
                  });
				
				//show the overlay div with loader
                setTimeout(function () {
                    $('#overlay').show();
                }, 1000);
				  
        				setTimeout(function () {
        					form.submit();
        				}, 2500);  
					
                }else{
                  shipstationError = shipstationError + 1; //update if API throw any error
                  $('#shippingError').html('Please correct shipping details');
                  $('.shippingDiv').hide();
                  return false;
                }
                
              }
            };

            var body = {
                "carrierCode": "canada_post",
                "serviceCode": "xpresspost",
                "packageCode": null,
                "fromPostalCode": formValues.shipping_zip,
                "toState": formValues.shipping_province,
                "toCountry": formValues.shipping_country, 
                "toPostalCode": "V6V 1Z4",
                "toCity": formValues.shipping_street_city,
                "weight": {
                "value": formValues.weight,
                "units": "pounds"
                },
                "dimensions": {
                "units": "centimeters",
                "length": formValues.length,
                "width": formValues.width,
                "height": formValues.height
                },
                "confirmation": "delivery",
                "residential": false
                };

                request.send(JSON.stringify(body));      
               //  return false;        
    }
    else {
      console.log('still error');
      return false; 
    }

    console.log(shipstationError);          
  });


$("input").keypress(function(){
  $(this).css('border-color','black');
  $("#error").html('');
  $("#error-info").html('');
});


$("#same_as_shipping").click(function(){
	
	var checked = $(this).is(':checked');
	if(checked){
		
		if($("#shipping_full_address").val() != "" && $("#shipping_street_city").val() != "" && $("#shipping_country").val() != "" && $("#shipping_zip").val() != "" && $("#shipping_first_name").val() != "" && $("#shipping_last_name").val() != "" && $("#shipping_phone").val() != ""){
			
            $("#error").html('');
			$("#billing_first_name").val($("#shipping_first_name").val());
			$("#billing_last_name").val($("#shipping_last_name").val());
			$("#billing_full_address").val($("#shipping_full_address").val());
			$("#billing_street_city").val($("#shipping_street_city").val());
            $("#billing_phone").val($("#shipping_phone").val());
			$("#billing_country").val($("#shipping_country").val());
			$("#billing_province").val($("#shipping_province").val()).change();
			$("#billing_zip").val($("#shipping_zip").val());
			
		}else{
			
			$("#error").html('Please fill the shipping address first !!');
			return false;
			e.preventDefault();
			
		}
		
		
	}
	
});

function copy(data){
	
	var checked = $("#same_as_shipping").is(':checked');
	if(checked){
		
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
</script>
@endsection