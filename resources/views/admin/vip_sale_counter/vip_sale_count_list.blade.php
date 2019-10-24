@extends('layouts.admin-layout')

@section('content')

        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">VIP SALE COUNTER</h4>

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
                                <th>Sale Start Date</th>
                                <th>Sale End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vipSale as $sale)
                            <tr>
                                <td>{{ $sale['id'] }}</td>
                                <td>{{ $sale['start_date'] }}</td>
                                <td>{{ $sale['end_date'] }}</td>
                                <td>
                                @if($sale['start_date'] <  Carbon\Carbon::now() && $sale['end_date'] > Carbon\Carbon::now() )
                                <label class="badge badge-gradient-success">Active</label>
                                @else
                                <label class="badge badge-gradient-danger">Inactive</label>
                                @endif
                                </td>
                                <td>
                                  <a href="{{url('admin/edit-sale/'.$sale['id'])}}" class="btn btn-xs btn-success">Edit</a>
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
