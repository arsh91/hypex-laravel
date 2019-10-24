@extends('layouts.admin-layout')

@section('content')
        <?php // echo '<pre>'; print_r($get_product_detail->toArray()); exit; ?>
        <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                View Product
              </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('admin/product-list') }}">Product Management</a></li>
                  <li class="breadcrumb-item active" aria-current="page">View Product</li>
                </ol>
              </nav>
            </div>
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title divide"><span>Product Info</span>  
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
                  
                        @if(!empty($get_product_detail->status ) && $get_product_detail->status == 1)
                          <button id="deleteButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">Deactive</button>
                        @else 
                          <button id="deleteButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">Active</button>  
                        @endif

                        <button id="removeButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">Delete</button>

                        <button id="editButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">Edit</button>
                    </h4> 
                        <div class="col-half pro-details">
                          <ul class="detail-list">
                            <li>
                              <h2>Product Name</h2>
                              <p>@if(!empty($get_product_detail->product_name )) {{ ucfirst($get_product_detail->product_name)}} @else N/A @endif</p>
                            </li>  

                            <li>
                              <h2>Product Nick Name</h2>
                              <p>@if(!empty($get_product_detail->product_nick_name )) {{ ucfirst($get_product_detail->product_nick_name)}} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Category</h2>
                              <p>@if(isset($get_product_detail->productCategory()->first()->category_name) && !empty($get_product_detail->productCategory()->first()->category_name )) {{ ucfirst($get_product_detail->productCategory()->first()->category_name)}} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Brand</h2>
                              <p>@if(isset($get_product_detail->productBrand()->first()->brand_name) && !empty($get_product_detail->productBrand()->first()->brand_name )) {{ ucfirst($get_product_detail->productBrand()->first()->brand_name)}} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Brand Type</h2>
                              <p>@if(isset($get_product_detail->productBrandType()->first()->brand_type_name) && !empty($get_product_detail->productBrandType()->first()->brand_type_name )) {{ ucfirst($get_product_detail->productBrandType()->first()->brand_type_name)}} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Style</h2>
                              <p>@if(!empty($get_product_detail->style )) {{ ucfirst($get_product_detail->style)}} @else N/A @endif</p>
                            </li> 
                          </ul>
                        </div> 

                        <!-- ================ col half ends --============== -->
                        <div class="col-half pro-images">

                          <!-- ===================== slider js =============== -->

                            <div class="item">            
                                <div class="clearfix" style="max-width:100%;">
                                    <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                                         @if(!empty($get_product_detail->product_image_link ) && count($get_product_detail->product_image_link) > 0)
                                            @for($i =0; $i< count($get_product_detail->product_image_link); $i++)
                                                <li data-thumb="{{url($get_product_detail->product_image_link[$i]) }}">
                                                    <img src="{{ asset(url($get_product_detail->product_image_link[$i])) }}" />
                                                </li>
                                            @endfor
                                        @endif                         
                                    </ul>
                                </div>
                            </div>

                          <!-- ============= slider js ends ================ --> 
                        </div>
                        <!-- =========== col half ends --====== -->
                  </div>
                </div>
              </div>

              <div class="col-12 grid-margin stretch-card product-sizes">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title divide"><span>Size Chart</span>  </h4> 

                      @php
                        if(!empty($get_product_detail->productSizes()->pluck('size')))  $size_array = $get_product_detail->productSizes()->pluck('size');
                        else $size_array = array();
                      @endphp
                      
                        <ul class="sizes">
                          @if(!empty($get_product_detail->productSizes ) && count($size_array) > 0)
                                @for($i =0; $i< count($size_array); $i++)
                                  <li class=""><span>{{ trim($size_array[$i]) }} </span></li>
                                @endfor
                            @else
                               <li class="">
                                <span>No Size</span>
                              </li>
                            @endif
                        </ul>

                  </div>
                </div>
              </div>

              <div class="col-12 grid-margin stretch-card product-details">
                <div class="card">
                  <div class="card-body">
                    <div class="col-half pro-details">
                          <ul class="detail-list">

                            <li>
                              <h2>Size Type</h2>
                              <p>@if(isset($get_product_detail->productSizeTypes()->first()->size_type) && !empty($get_product_detail->productSizeTypes()->first()->size_type )) {{ ucfirst($get_product_detail->productSizeTypes()->first()->size_type)}} @else N/A @endif</p>
                            </li>  

                            <li>
                              <h2>Color</h2>
                              <p>@if(!empty($get_product_detail->color )) {{ ucfirst($get_product_detail->color)}} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Release Date</h2>
                              <p>@if(!empty($get_product_detail->release_date )) {{ ucfirst($get_product_detail->release_date)}} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Trending</h2>
                              <p>@if(!empty($get_product_detail->trending ) && $get_product_detail->trending == 1) Active @else Deactivated @endif</p>
                            </li> 

                            <li>
                              <h2>Pass Code</h2>
                              <p>@if(!empty($get_product_detail->pass_code )) {{ $get_product_detail->pass_code }} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>VIP</h2>
                              <p>@if(!empty($get_product_detail->vip_status ) && $get_product_detail->vip_status == 1) Vip Product @else Normal Product @endif</p>
                            </li> 
   
                          </ul>
                        </div> 
                        <!-- ================ col half ends --============== -->

                        <div class="col-half pro-details">
                          <ul class="detail-list">

                            <li>
                              <h2>Season</h2>
                              <p>@if(!empty($get_product_detail->season )) {{ ucfirst($get_product_detail->season)}} @else N/A @endif</p>
                            </li>  

                            <li>
                              <h2>Retail Price</h2>
                              <!-- <p>@if(!empty($get_product_detail->retail_price )) {{ ucfirst($get_product_detail->retail_price)}} @else N/A @endif</p> -->
                               <?php  if($get_product_detail->currency_code == 'USD'){ ?>
                                <p> @if(!empty($get_product_detail->retail_price )) USD ${{ $get_product_detail->retail_price}} @else N/A @endif</p>
                              <?php } elseif($get_product_detail->currency_code == 'CAD'){ ?>
                                <p> @if(!empty($get_product_detail->retail_price )) CAD ${{ $get_product_detail->retail_price}} @else N/A @endif</p>
                                <?php } elseif($get_product_detail->currency_code == 'CNY'){ ?>
                                <p> @if(!empty($get_product_detail->retail_price )) CNY ¥{{ $get_product_detail->retail_price}} @else N/A @endif</p>
                                <?php } else{ ?>
                                <p> @if(!empty($get_product_detail->retail_price )) USD ${{ $get_product_detail->retail_price}} @else N/A @endif</p>
                              <?php } ?>
                            </li> 

                            <li>
                              <h2>Sart Counter</h2>
                              <p>@if(!empty($get_product_detail->start_counter )) {{ ucfirst($get_product_detail->start_counter)}} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Status</h2>
                              <p>@if(!empty($get_product_detail->status ) && $get_product_detail->status == 1) Active @else Deactivated @endif</p>
                            </li> 

                            <li>
                              <h2>Pass Value</h2>
                              <p>@if(!empty($get_product_detail->pass_value )) {{ $get_product_detail->pass_value }} @else N/A @endif</p>
                            </li> 

                            <li>
                              <h2>Scrap</h2>
                              <p>@if(!empty($get_product_detail->scrapped_status ) && $get_product_detail->scrapped_status == 1) Scrapped @else Not Scrapped @endif</p>
                            </li> 
   
                          </ul>
                        </div> 
                        <!-- ================ col half ends --============== -->


                        <h4 class="card-title divide mt-5"><span>Description</span>  </h4> 
                        <div class="full-des">
                          <p>@if(!empty($get_product_detail->description )) {{ ucfirst($get_product_detail->description)}} @else N/A @endif</p>

                        </div><!-- des ends -->
                  </div>
                </div>
              </div>
            </div>

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Other Product Images</h4>
                    <ul class="imageHolder">
                        @if(!empty($get_product_detail->other_product_image_link ) && count($get_product_detail->other_product_image_link) > 0)
                            @for($i =0; $i< count($get_product_detail->other_product_image_link); $i++)
                                @if(file_exists($get_product_detail->other_product_image_link[$i]))  
                                <li class="imageView">
                                  <img src="{{  url($get_product_detail->other_product_image_link[$i]) }}" class="changeImage">
                                </li>
                              @endif
                            @endfor
                        @else
                           <li class="imageView">
                            <span>No image Found</span>
                          </li>
                        @endif
                    </ul>
                </div>
            </div>
          </div>
      </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')

   <script type="text/javascript">
      $(document).ready(function(){
          $('#image-gallery').lightSlider({
                gallery:true,
                item:1,
                thumbItem:4,
                slideMargin:0,
                speed:500,
                auto:false,
                loop:true,
                onSliderLoad: function() {
                    $('#image-gallery').removeClass('cS-hidden');
                }  
            });

          $("#editButtonClick").click(function() {
              window.location = "<?php echo url('admin/edit-product/'); ?>"+"/"+"<?php echo $get_product_detail->id ; ?>";
          });

          $("#deleteButtonClick").click(function() {
              window.location = "<?php echo url('admin/action-on-product/'); ?>"+"/"+"<?php echo $get_product_detail->id ; ?>";
          });

          $("#removeButtonClick").click(function() {

            if (confirm("Are you sure?")) {
              window.location = "<?php echo url('admin/remove-product/'); ?>"+"/"+"<?php echo $get_product_detail->id ; ?>";
            }
            return false;

          });

          


      });
     
   </script>
@endsection
