@extends('layouts.admin-layout')

@section('content')

        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Currency List</h4>

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
                                <th>Currency Code</th>
                                <th>Conversion Rate</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currencyList as $curr)
                            <tr>
                                <td>{{ $curr['id'] }}</td>

                                @if($curr['currency_code'] == 'USD')
                                <td>{{ $curr['currency_code'] }}</td>
                                <td>${{ $curr['conversion_rate'] }}</td>
                                @elseif($curr['currency_code'] == 'CAD')
                                <td>{{ $curr['currency_code'] }}</td>
                                <td>${{ $curr['conversion_rate'] }}</td>
                                @elseif($curr['currency_code'] == 'CNY')
                                <td>{{ $curr['currency_code'] }}</td>
                                <td>¥{{ $curr['conversion_rate'] }}</td>
                                @endif

                                
                                <td>
                                @if($curr['status'] == 1)
                                <label class="badge badge-gradient-success">Active</label>
                                @else
                                <label class="badge badge-gradient-danger">Inactive</label>
                                @endif
                                </td>
                                <td>
                                  <a href="{{url('admin/edit-currency/'.$curr['id'])}}" class="btn btn-xs btn-success">Edit</a>
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