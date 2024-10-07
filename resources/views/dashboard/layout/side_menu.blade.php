<div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
    <div class="brand-logo">
      <a href="{{url('/tasks')}}">
        <img src="{{asset('dashboard/assets/assets/imgs/logo.webp')}}" class="logo-icon" alt="logo icon"style=" border-radius:50%;">
        <h5 class="logo-text">Dashboard</h5>
      </a>
    </div>
    <ul class="sidebar-menu do-nicescrol">
      <li class="sidebar-header">MAIN NAVIGATION</li>
      
      @can('show users')
      <li>
        <a href="{{url('/users')}}">
          <i class="zmdi zmdi-view-dashboard"></i> <span>Users</span>
        </a>
      </li>
      @endcan
      @can('show projects')
      <li>
        <a href="{{url('/projects')}}">
          <i class="zmdi zmdi-view-dashboard"></i> <span>Projects</span>
        </a>
      </li>
      @endcan
      @can('show departments')
      <li>
        <a href="{{url('/departments')}}">
          <i class="zmdi zmdi-view-dashboard"></i> <span>Departments</span>
        </a>
      </li>
      @endcan
      @can('show tasks')
      <li>
        <a href="{{url('/tasks')}}">
          <i class="zmdi zmdi-view-dashboard"></i> <span>Tasks</span>
        </a>
      </li>
      @endcan

    </ul>

</div>