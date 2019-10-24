<h4>Product Type</h4>
@php 
	if(!empty($types)){ 
		$ids = array();
		foreach($types as $t => $type){
		@endphp
		<div class="checkbox">
	        <input id="brand-checkbox{{$t}}" type="checkbox" name="type[]" value="{{$t}}" data-url="{{ url('product/get-brand-type') }}" class="search-filter brands-checkbox">
	        <label for="brand-checkbox{{$t}}">{{ $type }}</label>
	    </div>
	@php 
 	} 
}
@endphp
