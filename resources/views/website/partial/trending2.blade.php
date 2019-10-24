<div class="index-block product-carousel">
    <div class="container">

        <div class="row">
            <div class="innerdiv">
                <div class="section-title title-center">
                    <h2>@lang('home.Featured Products')<br/>
                        <small>@lang('home.Most popular shoes sold on HypeX')</small>
                    </h2>
                </div>
                <div class="item">
                    <ul id="responsive" class="f-collections content-slider">

                        @foreach($relatedProducts as $key=> $otherProducts)

                            @php
                                $relatedImages = $otherProducts['product_images'];
                                $otherImages = explode(',',$relatedImages);
                                $relatedImage = current($otherImages);
                            @endphp

                            
                                <li>
                                    <a href="{{ url('product-detail').'/'.base64_encode($otherProducts['id']) }}">
                                        <div class="product-grid">
                                            <div class="product-thumb"><img src="{{ url($relatedImage) }}" alt="" /></div>
                                            <div class="product-thumb-info">
                                                <h3 class="brand-title">{{$otherProducts['product_brand']['brand_name']}}</h3>
                                                <h2 class="product-title">{{ str_limit($otherProducts['product_name'],30) }}</h2>
                                                {{--<div class="product-price"><span><strong>{{$otherProducts['retail_price']}}</strong></span><span>{{$otherProducts['start_counter']}} Sold</span></div>--}}
                                                <div class="product-price">
                                                <span>
                                                    @if(Session::get('currencyCode') != '')
                                                        <strong>{{Session::get('currencyCode')}} {{App\Helpers\WebHelper::currencyConversion(Session::get('currencyCode'), $otherProducts['currency_code'], $otherProducts['retail_price'])}}</strong>
                                                    @else
                                                        <strong>{{$otherProducts['currency_code']}} {{$otherProducts['retail_price']}}</strong>
                                                    @endif
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                           

                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>