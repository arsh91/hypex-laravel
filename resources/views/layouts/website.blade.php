<!DOCTYPE html>
<html>

<!-- header -->
@include('elements.website-header')
<!-- /header -->
@if (Route::has('login'))
    <div class="top-right links">
        <div class="container">
            <div class="header-login">
                <ul>
                @auth
                <li class="vipPageLink"><a href="{{ url('/vip-home') }}">@lang('home.VIP Member Features')</a> </li>
                <li class="myAccountLink"><a href="{{ url('/user-account') }}">@lang('home.My Account')</a> </li>

                <li class="langChin"><a href="{{ url('/locale/ch') }}">中文简体</a></li>
                <li class="langEng"><a href="{{ url('/locale/en') }}">ENG</a> </li>
                <li class="logoutLink"><a href="{{ url('/logout') }}">@lang('home.Logout')</a></li>
                <li class="head_dropdown">
                    <select name="currecny" onchange="getCurrency(this);">
                        <option value="" >Select Currency</option>
                        <option value="USD">USD</option>
                        <option value="CAD">CAD</option>
                        <option value="CNY">CNY</option>
                    </select>
                </li>
                <!--p>{{ trans('sentence.welcome')}}</p-->


                @else
                    <li class="vipPageLink"><a href="{{ url('/vip-home') }}">@lang('home.VIP Member Features')</a> </li>
                    <li class="langChin"><a href="{{ url('/locale/ch') }}">中文简体</a></li>
                    <li class="langEng"><a href="{{ url('/locale/en') }}">ENG</a> </li>
                   <select name="currecny" onchange="getCurrency(this);">
                    <option value="">Select Currency</option>
                    <option value="USD">USD</option>
                    <option value="CAD">CAD</option>
                    <option value="CNY">CNY</option>
                </select>
                    <li class="loginLink"><a href="{{ url('/signin') }}">@lang('home.SignIn')</a></li>


                    @if (Route::has('register'))
                        <!-- <a href="{{ url('/signup') }}">@lang('home.SignUp')</a> -->
                    @endif

                    @endauth
                </ul>
            </div>
        </div>

    </div>
    </div>
@endif
<body>


<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>

<!---Change the currecy and add it in session---->
<script type="text/javascript">
    function getCurrency(data) {
        var currVal = $(data).children("option:selected").val();
        if (currVal != '' && currVal != undefined) {
            //store the price values into session
            $.ajax({
                url: "{{ url('/') }}/saveCurrencyToSession",
                type: "GET",
                data: {
                    currencyCode: currVal
                },
                success: function (response) { // What to do if we succeed
                    if (data == "success")
                        console.log(response);
                    if (response != '') {
                        window.location.reload();
                    }
                },
                error: function (response) {
                    alert('Error' + response);
                }
            });
        } else {
            console.log('No need to run ajax');
        }
    }
</script>

<!-- navbar -->
@include('elements.website-navbar')
<!-- /navbar -->

<!-- content-wrapper -->
@yield('website-sidebar')
<!-- /.content-wrapper -->

<!-- content-wrapper -->
@yield('content')
<!-- /.content-wrapper -->

<!-- footer -->
@include('elements.website-footer')
<!-- /footer -->

@yield('scripts')
</body>

</html>

