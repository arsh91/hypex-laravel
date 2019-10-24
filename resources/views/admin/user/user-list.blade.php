@extends('layouts.admin-layout')

@section('content')

<?php // echo '<pre>'; print_r($users); exit; ?>
<div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">User List</h4>

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
                      <table class="table table-bordered table-hover " id="userdata">
                        <thead>
                            <tr>
                              <th>Id</th>
                              <th>Name</th>
                              <th>User Name</th>
                              <th>Total Sells</th>
                              <th>Total Bids</th>
                              <th>Status</th>
                              <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($users) >0)
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user['id']}}</td>
                                <td>{{$user['full_name']}}</td>
                                <td>{{$user['user_name']}}</td>
                                <td>{{count($user['usersells'])}}</td>
                                <td>{{count($user['userbids'])}}</td>
                                <td>
                                  @if($user['status'] == 1)
                                  <label class="badge badge-gradient-success">Active</label>
                                  @else
                                  <label class="badge badge-gradient-danger">Inactive</label>
                                  @endif
                                </td>
                                <td>
                                    <a href="{{url('admin/each-user-detail'.'/'.$user['id'])}}" class="btn btn-gradient-info btn-sm">View</a>
                                    @if($user['status'] == 1)
                                    <a href="{{url('admin/user/'.$user['id'].'/0')}}" class="btn btn-gradient-secondary btn-sm">Disable</a>
                                    @else
                                    <a href="{{url('admin/user/'.$user['id'].'/1')}}" class="btn btn-gradient-info btn-sm">Enable</a>
                                    @endif
                                    <a href="{{url('admin/bids-products/'.$user['id'])}}" class="btn btn-gradient-primary btn-sm">Bids</a>
                                    
                                    <a href="{{url('admin/buy-history/'.$user['id'])}}" class="btn btn-gradient-secondary btn-sm">Buy History</a>

                                    <a href="{{url('admin/sell-products/'.$user['id'])}}" class="btn btn-gradient-success btn-sm">Sell</a>

                                    <a href="{{url('admin/sell-history/'.$user['id'])}}" class="btn btn-gradient-info btn-sm">Sell History</a>
                                   

                                </td>
                            </tr>
                            @endforeach
                        @else
                        No Records found for now!
                        @endif
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




        <!-- <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">User List</h4>

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
                      <table class="table table-bordered table-hover " id="myTable">
                        <thead>
                          <tr >
                             <th>
                              Sr No.
                            </th>
                            <th>
                              Name
                            </th>
                            <th>
                              User Name
                            </th>
                            <th>
                              Email
                            </th>
                            <th>
                              Phone Number
                            </th>
                            <th>
                              State
                            </th>
                            <th>
                              Country
                            </th>
                            <th>
                              Status
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div>
              </div>
            </div>
        </div> -->
        <!-- content-wrapper ends -->

@endsection

@section('js_content')
   <script type="text/javascript">
      $(document).ready(function($) {
        
          $(".clickable-row").click(function() {
              
          });

          var table = $('#myTable').DataTable({
            "columnDefs": [
              {"targets": 0, "orderable": false },
              {"targets": 7, "orderable": false }
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! url('admin/user-list-paginate') !!}',

            columns: [
                { data: 'DT_RowIndex'},
                { data: 'full_name', name: 'full_name' },
                { data: 'user_name', name: 'user_name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'state', name: 'state' },
                { data: 'country', name: 'country' },
                { data: 'status_custom', name: 'status_custom' },
              ],
          });

          $('#myTable tbody').on('click', 'tr', function () {
              var data = table.row( this ).data();
               //console.log(data.id);
              window.location = "<?php echo url('admin/each-user-detail'); ?>"+'/'+data.id;
          } );
      });
     
   </script>

   <script type="text/javascript">
      $(document).ready(function() {
          $('#userdata').DataTable();
      } );
    </script>
@endsection