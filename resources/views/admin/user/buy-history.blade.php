@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($buying_history); exit; ?>
<div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">User Buying History List</h4>

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
                              <th>Sr.No.</th>
                              <th>Date</th>
                              <th>Product</th>
                              <th>Price</th>
                              <th>Shipping Price</th>
                              <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $index = 1; ?>
                          @if(count($buying_history) > 0)
                            @foreach($buying_history as $buyhis)
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($buyhis['created_at'])); ?> </td>
                                <td><img src="{{url('/')}}/public/v1/website/products/{{$buyhis['product']['product_images']}}" alt=""/> <span>{{ $buyhis['product']['product_name']}}</span></td>
                                <td>{{ $buyhis['price'] }}</td>
                                <td>{{ $buyhis['shipping_price'] }}</td>
                                <td>{{ $buyhis['total_price'] }}</td>
                            </tr>
                            @endforeach
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


@endsection

@section('js_content')
   <script type="text/javascript">
      $(document).ready(function() {
          $('#userdata').DataTable();
      } );
    </script>
@endsection