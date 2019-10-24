@extends('layouts.admin-layout')

@section('content')

<?php // echo '<pre>'; print_r($get_product_list); exit; ?>
        <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">

                    <h4 class="card-title">Brand List</h4>
                     <div class="add_btntable">
                        <button type="button" id="addButtonClick" class="btn btn-primary btn-fw">Add Brand</button>
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
                              Brand Name
                            </th>
                            <th>
                              Brand Type Name
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
              window.location = "<?php echo url('admin/add-brand'); ?>";
          });

          var table = $('#myTable').DataTable({
            "columnDefs": [
              {"targets": 0, "orderable": false },
              // {"targets": 5, "orderable": false }
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! url('admin/brand-list-paginate') !!}',

            columns: [
                { data: 'DT_RowIndex'},
                { data: 'brand_name', name: 'brand_name' },
                { data: 'brand_types_list_with_comma_custom', name: 'brand_types_list_with_comma_custom' },
                { data: 'status_custom', name: 'status_custom' },
                // { data: 'action_custom', name: 'action_custom' }
              ],
          });

          $('#myTable tbody').on('click', 'tr', function () {
              var data = table.row( this ).data();
              // console.log(data.id);
              window.location = "<?php echo url('admin/each-brand-detail'); ?>"+'/'+data.id;
          } );



      });
      

   </script>
@endsection