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
	            
                <div id="exTab1" class="container-fluid"> 
                    
                 
                        <form method="POST" id="changepass" action="{{url('changeold-password')}}">   
                        {{ csrf_field() }}
                        <div class="tab-content clearfix">

                            <div class="tab-pane active" id="1a">

                                <div class="tab-pane-inner">


                                    <div class="row-fluid">

                                        <div class="col-md-6 colinput">

                                            <input type="password" class="form-control" id="opassword" value="" required name="opassword" autocomplete="nope">
                                            <label for="first_name">@lang('home.Current Password')</label>

                                        </div> <!-- col ends -->

                                        <div class="borderBreak"></div>

                                        <div class="col-md-6 colinput">
                                        <input type="password" class="form-control" id="npassword" value="" required name="npassword" autocomplete="nope">
                                            <label for="first_name">@lang('home.New Password')</label>
                                        </div> <!-- col ends -->

                                        <div class="col-md-6 colinput">
                                        <input type="password" class="form-control" required name="cpassword">
                                            <label for="first_name">@lang('home.Retype New Password')</label>
                                        </div> <!-- col ends -->


                                    </div><!-- row ends -->

                                    <div class="row-fluid"> 

                                        <div class="colinput btnFormSubmit">
                                             <input type="submit" name="" value="@lang('home.Change Password')" /> 
                                        </div> <!-- col ends -->

                                    </div><!-- row ends -->
                                </div><!-- tab-pane-inner -->                                
                            </div> <!-- tab ends -->
                        </div>
                    </form><!--#Shipping form ends here-->
                  </div>
	        </div><!-- account form inner -->
	    </div>
	</div>

    <script>
$("#changepass").validate({
    rules: {
        opassword: {
            required: true
        },
        npassword: {
            required: true,
            minlength: 6
        },
        cpassword: {
            required: true,
            minlength: 6,
            equalTo: "#npassword"
        },
    },
    messages: {
        opassword: {
            required: 'Please enter old password',
        },
        npassword: {
            required: "Please enter new password",
            minlength: "New password must be at least 6 characters long"
        },
        cpassword: {
            required: "Please confirm a password",
            minlength: "Confirm password must be at least 6 characters long",
            equalTo: "Please enter the same password as above"
        }
    }
});
</script>
@endsection