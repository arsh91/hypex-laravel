<form id="filter-form" class="desktopfilter mobileFIlter filterForm" method="get" action="#">
    <input type="hidden" id="csrfToken" name="_token" value="{{ csrf_token() }}">

    <script>

        $(document).ready(function () {
            var CSRF_TOKEN = $("#csrfToken").val();
            var sPageURL = window.location.search.substring(1);
            if (sPageURL) {
                var sURLVariables = sPageURL.split('&');
                for (var i = 0; i < sURLVariables.length; i++) {
                    var sParameterName = sURLVariables[i].split('=');
                    var decodedData = (decodeURIComponent(sParameterName));
                    if (decodedData.indexOf('filters') > -1) {
                        var valueCheckbox = sParameterName[1];
                        $('.check').each(function (e) {
                            if ($(this).val() == valueCheckbox) {
                                $(this).attr("checked", "checked");
                            }
                        });
                    }
                }
            }

            $(".checkbox input:checkbox").change(function (event) {

                event.preventDefault();
                var filters = $(".checkbox input:checkbox:checked").map(function () {
                    return $(this).val();
                }).get();
                console.log(filters);
                var category_id = '<?php echo $category_id ?>';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/') }}/products/products-filter",
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, filters: filters, category_id: category_id},
                    success: function (data) {
                        console.log(data);
                        if (data == 'reset') {
                            //window.location.href = "{{ url('/products/shoes')}}";
                            window.location.href = "{{ url('/category')}}" + '/<?php echo $category_id ?>';
                            // alert(newUrl);
                        } else {
                            $(".row").html(data);
                        }
                    }
                });
            });
        });


        if ($(window).width() < 767) {
            $("body").removeClass("modal-open");
        }
    </script>


    <input type="hidden" id="sort_val" name="sort_by" value="Most Popular">

    <div class="sidebar-filters">
        <div class="leftbar">
            <h4>@lang('home.Brands')</h4>
            @if(count($allBrands) > 0)
                @foreach($allBrands as $key=> $brands)
                    <div class="checkbox">
                        <input class="check" id="<?php echo 'brand-checkbox' . $key;?>" type="checkbox" name="brands[]"
                               value="{{ $brands['id'] }}" data-url="product/get-brand-type"
                               class="search-filter brands-checkbox">
                        <label for="<?php echo 'brand-checkbox' . $key;?>">{{ $brands['brand_name'] }}</label>
                    </div>
                @endforeach
            @endif
            <br>
            <h4>@lang('home.Gender')</h4>
            @if(count($allSizeTypes) > 0)
                @foreach($allSizeTypes as $key=> $sizeTypes)
					<?php $gender = $sizeTypes['size_type']; ?>
                    <div class="checkbox">
                        <input class="check" id="{{ $sizeTypes['size_type'] }}" type="checkbox" name="gender[]"
                               value="{{ $sizeTypes['id'] }}-{{ $sizeTypes['size_type'] }}" class="search-filter">
                        <label for="{{ $sizeTypes['size_type'] }}">{{ __("home.$gender") }}</label>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <input type="hidden" id="sorted_by" name="sorted_by" value="">
</form>