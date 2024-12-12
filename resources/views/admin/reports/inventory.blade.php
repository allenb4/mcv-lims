@extends('layouts.app')

@section('title')
    {{__('Inventory report')}}
@endsection

@section('breadcrumb')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">
          <i class="nav-icon fas fa-chart-bar"></i>
          {{__('Reports')}}
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Home')}}</a></li>
          <li class="breadcrumb-item active">{{__('Inventory report')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="card card-primary">
  <!-- card-header -->
  <div class="card-header">
    <h3 class="card-title">{{__('Inventory report')}}</h3>
  </div>
  <!-- /.card-header -->
  <!-- card-body -->
  <div class="card-body">

    <!-- Filtering Form -->
    <div id="accordion">
      <div class="card card-info">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="btn btn-primary collapsed"
              aria-expanded="false">
              <i class="fas fa-filter"></i> {{__('Filters')}}
          </a>
          <form method="get" action="{{route('admin.reports.inventory')}}">
            <div id="collapseOne" class="panel-collapse in collapse show">
                <div class="card-body">
                    <div class="row">
                        <!-- date range -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <label>{{__('Date range')}}:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" name="date" class="form-control float-right datepickerrange"
                                    @if(request()->has('date')) value="{{request()->get('date')}}" @endif id="date" required>
                            </div>
                        </div>
                        <!-- \date range -->

                        <!-- branches -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>{{__('Branch')}}</label>
                                <select class="form-control" name="branch_id[]" id="branch">
                                    @if(isset($branches))
                                      @foreach($branches as $branch)
                                        <option value="{{$branch['id']}}" selected>{{$branch['name']}}</option>
                                      @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <!-- \branches -->
                    </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cog"></i>
                    {{__('Generate')}}
                  </button>
                </div>
            </div>
          </form>
      </div>
    </div>
    <!-- Filtering Form -->

    @if(request()->has('date'))
    <div class="card card-primary card-tabs">
      <div class="card-header p-0">
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-one-purchases-tab" data-toggle="pill" href="#custom-tabs-one-purchases" role="tab" aria-controls="custom-tabs-one-purchases" aria-selected="false">{{__('Purchases')}}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-adjustments-tab" data-toggle="pill" href="#custom-tabs-one-adjustments" role="tab" aria-controls="custom-tabs-one-adjustments" aria-selected="false">{{__('Adjustments')}}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-transfers-tab" data-toggle="pill" href="#custom-tabs-one-transfers" role="tab" aria-controls="custom-tabs-one-transfers" aria-selected="false">{{__('Transfers')}}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-consumptions-tab" data-toggle="pill" href="#custom-tabs-one-consumptions" role="tab" aria-controls="custom-tabs-one-consumptions" aria-selected="false">{{__('Consumptions')}}</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="custom-tabs-one-tabContent">
          <div class="tab-pane fade active show" id="custom-tabs-one-purchases" role="tabpanel" aria-labelledby="custom-tabs-one-purchases-tab">
            <div class="row">
              <div class="col-lg-12 table-responsive">
                <table class="table table-striped table-bordered datatable">
                  <thead>
                    <tr>
                      <th>{{__('Branch')}}</th>
                      <th>{{__('Supplier')}}</th>
                      <th>{{__('Product')}}</th>
                      <th>{{__('Unit price')}}</th>
                      <th>{{__('Quantity')}}</th>
                      <th>{{__('Total price')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($purchase_products as $purchase)
                      <tr>
                        <td>
                          {{$purchase['purchase']['branch']['name']}}
                        </td>
                        <td>
                          {{$purchase['purchase']['supplier']['name']}}
                        </td>
                        <td>
                          {{$purchase['product']['name']}}
                        </td>
                        <td>
                          {{formated_price($purchase['price'])}}
                        </td>
                        <td>
                          {{$purchase['quantity']}}
                        </td>
                        <td>
                          {{formated_price($purchase['total_price'])}}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>  
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="custom-tabs-one-adjustments" role="tabpanel" aria-labelledby="custom-tabs-one-adjustments-tab">
            <div class="row">
              <div class="col-lg-12 table-responsive">
                <table class="table table-striped table-bordered datatable">
                  <thead>
                    <tr>
                      <th>{{__('Branch')}}</th>
                      <th>{{__('Type')}}</th>
                      <th>{{__('Product')}}</th>
                      <th>{{__('Quantity')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($adjustment_products as $adjustment)
                      <tr>
                        <td>
                          {{$adjustment['adjustment']['branch']['name']}}
                        </td>
                        <td>
                          @if($adjustment['type']==1)
                            <span class="badge badge-success">
                              {{__('In stock')}}
                            </span>
                          @else 
                            <span class="badge badge-danger">
                              {{__('Out stock')}}
                            </span>
                          @endif
                        </td>
                        <td>
                          {{$adjustment['product']['name']}}
                        </td>
                        <td>
                          {{$adjustment['quantity']}}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="custom-tabs-one-transfers" role="tabpanel" aria-labelledby="custom-tabs-one-transfers-tab">
            <div class="row">
              <div class="col-lg-12 table-responsive">
                <table class="table table-striped table-bordered datatable">
                  <thead>
                    <tr>
                      <th>{{__('From branch')}}</th>
                      <th>{{__('To branch')}}</th>
                      <th>{{__('Product')}}</th>
                      <th>{{__('Quantity')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($transfer_products as $transfer)
                      <tr>
                        <td>
                          {{$transfer['from_branch']['name']}}
                        </td>
                        <td>
                          {{$transfer['to_branch']['name']}}
                        </td>
                        <td>
                          {{$transfer['product']['name']}}
                        </td>
                        <td>
                          {{$transfer['quantity']}}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="custom-tabs-one-consumptions" role="tabpanel" aria-labelledby="custom-tabs-one-consumptions-tab">
            <div class="row">
              <div class="col-lg-12 table-responsive">
                <table class="table table-striped table-bordered datatable">
                  <thead>
                    <tr>
                      <th>{{__('Branch')}}</th>
                      <th>{{__('Test')}}</th>
                      <th>{{__('Product')}}</th>
                      <th>{{__('Quantity')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($consumption_products as $consumption)
                      <tr>
                        <td>
                          {{$consumption['branch']['name']}}
                        </td>
                        <td>
                          {{$consumption['testable']['name']}}
                        </td>
                        <td>
                          {{$consumption['product']['name']}}
                        </td>
                        <td>
                          {{$consumption['quantity']}}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
    @endif

   
  </div>
  <!-- /.card-body -->
</div>

@endsection
@section('scripts')
    <script src="{{url('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{url('js/admin/inventory_report.js')}}"></script>
@endsection