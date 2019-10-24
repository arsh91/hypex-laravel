@extends('layouts.admin-layout')

@section('content')

<?php // echo '<pre>'; print_r($sells); exit; ?>
<div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">User Sell List</h4>

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
                              <th>Ask Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $index = 1; ?>
                            @foreach($sells as $sell)
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td>{{ $sell['product']['product_name']}}</td>
                                <td>{{ $sell['size']['size']}}</td>
                                <td>{{$sell['actual_price']}}</td>
                                <td>{{$sell['ask_price']}}</td>
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