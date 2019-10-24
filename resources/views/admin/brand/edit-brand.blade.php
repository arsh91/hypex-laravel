@extends('layouts.admin-layout')

@section('content')
	  <div class="content-wrapper">

            <div class="page-header">
              <h3 class="page-title">
                Edit Brand
              </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('admin/brand-list') }} ">Brand Management</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit Brand</li>
                </ol>
              </nav>
            </div>

          	<div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif
                  <div class="alert alert-danger hideDivAlert">
                    <ul>
                    </ul>
                  </div>
                  @if(session()->has('success'))
                      <div class="alert alert-success">
                          {{ session()->get('success') }}
                      </div>
                  @endif


                  <form class="forms-sample" method="post">
                    @csrf
                    <div class="form-group col-8">
                      <label for="exampleInputName1">Brand Name</label>
                      <input type="text" class="form-control" name="brand_name" id="exampleInputName1" value="@if(!empty(old('brand_name'))) {{ old('brand_name') }} @else {{ $get_brand_detail->brand_name }} @endif" placeholder="Brand Name">

                    </div>
                    @php
                       $select_brand_type = !empty(old('select_brand_type')) ? old('select_brand_type') : $get_brand_detail->brandTypes()->pluck('brand_type_name')->toArray() ;
                    @endphp
                    <div class="form-group col-8">
                      <label>Add Brand Type</label>
                        <span class="input-group-append">
                    		<input type="text" class="form-control" name="select_brand_type[]" id="exampleInputName1" value="@if(isset($select_brand_type) && !empty($select_brand_type[0])) {{ $select_brand_type[0] }} @endif"  placeholder="Brand Type Name">
                        </span>
                    </div>


                  <div id="addMoreBrandType">
                        @if(isset($select_brand_type) && count($select_brand_type) > 1)
                            @foreach($select_brand_type as $key => $each_val)
                                @if($key >= 1)
                                     <div class="form-group col-8 removeClass" id="123"><span class="input-group-append"><input type="text" class="form-control" name="select_brand_type[{{ $key }}]" id="exampleInputName1" placeholder="Brand Type Name" value="{{ $each_val }}"><a class="clickCrossIcon"><i class="mdi mdi-table-row-remove"></i></a></span></div>
                                @endif
                            @endforeach  
                        @endif              	
                	</div>
                  <div>
                    <button id="addMoreBrandTypeButton" class="file-upload-browse btn btn-gradient-primary" type="button">Add More Brand Type</button>
                    <button type="submit"  class="btn btn-gradient-primary mr-2">Update</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
@endsection

@section('js_content')

   <script type="text/javascript">
      $(document).ready(function(){
            $('.hideDivAlert').hide();
          	$('#addMoreBrandTypeButton').click(function(){
          		$('#addMoreBrandType').append('<div class="form-group col-8 removeClass" id="123"><span class="input-group-append"><input type="text" class="form-control" name="select_brand_type[]" id="exampleInputName1" placeholder="Brand Type Name"><a class="clickCrossIcon"><i class="mdi mdi-table-row-remove"></i></a></span></div>');
          	})

          	$('#addMoreBrandType').on( "click",'.clickCrossIcon', function() {
                var result = confirm("are you sure want to delete this brand type?");
                if(result){
                    var thisvalue = $(this);
                    var brand_type_val = $(this).prev('input').val();
                    var brand_id = "<?php echo $get_brand_detail->id; ?>"
    			  	      var token = "{{ csrf_token() }}";
                    $.ajax({
                        url: "<?php echo url('admin/delete_brand_type');?>",
                        dataType: 'json',
                        type: 'post',
                        data:{'brand_id': brand_id, '_token' : token, 'brand_type_val' : brand_type_val },
                        contentType: 'application/x-www-form-urlencoded',
                        success: function( data, textStatus, jQxhr ){
                            console.log(data.status);
                            if(data.status == 1){
                              thisvalue.closest('#addMoreBrandType .removeClass').remove();
                            }else{
                                $('.hideDivAlert').show();
                                $('.hideDivAlert ul').html('');
                                $('.hideDivAlert ul').append("<li>This Brand type can not delete because it's already assign to some products.</li>");
                            }  
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                            console.log( errorThrown );
                        }
                    });
                }
		        });
      });
     
   </script>
@endsection