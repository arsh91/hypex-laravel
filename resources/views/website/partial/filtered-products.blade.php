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
                                <div class="product-thumb"><img src="{{ url('/').'/'.$mainImage }}"
                                                                class="img-responsive"></div>
                                <div class="product-thumb-info">
                                    <h3 class="brand-title">{{ $singleProduct['product_brand']['brand_name'] }}</h3>
                                    <h4 class="product-title">{{ str_limit($singleProduct['product_name'],30) }}</h4>
                                    <div class="product-price"><span><strong>{{$singleProduct['retail_price']}}</strong></span><span>{{$singleProduct['start_counter']}} @lang('home.Sold')</span>
                                    </div>
                                </div>
                            </div>

                        </a>


                    <!--a href="{{ url('product-detail').'/'.base64_encode($singleProduct['id']) }}">
                               <div class="adidasimg" style="">
                                    @if ($mainImage)
                        <img src="{{ url('/').'/'.$mainImage }}" class="img-responsive" style="max-width: 100%;height: 80px;vertical-align: middle;margin:0 auto">
                                    @else
                        <img src="{{ url('public/v1/website/uploads/product/1541763499422945.png') }}" class="img-responsive" style="width: 100%;height: 80px;">
                                    @endif
                            </div>
                        <p>{{ $singleProduct['product_brand']['brand_name'] }}</p>
                                <p  style="height: 30px;">{{ str_limit($singleProduct['product_name'],30) }}</p>
                                <h4>{{$singleProduct['retail_price']}}</h4>
                            </a-->
                    </li>
                @endif
            @endforeach
        </ul>
        {{ $paginate->links() }}
        @else
            <div>
                <div class="innerdiv">
                    <div class="col-xs-12 col-md-12">
                        <h2>@lang('home.No Data Found')</h2>
                    </div>
                </div>
            </div>
@endif
