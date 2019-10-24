@extends('layouts.admin-layout')

@section('content')
<?php  //echo '<pre>'; print_r($plan); exit();  ?> 
          <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
            Subscription Plan Edit 
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/plans-list') }}">Subscription Mangement</a></li>
                <li class="breadcrumb-item active" aria-current="page">Subscription Plan Edit</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Plan Info</h4>
                  
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

                  <form class="forms-sample" method="post" action="{{ url('admin/plans/edit-plans-detail/'.$plan['id'])}}">
                    @csrf

                    <div class="form-group">
                      <label for="exampleInputName1">Duration</label>
                      <select class="form-control selectpicker" id="selectDuration" required data-live-search="true"  name="duration">
                          <option value="monthly" @if($plan['duration'] == 'monthly') selected @endif>Monthly</option>
                          <option value="quarterly" @if($plan['duration'] == 'quarterly') selected @endif>Quarterly</option>
                          <option value="annually" @if($plan['duration'] == 'annually') selected @endif>Annually</option>
                        </select>
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Plan Name</label>
                      <input type="text" class="form-control" name="title" required id="exampleInputName1" value="@if(!empty(old('title'))) {{ old('title') }} @else {{ $plan->title }} @endif" placeholder="Plan Name">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">First Feature </label>
                      <input type="text" class="form-control" name="feature_1" required id="exampleInputName1" value="@if(!empty(old('feature_1'))) {{ old('feature_1') }} @else {{ $plan->feature_1 }} @endif" placeholder="First Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Second Feature </label>
                      <input type="text" class="form-control" name="feature_2" required id="exampleInputName1" value="@if(!empty(old('feature_2'))) {{ old('feature_2') }} @else {{ $plan->feature_2 }} @endif" placeholder="Second Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Third Feature </label>
                      <input type="text" class="form-control" name="feature_3" id="exampleInputName1" value="@if(!empty(old('feature_3'))) {{ old('feature_3') }} @else {{ $plan->feature_3 }} @endif" placeholder="Third Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Fourth Feature </label>
                      <input type="text" class="form-control" name="feature_4" id="exampleInputName1" value="@if(!empty(old('feature_4'))) {{ old('feature_4') }} @else {{ $plan->feature_4 }} @endif" placeholder="Fourth Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Price </label>
                      <input type="text" min="0" step="0.01" class="form-control" name="price" required id="exampleInputName1" value="@if(!empty(old('price'))) {{ old('price') }} @else {{ $plan->price }} @endif" placeholder="Price">
                    </div>

                    
                    
                 <div class="form-group row">
                    <label for="status" class="col-sm-12">Status</label>
                    <div class="col-sm-3">
                      <div class="form-check ">
                            <label class="form-check-label ">
                              <input type="radio" name="status" value="1" class="form-check-input" id="status" @if($plan->status == 1) checked  @endif>
                              Active
                            </label>
                      </div>
                      </div>
                      <div class="col-sm-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input"  name="status" value="2" id="status" @if($plan->status != 1) checked  @endif>
                              Deactive
                            </label>
                          </div>
                      </div>    
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

@section('js_content')

   <script type="text/javascript">
      $(document).ready(function(){
          
      });
     
   </script>
@endsection