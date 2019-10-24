@extends('layouts.admin-layout')

@section('content')

    @php
        $get_selected_category = $get_category_detail->categoryBrands->pluck('id')->toArray();
    @endphp

    <div class="content-wrapper">

        <div class="page-header">
            <h3 class="page-title">
                Edit Category
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('admin/category-list') }} ">Category Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
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


                    <form class="forms-sample" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group col-8">
                            <label for="exampleInputName1">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="exampleInputName1"
                                   placeholder="Name" value="{{ $get_category_detail->category_name }}">
                        </div>

                        <div class="form-group col-8">
                            <label>Select Brand</label>
                            <div class="input-group col-xs-12">
                                <select class="form-control selectpicker" data-live-search="true" name="select_brand[]"
                                        multiple>
                                    @if(count($brand_list) > 0)
                                        @foreach($brand_list as $eachBrand)
                                            @if(in_array($eachBrand->id,$get_selected_category))
                                                <option value="{{ $eachBrand->id }}"
                                                        selected="selected"> {{ $eachBrand->brand_name }} </option>
                                            @else
                                                <option value="{{ $eachBrand->id }}">{{ $eachBrand->brand_name }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value="" disabled> No Brand Found</option>
                                    @endif
                                </select>
                                <!-- <span class="input-group-append">
                                  <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                </span> -->
                            </div>
                        </div>

                        <?php
                        $img_cat_selected = !empty(old('category_image')) ? old('category_image') : (isset($get_category_detail->category_image) ? $get_category_detail->category_image : asset('v1/admin/images/faces-clipart/dummyImage.png'));
	                    $img_cat_selected = !empty($img_cat_selected) ?  url($img_cat_selected) : asset('v1/admin/images/faces-clipart/dummyImage.png');
                        ?>

                        <div class="form-group col-8">
                            <div class="image_upload">
                                <input type="file" name="category_image" id="imgupload" class="file-upload-default">
                                <img src="{{ $img_cat_selected }}" class="changeImage" id="myImg">
                                <button class="file-upload-browse" type="button"><i class="mdi mdi-upload"></i></button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gradient-primary mr-2">Update</button>
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

        $(":file").change(function () {
            var selected_img_source = $(this).closest(".image_upload").find(".changeImage");
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    selected_img_source.attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        //Trigger file click event
        $('.file-upload-browse').click(function(){
            var input_file_name = $(this).closest(".image_upload").find("input[type='file']").attr('name');
            $(this).closest(".image_upload").find("input[type='file']").trigger('click');
        });
    </script>
@endsection