@extends('layouts.website')
@section('content')
<div class="index-block contentPages">    
                    <div class="container contactUsPage text-center">  
                        <div class="innerdiv">
                                 
                            <div class="sectionTitle"><h1>@lang('home.Contact Us')</h1></div>

                            <div class="inerContent">

                                <h2>@lang('home.Stay Connect or share your queries') </h2>

                                @if(Session::has('success'))
                                <div class="alert alert-success">
                                    {{ Session::get('success') }}
                                </div>
                                @endif

                                @if(Session::has('error'))
                                <div class="alert alert-danger">
                                    {{ Session::get('error') }}
                                </div>
                                @endif


                               <div class="formGroup contactForm">

                               <form id="" class="contact-from" name="" method="post" action="{{ route('contact-us') }}">
                                            {{ csrf_field() }}
                                            
                                            <div class="input-field">
                                            <label>@lang('home.Name')</label>
                                            <input type="text" required class="form-control" name="email" value="<?php echo isset(Auth::user()->full_name)  ?Auth::user()->full_name : ''?>" />
                                            </div>
                                            <div class="input-field">
                                            <label>@lang('home.Email')</label>
                                            <input type="email" required class="form-control" name="email" value="<?php echo isset(Auth::user()->email) ? Auth::user()->email : '' ?>" />
                                            </div>
                                            <div class="input-field">
                                            <label>@lang('home.Subject')</label>
                                            <input type="text" required class="form-control" name="subject" />
                                            </div>
                                            <div class="input-field">
                                            <label>@lang('home.Message')</label>
                                                <textarea id="" class="form-control" name="message" value="" placeholder="@lang('home.Message')"></textarea>
                                            </div>
                                            <div class="input-field">
                                                <input type="submit" class="btn btn-primary btn-theme" value="@lang('home.Send')">
                                            </div>
                                        </form>

                               </div>
                               

                                <h2></h2>
                                <p></p>


                            </div>
                            
 


                        </div>                
                    </div><!-- container ends -->
                </div>  
                <script>
$(".contact-from2").validate({
    rules:{
        email: {
            required: true,
            email: true
        },
        name: "required",
        subject: "required",
        message: "required"
    },
    messages:{
        email: {
            required: "Email is required",
            email: "Please enter valid email"
        },
        name: "Name is required",
        message: "Message is required",
        subject: "subject is required"
    }
});
</script>
		
@endsection