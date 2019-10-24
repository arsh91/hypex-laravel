<div class="index-block featured-collections">
    <div class="container">

        <!-- <div class="section-title title-center"><h2>Hot Collections</h2></div> -->

        <div style="margin-bottom:50px;" class="section-title title-center"><h2> @lang('home.Hot Categories') </h2>
        </div>


        <div class="row">

            @forelse ($category_details as $category)
                <div class="col-xs-6 col-sm-2 col-md-2 ft-col">
                    <a href="{{ url('category').'/'.base64_encode($category['id']) }}">
                        @if($category['category_image'] == '')
                            <span><img src="{{url('/')}}/public/v1/website/categories/default-category.png"></span>
                        @else
                            <span><img src="{{$category['category_image']}}" alt="{{$category['category_name']}}"></span>
                        @endif
                        <p>{{str_limit($category['category_name'],10)}}</p>
                    </a>
                </div> <!-- collection ends -->
            @empty
                <div class="col-xs-6 col-sm-2 col-md-2 ft-col">No Category Created Yet!</div>
            @endforelse

        </div>
    </div>
</div>