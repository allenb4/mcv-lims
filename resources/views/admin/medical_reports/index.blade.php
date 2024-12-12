@extends('layouts.app')

@section('title')
{{ __('Medical reports') }}
@endsection

@section('breadcrumb')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fa fa-flag"></i>
                    {{__('Medical reports')}}
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Home')}}</a></li>
                    <li class="breadcrumb-item active">{{ __('Medical reports') }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ __('Medical reports table') }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <!-- filter -->
        <div id="accordion">
            <div class="card card-info">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="btn btn-primary collapsed" aria-expanded="false">
                <i class="fas fa-filter"></i> {{__('Filters')}}
              </a>
              <div id="collapseOne" class="panel-collapse in collapse">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                           <label for="filter_date">{{__('Date')}}</label>
                           <input type="text" class="form-control" id="filter_date" placeholder="{{__('Date')}}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                           <label for="filter_contract">{{__('Contract')}}</label>
                           <select name="filter_contract" id="filter_contract" class="form-control">
                           </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                           <label for="filter_created_by">{{__('Created by')}}</label>
                           <select name="filter_created_by" id="filter_created_by" class="form-control user_id">
                           </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                           <label for="filter_signed_by">{{__('Signed by')}}</label>
                           <select name="filter_signed_by" id="filter_signed_by" class="form-control user_id">
                           </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                         <label for="filter_status">{{__('Status')}}</label>
                         <select name="filter_status" id="filter_status" class="form-control select2">
                            <option value="" selected>{{__('All')}}</option>
                            <option value="1">{{__('Done')}}</option>
                            <option value="0">{{__('Pending')}}</option>
                         </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                           <label for="filter_barcode">{{__('Barcode')}}</label>
                           <input type="text" class="form-control" id="filter_barcode" placeholder="{{__('Barcode')}}">
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- \filter -->
        <div class="row">
            <div class="col-lg-12 table-responsive">
                <table id="medical_reports_table" class="table table-striped table-bordered"  width="100%">
                    <thead>
                        <tr>
                            <th width="10px">
                                <input type="checkbox" class="check_all" name="" id="">
                            </th>
                            <th width="10px">#</th>
                            <th width="10px">{{__('Created By')}}</th>
                            <th width="10px">{{__('Contract')}}</th>
                            <th width="10px">{{__('Barcode')}}</th>
                            <th width="100px">{{ __('Patient Code') }}</th>
                            <th>{{ __('Patient Name') }}</th>
                            <th width="50px">{{ __('Gender') }}</th>
                            <th width="50px">{{ __('Age') }}</th>
                            <th width="50px">{{ __('Phone') }}</th>
                            <th width="200px">{{ __('Tests') }}</th>
                            <th width="100px">{{ __('Date') }}</th>
                            <th class="text-center" width="10px">{{__('Done')}}</th>
                            <th class="text-center" width="10px">{{__('Signed')}}</th>
                            <th class="text-center" width="10px">{{__('Signed By')}}</th>
                            <th width="50px">{{ __('Action') }}</th>
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
@include('admin.groups.modals.print_barcode')

@endsection
@section('scripts')
    <script>
        var can_delete=@can('delete_medical_report')true @else false @endcan ;
        var can_view=@can('view_medical_report')true @else false @endcan ;
    </script>
    <script src="{{url('js/admin/medical_reports.js')}}"></script>
@endsection