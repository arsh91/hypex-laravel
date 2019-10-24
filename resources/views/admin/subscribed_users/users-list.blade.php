@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($subscribed_user); exit();  ?> 
        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Subscription Plans</h4>

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
                      
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover " id="myTablees">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribed_user as $plan)
                            <tr>
                                <td>{{ $plan['id'] }}</td>
                                <td>{{ $plan['user']['full_name'] }}</td>
                                <td>{{ ucwords($plan['plan']['duration']) }}</td>
                                <td>${{ $plan['price'] }}</td>
                                <td>
                                @if($plan['status'] == 1)
                                <label class="badge badge-gradient-success">Active</label>
                                @else
                                <label class="badge badge-gradient-danger">Inactive</label>
                                @endif
                                </td>
                                <td>
                                  <a href="{{url('admin/subscribed/each-sub-users-detail/'.$plan['id'])}}" class="btn btn-xs btn-success">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>




                    </div>
                  </div>
                </div>
              </div>
              <div>
              </div>
            </div>
        </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')
   <script type="text/javascript">
      $(document).ready(function() {
          $('#myTablees').DataTable();
      } );
    </script>

@endsection