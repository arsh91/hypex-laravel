@extends('layouts.admin-layout')

@section('content')
    <div class="content-wrapper">

        <div class="page-header">
            <h3 class="page-title">
                Add Category
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('admin/category-list') }} ">Category Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Category</li>
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


                    <form class="forms-sample" method="post" action="{{ url('admin/add-category') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group col-8">
                            <label for="exampleInputName1">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="exampleInputName1"
                                   placeholder="Name">
                        </div>

                        <div class="form-group col-8">
                            <label>Select Brand</label>
                            <div class="input-group col-xs-12">
                                <select class="form-control selectpicker" data-live-search="true" name="select_brand[]"
                                        multiple>
                                    @if(count($brand_list) > 0)
                                        @foreach($brand_list as $eachBrand)
                                            <option value="{{ $eachBrand->id }}">{{ $eachBrand->brand_name }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>No Brand Found</option>
                                    @endif
                                </select>
                                <!-- <span class="input-group-append">
                                  <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                </span> -->
                            </div>
                        </div>
                        <div class="form-group col-8">
                            <label for="exampleInputName1">Category Image</label>
                            <input type="file" class="form-control" name="category_image" id="exampleFile1"
                                   placeholder="Category Image">
                        </div>
                        <button type="submit" class="btn btn-gradient-primary mr-2">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

@endsection

@section('js_content')

    <script type="text/javascript">
        $(document).ready(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
@endsection