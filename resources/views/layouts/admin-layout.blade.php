<!DOCTYPE html>
<html lang="en">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
@include('layouts.admin-header-css')
<body>
  <div class="container-scroller">
    @include('layouts.admin-dashboard-header')
    <div class="container-fluid page-body-wrapper">
      @include('layouts.admin-dashboard-sidebar')
      <div class="main-panel">
         @yield('content')
         @include('layouts.admin-dashboard-footor')
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  @include('layouts.admin-footor-js')
  @yield('js_content')
</body>

</html>
