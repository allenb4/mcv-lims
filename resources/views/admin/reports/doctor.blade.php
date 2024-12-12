@extends('layouts.app')

@section('title')
  {{__('Doctor report')}}
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
          <li class="breadcrumb-item active">{{__('Doctor report')}}</li>
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
    <h3 class="card-title">{{__('Doctor report')}}</h3>
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
          <form method="get" action="{{route('admin.reports.doctor')}}">
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

                        <!-- doctors -->
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div class="form-group">
                                <label>{{__('Doctor')}}</label>
                                <select class="form-control" name="doctor_id" id="doctor" required>
                                    @if(isset($doctor))
                                        <option value="{{$doctor['id']}}" selected>{{$doctor['name']}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <!-- \doctors -->
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
    <div class="printable">

      <!-- Report summary -->
      <div class="card card-primary">
        <div class="card-header">
          <h5 class="card-title">
            {{__('Summary')}}
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-info-box">
              <div class="row">
                <div class="col-3 col-sm-4 col-xs-4">
                  <span class="icon">
                    <span class="text-center">
                      <i class="fa fa-money-bill-wave"></i>
                    </span>
                  </span>
                </div>
                <div class="col-7 col-sm-8 col-xs-8">
                  <h4 class="m-0">
                    {{formated_price($total)}}
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
                      <i class="fa fa-money-bill-wave"></i>
                    </span>
                  </span>
                </div>
                <div class="col-7 col-sm-8 col-xs-8">
                  <h4 class="m-0">
                    {{formated_price($paid)}}
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
                      <i class="fa fa-money-bill-wave"></i>
                    </span>
                  </span>
                </div>
                <div class="col-7 col-sm-8 col-xs-8">
                  <h4 class="m-0">
                    {{formated_price($due)}}
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
      <!-- \Report summary -->
      
      <!-- Report Details -->
      <div class="card card-primary card-tabs">
        <div class="card-header p-0">
          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-tabs-one-invoices-tab" data-toggle="pill" href="#custom-tabs-one-invoices" role="tab" aria-controls="custom-tabs-one-invoices" aria-selected="false">{{__('Invoices')}}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-one-payments-tab" data-toggle="pill" href="#custom-tabs-one-payments" role="tab" aria-controls="custom-tabs-one-payments" aria-selected="false">{{__('Payments')}}</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-one-tabContent">
            <div class="tab-pane fade active show" id="custom-tabs-one-invoices" role="tabpanel" aria-labelledby="custom-tabs-one-invoices-tab">
              <div class="row">
                <div class="col-lg-12 table-responsive">
                  <table class="table table-striped table-bordered datatable">
                    <thead>
                      <tr>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Patient Name')}}</th>
                        <th>{{__('Tests')}}</th>
                        <th>{{__('Subtotal')}}</th>
                        <th>{{__('Discount')}}</th>
                        <th>{{__('Total')}}</th>
                        <th>{{__('Paid')}}</th>
                        <th>{{__('Due')}}</th>
                        <th>{{__('Commission')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($groups as $group)
                      <tr>
                        <td>
                          {{$group['created_at']}}
                        </td>
                        <td>
                          @if(isset($group['patient']))
                          {{$group['patient']['name']}}
                          @endif
                        </td>
                        <td>
                          <ul class="pl-2">
                            @foreach($group['tests'] as $test)
                              <li>{{$test['test']['name']}}</li>
                            @endforeach
                            @foreach($group['cultures'] as $culture)
                              <li>{{$culture['culture']['name']}}</li>
                            @endforeach
                          </ul>
                          @foreach($group['packages'] as $package)
                          <b class="p-0 m-0">
                            {{$package['package']['name']}}
                          </b>
                          <ul class="pl-2">
                            @foreach($package['tests'] as $test)
                              <li>{{$test['test']['name']}}</li>
                            @endforeach
                            @foreach($package['cultures'] as $culture)
                              <li>{{$culture['culture']['name']}}</li>
                            @endforeach
                          </ul>
                          @endforeach
                        </td>
                        <td>{{formated_price($group['subtotal'])}}</td>
                        <td>{{formated_price($group['discount'])}}</td>
                        <td>{{formated_price($group['total'])}}</td>
                        <td>{{formated_price($group['paid'])}}</td>
                        <td>{{formated_price($group['due'])}}</td>
                        <td>{{formated_price($group['doctor_commission'])}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-one-payments" role="tabpanel" aria-labelledby="custom-tabs-one-payments-tab">
              <div class="row">
                <div class="col-lg-12 table-responsive">
                  <table class="table table-striped table-bordered datatable">
                    <thead>
                      <tr>
                        <th width="10px">#</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Amount')}}</th>
                        <th>{{__('Payment method')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($payments as $payment)
                      <tr>
                        <td>{{$payment['id']}}</td>
                        <td>{{date('Y-m-d',strtotime($payment['date']))}}</td>
                        <td>{{formated_price($payment['amount'])}}</td>
                        <td>{{$payment['payment_method']['name']}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- \Report Details -->
    </div>
    @endif
  </div>
  <!-- /.card-body -->

  <!-- card-footer -->
  @if(request()->has('date'))
    <div class="card-footer">
      <a href="{{request()->fullUrl()}}&pdf=true" class="btn btn-danger" target="_blank">
        <i class="fas fa-file-pdf"></i> {{__('PDF')}}
      </a>
    </div>
  @endif
  <!-- /.card-footer -->
</div>

@endsection
@section('scripts')
    <script src="{{url('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{url('js/admin/doctor_report.js')}}"></script>
@endsection