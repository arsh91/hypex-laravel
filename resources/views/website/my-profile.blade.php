@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content1')
        <div class="flex-center login-container  f-psd position-ref full-height">
		<div class="login-inner">
			<div class="container">
			<div class="login-inner-model">
		
			<form method="POST" action="{{ route('update-profile') }}">
			@csrf
				  <div class="container">
					<h2>@lang('home.My Profile')</h2>
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
					
					<input type="text" placeholder="@lang('home.First Name')*" value="{{ old('first_name', $first_name) }}" name="first_name" required>
					<input type="text" placeholder="@lang('home.Last Name')*" value="{{ old('last_name', $last_name) }}" name="last_name" required>
					<input type="text" placeholder="@lang('home.Enter UserName')*" value="{{ old('user_name', $user_name) }}" name="user_name" required >
					<input type="email" placeholder="@lang('home.Email')*" value="{{$email}}" name="email" required>
					<input type="text" placeholder="@lang('home.Phone')" value="{{ old('phone', $phone) }}" name="phone" maxlength='16'>
					<input type="text" placeholder="@lang('home.City')" value="{{ old('city', $city) }}" name="city" maxlength='16'>
					<input type="text" placeholder="@lang('home.State')" value="{{ old('state', $state) }}" name="state" maxlength='16'>
					<input type="text" placeholder="@lang('home.Country')" value="{{ old('country', $country) }}" name="country" maxlength='16'>
					<input type="text" placeholder="@lang('home.Postal Code')" value="{{ old('postal_code', $postal_code) }}" name="postal_code" minlength='4' maxlength='8'>
					
					<br/>

					<button type="submit" class="loginbtn">@lang('home.Update Profile')</button>
				  </div>
  
				  <!--div class="container signin">
					<p>Don't have an account? <a href="{{ url('/signup') }}">Create Account</a></p>
				  </div-->
			</form>
			</div>
			</div>
			</div>
			
        </div>
@endsection 





@section('content')
        
        
        
        
        <div class="account-section">    
                    <div class="account-form">
                        <div class="account-form-inner">
                            
                                <div id="exTab1" class="container-fluid"> 
                                    
                                    <div class="col-md-12">
                                        <ul  class="navtabsAccount">
                                            <li class="active"><a  href="#1a" data-toggle="tab">@lang('home.My Profile')</a></li>                                                                                                                                 
                                        </ul>
                                    </div><!-- col ends -->
                                    
                                        <form method="POST" action="{{ route('update-profile') }}">
                                        @csrf
                                            <div class="tab-content clearfix">

                                                <div class="tab-pane active" id="1a">

                                                    <div class="tab-pane-inner">


                                                        <div class="row-fluid">

                                                            <div class="col-md-6 colinput">
                                                                <input type="text" placeholder="@lang('home.First Name')*" value="{{ old('first_name', $first_name) }}" name="first_name" required>
                                                                <label for="name">@lang('home.First Name')</label>
                                                            </div> <!-- col ends -->

                                                            <div class="col-md-6 colinput">
                                                                <input type="text" placeholder="@lang('home.Last Name')*" value="{{ old('last_name', $last_name) }}" name="last_name" required>
                                                                <label for="name">@lang('home.Last Name')</label>
                                                            </div> <!-- col ends -->


                                                        </div><!-- row ends -->


                                                        <div class="row-fluid">

                                                            <div class="colinput">
                                                                <input type="text" placeholder="@lang('home.Enter UserName')*" value="{{ old('user_name', $user_name) }}" name="user_name" required >
                                                                <label for="name">@lang('home.Enter UserName')</label>
                                                            </div> <!-- col ends -->

                                                            <div class="colinput">
                                                                <input type="email" placeholder="@lang('home.Email')*" value="{{$email}}" name="email" required>
                                                                <label for="name">@lang('home.Email')</label>
                                                            </div> <!-- col ends -->


                                                        </div><!-- row ends -->



                                                        <div class="row-fluid">

                                                            <div class="colinput">
                                                                <input type="text" placeholder="@lang('home.Phone')" value="{{ old('phone', $phone) }}" name="phone" maxlength='16'>
                                                                <label for="name">@lang('home.Phone Number')</label>
                                                            </div> <!-- col ends -->

                                                            <div class="colinput">
                                                                <input type="text" placeholder="@lang('home.State & Street')" value="{{ old('state', $state) }}" name="state" maxlength='16'>
                                                                <label for="name">@lang('home.State & Street')</label>
                                                            </div> <!-- col ends -->


                                                        </div><!-- row ends -->



                                                        <div class="row-fluid">

                                                            <div class="colinput">
                                                                <input type="text" placeholder="@lang('home.City')" value="{{ old('city', $city) }}" name="city" maxlength='16'>
                                                                <label for="name">@lang('home.City')</label>
                                                            </div> <!-- col ends -->

                                                            <div class="colinput">                                                                 
                                                                <input type="text" placeholder="@lang('home.Country')" value="{{ old('country', $country) }}" name="country" maxlength='16'>
                                                                <label for="name">@lang('home.Country')</label>
                                                            </div>


                                                        </div><!-- row ends -->


                                                        <div class="row-fluid">

                                                            <div class="colinput">
                                                                <input type="text" placeholder="@lang('home.Postal Code')" value="{{ old('postal_code', $postal_code) }}" name="postal_code" minlength='4' maxlength='8'>
                                                                <label for="name">@lang('home.Postal Code')</label>
                                                            </div> <!-- col ends -->
 
                                                        </div><!-- row ends -->


                                                        <div class="row-fluid"> 

                                                            <div class="colinput btnFormSubmit">
                                                                 <input type="submit" name="update profile" value="@lang('home.Update Profile')" /> 
                                                            </div> <!-- col ends -->
 
                                                        </div><!-- row ends -->

                                                    </div><!-- tab-pane-inner -->
                                                  
                                                </div> <!-- tab ends -->
                                            </div>
                                        </form>
                                  </div>



                        </div><!-- account form inner -->
                    </div>
                </div>
        
        
        
@endsection 















