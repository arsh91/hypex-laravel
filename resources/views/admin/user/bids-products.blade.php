@extends('layouts.admin-layout')

@section('content')

<?php // echo '<pre>'; print_r($bidds); exit; ?>
<div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">User Bid List</h4>

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
                              <th>Product</th>
                              <th>Size</th>
                              <th>Actual Price</th>
                              <th>Bid Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $index = 1; ?>
                            @foreach($bidds as $bid)
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td>{{ $bid['product']['product_name']}}</td>
                                <td>{{ $bid['size']['size']}}</td>
                                <td>{{$bid['actual_price']}}</td>
                                <td>{{$bid['bid_price']}}</td>
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


@endsection

@section('js_content')
   <script type="text/javascript">
      $(document).ready(function() {
          $('#userdata').DataTable();
      } );
    </script>
@endsection