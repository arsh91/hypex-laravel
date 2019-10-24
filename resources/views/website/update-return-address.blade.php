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
                        <ul class="navtabsAccount">
                            <li class="active"><a href="#1a" data-toggle="tab">@lang('home.Update Return Address')</a></li>
                        </ul>
                    </div><!-- col ends -->
                    <form method="POST" action="{{ url('edit-return-address'.'/'.$returnAddr_id) }}">
                        @csrf
                        <div class="tab-content clearfix">

                            <div class="tab-pane active" id="1a">

                                <div class="tab-pane-inner">


                                    <div class="row-fluid">

                                        <div class="col-md-6 colinput">
                                            <input id="first_name" value="{{ old('first_name', $userReturnAddr['first_name']) }}" name="first_name" type="text" required>
                                            <label for="first_name">@lang('home.First Name')</label>
                                        </div> <!-- col ends -->

                                        <div class="col-md-6 colinput">
                                            <input id="last_name" value="{{ old('last_name', $userReturnAddr['last_name']) }}" name="last_name" type="text" required>
                                            <label for="last_name">@lang('home.Last Name')</label>
                                        </div> <!-- col ends -->


                                    </div><!-- row ends -->


                                    <div class="row-fluid">
                                        <div class="colinput">
                                            <input id="full_address" value="{{ old('full_address', $userReturnAddr['full_address']) }}" name="full_address" type="text" required>
                                            <label for="full_address">@lang('home.Full Address')</label>
                                        </div> <!-- col ends -->

                                        <div class="colinput">
                                            <input id="street_city" value="{{ old('street_city', $userReturnAddr['street_city']) }}" name="street_city" type="text" required>
                                            <label for="street_city">@lang('home.City')</label>
                                        </div> <!-- col ends -->


                                    </div><!-- row ends -->

                                    <div class="row-fluid">
                                        <div class="colinput">
                                            <input id="phone_number" value="{{ old('phone_number', $userReturnAddr['phone_number']) }}" name="phone_number" type="text" required>
                                            <label for="phone_number">@lang('home.Phone Number')</label>
                                        </div> <!-- col ends -->

                                        <div class="colinput">
                                            <input id="zip_code" value="{{ old('zip_code', $userReturnAddr['zip_code']) }}" name="zip_code" type="text" required>
                                            <label for="zip_code">@lang('home.Zip Code')</label>
                                        </div> <!-- col ends -->
                                    </div><!-- row ends -->

                                    <div class="row-fluid">
                                        <div class="colinput">
                                            <select name="country">
                                                <option value="CA" selected>@lang('home.Canada')</option>
                                            </select>
                                            <label for="name">@lang('home.Country')</label>
                                        </div> <!-- col ends -->
                                        <?php  $pr = $userReturnAddr['province'];?>
                                        <div class="colinput">
                                            <select name="province">
                                                <option value="AB"<?php if ($pr == 'AB' ) echo 'selected' ; ?>>@lang('home.Alberta')</option>
                                                <option value="BC" <?php if ($pr == 'BC' ) echo 'selected' ; ?>>@lang('home.British Columbia')</option>
                                                <option value="MB" <?php if ($pr == 'MB' ) echo 'selected' ; ?>>@lang('home.Manitoba')</option>
                                                <option value="NB" <?php if ($pr == 'NB' ) echo 'selected' ; ?>>@lang('home.New Brunswick')</option>
                                                <option value="Newfoundland and Labrador" <?php if ($pr == 'Newfoundland and Labrador' ) echo 'selected' ; ?>>@lang('home.Newfoundland and Labrador')
                                                </option>
                                                <option value="NL" <?php if ($pr == 'NL' ) echo 'selected' ; ?>>@lang('home.Northwest Territories')</option>
                                                <option value="NS" <?php if ($pr == 'NS' ) echo 'selected' ; ?>>@lang('home.Nova Scotia')</option>
                                                <option value="NU"  <?php if ($pr == 'NU' ) echo 'selected' ; ?>>@lang('home.Nunavut')</option>
                                                <option value="ON" <?php if ($pr == 'ON' ) echo 'selected' ; ?>>@lang('home.Ontario')</option>
                                                <option value="PE" <?php if ($pr == 'PE' ) echo 'selected' ; ?>>@lang('home.Prince Edward Island')</option>
                                                <option value="QC" <?php if ($pr == 'QC' ) echo 'selected' ; ?>>@lang('home.Quebec')</option>
                                                <option value="SK" <?php if ($pr == 'SK' ) echo 'selected' ; ?>>@lang('home.Saskatchewan')</option>
                                                <option value="YT" <?php if ($pr == 'YT' ) echo 'selected' ; ?>>@lang('home.Yukon')</option>
                                            </select>
                                            <label for="name">@lang('home.Province')</label>
                                        </div> <!-- col ends -->
                                    </div><!-- row ends -->

                                    <div class="row-fluid">
                                        <div class="colinput" id="shipCheckbox">
                                            <input type="checkbox" name="default" value="1" <?php echo ($userReturnAddr['default']==1 ? 'checked' : '');?>><span id="shipCheckboxSpan">@lang('home.Set As Default Address')</span>
                                        </div>
                                    </div>

                                    <div class="row-fluid">

                                        <div class="colinput btnFormSubmit">
                                            <input type="submit" name="update_shipping_address" value="@lang('home.update')"/>
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