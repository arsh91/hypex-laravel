@extends('layouts.admin-layout')

@section('content')
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              View User Detail
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/user-list') }}">User Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">View User Detail</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                 <!--  <h4 class="card-title divide"><span>View Product</span>   
                    <button type="submit" class="btn btn-gradient-primary mr-2 rightSide">Edit</button>
                  </h4> -->
                    <table class="table ">
                    <thead class="theadnew">
                      <tr>
                        <th width="30%">
                         User Info 
                        </th>
                        <th width="70%">
                          <button id="editButtonClick" type="submit" class="btn btn-gradient-primary mr-2 rightSide">Edit</button>
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                          <td>
                            Full Name
                          </td>
                          <td>
                           @if(!empty($get_user_detail->full_name))  {{ $get_user_detail->full_name }} @else N/A @endif
                          </td>
                      </tr>

                      <tr>
                          <td>
                           User name
                          </td>
                          <td>
                            @if(!empty($get_user_detail->user_name))  {{ $get_user_detail->user_name }} @else N/A @endif
                          </td>
                      </tr>

                     <tr>
                          <td>
                           Email
                          </td>
                          <td>
                            @if(!empty($get_user_detail->email))  {{ $get_user_detail->email }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Phone Number
                          </td>
                          <td>
                            @if(!empty($get_user_detail->phone))  {{ $get_user_detail->phone }} @else N/A @endif 
                          </td>
                      </tr>


                      <tr>
                          <td>
                            City
                          </td>
                          <td>
                            @if(!empty($get_user_detail->city))  {{ $get_user_detail->city }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            State
                          </td>
                          <td>
                            @if(!empty($get_user_detail->state))  {{ $get_user_detail->state }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Country
                          </td>
                          <td>
                            @if(!empty($get_user_detail->country))  {{ $get_user_detail->country }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Postal Code
                          </td>
                          <td>
                           @if(!empty($get_user_detail->postal_code))  {{ $get_user_detail->postal_code }} @else N/A @endif 
                          </td>
                      </tr>

                      <tr>
                          <td>
                            Status
                          </td>
                          <td>
                            @if(!empty($get_user_detail->status) && $get_user_detail->status == 1)  Active @else Deactivate @endif 
                          </td>
                      </tr>
                                
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('js_content')

   <script type="text/javascript">
      $(document).ready(function(){
          $("#editButtonClick").click(function() {
              window.location = "<?php echo url('admin/edit-user-detail/'); ?>"+"/"+"<?php echo $get_user_detail->id ; ?>";
          });
      });
     
   </script>
@endsection