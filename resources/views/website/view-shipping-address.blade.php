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
                        <ul class="navtabsAccount accountTabsColor">
                            <li class="active"><a href="#1a" data-toggle="tab">@lang('home.Shipping Address')</a></li>
                        </ul>
                    </div><!-- col ends -->

                    <div class="col-md-12 tab-content clearfix">

                        <div class="tab-pane active" id="1a">

                            <div class="tab-pane-inner">

                                @foreach ($userShippingAddr as $shipAddr)
                                <div class="row-fluid accountEditBreak">
                                    <div class="accountEditRow">
                                        <div class="inputValue">
                                            <p>{{ $shipAddr['first_name'] }} {{ $shipAddr['last_name'] }}</p>        
                                            <p>{{ $shipAddr['full_address'] }}</p>
                                            <p>{{ $shipAddr['street_city'] }}</p>
                                            <p>{{ $shipAddr['country'] }}</p>
                                            <p>{{ $shipAddr['province'] }}</p>
                                            <p>{{ $shipAddr['zip_code'] }}</p>
                                            <p>{{ $shipAddr['phone_number'] }}</p>
                                        </div>                                        
                                        <div class="inputEdit">
                                            <a href="{{url('update-shipping-address'.'/'.$shipAddr['id'])}}">@lang('home.Edit')</a>
                                            <a href="{{url('delete-shipping-address'.'/'.$shipAddr['id'])}}">/ @lang('home.Delete')</a>
                                        </div>
                                        @if($shipAddr['default'] == 1 )
                                        <div class="">
                                            Default Address
                                        </div>
                                        @endif
                                    </div> <!-- col ends -->
                                </div><!-- row ends -->
                                @endforeach
                            </div><!-- tab-pane-inner -->
                            <div class="row-fluid">
                                <div class="col-md-6 colinput linkCancel">
                                    <a href="{{url('add-shipping-address')}}">@lang('home.Add Shipping Address')</a>
                                </div> <!-- col ends -->
                                <div class="col-md-6 colinput linkCancel">
                                    <a href="{{url('user-account')}}">@lang('home.Back')</a>
                                </div> <!-- col ends -->
                            </div><!-- row ends -->
                        </div> <!-- tab1 content ends -->
                    </div>
                </div>
            </div><!-- account form inner -->
        </div>
    </div>
@endsection
