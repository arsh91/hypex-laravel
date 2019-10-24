@extends('layouts.admin-layout')

@section('content')
<?php  //echo '<pre>'; print_r($highestbid); exit; ?>    
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>                 
              </span>
              Bids Stats
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
            <div class="col-md-5 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">

              @if(count($highestbid) > 0)
              <a href="{{url('admin/each-product-detail/'.$highestbid[0]['product_id'])}}" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Higest Bid Product
                  </h4>
                  <h4 class="mb-5">Total Bids : {{ count($highestbid) }} Times</h4>
                  <h4 class="mb-5">Product Name : <?php echo wordwrap($highestbid[0]['product']['product_name'],15,'<br>') ?></h4>
                </div>
                </a>
                @else
                <a href="#" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Higest Bid Product
                  </h4>
                  <h4 class="mb-5">Total Bids : N/A Times</h4>
                  <h4 class="mb-5">Product Name : N/A</h4>
                </div>
                </a>
                @endif

              </div>
            </div>
            <div class="col-md-5 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
              @if(count($lowestbid) > 0)
              <a href="{{url('admin/each-product-detail/'.$lowestbid[0]['product_id'])}}" class="btn btn-gradient-info btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Lowest Bid Product</h4>
                 
                  <h4 class="mb-5">Total Bids : {{count($lowestbid)}} Times</h4>
                  <h4 class="mb-5">Product Name : <?php echo wordwrap($lowestbid[0]['product']['product_name'],15,'<br>') ?></h4>
                </div>
                </a>
                @else
                <a href="#" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Lowest Bid Product
                  </h4>
                  <h4 class="mb-5">Total Bids : N/A Times</h4>
                  <h4 class="mb-5">Product Name : N/A</h4>
                </div>
                </a>
                @endif
              </div>
            </div>
        </div>
        <!-- sell product -->
        <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>                 
              </span>
              Sell Stats
            </h3>
            
          </div>
        <div class="row">
            <div class="col-md-5 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
              @if(count($highestsell) > 0)
              <a href="{{url('admin/each-product-detail/'.$highestsell[0]['product_id'])}}" class="btn btn-gradient-info btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Higest Selling Product</h4>
                  
                  <h4 class="mb-5">Total Bids : {{count($highestsell)}} Times</h4>
                  <h4 class="mb-5">Product Name : <?php echo wordwrap($highestsell[0]['product']['product_name'],15,'<br>') ?></h4>
                </div>
                </a>
                @else
                <a href="#" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Higest Selling Product
                  </h4>
                  <h4 class="mb-5">Total Bids : N/A Times</h4>
                  <h4 class="mb-5">Product Name : N/A</h4>
                </div>
                </a>
                @endif

              </div>
            </div>
            <div class="col-md-5 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
              @if(count($lowestsell) > 0)
              <a href="{{url('admin/each-product-detail/'.$lowestsell[0]['product_id'])}}" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Lowest Selling Product</h4>
                  
                  <h4 class="mb-5">Total Bids : {{count($lowestsell)}} Times</h4>
                  <h4 class="mb-5">Product Name : <?php echo wordwrap($lowestsell[0]['product']['product_name'],15,'<br>') ?></h4>
                </div>
                </a>
                @else
                <a href="#" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Lowest Selling Product
                  </h4>
                  <h4 class="mb-5">Total Bids : N/A Times</h4>
                  <h4 class="mb-5">Product Name : N/A</h4>
                </div>
                </a>
                @endif
              </div>
            </div>
        </div>

        <!-- content-wrapper ends -->
        <div class="page-header">
            <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>                 
              </span>
              Order Stats
            </h3>
            
          </div>
        <div class="row">
            <div class="col-md-5 stretch-card grid-margin">
              <div class="card bg-gradient-danger card-img-holder text-white">
              @if(count($highestordered) > 0)
              <a href="{{url('admin/each-product-detail/'.$highestordered[0]['product_id'])}}" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Higest Ordered Product</h4>
                  
                  <h4 class="mb-5">Total Bids : {{count($highestordered)}} Times</h4>
                  <h4 class="mb-5">Product Name : <?php echo wordwrap($highestordered[0]['product']['product_name'],15,'<br>') ?></h4>
                </div>
                </a>
                @else
                <a href="#" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Higest Ordered Product
                  </h4>
                  <h4 class="mb-5">Total Bids : N/A Times</h4>
                  <h4 class="mb-5">Product Name : N/A</h4>
                </div>
                </a>
                @endif
              </div>
            </div>
            <div class="col-md-5 stretch-card grid-margin">
              <div class="card bg-gradient-info card-img-holder text-white">
              @if(count($lowestordered) > 0)
              <a href="{{url('admin/each-product-detail/'.$lowestordered[0]['product_id'])}}" class="btn btn-gradient-info btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>                  
                  <h4 class="font-weight-normal mb-3">Lowest Ordered Product</h4>
                  
                  <h4 class="mb-5">Total Bids : {{count($lowestordered)}} Times</h4>
                  <h4 class="mb-5">Product Name : <?php echo wordwrap($lowestordered[0]['product']['product_name'],15,'<br>') ?></h4>
                </div>
                </a>
                @else
                <a href="#" class="btn btn-gradient-danger btn-sm">
                <div class="card-body">
                  <img src="{{ asset('v1/admin/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                  <h4 class="font-weight-normal mb-3">Lowest Ordered Product
                  </h4>
                  <h4 class="mb-5">Total Bids : N/A Times</h4>
                  <h4 class="mb-5">Product Name : N/A</h4>
                </div>
                </a>
                @endif
              </div>
            </div>
        </div>

@endsection