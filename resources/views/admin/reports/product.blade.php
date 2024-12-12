@extends('layouts.app')

@section('title')
  {{__('Products report')}}
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
          <li class="breadcrumb-item active">{{__('Products report')}}</li>
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
    <h3 class="card-title">{{__('Products report')}}</h3>
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
          <form method="get" action="{{route('admin.reports.product')}}">
            <div id="collapseOne" class="panel-collapse in collapse show">
                <div class="card-body">
                  <div class="row">
                      <!-- Branches -->
                      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                          <div class="form-group">
                              <label>{{__('Branch')}}</label>
                              <select class="form-control" name="branch_id[]" id="branch" multiple>
                                  @if(request()->has('branch_id')&&isset($report_branches))
                                    @foreach($report_branches as $branch)
                                      <option value="{{$branch['id']}}" selected>{{$branch['name']}}</option>
                                    @endforeach
                                  @endif
                              </select>
                          </div>
                      </div>
                      <!-- \Branches -->

                      <!-- Products -->
                      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <div class="form-group">
                            <label>{{__('Product')}}</label>
                            <select class="form-control" name="product_id[]" id="product" multiple>
                                @if(request()->has('product_id')&&isset($products))
                                  @foreach($products as $product)
                                    <option value="{{$product['id']}}" selected>{{$product['name']}}</option>
                                  @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <!-- \Products -->
                    <input type="hidden" name="generate" value="true">
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

    <div class="printable">
      @if(isset($report_branches))
      <!-- Report Details -->
      <div class="card card-primary card-tabs">
        <div class="card-header p-0">
          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
            @php 
                $branches_count=0;
            @endphp
            @foreach($report_branches as $branch)
            <li class="nav-item">
              <a class="nav-link @if(!$branches_count) active @endif" id="custom-tabs-one-{{$branch['id']}}-tab" data-toggle="pill" href="#custom-tabs-one-{{$branch['id']}}" role="tab" aria-controls="custom-tabs-one-{{$branch['id']}}" aria-selected="false">{{$branch['name']}}</a>
            </li>
            @php 
                $branches_count++;
            @endphp
            @endforeach
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-one-tabContent">
            @php 
                $branches_count=0;
            @endphp
            @foreach($report_branches as $branch)
            <div class="tab-pane fade @if(!$branches_count) active show @endif" id="custom-tabs-one-{{$branch['id']}}" role="tabpanel" aria-labelledby="custom-tabs-one-{{$branch['id']}}-tab">
              <div class="row">
                <div class="col-lg-12 table-responsive">
                  <table class="table table-striped table-bordered datatable">
                    <thead>
                      <tr>
                        <th>{{__('SKU')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Quantity')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($branch['products'] as $product)
                      <tr>
                        <td>
                          {{$product['product']['sku']}}
                        </td>
                        <td>
                          {{$product['product']['name']}}
                        </td>
                        <td>
                          {{$product['quantity']}}
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <a href="{{route('admin.reports.branch_products')}}?branch_id={{$branch['id']}}&@if(request()->has('product_id'))@foreach(request('product_id') as $key=>$value)product_id[{{$key}}]={{$value}}& @endforeach @endif" class="btn btn-success">
                                    <i class="fa fa-file-excel"></i>
                                    {{__('Download')}}
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
            @php 
                $branches_count++;
            @endphp
            @endforeach
          </div>
        </div>
      </div>
      <!-- \Report Details -->
      @endif
    </div>
  </div>
  <!-- /.card-body -->
</div>

@endsection
@section('scripts')
    <script src="{{url('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{url('js/admin/product_report.js')}}"></script>
@endsection