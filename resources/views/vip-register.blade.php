@extends('layouts.website-without-header-footer')

@section('content')
        <div class="flex-center login-container register-container position-ref full-height">
			<div class="login-inner">
			<div class="container">
			<div class="login-inner-model">
                
			<form method="POST" action="{{ route('register') }}">
			@csrf
				  <div class="container">
					<h2>VIP Membership Registeration <a class="pull-right" href="{{ route('home') }}"> <img src="{{ asset('v1/website/uploads/thumbnail/logo.png') }}" alt="Back To Home" /> <span> x </span></a></h2>
					<p>Please fill in this form to become a VIP Member on HYPEX</p>
					<hr>

					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					
					<input type="text" placeholder="Enter First Name*" value="{{old('first_name')}}" name="first_name" required>
					 
				 
					<input type="text" placeholder="Enter Last Name*" value="{{old('last_name')}}" name="last_name" required>
				 
					 
					<input type="text" placeholder="Enter User Name*" value="{{old('user_name')}}" name="user_name" required>
					 
				 
					<input type="email" placeholder="Enter Email*" value="{{old('email')}}" name="email" required>
				 
				 
					<input type="password" minlength="6" placeholder="Enter Password" name="password" required>
					 
					 
					<p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

					<button type="submit" class="registerbtn loginbtn">Register</button>
				  </div>
  
				  <div class="container signin">
					<p>Already have an account? <a href="{{ url('/vip-signin') }}">Sign in</a>.</p>
				  </div>
			</form>
			</div>
			</div>
			</div>
			
        </div>
@endsection 
