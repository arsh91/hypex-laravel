
@foreach($vipSale as $key=> $singleProduct)
    <li>
        @php
            $file='';
            $mainImage = '';
            if(!empty($singleProduct['product_images'])) {
                $file = $singleProduct['product_images'];
                $prodImages = explode(',',$file);
                $mainImage = current($prodImages);
            }else{
                $mainImage = 'dummy.png';
            }
        @endphp
        <a href="{{ url('vip-product-detail').'/'.base64_encode($singleProduct['id']) }}">
            <div class="product-grid">
                <div class="product-thumb"><img src="{{ url('/').'/'.$mainImage }}" class="img-responsive"></div>
                <div class="product-thumb-info">
                    <h3 class="brand-title">{{ $singleProduct['product_brand']['brand_name'] }}</h3>
                    <h4 class="product-title">{{ str_limit($singleProduct['product_name'],20) }}</h4>
                    <div class="product-price"><span><strong>
                        <!-- {{$singleProduct['retail_price']}} -->
                        {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $singleProduct['currency_code'], $singleProduct['retail_price'])}}
                    </strong></span><span>{{$singleProduct['start_counter']}} @lang('home.Sold')</span></div>
                </div>
            </div>
        </a>
    </li> 
@endforeach      