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
                            <li class="active"><a href="#1a" data-toggle="tab">@lang('home.Account Settings')</a></li>
                            <li><a href="#2a" data-toggle="tab">@lang('home.Buying')</a></li>
                            <li><a href="#3a" data-toggle="tab">@lang('home.Selling')</a></li>
                        </ul>
                    </div><!-- col ends -->

                    <div class="col-md-12 tab-content clearfix">

                        <div class="tab-pane active" id="1a">

                            <div class="tab-pane-inner">


                                <div class="row-fluid accountEditBreak">

                                    <div class="accountEditRow">
                                        <div class="label">@lang('home.Email Address')</div>
                                        <div class="inputValue"><?php print_r($currentuser->email); ?></div>
                                        <div class="inputEdit">
                                            <a href="{{url('my-profile')}}">@lang('home.Edit')</a>
                                        </div>
                                    </div> <!-- col ends -->


                                    <div class="accountEditRow">
                                        <div class="label">@lang('home.Password')</div>
                                        <div class="inputValue">*****************</div>
                                        <div class="inputEdit">
                                            <!-- <button>Reset</button> -->
                                            <a href="{{url('changeold-password')}}">@lang('home.Edit')</a>
                                        </div>
                                    </div> <!-- col ends -->

                                </div><!-- row ends -->


                                <div class="row-fluid accountEditBreak">

                                    <div class="accountEditRow">
                                        <div class="label">@lang('home.Buyer Info')</div>
                                        <div class="inputValue"><i>****** @lang('home.SECURE') ******</i>
                                        </div>
                                        <div class="inputEdit">
                                            <a href="/save-payment">@lang('home.Click')</a>
                                        </div>
                                    </div> <!-- col ends -->


                                    <div class="accountEditRow">
                                        <div class="label">@lang('home.Payout Email')</div>
                                        <div class="inputValue">****** @lang('home.SECURE') ******</div>
                                        <div class="inputEdit">
                                             <a href="/payout-info">@lang('home.Click')</a>
                                        </div>
                                    </div> <!-- col ends -->
                                </div><!-- row ends -->

                                <div class="row-fluid accountEditBreak">

                                    <div class="accountEditRow">
                                        <div class="label">@lang('home.Shipping Info')</div>
                                        <div class="inputEdit">
                                             @if ($shipAdd >= 1)
                                                <a href="{{ url('view-shipping-address')}}">@lang('home.View')</a>
                                            @else
                                                <a href="{{ url('add-shipping-address')}}">@lang('home.Add')</a>
                                            @endif
                                        </div>
                                    </div> <!-- col ends -->
                                    <div class="accountEditRow">
                                        <div class="label">@lang('home.Return Info')</div>
                                        <div class="inputEdit">
                                            @if ($billAdd >=1 )
                                                <a href="{{ url('view-return-address')}}">@lang('home.View')</a>
                                            @else
                                                <a href="{{ url('add-return-address')}}">@lang('home.Add')</a>
                                            @endif
                                        </div>
                                    </div> <!-- col ends -->

                                </div><!-- row ends -->


                                <div class="row-fluid">

                                    <div class="col-md-6 colinput btnFormSubmit">
                                        <input type="submit" name="Save" value="@lang('home.Save')"/>
                                    </div> <!-- col ends -->


                                    <div class="col-md-6 colinput linkCancel">
                                        <a href="#">@lang('home.Cancel')</a>
                                    </div> <!-- col ends -->

                                </div><!-- row ends -->


                            </div><!-- tab-pane-inner -->

                        </div> <!-- tab1 content ends -->


                        <div class="tab-pane" id="2a">


                            <div id="exTab2" class="innerTabsSection">


                                <div class="innerTabsNav">
                                    <ul>
                                        <li class="active"><a href="#tab1" data-toggle="tab">@lang('home.Open Bid')</a></li>
                                        <li><a href="#tab2" data-toggle="tab">@lang('home.In Progress')</a></li>
                                        <li><a href="#tab3" data-toggle="tab">@lang('home.Buying History')</a></li>
                                    </ul>
                                </div><!-- col ends -->

                                <div class="tab-content clearfix">

                                    <div class="tab-pane active" id="tab1">

                                        <table id="openbids">

                                            <thead>
                                            <th>@lang('home.Date')</th>
                                            <th>@lang('home.Item')</th>
                                            <th>@lang('home.Product Price')</th>
                                            <th>@lang('home.Your Price')</th>
                                            <th>@lang('home.Expires On')</th>
                                            </thead>

                                            <tbody>

                                            @foreach($openbidds as $openbid)
                                            <tr>
                                                <td><?php echo date("d-m-Y", strtotime($openbid['created_at'])); ?> </td>
                                                <td><img src="{{url('/')}}/public/v1/website/products/{{$openbid['product']['product_images']}}" alt=""/> <span>{{ $openbid['product']['product_name']}}</span>
                                                </td>
                                                <td>${{$openbid['actual_price']}}</td>
                                                <td>${{$openbid['bid_price']}}</td>
                                                <td><?php echo date("d-m-Y", strtotime($openbid['bid_expiry_date'])); ?> </td>
                                            </tr>
                                            @endforeach

                                            </tbody>

                                        </table>
                                        


                                        <!-- <div class="accountPagination">
                                            <div class="totalPages"><span>Showing 1 to 5 of 5 entries</span></div>
                                            <div class="pageNav"><a href="#">Previous</a><a href="#">Next</a></div>
                                        </div>pagination ends -->

                                    </div><!-- tab1 content ends -->


                                    <div class="tab-pane" id="tab2">

                                        

                                        <table id="progressbids">

                                            <thead>
                                            <th>@lang('home.Date')</th>
                                            <th>@lang('home.Item')</th>
                                            <th>@lang('home.Price')</th>
                                            <th>@lang('home.Shipping Price')</th>
                                            <th>@lang('home.Total Price')</th>
                                            </thead>

                                            <tbody>

                                            @foreach($buyinghistory as $histbid)
                                            <tr>
                                                <td><?php echo date("d-m-Y", strtotime($histbid['created_at'])); ?> </td>
                                                <td><img src="{{url('/')}}/public/v1/website/products/{{$histbid['product']['product_images']}}" alt=""/> <span>{{ $histbid['product']['product_name']}}</span>
                                                </td>
                                                <td>${{ $histbid['price'] }}</td>
                                                <td>${{ $histbid['shipping_price'] }}</td>
                                                <td>${{ $histbid['total_price'] }}</td>
                                            </tr>
                                            @endforeach

                                            </tbody>

                                        </table>

                                        <!-- <div class="accountPagination">
                                            <div class="totalPages"><span>Showing 1 to 5 of 5 entries</span></div>
                                            <div class="pageNav"><a href="#">Previous</a><a href="#">Next</a></div>
                                        </div>pagination ends -->

                                    </div><!-- tab2 content ends -->


                                    <div class="tab-pane" id="tab3">

                                        <table id="buyinghistory">

                                            <thead>
                                            <th>@lang('home.Date')</th>
                                            <th>@lang('home.Item')</th>
                                            <th>@lang('home.Price')</th>
                                            <th>@lang('home.Shipping Price')</th>
                                            <th>@lang('home.Total Price')</th>
                                            </thead>

                                            <tbody>

                                            @foreach($buyinghistory as $histbid)
                                            <tr>
                                                <td><?php echo date("d-m-Y", strtotime($histbid['created_at'])); ?> </td>
                                                <td><img src="{{url('/')}}/public/v1/website/products/{{$histbid['product']['product_images']}}" alt=""/> <span>{{ $histbid['product']['product_name']}}</span>
                                                </td>
                                                <td>${{ $histbid['price'] }}</td>
                                                <td>${{ $histbid['shipping_price'] }}</td>
                                                <td>${{ $histbid['total_price'] }}</td>
                                            </tr>
                                            @endforeach

                                            </tbody>

                                        </table>

                                        <!-- <div class="accountPagination">
                                            <div class="totalPages"><span>Showing 1 to 5 of 5 entries</span></div>
                                            <div class="pageNav"><a href="#">Previous</a><a href="#">Next</a></div>
                                        </div>pagination ends -->

                                    </div><!-- tab3 content ends -->


                                </div>


                            </div><!-- inner tabs Section -->

                        </div> <!-- tab2 content ends -->


                        <div class="tab-pane" id="3a">


                            <div id="exTab2" class="innerTabsSection">


                                <div class="innerTabsNav">
                                    <ul>
                                        <li class="active"><a href="#tab4" data-toggle="tab">@lang('home.Open Offer')</a></li>
                                        <li><a href="#tab5" data-toggle="tab">@lang('home.In Progress')</a></li>
                                        <li><a href="#tab6" data-toggle="tab">@lang('home.Selling History')</a></li>
                                    </ul>
                                </div><!-- col ends -->

                                <div class="tab-content clearfix">

                                    <div class="tab-pane active" id="tab4">


                                        <table id="openselling"> 

                                            <thead>
                                            <th>@lang('home.Date')</th>
                                            <th>@lang('home.Item')</th>
                                            <th>@lang('home.Product Price')</th>
                                            <th>@lang('home.Your Price')</th>
                                            <th>@lang('home.Expires On')</th>
                                            </thead>

                                            <tbody>

                                            @foreach($opensells as $opensells)
                                            <tr>
                                                <td><?php echo date("d-m-Y", strtotime($opensells['created_at'])); ?> </td>
                                                <td><img src="{{url('/')}}/public/v1/website/products/{{$opensells['product']['product_images']}}" alt=""/> <span>{{ $opensells['product']['product_name']}}</span>
                                                </td>
                                                <td>${{$opensells['actual_price']}}</td>
                                                <td>${{$opensells['ask_price']}}</td>
                                                <td><?php echo date("d-m-Y", strtotime($opensells['sell_expiry_date'])); ?> </td>
                                            </tr>
                                            @endforeach

                                            </tbody>

                                        </table>

                                        <!-- <div class="accountPagination">
                                            <div class="totalPages"><span>Showing 1 to 5 of 5 entries</span></div>
                                            <div class="pageNav"><a href="#">Previous</a><a href="#">Next</a></div>
                                        </div>pagination ends -->

                                    </div><!-- tab1 content ends -->


                                    <div class="tab-pane" id="tab5">

                                        <table id="progressselling">

                                            <thead>
                                            <th>@lang('home.Date')</th>
                                            <th>@lang('home.Item')</th>
                                            <th>@lang('home.Price')</th>
                                            <th>@lang('home.Shipping Price')</th>
                                            <th>@lang('home.Total Price')</th>
                                            <th>@lang('home.Action')</th>
                                            </thead>

                                            <tbody>

                                            @foreach($sellinghistory as $sellhist)
                                            <tr>
                                                <td><?php echo date("d-m-Y", strtotime($sellhist['created_at'])); ?> </td>
                                                <td><img src="{{url('/')}}/public/v1/website/products/{{$sellhist['product']['product_images']}}" alt=""/> <span>{{ $sellhist['product']['product_name']}}</span>
                                                </td>
                                                <td>${{ $sellhist['price'] }}</td>
                                                <td>${{ $sellhist['shipping_price'] }}</td>
                                                <td>${{ $sellhist['total_price'] }}</td>
                                                <td><a href="{{url('/')}}/v1/admin/images/labelspdf/{{ $sellhist['ordershipped']['label_data'] }}" target="_blank" class="btn btn-xs btn-info">@lang('home.Download Label')</a></td>
                                            </tr>
                                            @endforeach

                                            </tbody>

                                        </table>

                                        <!-- <div class="accountPagination">
                                            <div class="totalPages"><span>Showing 1 to 5 of 5 entries</span></div>
                                            <div class="pageNav"><a href="#">Previous</a><a href="#">Next</a></div>
                                        </div>pagination ends -->

                                    </div><!-- tab2 content ends -->


                                    <div class="tab-pane" id="tab6">

                                        <table id="sellinghistory">

                                            <thead>
                                            <th>@lang('home.Date')</th>
                                            <th>@lang('home.Item')</th>
                                            <th>@lang('home.Price')</th>
                                            <th>@lang('home.Shipping Price')</th>
                                            <th>@lang('home.Total Price')</th>
                                            <th>@lang('home.Action')</th>
                                            </thead>

                                            <tbody>

                                            @foreach($sellinghistory as $sellhist)
                                            <tr>
                                                <td><?php echo date("d-m-Y", strtotime($sellhist['created_at'])); ?> </td>
                                                <td><img src="{{url('/')}}/public/v1/website/products/{{$sellhist['product']['product_images']}}" alt=""/> <span>{{ $sellhist['product']['product_name']}}</span>
                                                </td>
                                                <td>${{ $sellhist['price'] }}</td>
                                                <td>${{ $sellhist['shipping_price'] }}</td>
                                                <td>${{ $sellhist['total_price'] }}</td>
                                                <td><a href="{{url('/')}}/v1/admin/images/labelspdf/{{ $sellhist['ordershipped']['label_data'] }}" target="_blank" class="btn btn-xs btn-info">@lang('home.Download Label')</a></td>
                                            </tr>
                                            @endforeach

                                            </tbody>

                                        </table>

                                        <!-- <div class="accountPagination">
                                            <div class="totalPages"><span>Showing 1 to 5 of 5 entries</span></div>
                                            <div class="pageNav"><a href="#">Previous</a><a href="#">Next</a></div>
                                        </div>pagination ends -->

                                    </div><!-- tab3 content ends -->


                                </div>


                            </div><!-- inner tabs Section -->

                        </div> <!-- tab2 content ends -->


                        <!-- ============================= tab3 content ends =================== -->

                    </div>
                </div>


            </div><!-- account form inner -->
        </div>
    </div>

    <script type="text/javascript">
    // buying open bids
      $(document).ready(function() {
          $('#openbids').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "bAutoWidth": true
            });
         
      } );

        // buying in-progress bids
      $(document).ready(function() {
          $('#progressbids').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "bAutoWidth": true 
          });
      } );

      // buying history
      $(document).ready(function() {
          $('#buyinghistory').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "bAutoWidth": true 
          });
      } );

       // selling open 
       $(document).ready(function() {
          $('#openselling').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "bAutoWidth": true 
          });
      } );

      // selling in-progress
      $(document).ready(function() {
          $('#progressselling').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "bAutoWidth": true 
          });
      } );

      // selling history
      $(document).ready(function() {
          $('#sellinghistory').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "bAutoWidth": true 
          });
      } );
      
      $(document).ready(function() {
        $(".previous,.next,.dataTables_empty").hide();
      } );
    </script>
@endsection
