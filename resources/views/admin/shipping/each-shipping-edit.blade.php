@extends('layouts.admin-layout')

@section('content')
          <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              User Edit Info
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/user-list') }}">User Mangement</a></li>
                <li class="breadcrumb-item active" aria-current="page">User edit</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">User Info</h4>
                  
                   @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  @if(session()->has('success'))
                      <div class="alert alert-success">
                          {{ session()->get('success') }}
                      </div>
                  @endif

                  <form class="forms-sample" method="post" action="{{ url('admin/edit-user-detail/'.$get_user_detail->id)}}">
                    @csrf
                    <div class="form-group">
                      <label for="name1">First Name</label>
                      <input type="text" class="form-control" id="name1" value=" @if(!empty(old('first_name'))) {{ old('first_name') }} @else {{ $get_user_detail->first_name }} @endif" name="first_name" placeholder=" First Name">
                    </div>
                    <div class="form-group">
                      <label for="name2">Last Name</label>
                      <input type="text" class="form-control" id="name2" placeholder="Last Name" value=" @if(!empty(old('last_name'))) {{ old('last_name') }} @else {{ $get_user_detail->last_name }} @endif" name="last_name" >
                    </div>
                     <div class="form-group">
                      <label for="name3">User Name</label>
                      <input type="text" class="form-control" id="name3" placeholder="User Name" value=" @if(!empty(old('user_name'))) {{ old('user_name') }} @else {{ $get_user_detail->user_name }} @endif" name="user_name" >
                    </div>
                     <div class="form-group">
                      <label for="email">Email Address</label>
                      <input type="email" class="form-control" id="email" placeholder="Email Id" value=" @if(!empty(old('email'))) {{ old('email') }} @else {{ $get_user_detail->email }} @endif" name="email" >
                    </div>
                       <!-- <div class="form-group">
                      <label for="pass">Password</label>
                      <input type="password" class="form-control" id="pass" placeholder="Password">
                    </div> -->
                       <div class="form-group">
                      <label for="phone">Phone Number </label>
                      <input type="tel" class="form-control" id="Phone" placeholder="Phone Number" value=" @if(!empty(old('phone'))) {{ old('phone') }} @else {{ $get_user_detail->phone }} @endif" name="phone" >

                    </div>  
                     <div class="form-group">
                      <label for="city">City</label>
                      <input type="text" class="form-control" id="city" placeholder="City" value=" @if(!empty(old('city'))) {{ old('city') }} @else {{ $get_user_detail->city }} @endif" name="city" >
                    </div>
                     <div class="form-group">
                      <label for="state">State</label>
                      <input type="text" class="form-control" id="state" placeholder="state" value=" @if(!empty(old('state'))) {{ old('state') }} @else {{ $get_user_detail->state }} @endif" name="state" >
                    </div>
                       <div class="form-group">
                      <label for="country">Country</label>
                      <input type="text" class="form-control" id="country" placeholder="country" value=" @if(!empty(old('country'))) {{ old('country') }} @else {{ $get_user_detail->country }} @endif" name="country" >
                    </div>
                          <div class="form-group">
                      <label for="postalCode">Postal_code</label>
                      <input type="text" class="form-control" id="postalCode" placeholder="Postal Code" value=" @if(!empty(old('postal_code'))) {{ old('postal_code') }} @else {{ $get_user_detail->postal_code }} @endif" name="postal_code" >
                    </div>
          
                 <div class="form-group row">
                    <label for="status" class="col-sm-12">Status</label>
                    <div class="col-sm-3">
                      <div class="form-check ">
                            <label class="form-check-label ">
                              <input type="radio" name="status" value="1" class="form-check-input" id="status" @if($get_user_detail->status == 1) checked  @endif>
                              Active
                            </label>
                      </div>
                      </div>
                      <div class="col-sm-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input"  name="status" value="2" id="status" @if($get_user_detail->status != 1) checked  @endif>
                              Deactive
                            </label>
                          </div>
                      </div>    
                        </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
            
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')

   <script type="text/javascript">
      $(document).ready(function(){
          
      });
     
   </script>
@endsection