@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
 
<div class="blankPageTable">
    <div class="tableCell">
    	<h1 style="text-align:center;">SOME TECHNICAL ERROR OCCURED, PLEASE CONTACT US AT info@hypxex.com</h1>
    </div>
</div>
@endsection 

@section('scripts')

@endsection