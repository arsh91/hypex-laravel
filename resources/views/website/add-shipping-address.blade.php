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
                    
                    <div class="col-md-12">
                        <ul  class="navtabsAccount">
                            <li class="active"><a  href="#1a" data-toggle="tab">Add Shipping Address</a></li>
                        </ul>
                    </div><!-- col ends -->
                        <form method="POST" action="{{ route('add-shipping-address')}}">
                        @csrf
                        <div class="tab-content clearfix">

                            <div class="tab-pane active" id="1a">

                                <div class="tab-pane-inner">


                                    <div class="row-fluid">

                                        <div class="col-md-6 colinput">
                                            <input id="first_name" name="first_name" type="text" required>
                                            <label for="first_name">First Name</label>
                                        </div> <!-- col ends -->

                                        <div class="col-md-6 colinput">
                                            <input id="last_name" name="last_name" type="text" required>
                                            <label for="last_name">Last Name</label>
                                        </div> <!-- col ends -->


                                    </div><!-- row ends -->


                                    <div class="row-fluid">
                                        <div class="colinput">
                                            <input id="full_address" name="full_address" type="text" required>
                                            <label for="full_address">Full Address</label>
                                        </div> <!-- col ends -->

                                        <div class="colinput">
                                            <input id="street_city" name="street_city" type="text" required>
                                            <label for="street_city">City</label>
                                        </div> <!-- col ends -->


                                    </div><!-- row ends -->

                                    <div class="row-fluid">
                                        <div class="colinput">
                                            <input id="phone_number" name="phone_number" type="text" required>
                                            <label for="phone_number">Phone Number</label>
                                        </div> <!-- col ends -->

                                        <div class="colinput">
                                            <input id="zip_code" name="zip_code" type="text" required>
                                            <label for="zip_code">Zip Code</label>
                                        </div> <!-- col ends -->
                                    </div><!-- row ends -->

                                    <div class="row-fluid">
                                         <div class="colinput">              
                                            <select name="country">
                                                <option value="CA" selected>Canada</option>
                                            </select>
                                            <label for="name">Country</label>
                                        </div> <!-- col ends -->

                                        <div class="colinput">
                                            <select name="province">
                                                <option value="AB">Alberta</option>
												<option value="BC">British Columbia</option>
												<option value="MB">Manitoba</option>
												<option value="NB">New Brunswick</option>
												<option value="Newfoundland and Labrador">Newfoundland and Labrador</option>
												<option value="NL">Northwest Territories</option>
												<option value="NS">Nova Scotia</option>
												<option value="NU">Nunavut</option>
												<option value="ON">Ontario</option>
												<option value="PE">Prince Edward Island</option>
												<option value="QC">Quebec</option>
												<option value="SK">Saskatchewan</option>
												<option value="YT">Yukon</option>
                                            </select>
                                            <label for="name">Province</label>
                                        </div> <!-- col ends -->
                                    </div><!-- row ends -->

                                    <div class="row-fluid">
                                        <div class="colinput" id="shipCheckbox">
                                            <input type="checkbox" name="default" value="1"/>
                                            <span id="shipCheckboxSpan">Set As Default Address</span>
                                        </div>
                                    </div>

                                    <div class="row-fluid">
                                        <div class="col-md-6 colinput btnFormSubmit">
                                            <input type="submit" name="add_shipping_address" value="Add" />
                                        </div> <!-- col ends -->
                                        <div class="col-md-6 colinput linkCancel">
                                            <a href="{{url('view-shipping-address')}}">Cancel</a>
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
@endsection