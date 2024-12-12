@extends('layouts.app')

@section('title')
{{__('Reports')}}
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
          <li class="breadcrumb-item active">{{__('Reports')}}</li>
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
    <h3 class="card-title">{{__('Accounting Report')}}</h3>
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
          <form method="get" action="{{route('admin.reports.accounting')}}">
            <div id="collapseOne" class="panel-collapse in collapse show">
                <div class="card-body">
                        <div class="row">
                            <!-- date range -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label>{{__('Date range')}}:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="date" class="form-control float-right datepickerrange"
                                        @if(request()->has('date')) value="{{request()->get('date')}}" @endif id="date"
                                    required>
                                </div>
                            </div>
                            <!-- \date range -->
    
                            <!-- doctors -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>{{__('Doctor')}}</label>
                                    <select class="form-control" name="doctors[]" id="doctor" multiple>
                                        @if(isset($doctors))
                                        @foreach($doctors as $doctor)
                                        <option value="{{$doctor['id']}}" selected>{{$doctor['name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- \doctors -->
    
                            <!-- tests -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>{{__('Test')}}</label>
                                    <select class="form-control" name="tests[]" id="test" multiple>
                                        @if(isset($tests))
                                        @foreach($tests as $test)
                                        <option value="{{$test['id']}}" selected>{{$test['name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- \tests -->
    
                            <!-- cultures -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>{{__('Culture')}}</label>
                                    <select class="form-control" name="cultures[]" id="culture" multiple>
                                        @if(isset($cultures))
                                        @foreach($cultures as $culture)
                                        <option value="{{$culture['id']}}" selected>{{$culture['name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- \cultures -->
    
                            <!-- packages -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>{{__('Package')}}</label>
                                    <select class="form-control" name="packages[]" id="package" multiple>
                                        @if(isset($packages))
                                        @foreach($packages as $package)
                                        <option value="{{$package['id']}}" selected>{{$package['name']}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- \packages -->
    
                            <!-- contracts -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>{{__('Contract')}}</label>
                                    <select class="form-control" name="contracts[]" id="contract" multiple>
                                        @if(isset($contracts))
                                            @foreach($contracts as $contract)
                                                <option value="{{$contract['id']}}" selected>{{$contract['title']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- \contracts -->
    
                            <!-- contracts -->
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label>{{__('Branch')}}</label>
                                    <select class="form-control" name="branches[]" id="branch" multiple>
                                        @if(isset($branches))
                                            @foreach($branches as $branch)
                                                <option value="{{$branch['id']}}" selected>{{$branch['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <!-- \contracts -->
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
    @if(request()->has('date')||request()->has('doctors')||request()->has('tests')||request()->has('cultures'))

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
                <div class="col-2 col-sm-4 col-xs-4">
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
            <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-secondary-box">
              <div class="row">
                <div class="col-2 col-sm-4 col-xs-4">
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
                    {{__('paid')}}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-primary-box">
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
                    {{formated_price($total_expenses)}}
                  </h4>
                  <span>
                    {{__('Expenses')}}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-sm-12 col-xs-12 mt-4 mb-4 custom-warning-box">
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
                    {{formated_price($total_purchases)}}
                  </h4>
                  <span>
                    {{__('Purchase payments')}}
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
                    {{formated_price($profit)}}
                  </h4>
                  <span>
                    {{__('Profit')}}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- \Report summary -->

       <!-- Report balanace -->
       <div class="card card-primary">
        <div class="card-header">
          <h5 class="card-title">
            {{__('Balance')}}
          </h5>
        </div>
        <div class="card-body p-0 m-0">
          <div class="row">
            <div class="col-lg-12">

              <table class="table table-bordered table-striped m-0">
                <thead>
                  <tr>
                    <th>
                      <h6>
                        {{__('Payment method')}}
                      </h6>
                    </th>
                    <th>
                      <h6>
                        {{__('Income')}}
                      </h6>
                    </th>
                    <th>
                      <h6>
                        {{__('Expense')}}
                      </h6>
                    </th>
                    <th>
                      <h6>
                        {{__('Balance')}}
                      </h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($payment_methods as $payment_method)
                  <tr>
                    <td>{{$payment_method['name']}}</td>
                    <td>
                      {{formated_price($payment_method['income'])}}
                    </td>
                    <td>
                      {{formated_price($payment_method['expense'])}}
                    </td>
                    <td>
                      {{formated_price($payment_method['balance'])}}
                    </td>
                  </tr>
                  @endforeach 
                </tbody>
              </table>

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
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-one-expenses-tab" data-toggle="pill" href="#custom-tabs-one-expenses" role="tab" aria-controls="custom-tabs-one-expenses" aria-selected="false">{{__('Expenses')}}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-one-purchase-payments-tab" data-toggle="pill" href="#custom-tabs-one-purchase-payments" role="tab" aria-controls="custom-tabs-one-purchase-payments" aria-selected="false">{{__('Purchase payments')}}</a>
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
                      <td width="10px">#</td>
                      <th>{{__('Date')}}</th>
                      <th>{{__('Patient Name')}}</th>
                      <th>{{__('Doctor')}}</th>
                      <th>{{__('Contract')}}</th>
                      <th>{{__('Tests')}}</th>
                      <th>{{__('Subtotal')}}</th>
                      <th>{{__('Discount')}}</th>
                      <th>{{__('Total')}}</th>
                      <th>{{__('Paid')}}</th>
                      <th>{{__('Due')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($groups as $group)
                    <tr>
                      <td>
                        {{$group['id']}}
                      </td>
                      <td>
                        {{$group['created_at']}}
                      </td>
                      <td>
                        @if(isset($group['patient']))
                        {{$group['patient']['name']}}
                        @endif
                      </td>
                      <td>
                        @if(isset($group['doctor']))
                        {{$group['doctor']['name']}}
                        @endif
                      </td>
                      <td>
                        @if(isset($group['contract']))
                          {{$group['contract']['title']}}
                        @endif
                      </td>
                      <td>
                        <ul class="pl-2 m-0">
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
                        <ul class="pl-4 m-0">
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
                  <table class="table table-striped  table-bordered datatable">
                    <thead>
                      <tr>
                        <th width="10px">#</th>
                        <th width="10px">#{{__('Invoice')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Amount')}}</th>
                        <th>{{__('Payment method')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($payments as $payment)
                      <tr>
                        <td>
                          {{$payment['id']}}
                        </td>
                        <td>
                          {{$payment['group_id']}}
                        </td>
                        <td>
                          {{$payment['date']}}
                        </td>
                        <td>
                          {{formated_price($payment['amount'])}}
                        </td>
                        <td>
                          {{$payment['payment_method']['name']}}
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-one-expenses" role="tabpanel" aria-labelledby="custom-tabs-one-expenses-tab">
             <div class="row">
               <div class="col-lg-12 table-responsive">
                <table class="table table-striped  table-bordered datatable">
                  <thead>
                    <tr>
                      <th width="10px">#</th>
                      <th>{{__('Category')}}</th>
                      <th>{{__('Date')}}</th>
                      <th>{{__('Amount')}}</th>
                      <th>{{__('Payment method')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                      <td>{{$expense['id']}}</td>
                      <td>{{$expense['category']['name']}}</td>
                      <td>{{date('Y-m-d',strtotime($expense['date']))}}</td>
                      <td>{{formated_price($expense['amount'])}}</td>
                      <td>
                        {{$expense['payment_method']['name']}}
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
               </div>
             </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-one-purchase-payments" role="tabpanel" aria-labelledby="custom-tabs-one-purchase-payments-tab">
              <div class="row">
                <div class="col-lg-12 table-responsive">
                  <table class="table table-striped  table-bordered datatable">
                    <thead>
                      <tr>
                        <th width="10px">#</th>
                        <th width="10px">#{{__('Purchase')}}</th>
                        <th>{{__('Date')}}</th>
                        <th>{{__('Amount')}}</th>
                        <th>{{__('Payment method')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($purchase_payments as $payment)
                      <tr>
                        <td>
                          {{$payment['id']}}
                        </td>
                        <td>
                          {{$payment['purchase_id']}}
                        </td>
                        <td>
                          {{$payment['date']}}
                        </td>
                        <td>
                          {{formated_price($payment['amount'])}}
                        </td>
                        <td>
                          {{$payment['payment_method']['name']}}
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
      </div>
      <!-- \Report Details -->

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
<script src="{{url('plugins/print/jQuery.print.min.js')}}"></script>
<script src="{{url('js/admin/accounting_report.js')}}"></script>
@endsection