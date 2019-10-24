@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($orders);  ?>
        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Order Shipping List</h4>

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
                                <th>Order Id</th>
                                <th>Shipment ID</th>
                                <th>Ship Date</th>
                                <th>Shipment Cost</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipping as $ship)
                            <tr>
                                <td><a href="{{url('admin/order/each-order-detail/'.$ship['order_id'])}}" class="btn ">{{$ship['order_id']}}</a></td>
                                <td>{{$ship['shipment_id']}}</td>
                                <td>{{$ship['ship_date']}}</td>
                                <td>${{$ship['shipment_cost']}}</td>
                                <td>
                                  <a href="{{url('admin/shipping/each-shipping-detail/'.$ship['id'])}}" class="btn btn-xs btn-info">View</a>
                                  <a href="{{url('/')}}/v1/admin/images/labelspdf/{{ $ship['label_data'] }}" target="_blank" class="btn btn-xs btn-info">Download Label</a>
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
            ajax: '{!! url('admin/order-list-paginate') !!}',

            columns: [
                { data: 'DT_RowIndex'},
                { data: 'full_name', name: 'full_name' },
                { data: 'user_name', name: 'user_name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'state', name: 'state' },
              ],
          });

          $('#myTable tbody').on('click', 'tr', function () {
              var data = table.row( this ).data();
               console.log(data.id);
              window.location = "<?php echo url('admin/each-order-detail'); ?>"+'/'+data.id;
          } );
      });
     
   </script>
   <script type="text/javascript">
      $(document).ready(function() {
          $('#myTablees').DataTable();
      } );
    </script>

@endsection