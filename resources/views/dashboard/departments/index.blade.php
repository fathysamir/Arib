@extends('dashboard.layout.app')
@section('title', 'Dashboard - departments')
@section('content')	
<style>
    .pagination{
        display: inline-flex;
    }
</style>
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <div style="display: flex;">
                            <h5 class="card-title" style="width: 55%;">Departments</h5>
                            <form id="searchForm" class="search-bar" style="margin-bottom:1%;margin-right:20px;margin-left:0px;"method="post" action="{{ route('departments') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control" placeholder="Enter keywords" name="search">
                                <a href="javascript:void(0);" id="submitForm"><i class="icon-magnifier"></i></a>
                            </form>
                            @can('create departments')
                            <a  class="btn btn-light px-5" style="margin-bottom:1%; " href="{{route('add.department')}}">create</a>
                            @endcan
                        </div>
                       
                        @if(session('error'))
                        <div id="errorAlert" class="alert alert-danger" style="padding-top:5px;padding-bottom:5px; padding-left: 10px; background-color:brown;border-radius: 20px; color:beige;">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div id="successAlert" class="alert alert-success"style="padding-top:5px;padding-bottom:5px; padding-left: 10px; background-color:green;border-radius: 20px; color:beige;">
                            {{ session('success') }}
                        </div>
                    @endif

                        <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              
                              <th scope="col">Name</th>
                              <th scope="col">Employees Count</th>
                              <th scope="col">Total Salary</th>
                              <th scope="col">Activation</th>

                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(!empty($all_departments) && $all_departments->count())
                            @foreach($all_departments as $department)
                              <tr>
                                <td>{{$department->name}}</td>
                                <td>{{$department->employee_count}}</td>
                                <td>{{$department->total_salary ? $department->total_salary .' EGP':'_'}} </td>
                               
                                <td>@if($department->is_active==1) <span class="badge badge-secondary" style="background-color:rgb(50, 134, 50); width:100%;">Active</span> @else <span class="badge badge-secondary" style="background-color:rgb(255,0,0);width:100%;">Unactive</span> @endif</td>

                                
                                <td>
                                  
                                  
                                @can('edit departments')
                                  <a href="{{url('/department/edit/'.$department->id)}}" style="margin-right: 1rem;">
                                    <span  class="bi bi-pen" style="font-size: 1rem; color: rgb(255,255,255);"></span>
                                  </a>
                                  @endcan
                                  @can('delete departments')
                                  <a href="{{url('/department/delete/'.$department->id)}}">
                                    <span class="bi bi-trash" style="font-size: 1rem; color: rgb(255,255,255);"></span>
                                  </a>
                                  @endcan
                                  
                                </td>
                              </tr>
                            @endforeach
                          @else
                              <tr>
                                <td>There are no Departments.</td>
                              </tr>
                          @endif
                          </tbody>
                        </table>
                        <div style="text-align: center;">
                        
                        {!! $all_departments->appends(['search' => request('search')])->links("pagination::bootstrap-4") !!}
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="overlay toggle-menu"></div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#submitForm').on('click', function() {
            $('#searchForm').submit();
        });
    });
    </script>
    <script>
        // Set a timeout to hide the error or success message after 5 seconds
        setTimeout(function() {
            $('#errorAlert').fadeOut();
            $('#successAlert').fadeOut();
        }, 4000); // 5000 milliseconds = 5 seconds
    </script>
@endpush
