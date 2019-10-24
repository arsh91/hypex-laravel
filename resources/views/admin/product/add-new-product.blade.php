@extends('layouts.admin-layout')

@section('content')
    <div class="content-wrapper">

          <div class="page-header">
            <h3 class="page-title">
              Add Product
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/product-list') }} ">Product Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Product</li>
              </ol>
            </nav>
          </div>
          
          <div class="row">
            
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Add Product</h4>
                  

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

                  <form class="forms-sample" method="post" action="{{ url('admin/add-new-product') }}" enctype="multipart/form-data"> 
                    @csrf
                    <div class="form-group">
                      <label for="productName1">Product Name</label><span>*</span>
                      <input type="text" class="form-control" id="productName1" placeholder="Name" value="{{ old('product_name') }}" name="product_name">
                    </div>
                    <div class="form-group">
                      <label for="productNickName">Product Nick Name</label>
                      <input type="text" class="form-control" id="productNickName" placeholder="Nick Name" value="{{ old('product_nick_name') }}" name="product_nick_name">
                    </div>
                    <div class="form-group">
                      <label for="selectCategory">Category</label><span>*</span>
                        <select class="form-control selectpicker" id="selectCategory" data-live-search="true" value="{{ old('category_name') }}" name="category_name">
                          <option value="" disabled selected>Select Category</option>
                          @if(count($get_category_list) > 0)
                              @foreach($get_category_list as $each_category)
                                  <option value="{{ $each_category['id'] }}" @if(old('category_name') == $each_category['id']) selected @endif>{{  $each_category['category_name'] }}</option>
                              @endforeach
                          @endif
                        </select>
                      </div>
                    <div class="form-group">
                      <label for="selectBrand">Brand</label><span>*</span>
                        <select class="form-control selectpicker" id="selectBrand" data-live-search="true"  name="brand_name">
                        <option value="" disabled selected>Select Brand</option>
                          @if(count($get_brand_list) > 0)
                              @foreach($get_brand_list as $each_brand)
                                  <option value="{{ $each_brand['id'] }}" @if(old('brand_name') == $each_brand['id']) selected @endif>{{  $each_brand['brand_name'] }}</option>
                              @endforeach
                          @endif
                        </select>
                      </div>
                   <div class="form-group">
                      <label for="selectBrandType">Brand Type</label><span>*</span>
                        <select class="form-control selectpicker" id="selectBrandType" data-live-search="true" name="brand_type">
                        <option value="" selected disabled>Select Brand Type</option>
                        </select>
                      </div>
                     <div class="form-group">
                      <label for="style">Style</label>
                      <input type="text" class="form-control" value="{{ old('style') }}" id="style" placeholder="Style" name="style">
                    </div>  
                     <div class="form-group">
                      <label for="size">Size</label><span>*</span>
                        <select class="form-control selectpicker" id="selectBrandType" multiple data-live-search="true" name="size[]">
                        <option value="" disabled>Select size</option>

                          @if(count($get_size_list) > 0)
                              @foreach($get_size_list as $each_size)
                                  @if(!empty(old('size')))
                                    @if(in_array($each_size['size'], old('size')))
                                      <option value="{{ $each_size['size'] }}" selected>{{  $each_size['size'] }}</option>
                                    @else
                                      <option value="{{ $each_size['size'] }}">{{  $each_size['size'] }}</option>
                                    @endif
                                  @else
                                     <option value="{{ $each_size['size'] }}">{{  $each_size['size'] }}</option>
                                  @endif
                              @endforeach
                          @endif
                        </select>
                      </div>
                    <div class="form-group">
                      <label for="size_type">Size Type</label><span>*</span>
                      <select class="form-control selectpicker" id="selectBrandType" data-live-search="true"  name="size_type">
                        <option value="" disabled selected> Select size </option>
                          @if(count($get_product_size_type) > 0)
                              @foreach($get_product_size_type as $each_size_type)
                                  <option value="{{ $each_size_type['id'] }}" @if(old('size_type') == $each_size_type['id']) selected @endif>{{  $each_size_type['size_type'] }} </option>
                              @endforeach
                          @endif
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="color">Color</label>
                      <input type="text" class="form-control" id="color" placeholder="color" value="{{ old('color') }}" name="color">
                    </div>
                    <div class="form-group">
                      <label for="season">Season</label>
                      <input type="text" class="form-control" id="season" placeholder="season" value="{{ old('season') }}" name="season">
                    </div>

                    <div class="form-group">
                      <label for="size_type" >Currency Type</label><span>*</span>
                      <select class="form-control selectpicker" id="currency_code" data-live-search="true"  name="currency_code">
                        <option value="" disabled selected> Select Currency </option>
                        <option value="USD" @if(old('currency_code') == 'USD') selected @endif> United States dollar </option>
                        <option value="CAD" @if(old('currency_code') == 'CAD') selected @endif> Canadian dollar </option>
                        <option value="CNY" @if(old('currency_code') == 'CNY') selected @endif> China Yuan </option>
                        </select>
                    </div>

                    <div class="form-group">
                      <label for="retail_price">Retail Price</label><span>*</span>
                      <input type="text" class="form-control" id="retail_price" placeholder="Retail Price" value="{{ old('retail_price') }}" name="retail_price">
                    </div>
                    <div class="form-group">
                      <label for="description">Description</label>
                      <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea >
                    </div>
                  <div class="form-group">
                  <div class="imagesArea">
                     <div class="image_upload">
                       <input type="file" name="img[0]" id="imgupload" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage" id="myImg">   
                        <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>
                     
                      
                      <div class="image_upload">
                       <input type="file" name="img[1]" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage">                  
                     
                      <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>
               
                  
                      <div class="image_upload">
                       <input type="file" name="img[2]" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage">                  
                     
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>
                 
                      
                      <div class="image_upload">
                       <input type="file" name="img[3]" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage">                  
                     
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                     
                      </div>
                    </div>
                    </div>
                    <div class="form-group">
                      <label>Other Product Image</label>
                                    
                     <div class="imagesArea">
                    <div class="image_upload">
                       <input type="file" name="othere_img[0]" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage">                  
                        <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                        </div>
                     
                      <div class="image_upload">
                       <input type="file" name="othere_img[1]" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage">
                        <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                      </div>

                      <div class="image_upload">
                       <input type="file" name="othere_img[2]" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage">                  
                     
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>

                      <div class="image_upload">
                       <input type="file" name="othere_img[3]" class="file-upload-default">
                        <img src="{{ asset('v1/admin/images/faces-clipart/dummyImage.png') }}" class="changeImage">  
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                      </div>
                    </div>
                    </div>
                       <div class="form-group">
                      <label for="date">Release Date</label>
                      <input type="text" class="form-control" id="datetimepicker" placeholder="yyyy/mm/dd" value="{{ old('release_date') }}" name="release_date">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>

                    <div class="form-group">
                      <label for="start_counter">Start Counter</label><span>*</span>
                      <input type="text" class="form-control" id="start_counter" placeholder="Start Counter" value="{{ old('start_counter') }}" name="start_counter">
                    </div>

                    <div class="form-group">
                      <label for="pass_code">Pass Code</label>
                      <input type="text" class="form-control" id="pass_code" placeholder="Pass Code" value="{{ old('pass_code') }}" name="pass_code">
                    </div>

                    <div class="form-group">
                      <label for="pass_value">Pass Value</label>
                      <input type="number" class="form-control" id="pass_value" placeholder="Pass Value" value="{{ old('pass_value') }}" name="pass_value">
                    </div>

                    <div class="form-group row">
                    <label for="trending" class="col-sm-12">Trending</label>
                    <div class="col-sm-3">
                      <div class="form-check ">
                            <label class="form-check-label ">
                              <input type="radio" name="trending" class="form-check-input" value="1" id="trending" @if(old('trending') == 1) checked @endif>
                              Active 
                            </label>
                      </div>
                      </div>
                      <div class="col-sm-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" name="trending" class="form-check-input" value="2" id="trending" @if(old('trending') != 1) checked @endif>
                              Deactive
                            </label>
                          </div>
                      </div>    
                     
                     
                        </div>

                 <div class="form-group row">
                    <label for="status" class="col-sm-12">Status</label>
                    <div class="col-sm-3">
                      <div class="form-check ">
                            <label class="form-check-label ">
                              <input type="radio" value="1" name="status" class="form-check-input" id="status" @if(old('status') == 1) checked @endif>
                              Active
                            </label>
                      </div>
                      </div>
                      <div class="col-sm-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" value="0" name="status" class="form-check-input"  id="status" @if(old('status') != 1) checked @endif>
                              Deactive
                            </label>
                          </div>
                      </div>
                        </div> 


                        <div class="form-group row">
                    <label for="status" class="col-sm-12">Vip Product</label>
                    <div class="col-sm-3">
                      <div class="form-check ">
                            <label class="form-check-label ">
                              <input type="radio" value="1" name="vip_status" class="form-check-input" id="vip_status" @if(old('vip_status') == 1) checked @endif>
                              Vip Product
                            </label>
                      </div>
                      </div>
                      <div class="col-sm-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" value="0" name="vip_status" class="form-check-input"  id="vip_status" @if(old('vip_status') != 1) checked @endif>
                              Normal Product
                            </label>
                          </div>
                      </div>
                        </div> 

                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
            
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('js_content')
   <script type="text/javascript">
      $(document).ready(function($) {
          $('#selectBrand').change(function(){
              $('.selectpicker').selectpicker('refresh');
              var id = $(this).val();
              var token = "{{ csrf_token() }}";
              $.ajax({
                  url: "<?php echo url('admin/get_brand_type_list');?>",
                  dataType: 'json',
                  type: 'post',
                  data:{'id': id, '_token' : token },
                  contentType: 'application/x-www-form-urlencoded',
                  // data: $(this).serialize(),
                  success: function( data, textStatus, jQxhr ){
                      $('#selectBrandType').html('');
                      $('#selectBrandType').append(' <option value="" disabled selected>Select Brand Type</option>');
                      if(data.length > 0){
                          for(var i=0; i < data.length; i++){
                              console.log(data[i].brand_type_name)
                              $('#selectBrandType').append(' <option value="'+data[i].id+'">'+data[i].brand_type_name+'</option>');
                          }
                      }
                      $('.selectpicker').selectpicker('refresh');
                  },
                  error: function( jqXhr, textStatus, errorThrown ){
                      console.log( errorThrown );
                  }
              });
          });

          var brand_name_selected = "<?php if(!empty(old('brand_name')))  echo(old('brand_name')); ?>";
          var brand_type_name_selected = "<?php if(!empty(old('brand_type')))  print_r(old('brand_type')); ?>";
          if(brand_type_name_selected.length > 0 && brand_name_selected.length > 0){
              $('.selectpicker').selectpicker('refresh');
              var id = brand_name_selected;
              var token = "{{ csrf_token() }}";
              $.ajax({
                  url: "<?php echo url('admin/get_brand_type_list');?>",
                  dataType: 'json',
                  type: 'post',
                  data:{'id': id, '_token' : token },
                  contentType: 'application/x-www-form-urlencoded',
                  // data: $(this).serialize(),
                  success: function( data, textStatus, jQxhr ){
                      $('#selectBrandType').html('');
                      $('#selectBrandType').append(' <option value="" disabled selected>Select Brand Type</option>');
                      if(data.length > 0){
                          for(var i=0; i < data.length; i++){
                              console.log(data[i].brand_type_name)
                              if(data[i].id == brand_type_name_selected){
                                  $('#selectBrandType').append('<option value="'+data[i].id+'" selected>'+data[i].brand_type_name+'</option>');
                              }else{
                                  $('#selectBrandType').append(' <option value="'+data[i].id+'" >'+data[i].brand_type_name+'</option>');
                              }
                              
                          }
                      }
                      $('.selectpicker').selectpicker('refresh');
                  },
                  error: function( jqXhr, textStatus, errorThrown ){
                      console.log( errorThrown );
                  }
              });
          }


          var test = $('#datetimepicker').datetimepicker({
              viewMode: 'years',
              format: 'YYYY-MM-DD'
          });

          $(":file").change(function () {
            var selected_img_source = $(this).closest(".image_upload").find(".changeImage");
              if (this.files && this.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                     selected_img_source.attr('src', e.target.result);
                  };
                  reader.readAsDataURL(this.files[0]);
              }
          });
          
          $('.file-upload-browse').click(function(){ 
              var input_file_name = $(this).closest(".image_upload").find("input[type='file']").attr('name');
               $(this).closest(".image_upload").find("input[type='file']").trigger('click');
          });
      });


   </script>
@endsection