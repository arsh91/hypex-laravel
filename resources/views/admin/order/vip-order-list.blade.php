@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($orders);  ?>
        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Order List</h4>

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
                                <th>Product Name</th>
                                <th>Product Image</th>
                                <th>Buyer Name</th>
                                <!--th>Seller Name</th-->
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{$order['id']}}</td>
                                <td>{{$order['product']['product_name']}}</td>
                                <td>
                                  @if($order['product']['product_images'] != '')
                                  <img src="{{url('/')}}/{{$order['product']['product_images']}}" style="width: 36px; height: 36px; border-radius: 100%;"/>
                                  @else
                                  <img src="{{url('/')}}/v1/admin/images/noimage.png" style="width: 36px; height: 36px; border-radius: 100%;"/>
                                    @endif
                                </td>
                                <td>{{$order['users']['first_name']}}</td>
                                <!--td>{{$order['seller']['first_name']}}</td-->
                                <td>${{$order['total_price']}}</td>
                                <td>
                                  <a href="{{url('admin/order/each-order-detail/'.$order['id'])}}" class="btn btn-gradient-info btn-sm">View</a>

                                  @if($order['status'] == 1)
                                    <!--a href="{{url('admin/order-data/'.$order['order_ref_number'].'/'.$order['id'])}}" class="btn btn-gradient-secondary btn-sm orderdata">Pass</a-->           
                                  @endif

                                  @if($order['status'] == 1)
                                  <!--a href="{{url('admin/order/deleteorder/'.$order['id'].'/0')}}" class="btn btn-gradient-danger btn-sm" onclick = "if (! confirm('Do you want to delete this order id {{$order['id']}}?')) { return false; }">Reject</a-->
                                  @endif
                                  
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