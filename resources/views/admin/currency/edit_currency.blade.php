@extends('layouts.admin-layout')

@section('content')
<?php  //echo '<pre>'; print_r($plan); exit();  ?> 
          <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
            UPDATE CURRENCY RATE
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/currency-list') }}">VIEW CURRENCY</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Currency</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  
                   @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  @if(session()->has('success'))
                      <div class="alert alert-success">
                          {{ session()->get('success') }}
                      </div>
                  @endif

                  <form class="forms-sample" method="post" action="{{ url('admin/edit-currency/'.$currency['id'])}}">
                    @csrf

                    
                    <div class="form-group col-8">
                      <label for="exampleInputName1">Currency Code</label>
                      <strong><span>{{$currency['currency_code']}}</span></strong>
                      
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Conversion Rate </label>
                      <input type="text" class="form-control" name="conversion_rate" required id="exampleInputName1" value="@if(!empty(old('conversion_rate'))) {{ old('conversion_rate') }} @else {{ $currency->conversion_rate }} @endif">
                    </div>

                    
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
            
                  </form>

                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->

@endsection