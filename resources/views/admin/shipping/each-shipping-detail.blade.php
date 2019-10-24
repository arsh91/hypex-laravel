@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($orders); exit; ?>    
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              View shipping Detail
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/shipping-list') }}">Shipping Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Shipping Detail</li>
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
                        Shipping Info 
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>
                            ID
                          </td>
                          <td>
                           @if(!empty($shipping['id']))  {{ $shipping['id'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Order ID
                          </td>
                          <td>
                            <a href="{{url('admin/order/each-order-detail/'.$shipping['order_id'])}}">
                            @if(!empty($shipping['order_id']))  {{ $shipping['order_id'] }} @else N/A @endif
                            </a>
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Shipment ID
                          </td>
                          <td>
                            @if(!empty($shipping['shipment_id']))  {{ $shipping['shipment_id'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Ship Date
                          </td>
                          <td>
                            @if(!empty($shipping['ship_date']))  {{ $shipping['ship_date'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Shipment Cost
                          </td>
                          <td>
                            $@if(!empty($shipping['shipment_cost']))  {{ $shipping['shipment_cost'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Tracking Number
                          </td>
                          <td>
                            @if(!empty($shipping['tracking_number']))  {{ $shipping['tracking_number'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Carrier Code
                          </td>
                          <td>
                            @if(!empty($shipping['carrier_code']))  {{ $shipping['carrier_code'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Service Code
                          </td>
                          <td>
                            @if(!empty($shipping['service_code']))  {{ $shipping['service_code'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                          Package Code
                          </td>
                          <td>
                            @if(!empty($shipping['package_code']))  {{ $shipping['package_code'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Status
                          </td>
                          <td>
                            @if(!empty($shipping['status']) && $shipping['status'] == 1)  Shipped @else Deactivate @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Download Label
                          </td>
                          <td>
                          <a href="{{url('/')}}/v1/admin/images/labelspdf/{{ $shipping['label_data'] }}" target="_blank" class="btn btn-xs btn-info">Download Label</a>
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
