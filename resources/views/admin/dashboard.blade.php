@extends('layouts.admin-layout')

@section('content')
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>                 
              </span>
              Dashboard
            </h3>
            <nav aria-label="breadcrumb">
              <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                  <span></span>Overview
                  <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
              </ul>
            </nav>
          </div>
          <div class="row">
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Total Users
                    <i class="mdi mdi-chart-line mdi-12px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $userCount }}</h2>
                  <h6 class="card-text">Increased by 60%</h6>
                </div>
              </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Total Products
                    <i class="mdi mdi-bookmark-outline mdi-12px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $productCount }}</h2>
                  <h6 class="card-text">Decreased by 10%</h6>
                </div>
              </div>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>     
                  <h4 class="font-weight-normal mb-3">Total Category
                    <i class="mdi mdi-diamond mdi-12px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $categoryCount }}</h2>
                  <h6 class="card-text">Increased by 5%</h6>
                </div>
              </div>
            </div>
             <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                                    
                  <h4 class="font-weight-normal mb-3">Total Brand
                    <i class="mdi mdi-diamond mdi-12px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $brandCount }}</h2>
                  <h6 class="card-text">Increased by 5%</h6>
                </div>
              </div>
            </div>
          </div>


          <div class="row">
          
          <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                                    
                  <h4 class="font-weight-normal mb-3">VIP Users
                    <i class="mdi mdi-diamond mdi-12px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $subScribedUsersCount }}</h2>
                  <h6 class="card-text">Increased by 1%</h6>
                </div>
              </div>
            </div>
            
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                                    
                  <h4 class="font-weight-normal mb-3">VIP Products
                    <i class="mdi mdi-diamond mdi-12px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $vipProductCount }}</h2>
                  <h6 class="card-text">Increased by 5%</h6>
                </div>
              </div>
            </div>
            
            
            <div class="col-md-3 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Total Orders
                    <i class="mdi mdi-chart-line mdi-12px float-right"></i>
                  </h4>
                  <h2 class="mb-5">{{ $orderCount }}</h2>
                  <h6 class="card-text">Increased by 15%</h6>
                </div>
              </div>
            </div>
            
          </div>
        
        <!--   <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="clearfix">
                    <h4 class="card-title float-left">Visit And Sales Statistics</h4>
                    <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>                                     
                  </div>
                  <canvas id="visit-sale-chart" class="mt-4"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Traffic Sources</h4>
                  <canvas id="traffic-chart"></canvas>
                  <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>                                                      
                </div>
              </div>
            </div>
          </div> -->
        </div>
        <!-- content-wrapper ends -->

@endsection