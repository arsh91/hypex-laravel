@extends('layouts.admin-layout')

@section('content')

<?php  //echo '<pre>'; print_r($selling_history); exit; ?>
<div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">User Selling History List</h4>

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
                          @if(count($selling_history) > 0)
                            @foreach($selling_history as $sellhis)
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td><?php echo date("d-m-Y", strtotime($sellhis['created_at'])); ?> </td>
                                <td><img src="{{url('/')}}/public/v1/website/products/{{$sellhis['product']['product_images']}}" alt=""/> <span>{{ $sellhis['product']['product_name']}}</span></td>
                                <td>{{ $sellhis['price'] }}</td>
                                <td>{{ $sellhis['shipping_price'] }}</td>
                                <td>{{ $sellhis['total_price'] }}</td>
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