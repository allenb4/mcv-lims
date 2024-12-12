@extends('layouts.app')

@section('title')
{{__('Packages')}}
@endsection

@section('breadcrumb')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">
            <i class="nav-icon fas fa-box"></i>
            {{__('Packages')}}
          </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item active">{{__('Packages')}}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title">{{__('Packages Table')}}</h3>
      @can('create_package')
        <a href="{{route('admin.packages.create')}}" class="btn btn-primary btn-sm float-right">
          <i class="fa fa-plus"></i> {{__('Create')}} 
        </a>
      @endcan
    </div>
   
    <div class="card-body">

      <div class="row">
        <div class="col-lg-12 table-responsive">
          <table id="packages_table" class=" table table-striped table-bordered table-bordered">
            <thead>
              <tr>
                <th width="10px">
                  <input type="checkbox" class="check_all" name="" id="">
                </th>
                <th width="10px">#</th>
                <th>{{__('Name')}}</th>
                <th>{{__('Shortcut')}}</th>
                <th>{{__('Tests')}}</th>
                <th width="100px">{{__('Price')}}</th>
                <th width="100px">{{__('Action')}}</th>
              </tr>
            </thead>
            <tbody>
                
            </tbody>
          </table>

        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>

@endsection
@section('scripts')
  <script>
    var can_delete=@can('delete_package')true @else false @endcan
  </script>
  <script src="{{url('js/admin/packages.js')}}"></script>
@endsection