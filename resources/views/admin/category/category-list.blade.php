@extends('layouts.admin-layout')

@section('content')

<?php // echo '<pre>'; print_r($get_product_list); exit; ?>
        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Category Management</h4>
                      <div class="add_btntable">
                        <button type="button" id="addButtonClick" class="btn btn-primary btn-fw">Add Category</button>
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

                      <table class="table table-bordered table-hover " id="myTable">
                        <thead>
                          <tr >
                             <th>
                              Sr No.
                            </th>
                            <th>
                              Category Name
                            </th>
                             <th>
                              Brand Name
                            </th>
                            <th>
                              Status
                            </th>
                            <!--  <th>
                              Action
                            </th> -->
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
              window.location = "<?php echo url('admin/add-category'); ?>";
          });

          var table = $('#myTable').DataTable({
            "columnDefs": [
              {"targets": 0, "orderable": false },
              // {"targets": 5, "orderable": false }
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! url('admin/category-list-paginate') !!}',

            columns: [
                { data: 'DT_RowIndex'},
                { data: 'category_name', name: 'category_name' },
                { data: 'brand_list_custom', name: 'brand_list_custom' },
                { data: 'status_custom', name: 'status_custom' },
                // { data: 'action_custom', name: 'action_custom' }
              ],
          });

          $('#myTable tbody').on('click', 'tr', function () {
              var data = table.row( this ).data();
              // console.log(data.id);
              window.location = "<?php echo url('admin/each-category-detail'); ?>"+'/'+data.id;
          } );



      });
      

   </script>
@endsection