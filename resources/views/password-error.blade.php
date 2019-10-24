@extends('layouts.website')

@section('content')
        <div class="flex-center position-ref full-height">
	
				<div style="text-align:center;"> 
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
				</div>
			
        </div>
@endsection 
