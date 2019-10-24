@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
<div class="container">
		<div class='row hypeCardPage'>
        
			<div class='hype-card-block'>
                <div class="hypeCard cardPadding">
                    <div class="hypeCardFace hypeCardFace-front">
                        <div class="cardInner">
                            <div class="cardrow">
                                <span class="blankBlock"></span>
                                <h3 class="card-type">@lang('home.CARD')</h3>
                            </div><!-- card row ends -->
                            
                            <div class="cardrow">
                                <div class="cardnumber">
                                    <span id="0"></span>
                                    <span id="1"></span>
                                    <span id="2"></span>
                                    <span id="3"></span>
                                    
                                    <span id="4"></span>
                                    <span id="5"></span>
                                    <span id="6"></span>
                                    <span id="7"></span>
                                    
                                    <span id="8"></span>
                                    <span id="9"></span>
                                    <span id="10"></span>
                                    <span id="11"></span>
                                    
                                    <span id="12"></span>
                                    <span id="13"></span>
                                    <span id="14"></span>
                                    <span id="15"></span>
                                </div>
                            </div><!-- row ends -->
                            
                            <div class="cardrow">
                                <div class="cardHolder">--</div>
                                <div class="cardExpiryDate">
                                    <label>@lang('home.Valid')<br />@lang('home.Through')</label>
                                    <div class="ExpDate">
                                        <label>@lang('home.Month/Year')</label>
                                        <div class="monthYear1"><span class="month">@lang('home.MM')</span> <small>/</small> <span class="year">@lang('home.YYYY')</span> </div>
                                    </div>
                                </div>
                            </div><!-- row ends -->
                            
                        </div><!-- card inner ends -->      
                    </div><!-- hype card face ends -->
                    
                    <!-- ============================================= card face 2 start ===================== -->
                    
                     <div class="hypeCardFace hypeCardFace-back">
                        <div class="cardStripe"></div>
                        <div class="cardCvv"><div class="cvvStripe"></div><span class="backsideCVV">@lang('home.CVV')</span></div>
                        <div class="cardBackBottom">
                            <span class="blankBlock"></span>
                            <p></p>
                        </div>
                     
                     </div>
                    
                    
                    
                </div><!-- card ends -->
                
                
                
                
                
            </div>
            
            
            
            
            
            
			<div class='hype-card-block cardDetailForm'>
				<script src='https://js.stripe.com/v2/' type='text/javascript'></script>
                <form accept-charset="UTF-8" action="{{ route('save-cards') }}" class="require-validation" data-cc-on-file="false"
					data-stripe-publishable-key="pk_test_4IM4LmSwVO580Z1zC3BsShzx" id="payment-form" method="POST">
				<!--form accept-charset="UTF-8" action="{{ route('save-cards') }}" class="require-validation" data-cc-on-file="false"
					data-stripe-publishable-key="pk_live_VCgTWfZJ6eQGGJPba9ddqug3" id="payment-form" method="POST"-->
					{{ csrf_field() }}
					

					<!-- account details -->
					<div class='form-row'>
						<div class='col-xs-12 form-group required'>
							<label class='control-label'>@lang('home.Name on Card')</label> <input
								class='form-control cardname' size='4' type='text'>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-xs-12 form-group card required'>
							<label class='control-label'>@lang('home.Card Number')</label> <input
								autocomplete='off' class='form-control card-number' size='20'
								type='text'>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-xs-4 form-group cvc required'>
							<label class='control-label'>@lang('home.CVC')</label> <input
								autocomplete='off' class='form-control card-cvc'
								placeholder='ex. 311' maxlength="4" type='text'>
						</div>
						<div class='col-xs-4 form-group expiration required'>
							<label class='control-label'>@lang('home.Expiration')</label> <input
								class='form-control card-expiry-month' placeholder="@lang('home.MM')" maxlength="2"
								type='text'>
						</div>
						<div class='col-xs-4 form-group expiration required'>
							<label class='control-label'> </label> <input
								class='form-control card-expiry-year' placeholder="@lang('home.YYYY')"
								maxlength="4" type='text'>
						</div>
					</div>
					<!--div class='form-row cardButtons'>
						<div class='col-md-12'>
							<div class='form-control total btn btn-info'>
								<span class='amount'>Save Card</span>
							</div>
						</div>
					</div-->

					<div class='form-row'>
						<div class='col-md-12 form-group'>
							<button class='form-control btn btn-primary submit-button'
								type='submit' style="margin-top: 10px;">@lang('home.Save Card')</button>
						</div>
					</div>
					<div class='form-row'>
						<div class='col-md-12 error form-group hide'>
							<div class='alert-danger alert'>@lang('home.Please correct the errors and try again').</div>
						</div>
					</div>

				</form>
				@if ((Session::has('success-message')))
				<div class="alert alert-success col-md-12">{{
					Session::get('success-message') }}</div>
				@endif @if ((Session::has('fail-message')))
				<div class="alert alert-danger col-md-12">{{
					Session::get('fail-message') }}</div>
				@endif
			</div>
			<!-- <div class='col-md-4'></div> -->
		</div>
	</div>
    
   <div class="cardDetails">
   <h3>@lang('home.My Saved Cards')</h3>
    <br/>
        <div class="saved-payment-cards">
        
            @if(count($stripeData) > 0)
                @foreach($stripeData as $keyID=>$value)
                    @foreach($value as $key=>$stripe)
                                                            
                        Last 4 Digits : {{ $stripe->last4 }}
                        <br/>
                        Expiry Month : {{ $stripe->exp_month }}
                        <br/>
                        Expiry Year : {{ $stripe->exp_year }}
                        <a href="remove-card/{{ $keyID}}">@lang('home.Remove card')</a>
                        <hr>
                    @endforeach
                @endforeach
            @endif	
            
             @if(count($stripeData) == 0)
                 <h4>@lang("home.You don't have any payment info saved").</h4>
             @endif	
        
        </div>
    </div>
    
    
    
    
    
    
