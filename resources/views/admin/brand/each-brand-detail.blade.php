@extends('layouts.admin-layout')

@section('content')
        <div class="content-wrapper">
            
            <div class="page-header">
              <h3 class="page-title">
                Brand Detail
              </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('admin/brand-list') }} ">Brand Management</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Each Brand Detail</li>
                </ol>
              </nav>
            </div>


          	<div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-description">Brand Name: <code>{{ $get_brand_detail->brand_name }}</code> 
                  <p class="card-description">Brand Type List
                    @php(
                    $brand_list = $get_brand_detail->brandTypes()->pluck('brand_type_name')
                    )
                  <ul class="list-ticked">
                    @if(count($brand_list) > 0)
                        @for($i=0; $i < count($brand_list); $i++)
                          <li>{{ $brand_list[$i] }}</li>
                        @endfor
                    @else
                        <li>No Brand Found.</li>
                    @endif
                  </ul>
                  <button id="editButtonClick" type="button" class="btn btn-success btn-fw">Edit</button>
                  
                  @if(!empty($get_brand_detail->status ) && $get_brand_detail->status == 1)
                    <button id="deleteButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">Deactive</button>
                  @else 
                    <button id="deleteButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">Active</button>  
                  @endif
                </div>
              </div>
            </div>
       
        <!-- content-wrapper ends -->
          
       
        </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')

   <script type="text/javascript">
      $(document).ready(function(){
          $("#editButtonClick").click(function() {
              window.location = "<?php echo url('admin/edit-brand/'); ?>"+"/"+"<?php echo $get_brand_detail->id ; ?>";
          });

          $("#deleteButtonClick").click(function() {
              window.location = "<?php echo url('admin/delete-brand/'); ?>"+"/"+"<?php echo $get_brand_detail->id ; ?>";
          });

      });
     
   </script>
@endsection