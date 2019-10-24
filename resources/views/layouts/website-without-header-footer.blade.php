<!DOCTYPE html>
<html>

<!-- header -->
        @include('elements.website-header')
<!-- /header -->

<body>
        
	<!-- content-wrapper -->
        @yield('website-sidebar')
    <!-- /.content-wrapper -->
	
    <!-- content-wrapper -->
        @yield('content')
    <!-- /.content-wrapper -->
	
    @yield('scripts')
</body>

</html>
