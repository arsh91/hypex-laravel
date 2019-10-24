@extends('layouts.admin-layout')

@section('content')
    <div class="content-wrapper">

        <div class="page-header">
            <h3 class="page-title">
                Category Detail
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('admin/category-list') }} ">Category Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> Each Category Detail</li>
                </ol>
            </nav>
        </div>


        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <p class="card-description">Category Name: <code>{{ $get_category_detail->category_name }}</code>
                    </p>
                    <p class="card-description">Brand List: </p>
                    @php(
                    $brand_list = $get_category_detail->categoryBrands()->pluck('brand_name')
                    )
                    <ul class="list-ticked">
                        @if(count($brand_list) > 0)
                            @for($i=0; $i < count($brand_list); $i++)
                                <li>{{ $brand_list[$i] }}</li>
                            @endfor
                        @else
                            <li>No Brand Found.</li>
                        @endif
                    </ul>
                    <p class="card-description">Category Image:
                        <div class="image_upload">
                            @if($get_category_detail->category_image)
                                <img src="{{url('/')}}/{{$get_category_detail->category_image}}">
                            @else
                                <img src="{{url('/')}}/public/v1/website/categories/default-category.png">
                            @endif
                        </div>
                    </p>
                    <button id="editButtonClick" type="button" class="btn btn-success btn-fw">Edit</button>
                    @if(!empty($get_category_detail->status ) && $get_category_detail->status == 1)
                        <button id="deleteButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">
                            Deactive
                        </button>
                    @else
                        <button id="deleteButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">
                            Active
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->

@endsection

@section('js_content')

    <script type="text/javascript">
        $(document).ready(function () {
            $("#editButtonClick").click(function () {
                window.location = "<?php echo url('admin/edit-category/'); ?>" + "/" + "<?php echo $get_category_detail->id; ?>";
            });

            $("#deleteButtonClick").click(function () {
                window.location = "<?php echo url('admin/delete-category/'); ?>" + "/" + "<?php echo $get_category_detail->id; ?>";
            });

        });

    </script>
@endsection