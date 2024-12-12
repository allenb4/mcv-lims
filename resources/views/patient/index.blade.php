@extends('layouts.app')
@section('title')
{{__('Dashboard')}}
@endsection
@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="nav-info-box-icon fas fa-th"></i>
          {{__('Dashboard')}}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">{{__('Dashboard')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@endsection
@section('content')
<!-- Small boxes (Stat box) -->
<div class="card card-primary">
  <div class="card-header">
    <h5 class="card-title">
      {{__('Summary')}}
    </h5>
  </div>
  <div class="card-body">
    <div class="row">
      <!-- ./col -->
      <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-primary-box">
        <div class="row">
          <div class="col-3 col-sm-4 col-xs-4">
            <span class="icon">
              <span class="text-center">
                <i class="fa fa-flask"></i>
              </span>
            </span>
          </div>
          <div class="col-7 col-sm-8 col-xs-8">
            <h4 class="m-0">
              {{$group_tests_count}}
            </h4>
            <span>
              {{__('Reports')}}
            </span>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-success-box">
        <div class="row">
          <div class="col-3 col-sm-4 col-xs-4">
            <span class="icon">
              <span class="text-center">
                <i class="fa fa-check-double"></i>
              </span>
            </span>
          </div>
          <div class="col-7 col-sm-8 col-xs-8">
            <h4 class="m-0">
              {{$done_tests_count}}
            </h4>
            <span>
              {{__('Completed reports')}}
            </span>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-danger-box">
        <div class="row">
          <div class="col-3 col-sm-4 col-xs-4">
            <span class="icon">
              <span class="text-center">
                <i class="fa fa-pause"></i>
              </span>
            </span>
          </div>
          <div class="col-7 col-sm-8 col-xs-8">
            <h4 class="m-0">
              {{$pending_tests_count}}
            </h4>
            <span>
              {{__('Completed reports')}}
            </span>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-primary-box">
        <div class="row">
          <div class="col-3 col-sm-4 col-xs-4">
            <span class="icon">
              <span class="text-center">
                <i class="fa fa-dollar-sign"></i>
              </span>
            </span>
          </div>
          <div class="col-7 col-sm-8 col-xs-8">
            <h4 class="m-0">
              {{formated_price(auth()->guard('patient')->user()->total)}}
            </h4>
            <span>
              {{__('Total')}}
            </span>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-success-box">
        <div class="row">
          <div class="col-3 col-sm-4 col-xs-4">
            <span class="icon">
              <span class="text-center">
                <i class="fa fa-dollar-sign"></i>
              </span>
            </span>
          </div>
          <div class="col-7 col-sm-8 col-xs-8">
            <h4 class="m-0">
              {{formated_price(auth()->guard('patient')->user()->paid)}}
            </h4>
            <span>
              {{__('Paid')}}
            </span>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-danger-box">
        <div class="row">
          <div class="col-3 col-sm-4 col-xs-4">
            <span class="icon">
              <span class="text-center">
                <i class="fa fa-dollar-sign"></i>
              </span>
            </span>
          </div>
          <div class="col-7 col-sm-8 col-xs-8">
            <h4 class="m-0">
              {{formated_price(auth()->guard('patient')->user()->due)}}
            </h4>
            <span>
              {{__('Due')}}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- /.row -->

@endsection
@section('scripts')
<script>
  (function($){
      
      "use strict";
      
      $('#dashboard').addClass('active');
    })(jQuery);
  
</script>
@endsection