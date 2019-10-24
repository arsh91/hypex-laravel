@extends('layouts.website-without-header-footer')

@section('content')

 
					
					<div class="flex-center login-container position-ref full-height">
						<div class="login-inner">
						<div class="container">
						<div class="login-inner-model">
					
					
					
                        <form class="form-horizontal" method="POST" action="{{ route('reset-password') }}">
						<h2>@lang('home.Reset Password') <a class="pull-right" href="{{ route('home') }}"> <img src="{{ asset('v1/website/uploads/thumbnail/logo.png') }}" alt="Back To Home" /> <span> x </span></a></h2>
						
                            {{ csrf_field()}}
    
                            <input type="hidden" name="token" value="{{ $token }}" />
    
                            <div class=" {{ $errors->has('password') ? ' has-error' : '' }}">
                               <!--  <label for="password" class="col-md-4 control-label">Password</label> -->
    
                                
                                    <input id="password" type="password" class="form-control" placeholder="@lang('home.Enter Password')" name="password" required>
    
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                              
                            </div>
    
                            <div class="{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                               <!--  <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label> -->
                                 
                                    <input id="password-confirm" type="password" class="form-control" placeholder="@lang('home.Confirm Password')" name="password_confirmation" required>
    
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                 
                            </div>
    
                            <div class="">
                                 
                                    <button type="submit" class="loginbtn">
                                        @lang('home.Reset Password')
                                    </button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
@endsection
