<nav class="navbar navbar-default">
<div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="modal" data-target="#myModalhed" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="{{url('/')}}"><img src="{{ asset('v1/website/img/logo.png')}}" class="img-responsive"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <form method="get" action="{{ url('search-result') }}" class="top-search navbar-form navbar-left">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" class="form-control" name="search_keyword" value="@php echo !empty($search_keyword) ? $search_keyword : ''; @endphp" placeholder="@lang('home.Search for products')">
        <a href="javascript:void(0);" onclick="top_search(this)"><i class="fas fa-search"></i></a>
        </div>
        
    </form>
    
    <ul class="nav navbar-nav navbar-right">
        <li><a href="{{ url('category').'/'.base64_encode(1) }}">@lang('home.Shoes')</a></li>
        <li><a href="{{ url('category').'/'.base64_encode(3) }}">@lang('home.Streetwear')</a></li>
        <li><a href="{{url('faq')}}">@lang('home.FAQ')</a></li>
        <li><a href="{{ url('category').'/'.base64_encode(2) }}" class="btn-sell">@lang('home.Shop Now')</a></li>
    </ul>
    </div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>

<!-- Modal -->
<div class="modal fade modalnav" id="myModalhed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body">
          <nav class="navbar navbar-default">
            <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="navbar-collapse" id="bs-example-navbar-collapse-1">
                <form method="get" action="{{ url('search-result') }}" class="top-search navbar-form navbar-left">
                    {{ csrf_field() }}
                    <div class="form-group">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search_keyword" value="@php echo !empty($search_keyword) ? $search_keyword : ''; @endphp" placeholder="Search for brand or product... etc">
                        <a href="javascript:void(0);" onclick="top_search(this)"><i class="fas fa-search"></i></a>
                    </div>
                    
                </form>
                
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('category').'/'.base64_encode(1) }}">Shoes</a></li>

                    <li><a href="{{ url('category').'/'.base64_encode(2) }}">Streetwear</a></li>
                    <li><a href="{{url('faq')}}">FAQ</a></li>
                    <li><a href="{{url('search-sell')}}" class="btn-sell">Sell/Offer</a></li>
                </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
            
            <div class="toplogin">
              {{--  <a href="{{url('/signin')}}">Login</a> | <a href="{{url('/signin')}}">Signup</a>--}}

                @if (Route::has('login'))
                    <div class="pull-center">
                        @if (Auth::check())
                            <a href="{{ url('/my-buy-products') }}" >My Account</a> | <a href="{{ url('/signout') }}">Logout</a>
                        @else
							<a href="{{ url('/signin') }}">VIP Members</a> |
                            <a href="{{ url('/signin') }}">Login</a> |
                            <!-- <a href="{{ url('/signin') }}">Signup</a> -->
                        @endif
                    </div>
                @endif
                
            </div>
            
            
      </div>
      
    </div>
  </div>
</div>

<script>
var top_search = function(_this){
    $(_this).closest('form').submit();
}
</script>
