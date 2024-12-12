<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{url('img/logo.png')}}" alt="AdminLTE Logo" class="brand-image elevation-3">
      <span class="brand-text font-weight-light">{{$info['name']}}</span>
    </a>
    <!-- \Brand Logo -->
    
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            @if(auth()->guard('admin')->check())
              <img src="@if(!empty(auth()->guard('admin')->user()->avatar)){{url('uploads/user-avatar/'.auth()->guard('admin')->user()->avatar)}}@else {{url('img/avatar.png')}} @endif" class="img-circle elevation-2" alt="Avatar">
            @else 
              <img src="@if(!empty(auth()->guard('patient')->user()->avatar)){{url('uploads/patient-avatar/'.auth()->guard('patient')->user()->avatar)}}@else {{url('img/avatar.png')}} @endif" class="img-circle elevation-2" alt="Avatar">
            @endif
          </div>
          <div class="info">
            <a href="#" class="d-block">
              @if(Auth::guard('admin')->check())
                {{Auth::guard('admin')->user()->name}}
              @else 
                {{Auth::guard('patient')->user()->name}}<br>
                {{__('Code')}}: {{Auth::guard('patient')->user()->code}}
              @endif
            </a>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <!-- Admin sidebar -->
        @can('admin')
          @include('partials.admin_sidebar')
        <!-- \Admin sidebar -->
        <!-- Patient sidebar -->
        @else
          @include('partials.patient_sidebar')
        @endcan
        <!-- \Patient sidebar -->
      <!-- /.sidebar-menu -->
    </div>
</aside>
<!-- /.sidebar -->