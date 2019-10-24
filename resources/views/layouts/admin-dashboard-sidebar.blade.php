<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        @php
          $name = ucfirst(Auth::user()->first_name).' '.Auth::user()->last_name;
          $image = !empty(Auth::user()->image) ? asset(Auth::user()->image) : asset('v1/admin/images/faces-clipart/user-dummy.png');
        @endphp
        <div class="nav-profile-image">
          <img src="{{ $image  }}" alt="profile">
          <span class="login-status online"></span> <!--change to offline or busy as needed-->              
        </div>
        <div class="nav-profile-text d-flex flex-column">

          <span class="font-weight-bold mb-2">{{ $name  }}</span>
          <!-- <span class="text-secondary text-small">Project Manager</span> -->
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ url('admin/dashboard') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link " data-toggle="collapse" href="#general-pages" aria-expanded="false" aria-controls="general-pages" >
        <span class="menu-title">Product Management</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-medical-bag menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "category-list" || Request::segment(2) == 'add-category' || Request::segment(2) == 'edit-category' || Request::segment(2) == 'each-category-detail' || Request::segment(2) == "brand-list" || Request::segment(2) == 'add-brand' || Request::segment(2) == 'edit-brand' || Request::segment(2) == 'each-brand-detail' || Request::segment(2) == "product-list" || Request::segment(2) == 'add-new-product' || Request::segment(2) == 'edit-product' || Request::segment(2) == 'each-product-detail' || Request::segment(2) == 'vip-product-list' || Request::segment(2) == 'deactivated-product-list') echo 'show'; ?>" id="general-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ url('admin/new-product-import') }}">Product Import </a></li>

          <li class="nav-item"> <a class="nav-link  <?php if(Request::segment(2) == "product-list" || Request::segment(2) == 'add-new-product' || Request::segment(2) == 'edit-product' || Request::segment(2) == 'each-product-detail') echo 'active'; ?>" href="{{ url('admin/product-list') }}">All Product List </a></li>
          
          <li class="nav-item"> <a class="nav-link  <?php if(Request::segment(2) == "deactivated-product-list") echo 'active'; ?>" href="{{ url('admin/deactivated-product-list') }}">Deactivated Products</a></li>
          
          <li class="nav-item"> <a class="nav-link  <?php if(Request::segment(2) == "vip-product-list") echo 'active'; ?>" href="{{ url('admin/vip-product-list') }}">VIP Products</a></li>

          <li class="nav-item"> <a class="nav-link  <?php if(Request::segment(2) == "brand-list" || Request::segment(2) == 'add-brand' || Request::segment(2) == 'edit-brand' || Request::segment(2) == 'each-brand-detail') echo 'active'; ?>" href="{{ url('admin/brand-list') }}">Brand List </a></li>

          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "category-list" || Request::segment(2) == 'add-category' || Request::segment(2) == 'edit-category' || Request::segment(2) == 'each-category-detail') echo 'active'; ?>" href="{{ url('admin/category-list') }}">Category List </a></li>
        </ul>
        </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#user-pages" aria-expanded="false" aria-controls="user-pages">
        <span class="menu-title">User Management</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "user-list" || Request::segment(2) == 'edit-user-detail' || Request::segment(2) == 'each-user-detail') echo 'show'; ?>" id="user-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "user-list" || Request::segment(2) == 'edit-user-detail' || Request::segment(2) == 'each-user-detail') echo 'active'; ?>" href="{{ url('admin/user-list') }}">User List </a></li>

        </ul>
        </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#stats-pages" aria-expanded="false" aria-controls="stats-pages">
        <span class="menu-title">Stats Management</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "highestbid") echo 'show'; ?>" id="stats-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "highestbid") echo 'active'; ?>" href="{{ url('admin/highestbid') }}">Product Stats</a></li>
        </ul>
        </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#order-pages" aria-expanded="false" aria-controls="order-pages">
        <span class="menu-title">Order Management</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "order-list" || Request::segment(2) == 'order-rejected' || Request::segment(2) == 'each-order-detail' ||  Request::segment(2) == 'order-history' ) echo 'show'; ?>" id="order-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "order-list") echo 'active'; ?>" href="{{ url('admin/order-list') }}">Order List </a></li>
          
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "vip-order-list") echo 'active'; ?>" href="{{ url('admin/vip-order-list') }}">VIP Order List </a></li>
          
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == 'order-history') echo 'active'; ?>" href="{{ url('admin/order-history') }}">Completed Orders</a></li>
          
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == 'order-rejected') echo 'active'; ?>" href="{{ url('admin/order-rejected') }}">Rejected Orders</a></li>
          
        </ul>
        </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#shipping-pages" aria-expanded="false" aria-controls="shipping-pages">
        <span class="menu-title">Shipping Management</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "shipping-list" || Request::segment(2) == 'edit-shipping-detail' || Request::segment(2) == 'each-shipping-detail') echo 'show'; ?>" id="shipping-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "shipping-list" || Request::segment(2) == 'edit-shipping-detail' || Request::segment(2) == 'each-shipping-detail') echo 'active'; ?>" href="{{ url('admin/shipping-list') }}">Shipping List </a></li>
        </ul>
        </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#plans-pages" aria-expanded="false" aria-controls="plans-pages">
        <span class="menu-title">Subscription Plans </span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "plans-list" || Request::segment(2) == 'edit-plans-detail' || Request::segment(2) == 'add-plan') echo 'show'; ?>" id="plans-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "add-plan" ) echo 'active'; ?>" href="{{ url('admin/plans/add-plan') }}">Add Plan </a></li>
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "plans-list" ) echo 'active'; ?>" href="{{ url('admin/plans-list') }}">Plan List </a></li>
        </ul>
        </div>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#vip-sales" aria-expanded="false" aria-controls="vip-sales">
        <span class="menu-title">VIP Sale Counter</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "vip-list" || Request::segment(2) == 'edit-vip-sale') echo 'show'; ?>" id="vip-sales">
      
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "vip-sale-list" ) echo 'active'; ?>" href="{{ url('admin/vip-sale-list') }}">VIP Sale </a></li>
        </ul>
      
       </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#currency" aria-expanded="false" aria-controls="currency">
        <span class="menu-title">Currency</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "currency-list" || Request::segment(2) == 'edit-currency') echo 'show'; ?>" id="currency">
      
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "currency-list" ) echo 'active'; ?>" href="{{ url('admin/currency-list') }}">Currency </a></li>
        </ul>
      
       </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="collapse" href="#users-pages" aria-expanded="false" aria-controls="users-pages">
        <span class="menu-title">Subscribed Users </span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
      <div class="collapse <?php if(Request::segment(2) == "users-list" || Request::segment(2) == 'each-sub-users-detail') echo 'show'; ?>" id="users-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link <?php if(Request::segment(2) == "users-list" ) echo 'active'; ?>" href="{{ url('admin/subscribed/users-list') }}">Users List </a></li>
        </ul>
        </div>
    </li>
    
     <li class="nav-item">
      <a class="nav-link" href="{{ url('admin/logout') }}">
        <span class="menu-title">Logout</span>
        <i class="mdi mdi-logout menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>
 <!-- partial -->