@endsection 

@section('scripts')

<script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script>
		$(function() {
			  $('form.require-validation').bind('submit', function(e) {
			    var $form         = $(e.target).closest('form'),
			        inputSelector = ['input[type=email]', 'input[type=password]',
			                         'input[type=text]', 'input[type=file]',
			                         'textarea'].join(', '),
			        $inputs       = $form.find('.required').find(inputSelector),
			        $errorMessage = $form.find('div.error'),
			        valid         = true;
			    $errorMessage.addClass('hide');
			    $('.has-error').removeClass('has-error');
			    $inputs.each(function(i, el) {
			      var $input = $(el);
			      if ($input.val() === '') {
			        $input.parent().addClass('has-error');
			        $errorMessage.removeClass('hide');
			        e.preventDefault();
			      }
			    });
			  });
			});
			$(function() {
			  var $form = $("#payment-form");
			  $form.on('submit', function(e) {
			    if (!$form.data('cc-on-file')) {
			      e.preventDefault();
			      Stripe.setPublishableKey($form.data('stripe-publishable-key'));
			      Stripe.createToken({
			        number: $('.card-number').val(),
			        cvc: $('.card-cvc').val(),
			        exp_month: $('.card-expiry-month').val(),
			        exp_year: $('.card-expiry-year').val()
			      }, stripeResponseHandler);
			    }
			  });
			  function stripeResponseHandler(status, response) {
			    if (response.error) {
			      $('.error')
			        .removeClass('hide')
			        .find('.alert')
			        .text(response.error.message);
			    } else {
			      // token contains id, last4, and card type
			      var token = response['id'];
			      //var paypal_id = $(".paypal_id").val();
					
						// insert the token into the form so it gets submitted to the server
			      $form.find('input[type=text]').empty();
			      $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
			      $form.get(0).submit();
			    }
			  }
			})
		</script>
<script>

var card = document.querySelector('.hypeCard');
card.addEventListener( 'click', function() {
  card.classList.toggle('is-flipped');
});


$(".card-cvc").click(function(){
   $(".hypeCard").addClass('is-flipped');
});

$(".card-expiry-month,.card-number,.cardname").click(function(){
    $(".hypeCard").removeClass('is-flipped');
});

$(".card-number").keyup(function() {
  
  var number = $(".card-number").val();
  var length = number.length;
  var emptyFields = 16 - number.length;

  for (var i = 0; i < number.length; ++i) {
    var chr = number.charAt(i);
    $("#"+i).html(chr);
    $("#"+i).addClass('filled');
  }
  
  for (var i = length; i < emptyFields; ++i) {
    $("#"+i).html('');
    $("#"+i).removeClass('filled');
  }
  
  
});


$(".cardname").keyup(function(){
    
    $(".cardHolder").html($(".cardname").val());
    
});

$(".card-expiry-month").keyup(function(){
    
    var month = $(".card-expiry-month").val();
    var monLen = month.length;
    if(monLen == 0){
        $(".month").html('MM');
    }else{
        $(".month").html($(".card-expiry-month").val());
    }
    
});


$(".card-expiry-year").keyup(function(){
    
    var year = $(".card-expiry-year").val();
    var yrLen = year.length;
    if(yrLen == 0){
        $(".year").html('YYYY');
    }else{
        $(".year").html($(".card-expiry-year").val());
    }
    
    
});


$(".card-cvc").keyup(function(){
    
    var cvv = $(".card-cvc").val();
    var cvvLen = cvv.length;
    if(cvvLen == 0){
        $(".backsideCVV").html('CVV');
    }else{
        $(".backsideCVV").html($(".card-cvc").val());
    }
    
    
});


$(".card-number").keydown(function (event) {
    
    var number = $(".card-number").val();
    var length = number.length;
    var num = event.keyCode;
    
    if(length < 2){
       $(".card-type").html('CARD'); 
    }
    
    if(length > 2){
       cardType = GetCardType(number);
       $(".card-type").html(cardType);
    }
    
    
    if(length > 15 && num != 8){
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




$(".card-cvc").keydown(function (event) {
    
    var number = $(".card-cvc").val();
    var length = number.length;
    var num = event.keyCode;
    
    if(length > 3 && num != 8){
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


$(".card-expiry-month").keydown(function (event) {
    
    var number = $(".card-expiry-month").val();
    var length = number.length;
    var num = event.keyCode;
    
    if(length > 1 && num != 8){
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



$(".card-expiry-year").keydown(function (event) {
    
    var number = $(".card-expiry-year").val();
    var length = number.length;
    var num = event.keyCode;
    
    if(length > 3 && num != 8){
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






$(".cardname").keypress(function(e) {
    
        var key = e.keyCode;
        var number = $(".cardname").val();
        var length = number.length;

        if(length > 16 && key != 8){
            event.preventDefault();
        }
        if (key >= 48 && key <= 57) {
                e.preventDefault();
        }
        
        
});




$('.cardname').on('keypress', function (event) {
    var regex = new RegExp("^[0-9a-zA-Z \b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});




function GetCardType(number)
{
    // visa
    var re = new RegExp("^4");
    if (number.match(re) != null)
        return "Visa";

    // Mastercard 
    // Updated for Mastercard 2017 BINs expansion
     if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number)) 
        return "Mastercard";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "AMEX";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "Discover";

    // Diners
    re = new RegExp("^36");
    if (number.match(re) != null)
        return "Diners";

    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "Diners - Carte Blanche";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "JCB";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "Visa Electron";

    return "";
}

</script>
@endsection
