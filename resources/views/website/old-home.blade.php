@extends('layouts.website')

{{-- Web site Title --}}

@section('title') {!! $title !!} :: @parent @endsection

{{-- Content --}}

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
{{-- Content1 --}}
@include('website.partial.mainbanner')
{{-- Content1 --}}

{{-- Content2--}}
@include('website.partial.trending')
{{-- Content2 --}}

{{-- Content3 --}}
@include('website.partial.trending2')
{{-- Content3 --}}

{{-- Content4 --}}
@include('website.partial.trending3')
{{-- Content4 --}}

{{-- Content4 --}}
    <div class="Trending UpcomingR">
        <div class="container">
            <h3>Upcoming Releasing</h3>
            <div class="row">
                @if(count($upcoming_releasing_products) > 0)
                    <div class="innerdiv">
                        @foreach($upcoming_releasing_products as $k=> $releasing_product)
                            @if(count($releasing_product) > 0)
                            <div class="col-sm-6 col-xs-6 col-lg-2 col-md-2">
                                @php
                                    $file='';
                                    if($releasing_product['product_images']) {
                                        $file = $releasing_product['product_images'];
										$prodImages = explode(',',$file);
										$mainImage = current($prodImages);
                                    }
                                @endphp
                                 <!-- <p>{{ $releasing_product['release_date'] }}</p>-->
                                <a href="{{ url('product-detail').'/'.base64_encode($releasing_product['id']) }}">
                                    <div class="adidasimg" style="">
                                    <h5>{{ Carbon\Carbon::parse($releasing_product['release_date'])->format('M.d Y') }}</h5>
                                    @if ($mainImage)
                                        <img src="{{ url('/').'/'.$mainImage }}" class="img-responsive" style="max-width: 100%;height: 80px;vertical-align: middle;margin:0 auto">
                                    @else
                                        <img src="{{ url('public/v1/website/uploads/product/1541763499422945.png') }}" class="img-responsive" style="width: 100%;height: 80px;">
                                    @endif
                                </div>
									<p>{{ $releasing_product['product_brand']['brand_name'] }}</p>
                                    <p style="height: 30px;">{{ str_limit($releasing_product['product_name'],30) }}</p>
                                </a>
                            </div>
                            @endif
                        @endforeach
                        <div class="btnview">
                            <a href="{{ url('/upcoming-list') }}" class="btn-sell" style="margin-top:10px;">View All</a>
                        </div>
                    </div>
            </div>
            @else
                <div> No data are found</div>
            @endif
        </div>
    </div>
{{-- Content4 --}}
@endsection