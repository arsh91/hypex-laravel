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
            <!--<div class="sidebar col-xs-12">
                <h3>Shoes</h3>
            </div> -->
            <div class="sidebar mobile-sidebar col-xs-12 col-sm-3 col-md-3">

                <div class="sidebarMobileView text-right">
                    <button id="filterToggle"><span class="mr-3">Product Filter</span> <i class="fa fa-align-right"></i>
                    </button>
                </div>

                @include('website.partial.sidebar-filter')

                <div id="slideOverlayFilter" class="sidebarOverlay"></div>

            </div>


            <div class="product-listing col-xs-12 col-sm-9 col-md-9">
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
                                                    <div class="product-thumb"><img src="{{ url('/').'/'.$mainImage }}"
                                                                                    class="img-responsive"></div>
                                                    <div class="product-thumb-info">
                                                        <h3 class="brand-title">{{ $singleProduct['product_brand']['brand_name'] }}</h3>
                                                        <h4 class="product-title">{{ str_limit($singleProduct['product_name'],25) }}</h4>
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
                                                            <span>{{$singleProduct['start_counter']}} @lang('home.Sold')</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="innerdiv">
                            <div class="col-xs-12 col-md-12">
                                <h2>@lang('home.No Data Found')</h2>
                            </div>
                        </div>
                    @endif
                    {{ $paginate->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <script type="text/javascript">
        $("#filterToggle").on('click', function () {
            $("#filter-form, #slideOverlayFilter").toggleClass("sideToggle");
        });


        $("#slideOverlayFilter").click(function () {

            $("#filter-form").removeClass('sideToggle');
            $("#slideOverlayFilter").removeClass('sideToggle');
        });

    </script>

@endsection