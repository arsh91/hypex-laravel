@extends('layouts.admin-layout')

@section('content')
        <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                Add Plan
              </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('admin/brand-list') }} ">Subscription Plan</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Add Plan</li>
                </ol>
              </nav>
            </div>

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


                  <form class="forms-sample" method="post" action="{{ route('add_plan') }}">
                    @csrf

                   

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Duration</label>
                      <select class="form-control selectpicker" id="selectDuration" required data-live-search="true"  name="duration">
                        <option value="" disabled selected>Select Duration</option>
                          <option value="monthly">Monthly</option>
                          <option value="half-yearly">Half-Yearly</option>
                          <option value="annually">Annually</option>
                        </select>
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Plan Name</label>
                      <input type="text" class="form-control" name="title" required id="exampleInputName1" value="{{ old('title') }}" placeholder="Plan Name">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">First Feature </label>
                      <input type="text" class="form-control" name="feature_1" required id="exampleInputName1" value="{{ old('feature_1') }}" placeholder="First Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Second Feature </label>
                      <input type="text" class="form-control" name="feature_2" required id="exampleInputName1" value="{{ old('feature_2') }}" placeholder="Second Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Third Feature </label>
                      <input type="text" class="form-control" name="feature_3" id="exampleInputName1" value="{{ old('feature_3') }}" placeholder="Third Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Fourth Feature </label>
                      <input type="text" class="form-control" name="feature_4" id="exampleInputName1" value="{{ old('feature_4') }}" placeholder="Fourth Feature">
                    </div>

                    <div class="form-group col-8">
                      <label for="exampleInputName1">Price </label>
                      <input type="number" min="0" step="0.01" class="form-control" name="price" required id="exampleInputName1" value="{{ old('price') }}" placeholder="Price">
                    </div>


                  
                  <div>
                    <button type="submit"  class="btn btn-gradient-primary mr-2">Add</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')

@endsection