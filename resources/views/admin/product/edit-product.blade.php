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
                <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Product</h4>
                  

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

                  <form class="forms-sample" action="#" id="scrap-form" method="post">
                  @csrf
                    <div class="form-group">
                      <label for="productName1">Scrap Link</label>
                      <input type="text" class="form-control" id="scrap_link" placeholder="Scrap Link"  name="scrap_link" required>
                    </div>
                    <div class="form-group">
                        <div id="scrapLoading" style="display: none;color: green;font-size: 16px;">
                        <img src="https://s3.gifyu.com/images/loading877e886df6611708.gif" alt="loading" width="18%">
                                        <p>Scraping Data from Stockx ...</p>
                        </div>
                      <button type="button" class="btn btn-gradient-primary mr-2 btn-scrap">Scrap Bids</button>
                      <button type="button" class="btn btn-gradient-primary mr-2 btn-scrap-sell">Scrap Asks</button>
                      <br/>
                      <b><span id="bidmsg"></span></b><br/><b><span id="askmsg"></span></b>
                    </div>
                  </form>

                  <form class="forms-sample" method="post" enctype="multipart/form-data"> 
                    @csrf
                    <div class="form-group">
                      <label for="productName1">Product Name</label>
                      <input type="text" class="form-control" id="productName1" placeholder="Name" value="@if(!empty(old('product_name'))) {{old('product_name')}} @else {{ $get_product_detail->product_name }}@endif" name="product_name">
                    </div>
                    <div class="form-group">
                      <label for="productNickName">Product Nick Name</label>
                      <input type="text" class="form-control" id="productNickName" placeholder="Nick Name" value="@if(!empty(old('product_nick_name'))) {{old('product_nick_name')}} @else {{ $get_product_detail->product_nick_name }}@endif" name="product_nick_name">
                    </div>

                    @php(
                        $category_name_selected =!empty(old('category_name')) ? old('category_name') : $get_product_detail->category_id 
                    )

                    <div class="form-group">
                      <label for="selectCategory">Category</label>
                        <select class="form-control selectpicker" id="selectCategory" data-live-search="true" name="category_name">
                          <option value="" disabled selected>Select Category</option>
                          @if(count($get_category_list) > 0)
                              @foreach($get_category_list as $each_category)
                                  <option value="{{ $each_category['id'] }}" @if($category_name_selected == $each_category['id']) selected @endif>{{  $each_category['category_name'] }}</option>
                              @endforeach
                          @endif
                        </select>
                      </div>

                      @php(
                        $brand_name_selected =!empty(old('brand_name')) ? old('brand_name') : $get_product_detail->brand_id 
                      )

                    <div class="form-group">
                      <label for="selectBrand">Brand</label>
                        <select class="form-control selectpicker" id="selectBrand" data-live-search="true"  name="brand_name">
                        <option value="" disabled selected>Select Brand</option>
                          @if(count($get_brand_list) > 0)
                              @foreach($get_brand_list as $each_brand)
                                  <option value="{{ $each_brand['id'] }}" @if($brand_name_selected == $each_brand['id']) selected @endif>{{  $each_brand['brand_name'] }}</option>
                              @endforeach
                          @endif
                        </select>
                      </div>

                    

                   <div class="form-group">
                      <label for="selectBrandType">Brand Type</label>
                        <select class="form-control selectpicker" id="selectBrandType" data-live-search="true" name="brand_type">
                        <option value="" selected disabled>Select Brand Type</option>
                        </select>
                      </div>
                     <div class="form-group">
                      <label for="style">Style</label>
                      <input type="text" class="form-control" value="@if(!empty(old('style'))) {{old('style')}} @else {{ $get_product_detail->style }}@endif" id="style" placeholder="Style" name="style">
                    </div>  

                   <?php
                      $size_selected =!empty(old('size')) ? old('size') : $get_product_detail->productSizes()->pluck('size')->toArray();
                   ?>
                     <div class="form-group">
                      <label for="size">Size</label>
                        <select class="form-control selectpicker" id="selectBrandType" multiple data-live-search="true" name="size[]">
                        <option value="" disabled>Select size</option>

                          @if(count($get_size_list) > 0)
                              @foreach($get_size_list as $each_size)
                                  @if(!empty($size_selected))
                                    @if(in_array($each_size['size'],$size_selected))
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

                    @php(
                      $size_type_selected = !empty(old('size_type')) ? old('size_type') : $get_product_detail->size_type_id 
                    )


                    <div class="form-group">
                      <label for="size_type">Size Type</label>
                      <select class="form-control selectpicker" id="selectBrandType" data-live-search="true"  name="size_type">
                        <option value="" disabled selected> Select size </option>
                          @if(count($get_product_size_type) > 0)
                              @foreach($get_product_size_type as $each_size_type)
                                  <option value="{{ $each_size_type['id'] }}" @if($size_type_selected == $each_size_type['id']) selected @endif>{{  $each_size_type['size_type'] }} </option>
                              @endforeach
                          @endif
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="color">Color</label>
                      <input type="text" class="form-control" id="color" placeholder="color" value="@if(!empty(old('color'))) {{old('color')}} @else {{ $get_product_detail->color }}@endif" name="color">
                    </div>
                    <div class="form-group">
                      <label for="season">Season</label>
                      <input type="text" class="form-control" id="season" placeholder="season" value="@if(!empty(old('season'))) {{old('season')}} @else {{ $get_product_detail->season }}@endif" name="season">
                    </div>

                    <div class="form-group">
                      <label for="size_type" >Currency Type</label>
                      <select class="form-control selectpicker" id="currency_code" data-live-search="true"  name="currency_code">
                        <option value="USD" @if($get_product_detail->currency_code == 'USD') selected @endif > United States dollar </option>
                        <option value="CAD" @if($get_product_detail->currency_code == 'CAD') selected @endif > Canadian dollar </option>
                        <option value="CNY" @if($get_product_detail->currency_code == 'CNY') selected @endif > China Yuan </option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="retail_price">Retail Price</label>
                      <input type="text" class="form-control" id="retail_price" placeholder="Retail Price" value="@if(!empty(old('retail_price'))) {{old('retail_price')}} @else {{ $get_product_detail->retail_price }}@endif" name="retail_price">
                    </div>
                    <div class="form-group">
                      <label for="description">Description</label>
                      <textarea class="form-control" id="description" name="description" rows="4">@if(!empty(old('description'))) {{old('description')}} @else {{ $get_product_detail->description }}@endif</textarea >
                    </div>
                  <div class="form-group">
                  <div class="imagesArea">

                      <?php
                        $img_0_selected = !empty(old('img[0]')) ? old('img[0]') : (isset($get_product_detail->product_image_link[0]) ? $get_product_detail->product_image_link[0] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $img_0_selected = !empty($img_0_selected) ?  url($img_0_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>

                      <div class="image_upload">
                       <input type="file" name="img[0]" id="imgupload" class="file-upload-default">
                        <img src="{{ $img_0_selected }}" class="changeImage" id="myImg">
                        <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                      </div>
                     
                      <?php
                        $img_1_selected = !empty(old('img[1]')) ? old('img[1]') : (isset($get_product_detail->product_image_link[1]) ? $get_product_detail->product_image_link[1] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $img_1_selected = !empty($img_1_selected) ?  url($img_1_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>

                      <div class="image_upload">
                       <input type="file" name="img[1]" class="file-upload-default">
                        <img src="{{ $img_1_selected }}" class="changeImage">                  
                     
                      <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>
               
                      <?php
                        $img_2_selected = !empty(old('img[2]')) ? old('img[2]') : (isset($get_product_detail->product_image_link[2]) ? $get_product_detail->product_image_link[2] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $img_2_selected = !empty($img_2_selected) ?  url($img_2_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>

                      <div class="image_upload">
                       <input type="file" name="img[2]" class="file-upload-default">
                        <img src="{{ $img_2_selected }}" class="changeImage">                  
                     
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>
                 
                      <?php
                        $img_3_selected = !empty(old('img[3]')) ? old('img[3]') : (isset($get_product_detail->product_image_link[3]) ? $get_product_detail->product_image_link[3] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $img_3_selected = !empty($img_3_selected) ?  url($img_3_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>


                      <div class="image_upload">
                       <input type="file" name="img[3]" class="file-upload-default">
                        <img src="{{ $img_3_selected }}" class="changeImage">
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                      </div>
                    </div>
                    </div>
                    <div class="form-group">
                      <label>Other Product Image</label>
                                    
                     <div class="imagesArea">
                      <?php
                        $othere_img_0_selected = !empty(old('othere_img[0]')) ? old('othere_img[0]') : (isset($get_product_detail->other_product_image_link[0]) ? $get_product_detail->other_product_image_link[0] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $othere_img_0_selected = !empty($othere_img_0_selected) ?  url($othere_img_0_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>

                      <div class="image_upload">
                       <input type="file" name="othere_img[0]" class="file-upload-default">
                        <img src="{{ $othere_img_0_selected }}" class="changeImage">                  
                        <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>
                      
                      <?php
                        $othere_img_1_selected = !empty(old('othere_img[1]')) ? old('othere_img[1]') : (isset($get_product_detail->other_product_image_link[1]) ? $get_product_detail->other_product_image_link[1] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $othere_img_1_selected = !empty($othere_img_1_selected) ?  url($othere_img_1_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>


                      <div class="image_upload">
                       <input type="file" name="othere_img[1]" class="file-upload-default">
                        <img src="{{ $othere_img_1_selected }}" class="changeImage">
                        <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                      </div>


                      <?php
                        $othere_img_2_selected = !empty(old('othere_img[2]')) ? old('othere_img[2]') : (isset($get_product_detail->other_product_image_link[2]) ? $get_product_detail->other_product_image_link[2] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $othere_img_2_selected = !empty($othere_img_2_selected) ?  url($othere_img_2_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>


                      <div class="image_upload">
                       <input type="file" name="othere_img[2]" class="file-upload-default">
                        <img src="{{ $othere_img_2_selected }}" class="changeImage">
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                    
                      </div>


                      <?php
                        $othere_img_3_selected = !empty(old('othere_img[3]')) ? old('othere_img[3]') : (isset($get_product_detail->other_product_image_link[3]) ? $get_product_detail->other_product_image_link[3] : asset('v1/admin/images/faces-clipart/dummyImage.png'));
                        $othere_img_3_selected = !empty($othere_img_3_selected) ?  url($othere_img_3_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');   
                      ?>


                      <div class="image_upload">
                       <input type="file" name="othere_img[3]" class="file-upload-default">
                        <img src="{{ $othere_img_3_selected }}" class="changeImage">  
                       <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                      </div>
                    </div>
                    </div>
                       <div class="form-group">
                      <label for="date">Release Date</label>
                      <input type="text" class="form-control" id="datetimepicker" placeholder="yyyy/mm/dd" value="@if(!empty(old('release_date'))) {{old('release_date')}} @else {{ $get_product_detail->release_date }}@endif" name="release_date">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                    <div class="form-group">
                      <label for="start_counter">Start Counter</label>
                      <input type="text" class="form-control" id="start_counter" placeholder="Start Counter" value="@if(!empty(old('start_counter'))) {{old('start_counter')}} @else {{ $get_product_detail->start_counter }}@endif" name="start_counter">
                    </div>

                    <div class="form-group">
                      <label for="pass_code">Pass Code</label>
                      <input type="text" class="form-control" id="pass_code" placeholder="Pass Code" value="@if(!empty(old('pass_code'))) {{old('pass_code')}} @else {{ $get_product_detail->pass_code }}@endif" name="pass_code">
                    </div>

                    <div class="form-group">
                      <label for="pass_value">Pass Value</label>
                      <input type="text" class="form-control" id="pass_value" placeholder="Pass Value" value="@if(!empty(old('pass_value'))) {{old('pass_value')}} @else {{ $get_product_detail->pass_value }}@endif" name="pass_value">
                    </div>


                    <div class="form-group row">
                        <label for="trending" class="col-sm-12">Trending</label>
                        <div class="col-sm-3">

                        @php(
                          $trending_selected =!empty(old('trending')) ? old('trending') : $get_product_detail->trending 
                        )
                          <div class="form-check ">
                                <label class="form-check-label ">
                                  <input type="radio" name="trending" class="form-check-input" value="1" id="trending" @if($trending_selected == 1) checked @endif>
                                  Active 
                                </label>
                          </div>
                          </div>
                          <div class="col-sm-3">
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input type="radio" name="trending" class="form-check-input" value="2" id="trending" @if($trending_selected != 1) checked @endif>
                                  Deactive
                                </label>
                              </div>
                          </div> 
                    </div>

                @php(
                    $status_selected =  !empty(old('status')) ? old('status') : $get_product_detail->status
                  )

                 <div class="form-group row">
                    <label for="status" class="col-sm-12">Status</label>
                    <div class="col-sm-3">
                      <div class="form-check ">
                            <label class="form-check-label ">
                              <input type="radio" value="1" name="status" class="form-check-input" id="status" @if($status_selected == 1) checked @endif>
                              Active
                            </label>
                      </div>
                      </div>
                      <div class="col-sm-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" value="2" name="status" class="form-check-input"  id="status" @if($status_selected != 1) checked @endif>
                              Deactive
                            </label>
                          </div>
                      </div>
                        </div>   


                        @php(
                    $vip_status =  !empty(old('vip_status')) ? old('vip_status') : $get_product_detail->vip_status
                  )
                        <div class="form-group row">
                    <label for="status" class="col-sm-12">Vip Product</label>
                    <div class="col-sm-3">
                      <div class="form-check ">
                            <label class="form-check-label ">
                            <input type="radio" value="1" name="vip_status" class="form-check-input" id="status" @if($vip_status == 1) checked @endif>
                              Vip Product
                            </label>
                      </div>
                      </div>
                      <div class="col-sm-3">
                          <div class="form-check">
                            <label class="form-check-label">
                            <input type="radio" value="0" name="vip_status" class="form-check-input"  id="status" @if($vip_status != 1) checked @endif>
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

          var brand_name_selected = "<?php if(!empty(old('brand_name')))  echo (old('brand_name')); else echo($get_product_detail->brand_id); ?>";
          var brand_type_name_selected = "<?php if(!empty(old('brand_type')))  print_r(old('brand_type')); else echo($get_product_detail->brand_type_id); ?>";
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


    $(document).ready(function () {
        $(document).ajaxStart(function () {
            $('.btn-scrap').attr("disabled", true);
            $('.btn-scrap-sell').attr("disabled", true);
            $("#scrapLoading").show();
        }).ajaxStop(function () {
            $("#scrapLoading").hide();
            $('.btn-scrap').attr("disabled", false);
            $('.btn-scrap-sell').attr("disabled", false);
        });
    });

    $(document).ready(function() {
        
    // Script to fetch BIDS from STOCKX
    $('.btn-scrap').on('click', function (e) {
        e.preventDefault();
        var id = '<?php echo $get_product_detail->id; ?>';
        var scrap_link = $('#scrap_link').val();
            $.ajax({
              url: '{{ url('/') }}/admin/scrap-product/' + id,
              data: $("#scrap-form").serialize(),
              method: 'post',
              success: function(response){
                $("#bidmsg").html(response);
              }
            });
      });
      
      
      // Script to fetch ASKS from STOCKX
           $('.btn-scrap-sell').on('click', function (e) {
            e.preventDefault();
            var id = '<?php echo $get_product_detail->id; ?>';
            var scrap_link = $('#scrap_link').val();
                $.ajax({
                  url: '{{ url('/') }}/admin/scrap-product-sell/' + id,
                  data: $("#scrap-form").serialize(),
                  method: 'post',
                  success: function(response){
                    $("#askmsg").html(response);
                  }
                });
          });

    });



   </script>
@endsection