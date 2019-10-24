@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($subscribed_user_detail); exit(); ?>    
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              View User Detail
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/subscribed/users-list') }}">Subscription Mangement</a></li>
                <li class="breadcrumb-item active" aria-current="page">Plan Detail</li>
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
                           @if(!empty($subscribed_user_detail['id']))  {{ $subscribed_user_detail['id'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            User Name
                          </td>
                          <td>
                           @if(!empty($subscribed_user_detail['user']['full_name']))  {{ $subscribed_user_detail['user']['full_name'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Plan Duration
                          </td>
                          <td>
                           @if(!empty($subscribed_user_detail['plan']['duration']))  {{ $subscribed_user_detail['plan']['duration'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Start Date
                          </td>
                          <td>
                           @if(!empty($subscribed_user_detail['start_date']))  {{ $subscribed_user_detail['start_date'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            End Date
                          </td>
                          <td>
                           @if(!empty($subscribed_user_detail['end_date']))  {{ $subscribed_user_detail['end_date'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Price
                          </td>
                          <td>
                           @if(!empty($subscribed_user_detail['id']))  {{ $subscribed_user_detail['id'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Payment via
                          </td>
                          <td>
                           @if(!empty($subscribed_user_detail['price']))  {{ $subscribed_user_detail['price'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           Price
                          </td>
                          <td>
                           @if(!empty($subscribed_user_detail['id']))  {{ $subscribed_user_detail['id'] }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Status
                          </td>
                          <td>
                            @if(!empty($subscribed_user_detail['status']) && $subscribed_user_detail['status'] == 1)  Active @else Deactivate @endif 
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
