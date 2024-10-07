@extends('dashboard.layout.app')
@section('title', 'Dashboard - employees')
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
                            <h5 class="card-title" style="width: 55%;">Employees</h5>
                            <form id="searchForm" class="search-bar" style="margin-bottom:1%;margin-right:20px;margin-left:0px;"method="post" action="{{ route('users') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control" placeholder="Enter keywords" name="search">
                                <a href="javascript:void(0);" id="submitForm"><i class="icon-magnifier"></i></a>
                            </form>
                            @can('create users')
                            <a  class="btn btn-light px-5" style="margin-bottom:1%; " href="{{route('add.user')}}">create</a>
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
                              <th scope="col">Email</th>
                              <th scope="col">Phone Number</th>
                              <th scope="col">Salary</th>
                              <th scope="col">Manager Name</th>
                              <th scope="col">Role</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(!empty($all_users) && $all_users->count())
                            @foreach($all_users as $user)
                              <tr>
                                <td><span class="user-profile"><img @if(getFirstMediaUrl($user,$user->avatarCollection)!=null) src="{{getFirstMediaUrl($user,$user->avatarCollection)}}" @else src="{{asset('logos/user_logo.png')}}" @endif class="img-circle" alt="user avatar"></span> {{$user->first_name}} {{$user->last_name}}</td>
                                <td>{{$user->email}}</td>
                               
                                <td>{{$user->phone}}</td>
                                <td>{{$user->salary}}</td>
                                <td>{{$user->manager_name}}</td>
                                <td>{{$user->roles->first()->name}}</td>
                                
                                <td>
                                  
                                  
                                  @can('edit users')
                                  <a href="{{url('/user/edit/'.$user->id)}}" style="margin-right: 1rem;">
                                    <span  class="bi bi-pen" style="font-size: 1rem; color: rgb(255,255,255);"></span>
                                  </a>
                                  @endcan
                                  @can('delete users')
                                  <a href="{{url('/user/delete/'.$user->id)}}">
                                    <span class="bi bi-trash" style="font-size: 1rem; color: rgb(255,255,255);"></span>
                                  </a>
                                  @endcan
                                 
                                  
                                </td>
                              </tr>
                            @endforeach
                          @else
                              <tr>
                                <td>There are no Employees.</td>
                              </tr>
                          @endif
                          </tbody>
                        </table>
                        <div style="text-align: center;">
                        {!! $all_users->appends(['search' => request('search')])->links("pagination::bootstrap-4") !!}
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
@endpush
