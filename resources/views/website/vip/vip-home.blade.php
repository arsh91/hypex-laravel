@extends('layouts.website')

@if ($message = Session::get('success'))
<div class="alert-success alert-block" style="text-align: center;margin-top: 5px;">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@section('content')
    <div class="container-fluid">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
    
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
    
        <div class="item active">
            <img src="{{ url('public/v1/website/img/carousel-1.jpg') }}" alt="Los Angeles" style="width:100%;" >
            <div class="carousel-caption">
            </div>
        </div>
    
        <div class="item">
            <img src="{{ url('public/v1/website/img/carousel-2.jpg') }}" alt="Chicago" style="width:100%;">
            <div class="carousel-caption">
            </div>
        </div>
        
        <div class="item">
            <img src="{{ url('public/v1/website/img/carousel-3.jpg') }}" alt="New York" style="width:100%;">
            <div class="carousel-caption">
            </div>
        </div>
    
        </div>
    
        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="fa fa-angle-left"></span>
        <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="fa fa-angle-right"></span>
        <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<!-- CAROUSEL ENDS HERE -->


<div class="index-block graph">
    <div class="container">
        <div class="row">
            <div class="innerdiv"> 
                <!--div class="section-title">
                    <h2>VIP Levels</h2>
                    <p class="pull-right"><a href="">How it works</a></p>
                </div-->
                 <div class="section-title title-center"><h2>VIP LEVELS</h2></div>
                <div class="col-xs-12 col-sm-offset-2 col-sm-8 chart_shadow">
                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="index-block text-center timer_bg">
    <div class="container">
        <div class="row">
            <div class="innerdiv">    
                <!--div class="section-title">
                    <h2 style="color:#fff">BD Sales</h2>
                    <p class="pull-right"><a href="" style="color:#fff">How it works</a></p>
                </div-->
                <div id="clockdiv">
                    <div>
                        <span class="days"></span>
                        <div class="smalltext">Days</div>
                    </div>
                    <div>
                        <span class="hours"></span>
                        <div class="smalltext">Hours</div>
                    </div>
                    <div>
                        <span class="minutes"></span>
                        <div class="smalltext">Minutes</div>
                    </div>
                    <div>
                        <span class="seconds"></span>
                        <div class="smalltext">Seconds</div>
                    </div>
                </div>
                <div class="btnview" style="margin-top:1px">
                    <a href="" class="btn-sell" style="margin-top:10px;">Enter</a>
                </div>
            </div>    
        </div>
    </div>
</div>                


<div class="index-block product-carousel">    
    <div class="container">                        
        <div class="row">
            <div class="innerdiv">
			 <div class="section-title title-center"><h2>VIP STORE</h2></div>
                <!--div class="section-title"><h2>VIP Store</h2><p class="pull-right"><a href="">How it works</a></p></div-->
                <div class="item">
                    <ul id="content-slider" class="content-slider">


                        @foreach($relatedProducts as $key=> $otherProducts)

										@php 
											$relatedImages = $otherProducts['product_images'];
											$otherImages = explode(',',$relatedImages);
											$relatedImage = current($otherImages);
										@endphp
													
										<a href="{{ url('product-detail').'/'.base64_encode($otherProducts['id']) }}">
											<li>
												<div class="product-grid">
													<div class="product-thumb"><img src="{{ url($relatedImage) }}" alt="" /></div>
													<div class="product-thumb-info">
														<h3 class="brand-title">{{$otherProducts['product_brand']['brand_name']}}</h3>
														<h2 class="product-title">{{$otherProducts['product_name']}}</h2>
														<div class="product-price"><span><strong>{{$otherProducts['retail_price']}}</strong></span><span>{{$otherProducts['start_counter']}} Sold</span></div>
													</div>
												</div>
											</li>
										</a>
													
						@endforeach

                    </ul>
                </div>

                <div class="btnview" style="margin-top:1px">
                    <a href="" class="btn-sell">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>

     <!-- / section ends ======================================  -->     
    


    <div class="index-block">    
        <div class="container">                        
            <div class="row">
                <div class="innerdiv">
                    <!-- <div class="section-title"><h2>Other benefits</h2></div> -->
                    <div class="section-title title-center"><h2>Other Benefits</h2></div>
                    <div style="margin:50px 0;">
                        <div class="col-sm-6">
                            <img src="{{ url('public/v1/website/img/benefits.png') }}" alt="" class="img-responsive">
                        </div>
                        <div class="col-sm-6">
                            <h2>Lorem ipsum</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium</p>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
                            <p>At vero eos et accusamus et iusto odio dignissimos</p>
                        </div>
                    </div>

                </div>
            </div><!-- row ends -->
        </div>
    </div>

    <div class="index-block product-carousel">    
        <div class="container">                        
            <div class="row">
                <div class="innerdiv">
                    <div class="btnview" style="margin-top:1px">
                        <a href="" class="btn-sell">BECOME A VIP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>   



@endsection 

@section('scripts')

<script>
    $(document).ready(function(){
        // Activate Carousel
        $("#myCarousel").carousel({interval: 2500});
    
    });
</script>

<!-- countdown timer script -->
<script>
    function getTimeRemaining(endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    total: t,
    days: days,
    hours: hours,
    minutes: minutes,
    seconds: seconds
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  var daysSpan = clock.querySelector(".days");
  var hoursSpan = clock.querySelector(".hours");
  var minutesSpan = clock.querySelector(".minutes");
  var secondsSpan = clock.querySelector(".seconds");

  function updateClock() {
    var t = getTimeRemaining(endtime);

    daysSpan.innerHTML = t.days;
    hoursSpan.innerHTML = ("0" + t.hours).slice(-2);
    minutesSpan.innerHTML = ("0" + t.minutes).slice(-2);
    secondsSpan.innerHTML = ("0" + t.seconds).slice(-2);

    if (t.total <= 0) {
      clearInterval(timeinterval);
    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}

var deadline = new Date(Date.parse(new Date()) + 2 * 24 * 60 * 60 * 1000);
initializeClock("clockdiv", deadline);

</script>


<!-- LINE CHART SCRIPT -->
<script>
    window.onload = function () {
    
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title:{
            // text: "VIP CHART"
        },
        axisY:{
            includeZero: false
        },
        data: [{        
            type: "line",       
            dataPoints: [
                { x: 50 , y: 450 },
                { x: 100 , y: 414 },
                { x: 150 , y: 520 },
                { x: 160 , y: 460 },
                { x: 180 , y: 450 },
                { x: 200 , y: 500 },
                { x: 220 , y: 480 },
                { x: 240 , y: 480 },
                { x: 250 ,y: 410 },
                { x: 280 ,y: 500 },
                { x: 450 ,y: 480 },
                { x: 500 ,y: 510 }
            ]
        }]
    });
    chart.render();
    
    }
    </script>
	
 <script>
         $(document).ready(function() {  
         $("#content-slider").lightSlider({
                loop:true,
                keyPress:true,
                item:4,
                nav:true,
                pager:false,
                slideMargin:20
            });        
            $('#image-gallery').lightSlider({
                gallery:true,
                item:1,
                thumbItem:4,
                slideMargin:0,
                speed:500,
                auto:false,
                loop:true,
                onSliderLoad: function() {
                    $('#image-gallery').removeClass('cS-hidden');
                }  
            });
        });
    </script>

@endsection
