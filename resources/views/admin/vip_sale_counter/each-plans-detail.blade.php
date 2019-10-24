@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($get_plan_detail); exit(); ?>    
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              View shipping Detail
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/plans-list') }}">Subscription Mangement</a></li>
                <li class="breadcrumb-item active" aria-current="page">Subscription Plan Detail</li>
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
                        Subscription Plan Info 
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>
                            ID
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['id']))  {{ $get_plan_detail['id'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Duration
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['duration']))  {{ $get_plan_detail['duration'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Title
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['title']))  {{ $get_plan_detail['title'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           First Feature 
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['feature_1']))  {{ $get_plan_detail['feature_1'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Second Feature 
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['feature_2']))  {{ $get_plan_detail['feature_2'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Third Feature 
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['feature_3']))  {{ $get_plan_detail['feature_3'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Fourth Feature 
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['feature_4']))  {{ $get_plan_detail['feature_4'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Price 
                          </td>
                          <td>
                           @if(!empty($get_plan_detail['price']))  {{ $get_plan_detail['price'] }} @else N/A @endif
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
                                
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>

@endsection
