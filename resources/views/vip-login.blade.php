@extends('layouts.website-without-header-footer')

@section('content')
        <div class="flex-center login-container position-ref full-height">
			<div class="login-inner">
			<div class="container">
			<div class="login-inner-model">
			<form method="POST" action="{{ route('login') }}">
			@csrf
					<h2></h2>
					<h2>VIP Members Login<a class="pull-right" href="{{ route('home') }}"> <img src="{{ asset('v1/website/uploads/thumbnail/logo.png') }}" alt="Back To Home" /> <span> x </span></a></h2>
					 
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					<!-- <label for="email"><b>Email</b></label> -->
					<input type="email" placeholder="Enter Email" value="{{old('email')}}" name="email" required>
					<br/>
					<!-- <label for="psw"><b>Password</b></label> -->
					<input type="password" minlength="6" placeholder="Enter Password" name="password" required>
					<br/>

					<button type="submit" class="loginbtn">Sign In</button>
				   
  
				  <div class="signin">
					<p>Already a VIP member? <a href="{{ url('/vip-signup') }}">Sign up</a>
					<span style="float:right;"><a href="{{ url('/forgot-password') }}">Forgot Password?</a></span>
				  </div>

			</form>
			</div>
			</div>
			</div>
        </div>
@endsection 
