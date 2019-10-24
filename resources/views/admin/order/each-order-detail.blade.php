@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($orders); exit; ?>    
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              View order Detail
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/order-list') }}">Order Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Order Detail</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                 <!--  <h4 class="card-title divide"><span>View Product</span>   
                    <button type="submit" class="btn btn-gradient-primary mr-2 rightSide">Edit</button>
                  </h4> -->
                    <table class="table ">
                    <thead class="theadnew">
                      <tr>
                        <th width="30%">
                        Order Info 
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>
                            Order ID
                          </td>
                          <td>
                           @if(!empty($orders['id']))  {{ $orders['id'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Order Date
                          </td>
                          <td>
                           <?php echo date("d-m-Y", strtotime($orders['created_at'])); ?> 
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Buyer Name
                          </td>
                          <td>
                            @if(!empty($orders['users']['first_name']))  {{ $orders['users']['first_name'] }} @else N/A @endif
                          </td>
                      </tr>

                     <tr>
                          <td>
                           Seller Name
                          </td>
                          <td>
                            @if(!empty($orders['seller']['first_name']))  {{ $orders['seller']['first_name'] }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Product
                          </td>
                          <td>
                            @if(!empty($orders['product']['product_name']))  {{ $orders['product']['product_name'] }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Product Image
                          </td>
                          <td>
                          <?php if($orders['product']['product_images'] != ''){ ?>
                    <img src="{{url('/')}}/v1/admin/images/{{$orders['product']['product_images']}}" style="width: 190px; margin-bottom: 20px;
                    " class="previewHolder"/>
                    <?php }else{ ?>
                    <img src="{{url('/')}}/v1/admin/images/noimage.png" style="width: 190px; margin-bottom: 20px;
                    " class="previewHolder"/>
                    <?php } ?>
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Payment Type
                          </td>
                          <td>
                           Online through Online 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Payment Gateway
                          </td>
                          <td>
                           Stripe Payment Gateway
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Payment Status
                          </td>
                          <td>
                          @if($orders['status'] == 0)
                            Not-Completed
                          @elseif($orders['status'] == 1)
                            Completed
                          @elseif($orders['status'] == 2)
                            Completed
                          @elseif($orders['status'] == 3)
                            Completed
                          @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Payment Details
                          </td>
                          <td>
                          <?php $data=json_decode($orders['payment_data']); ?>
                           Last 4 Digit Card Number : <?php echo $data->payment_method_details->card->last4; ?>
                          </td>
                          <td>
                           Country : <?php echo $data->payment_method_details->card->country; ?>
                          </td>
                          <td>
                           Card Type :<?php echo $data->payment_method_details->card->brand; ?>
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Payout Details
                          </td>
                          <td>
                          @if(!empty($orders['payout_email']))  {{ $orders['payout_email']}} @else N/A @endif 
                          </td>
                      </tr>


                      <tr>
                          <td>
                            Category
                          </td>
                          <td>
                            @if(!empty($orders['procategory']['category_name']))  {{ $orders['procategory']['category_name'] }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Brand
                          </td>
                          <td>
                            @if(!empty($orders['brand']['brand_name']))  {{ $orders['brand']['brand_name'] }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Price
                          </td>
                          <td>
                            @if(!empty($orders['price']))  ${{ $orders['price'] }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Shipping Price
                          </td>
                          <td>
                            @if(!empty($orders['shipping_price']))  ${{ $orders['shipping_price'] }} @else N/A @endif 
                          </td>
                      </tr>


                      <tr>
                          <td>
                          Toral Price
                          </td>
                          <td>
                            @if(!empty($orders['total_price']))  ${{ $orders['total_price'] }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Shipping Address
                          </td>
                          <td>
                            {{$orders['shipping']['full_address'] }},{{$orders['shipping']['street_city'] }},
                            {{$orders['shipping']['country'] }} ,{{$orders['shipping']['zip_code'] }}</td>
                      </tr>

                      <tr>
                          <td>
                          Billing Address
                          </td>
                          <td>
                            {{$orders['billing']['full_address'] }},{{$orders['billing']['street_city'] }},
                            {{$orders['billing']['country'] }} ,{{$orders['billing']['zip_code'] }}</td>
                      </tr>

                      

                      <tr>
                          <td>
                            Status
                          </td>
                          <td>
                          @if($orders['status'] == 0)
                            Rejected
                          @elseif($orders['status'] == 1)
                            Accepted
                          @elseif($orders['status'] == 2)
                            Shipped
                          @endif
                          </td>
                      </tr>
                                
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>

@endsection
