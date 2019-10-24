@extends('layouts.website-without-header-footer')

@section('content')
       <?php 
		$registerClass = 'showOverlay';
		$loginClass = '';
		if($errors->any() && !session('form')){
			$registerClass = '';
			$loginClass = 'showOverlay';
		}
	   ?>
		
		<div class="hype-signupform">
	 
		<!-- main content -->
		<div class="agile_info">

			<!-- ================================= left grid ends ========================== -->

			<div class="hypeLogin_info hypeSignIn-Blocks">

				<!-- ============================== overlay div ====================== -->

				<div id="leftOverlay" class="w3l_form hype-overlayform <?php echo $loginClass; ?>">				
					<div class="left_grid_info hype-sign-block">
						<img class="hype-formLogo" src="{{ url('public/v1/website/img/hype-logo-white.png') }}" alt="" />
						<h1>@lang('home.Start Selling Now On Hypex')</h1>
							<p>@lang('home.Register with HYPEX')</p>					 
							<img style="max-width:450px; display:inline-block;margin-top:60px;" src="{{ url('public/v1/website/img/loginGraphics.png') }}" alt="" />					 
					</div>			
				</div>

			<!-- ========================================= overlay div ends ================ -->



				<div class="hypeLogin_infoInner">
					<h2>@lang('home.Login')</h2>
					<p>@lang('home.Enter your details to login.')</p>
					<form method="POST" action="{{ route('login') }}">
					@csrf

					@if ($errors->any() && session('form') == 'login')
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif



						<label>@lang('home.Email Address')</label>
						<div class="input-group">
							<span class="fa fa-envelope" aria-hidden="true"></span>
							<input type="email" placeholder="@lang('home.Enter Email')" value="{{old('email')}}" name="email" required> 
						</div>
						<label>@lang('home.Password')</label>
						<div class="input-group">
							<span class="fa fa-lock" aria-hidden="true"></span>
							<input type="password" minlength="6" placeholder="@lang('home.Enter Password')" name="password" required>
						</div> 
						<div class="login-check">
							 <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i> </i> </label><b>@lang('home.Remember Me')</b>
						</div>						
							<button class="btn btn-danger btn-block" type="submit">@lang('home.Login')</button >                
					</form>
					<p class="account">@lang('home.By clicking login, you agree to our') <a href="{{url('term-condition')}}">@lang('home.Terms & Condition')</a></p>
					<p class="account">@lang('home.Forgot Password?') <a href="{{ url('/forgot-password') }}">@lang('home.Forgot Password')</a></p>
					<p class="account1">@lang('home.Dont have an account?') <a href="#" id="registerOverlay">@lang('home.Register here')</a></p>
				</div><!-- iner ends -->
			</div>
			<!-- login form ends -->


			 

			<div class="hypeLogin_info hypeSignUp-Blocks">


				<!-- ============================== overlay div ====================== -->

				<div id="rightOverlay" class="w3l_form hype-overlayform <?php echo $registerClass; ?>">				
					<div class="left_grid_info hype-sign-block">
						<img class="hype-formLogo" src="{{ url('public/v1/website/img/hype-logo-white.png') }}" alt="" />
						<h1>@lang('home.Start Selling Now On Hypex')</h1>
							<p>@lang('home.Login to your HYPEX Account.')</p>					 
							<img style="max-width:500px; display:inline-block;margin-top:0px;" src="{{ url('public/v1/website/img/jordan-wall.png') }}" alt="" />					 
					</div>			
				</div>

			<!-- ========================================= overlay div ends ================ -->



				<div class="hypeLogin_infoInner">
					<h2>@lang('home.Register')</h2>
					<p>@lang('home.Please fill in this form to create an account on HYPEX.')</p>
					<form method="POST" action="{{ route('register') }}">
					@csrf

					@if ($errors->any() && !session('form'))
						
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif


						<label>@lang('home.First Name')</label>
						<div class="input-group">
							<span class="fa fa-user" aria-hidden="true"></span>
							<input type="text" placeholder="@lang('home.Enter First Name')*" value="{{old('first_name')}}" name="first_name" required>
						</div>

						<label>@lang('home.Last Name')</label>
						<div class="input-group">
							<span class="fa fa-user" aria-hidden="true"></span>
							<input type="text" placeholder="@lang('home.Enter Last Name')*" value="{{old('last_name')}}" name="last_name" required>
						</div>

						<label>@lang('home.User Name')</label>
						<div class="input-group">
							<span class="fa fa-envelope" aria-hidden="true"></span>
							<input type="text" placeholder="@lang('home.Enter User Name')*" value="{{old('user_name')}}" name="user_name" required>
						</div>

						<label>@lang('home.Email')</label>
						<div class="input-group">
							<span class="fa fa-envelope" aria-hidden="true"></span>
							<input type="email" placeholder="@lang('home.Enter Email')*" value="{{old('email')}}" name="email" required>
						</div>
	 
						 

						<label>@lang('home.Create Password')</label>
						<div class="input-group">
							<span class="fa fa-lock" aria-hidden="true"></span>
							<input type="password" minlength="6" placeholder="@lang('home.Enter Password')" name="password" required>
						</div> 

						<p>@lang('home.By creating an account you agree to our') <a href="#">@lang('home.Terms & Privacy')</a>.</p>
	 					
							<button class="btn btn-danger btn-block" type="submit">@lang('home.Register')</button >                
					</form>
					<p class="account">@lang('home.By creating an account you agree to our') <a href="#">@lang('home.Terms & Conditions!')</a></p>
					<p class="account1">@lang('home.Already have an account?') <a id="loginOverlay" href="#" >@lang('home.Login here')</a></p>
				</div>
			</div>

  
		</div>
		<!-- //main content -->
	</div>

	<script type="text/javascript">

			if ($(window).width() >= 685) { 

			$(function() {                       
					$("#loginOverlay").click(function() {  
					$("#rightOverlay").addClass("showOverlay");   
					$("#leftOverlay").removeClass("showOverlay");   

						});

						$("#registerOverlay").click(function() {  
								$("#rightOverlay").removeClass("showOverlay");   
								$("#leftOverlay").addClass("showOverlay");   

						});
					});

			}else{
				// alert('lesser to 685');
				$(function() {                       
					$("#loginOverlay").click(function() {  
					$("#rightOverlay").addClass("showOverlay");   
					$("#leftOverlay").removeClass("showOverlay");   

						});

						$("#registerOverlay").click(function() {  
								$(".hypeSignUp-Blocks").css("display", "block");
								$(".hypeSignIn-Blocks").css("display", "none");  

						});

						$("#loginOverlay").click(function() {  
								$(".hypeSignUp-Blocks").css("display", "none");
								$(".hypeSignIn-Blocks").css("display", "block");  

						});
					});
			} 

		  $(window).resize(function(){

       		if ($(window).width() >= 800) { 

				$(function() {                       
					$("#loginOverlay").click(function() {  
					$("#rightOverlay").addClass("showOverlay");   
					$("#leftOverlay").removeClass("showOverlay");   

						});

							$("#registerOverlay").click(function() {  
								$("#rightOverlay").removeClass("showOverlay");   
								$("#leftOverlay").addClass("showOverlay");   

							});
						});

			}else{
				// alert('lesser to 685');
				$(function() {                       
					$("#loginOverlay").click(function() {  
					$("#rightOverlay").addClass("showOverlay");   
					$("#leftOverlay").removeClass("showOverlay");   

						});

						$("#registerOverlay").click(function() {  
								$(".hypeSignUp-Blocks").css("display", "block");
								$(".hypeSignIn-Blocks").css("display", "none");  

						});

						$("#loginOverlay").click(function() {  
								$(".hypeSignUp-Blocks").css("display", "none");
								$(".hypeSignIn-Blocks").css("display", "block");  

						});
					});
			}     

});
				 

</script>



@endsection 
