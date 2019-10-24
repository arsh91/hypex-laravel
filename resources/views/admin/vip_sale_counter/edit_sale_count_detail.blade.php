@extends('layouts.admin-layout')

@section('content')
<?php  //echo '<pre>'; print_r($plan); exit();  ?> 
          <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
            UPDATE VIP SALE
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/vip-sale-list') }}">VIEW SALE</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Sale</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">VIP SALE TIMINGS</h4>
                  
                   @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  @if(session()->has('success'))
                      <div class="alert alert-success">
                          {{ session()->get('success') }}
                      </div>
                  @endif

                  <form class="forms-sample" method="post" action="{{url('admin/edit-sale/'.$sale['id'])}}">
                    @csrf
                    
                    
                    <div class="form-group setDateStyle">
                      <label for="saleTime">SET SALE TIME</label><br />
                      <input type="text" name="datetimes" style="width:50%" />
                    </div>

                    
                    <button type="submit" class="btn btn-gradient-primary mr-2">SAVE</button>
            
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')
<script>
$(function() {
  $('input[name="datetimes"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour').add(32, 'hour'),
    locale: {
      format: 'YYYY/MM/DD HH:mm:SS'
    }
  });
});
</script>

@endsection