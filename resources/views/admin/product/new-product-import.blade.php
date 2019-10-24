@extends('layouts.admin-layout')

@section('content')
        <div class="content-wrapper">
          <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Basic form elements</h4>
                  <p class="card-description">
                    Basic form elements
                  </p>

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


                  <form class="forms-sample"id="formSubmit" method="post" action="{{ url('admin/new-product-import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      <label>File upload</label>
                      <input type="file" name="file" class="file-upload-default">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled placeholder="Upload file xlsx,xls,ods" name="excel">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                        </span>
                      </div>
                    </div>
                   
                    <button type="submit" id="submitButton" class="btn btn-gradient-primary mr-2">Submit</button>
                  </form>
                </div>
              </div>
            </div>
          
       
        </div>
        <!-- content-wrapper ends -->

@endsection

@section('js_content')
   <script src="{{ asset('v1/admin/js/file-upload.js') }}"></script>
   <script type="text/javascript">
      $(document).ready(function(){
          $("#formSubmit").submit(function(){
              // alert('etfr');
              $("#submitButton").attr("disabled", true);
          });
      });
     
   </script>
@endsection