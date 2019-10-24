<h4>@lang('home.Brands')</h4>
@php 
	if(!empty($brands)){ 
		$ids = array();
		foreach($brands as $b => $brand){
		@endphp
		<div class="checkbox">
	        <input id="brand-checkbox{{$b}}" type="checkbox" name="brands[]" value="{{$b}}" data-url="{{ url('product/get-brand-type') }}" class="search-filter brands-checkbox">
	        <label for="brand-checkbox{{$b}}">{{ $brand }}</label>
	    </div>
	@php 
 	} 
}
@endphp
