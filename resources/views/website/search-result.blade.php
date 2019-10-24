@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
    <div class="Trending">    
    <div class="container">
                <div class="index-block search-block-result-header">    
                     
                        <div class="searchResultLeft">
                           <h2> {{count($allProducts)}} results for "{{Session::get('search_keyword')}}"</h2>
                        </div>
                        <!-- <div class="searchResultRight">
                            <label>Show Products</label>
                          <select>
                            <option>20</option>
                            <option>30</option>
                            <option>40</option>
                            <option>50</option>
                          </select>
                        </div> -->

                    
                </div> 


                <!-- ============== pagination ================= -->

               <!--  <div class="index-block search-page-pagination">    
                    <div class="container text-center">  
                         <ul class="pagination">
                          <li><a href="#">1</a></li>
                          <li class="active"><a href="#">2</a></li>
                          <li><a href="#">3</a></li>
                          <li><a href="#">4</a></li>
                          <li><a href="#">5</a></li>
                        </ul> 
                    </div>
                </div>
               --> 
        
		<div class="product-listing col-xs-12 col-sm-12 col-md-12">
        <div class="row">
            @if(count($allProducts) > 0)
                <div class="innerdiv">
            <ul class="productItemListing">
                    @foreach($allProducts as $k=> $singleProduct)
                        @if(isset($singleProduct) && count($singleProduct) > 0)
                        <li class="proItem col-xs-6 col-sm-4 col-md-4 col-lg-3">
                            @php
                                $file='';
                                if(!empty($singleProduct['product_images'])) {
                                    $file = $singleProduct['product_images'];
									$prodImages = explode(',',$file);
									$mainImage = current($prodImages);
                                }
                            @endphp

							
						<a href="{{ url('product-detail').'/'.base64_encode($singleProduct['id']) }}">
							 
								<div class="product-grid">
								<div class="product-thumb"><img src="{{ url('/').'/'.$mainImage }}" class="img-responsive"></div>
									<div class="product-thumb-info">
										<h3 class="brand-title">{{ $singleProduct['product_brand']['brand_name'] }}</h3>
										<h4 class="product-title">{{ str_limit($singleProduct['product_name'],30) }}</h4>
										<div class="product-price">
                                            <span>
                                                <strong>
                                                     @if(Session::get('currencyCode') != '')
                                                        <strong>{{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $singleProduct['currency_code'], $singleProduct['retail_price'])}}</strong>
                                                    @else
                                                        <strong>{{$singleProduct['currency_code']}} {{$singleProduct['retail_price']}}</strong>
                                                    @endif
                                                </strong>
                                            </span>
                                            <span>{{$singleProduct['start_counter']}} Sold</span></div>
									</div>
								</div>
							 
                        </a>
							

                        </li>
                        @endif
                    @endforeach
</ul>
                </div>
            @else
                    <div class="noProduct"> No product found</div>
            @endif
			{{ $paginate->links() }}
        </div>
		</div>
                            </div>
    
</div>
@endsection 
