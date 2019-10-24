<div class="searchlist">
	@php 
	if(!empty($product)){
		foreach($product as $list_product){
		$images = json_decode($list_product['product_images']);
	  @endphp
	<div class="borderbot">
		<div class="row">
			<!-- <div class="col-sm-4 col-xs-4"><img src="{{ url('/public/uploads/product/thumbnail/$images[0]') }} class="img-responsive"></div> -->
			<div class="col-sm-4 col-xs-4">
				<a href="{{ url('sell-detail/').'/'.$list_product['id'] }}"><img src="{{ url('/public/uploads/product/thumbnail').'/'.$images[0] }}" class="img-responsive"></a></div>
			<div class="col-sm-8 col-xs-8">
				<a href="{{ url('sell-detail/').'/'.$list_product['id'] }}">
					<h5>{{ $list_product['brand_name'] }}</h5>
					<p>{{ $list_product['product_name'] }}</p>
				</a>
			</div>
		</div>
	</div>
	@php } } @endphp
</div>