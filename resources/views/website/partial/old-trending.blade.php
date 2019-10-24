<div class="Trending">
    <div class="container">
        <h3>Most Popular</h3>
        <span>Most popular shoes sold on HypeX</span>
        <div class="row">
            @if(isset($trending_products) && count($trending_products) > 0)
                <div class="innerdiv">
                    @foreach($trending_products as $k=> $trend_product)
                        @if(count($trend_product) > 0)
                        <div class="col-xs-6 col-md-2">
                            @php
                                $file='';
                                if(!empty($trend_product['product_images'])) {
                                    $file = $trend_product['product_images'];
									$prodImages = explode(',',$file);
									$mainImage = current($prodImages);
                                }
                            @endphp
                            <a href="{{ url('product-detail').'/'.base64_encode($trend_product['id']) }}">
                                <div class="adidasimg" style="">
                                    {{--<h5>{{ Carbon\Carbon::parse($trend_product['release_date'])->format('M.d') }}</h5>--}}
                                    @if ($mainImage)
                                        <img src="{{ url('/').'/'.$mainImage }}" class="img-responsive" style="max-width: 100%;height: 80px;vertical-align: middle;margin:0 auto">
                                    @else
                                        <img src="{{ url('public/v1/website/uploads/product/1541763499422945.png') }}" class="img-responsive" style="width: 100%;height: 80px;">
                                    @endif
                                	</div>
                                <p>{{ $trend_product['product_brand']['brand_name'] }}</p>
                                <p style="height: 30px;">{{ str_limit($trend_product['product_name'],30) }}</p>
                              <h4>{{$trend_product['retail_price']}}</h4>
                            </a>
                        </div>
                        @endif
                    @endforeach
                    <div class="btnview">
                        <a href="{{ url('/products/shoes') }}" class="btn-sell">View All</a>
                    </div>
                </div>
            @else
                <div> No data are found</div>
            @endif
        </div>
    </div>
</div>