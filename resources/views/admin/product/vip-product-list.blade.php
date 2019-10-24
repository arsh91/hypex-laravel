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
                    <input type="text" id="vipsearch" class="adminvipproducts" placeholder="Search..">

                      <table class="table table-bordered table-hover" id="myTable" style="width:100%">
                        <thead>
                          <tr >
                            <th>Product ID</th>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Retail Price</th>
                            <th>Status</th>
                            <th>Release date</th>
                          </tr>
                        </thead>
                        <tbody id="tablebody">

                        @foreach($shoesData['allProducts'] as $key=>$product)
                        
                            <tr data-href="{{url('admin/each-product-detail/'.$product['id'])}}">
                                <td>{{ $product['id'] }}</td>
                                <td>
                                  @if($product['product_images'] != '')
                                  <img src="{{url('/')}}/{{$product['product_image_link'][0]}}" style="width: 36px; height: 36px; border-radius: 100%;"/>
                                  @else
                                  <img src="{{url('/')}}/v1/admin/images/noimage.png" style="width: 36px; height: 36px; border-radius: 100%;"/>
                                  @endif
                                </td>
                                <td>{{ str_limit($product['product_name'],20) }}</td>
                                <td>{{ $product['product_category']['category_name'] }}</td>
                                <td>{{ $product['product_brand']['brand_name'] }}</td>
                                <?php  if($product['currency_code'] == 'USD'){ ?>
                                  <td> USD ${{ $product['retail_price'] }}</td>
                                <?php } elseif($product['currency_code'] == 'CAD'){ ?>
                                  <td> CAD ${{ $product['retail_price'] }}</td>
                                <?php } elseif($product['currency_code'] == 'CNY'){ ?>
                                  <td> CNY ¥{{ $product['retail_price'] }}</td>
                                <?php } else{ ?>
                                  <td> USD ${{ $product['retail_price'] }}</td>
                                <?php } ?>
                              
                                <td>
                                @if($product['status'] == 1)
                                <label class="badge badge-gradient-success">Active</label>
                                @else
                                <label class="badge badge-gradient-danger">Inactive</label>
                                @endif
                                </td>
                                <td><?php echo date("d-m-Y", strtotime($product['release_date'])); ?></td>
                            </tr>
                          
                          @endforeach


                        </tbody>

                      </table>
                    </div>
                    <div id="productpage">
                    {{ $shoesData['paginate']->links() }}
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

        var origin   = window.location.origin;

        $('#newtable').hide();
        
          $("#addButtonClick").click(function() {
              window.location = "<?php echo url('admin/add-new-product'); ?>";
          });

          
          $(".clickable-row").click(function() {
              
          });

          $('#myTable tbody').on('click', 'tr', function () {
              window.location = $(this).data("href");
          } );
      });
     
  </script>
  <script type="text/javascript">
     $(document).ready(function () {
        var typingTimer;                //timer identifier
        var doneTypingInterval = 100;  //time in ms (5 seconds)

        $("#vipsearch").on('keydown', function () {
            clearTimeout(typingTimer);
            if ($('#vipsearch').val()) {
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            }
        });
    });

    function formatDate (input) {
  var datePart = input.match(/\d+/g),
  year = datePart[0].substring(), // get only two digits
  month = datePart[1], day = datePart[2];

  return day+'-'+month+'-'+year;
}
    //user is "finished typing," do something

      function doneTyping() {
        var key = $('#vipsearch').val();
            if (key.length >= 1) {
              $.ajax({
                  url: 'vip-search-product-list/?myInput='+key,
                  type: 'GET',
                  success: function(response){
                      var trHTML = '';
                      var data = response.allProducts;
                      for(var i = 0; i < response.allProducts.length; i++)
                      {   
                          trHTML += 
                          '<tr data-href="'+ origin + '/' + 'admin/each-product-detail' + '/' + data[i].id +'"><td>' + data[i].id + '</td><td>' 
                          + (data[i].product_image_link[0] != ''  ? '<img src=" '+ origin + '/' + data[i].product_image_link[0] + '" style="width: 36px; height: 36px; border-radius: 100%;"/>' : '<img src="' + origin + '/v1/admin/images/noimage.png" style="width: 36px; height: 36px; border-radius: 100%;"/>')+  
                          '</td><td>' + data[i].product_name.substring(0, 19)+ '...' + 
                          '</td><td>' + data[i].product_category.category_name + 
                          '</td><td>' + data[i].product_brand.brand_name + 
                          '</td><td>US $' + data[i].retail_price + '</td><td>'
                          + (data[i].status == 1 ? '<label class="badge badge-gradient-success">Active</label>' : '<label class="badge badge-gradient-danger">Inactive</label>')+  
                          '</td><td>' + formatDate(data[i].release_date_format) + 
                          '</td></tr>';            
                      };
                      if(response.allProducts.length > 0){
                        $('#tablebody').html(trHTML);
                        $('#productpage').css("display","none");
                        
                      }else{
                        var noresult = '<tr><td>No Match Found.</td></tr>';
                        $('#tablebody').html(noresult);
                        $('#productpage').css("display","none");
                      }
                      
                  },
                  error: function(response){
                  },
              });
            }

            if(key.length <= 0){
                $.ajax({
                  url: '/admin/vip-all-product-list/',
                  type: 'GET',
                  success: function(response){
                      var trHTML = '';
                      var data = response.allProducts;
                      for(var i = 0; i < response.allProducts.length; i++)
                      {   
                          trHTML += 
                          '<tr data-href="'+ origin + '/' + 'admin/each-product-detail' + '/' + data[i].id +'"><td>' + data[i].id + '</td><td>' 
                          + (data[i].product_image_link[0] != ''  ? '<img src=" '+ origin + '/' + data[i].product_image_link[0] + '" style="width: 36px; height: 36px; border-radius: 100%;"/>' : '<img src="' + origin + '/v1/admin/images/noimage.png" style="width: 36px; height: 36px; border-radius: 100%;"/>')+  
                          '</td><td>' + data[i].product_name.substring(0, 19) + '...'+ 
                          '</td><td>' + data[i].product_category.category_name + 
                          '</td><td>' + data[i].product_brand.brand_name + 
                          '</td><td>US $' + data[i].retail_price + '</td><td>'
                          + (data[i].status == 1 ? '<label class="badge badge-gradient-success">Active</label>' : '<label class="badge badge-gradient-danger">Inactive</label>')+  
                          '</td><td>' + formatDate(data[i].release_date_format) + 
                          '</td></tr>';            
                      };
                      if(response.allProducts.length > 0){
                        $('#productpage').css("display","block");
                        $('#tablebody').html(trHTML);
                      }
                  },
                  error: function(response){
                  },
              });
              }

        }
    </script>


@endsection