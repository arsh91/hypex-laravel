@extends('layouts.admin-layout')

@section('content')

<?php // echo '<pre>'; print_r($get_product_list); exit; ?>
        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                   

                    <h4 class="card-title">Product List</h4>


                    <div class="add_btntable">
                        <button type="button" id="addButtonClick" class="btn btn-primary btn-fw">Add Product</button>
                    </div>

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
                      <table class="table table-bordered table-hover" id="myTable" style="width:100%">
                        <thead>
                          <tr >
                             <th>
                              Sr No.
                            </th>
                            <th>
                              Product Image
                            </th>
                            <th>
                              Product Name
                            </th>
                            <th>
                              Category
                            </th>
                            <th>
                              Brand
                            </th>
                            <th>
                              Retail Price
                            </th>
                            <th>
                              Status
                            </th>
                            <th>
                              Release date
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
        </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')
   <script type="text/javascript">
      $(document).ready(function($) {
        
          $("#addButtonClick").click(function() {
              window.location = "<?php echo url('admin/add-new-product'); ?>";
          });

          
          $(".clickable-row").click(function() {
              
          });

          var table = $('#myTable').DataTable({
            "pageLength": 10,
            "columnDefs": [
              { "width": "4%", "targets": 0, "orderable": false },
              { "width": "4%", "targets": 1 , "orderable": false},
              // { "width": "32%", "targets": 1 },
              // { "width": "10%", "targets": 2 },
              // { "width": "10%", "targets": 3 },
              // { "width": "8%", "targets": 4 },
              { "width": "8%", "targets": 6, "orderable": false },
              { "width": "12%", "targets": 7 }
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! url('admin/product-list-paginate') !!}',

            columns: [
                { data: 'DT_RowIndex'},
                { data: 'product_image_custom', name: 'product_image_custom' },
                { data: 'product_name_custom', name: 'product_name_custom' },
                { data: 'category_name', name: 'category_name' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'retail_price', name: 'retail_price' },
                { data: 'status_custom', name: 'status_custom' },
                { data: 'release_date_format', name: 'release_date_format' }
              ],
          });

          $('#myTable tbody').on('click', 'tr', function () {
              var data = table.row( this ).data();
              // console.log(data.id);
              window.location = "<?php echo url('admin/each-product-detail'); ?>"+'/'+data.id;
          } );
      });
     
   </script>
@endsection