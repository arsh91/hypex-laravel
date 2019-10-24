@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
        
        <div class="account-section">    
                    <div class="account-form">
                        <div class="account-form-inner">
                            
                                <div id="exTab1" class="container-fluid passwordReset"> 
                                    
                                        <form method="POST" action="{{ route('forgot-password') }}">
																				@csrf
																				
																				@if ($errors->any())
																				<div class="alert alert-danger">
																					<ul>
																						@foreach ($errors->all() as $error)
																							<li>{{ $error }}</li>
																						@endforeach
																					</ul>
																				</div>
																			@endif
                                            <div class="tab-content clearfix">

                                                <div class="tab-pane active" id="1a">

                                                    <div class="tab-pane-inner">

                                                        <div class="row-fluid">

                                                            <div class="colinput">
																																<input type="email"  value="{{old('email')}}" name="email" required>
                                                                <label for="name">@lang('home.Email')</label>
                                                            </div> <!-- col ends -->


																												</div><!-- row ends -->
																												
                                                        <div class="row-fluid"> 

                                                            <div class="colinput btnFormSubmit">
																														<button type="submit" class="loginbtn">@lang('home.Send Password Reset Link')</button>	
                                                            </div> <!-- col ends -->
 
                                                        </div><!-- row ends -->

                                                    </div><!-- tab-pane-inner -->
                                                  
                                                </div> <!-- tab ends -->
																						</div>
																						
																				</form>
																				<div class="signin text-left">
																					<p>@lang("home.Don't have an account")? <a href="{{ url('/signin') }}">@lang('home.Create Account')</a></p>
				  															</div>
                                  </div>


                        </div><!-- account form inner -->
                    </div>
                </div>
        
        
        
@endsection 















