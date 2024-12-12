@extends('layouts.app')

@section('title')
{{__('Invoice')}}
@endsection

@section('css')
    <link rel="stylesheet" href="{{url('css/print.css')}}">
@endsection

@section('breadcrumb')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    {{__('Invoices')}}
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.index')}}">{{__('Home')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.groups.index')}}">{{__('Invoices')}}</a></li>
                    <li class="breadcrumb-item active">{{__('Invoice')}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            {{__('Invoice')}}
        </h3>
    </div>
    <!-- patient code -->
    <input type="hidden" name="patient_code" @if(isset($group['patient'])) value="{{$group['patient']['code']}}" @endif
        id="patient_code">

    <div class="card-body p-0">
        <div class="p-3 mb-3" id="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table width="100%">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <table width="100%" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td width="50%">
                                                    <span class="title">{{__('Barcode')}} :</span>
                                                    <span class="data">
                                                        {{$group['barcode']}}
                                                    </span>
                                                </td>
                                                <td width="50%">
                                                    <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($group['barcode'], $barcode_settings['type'])}}" alt="barcode" class="margin" width="100"/><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%">
                                                    <span class="title">{{__('Patient Code')}} :</span> <span
                                                        class="data">
                                                        @if(isset($group['patient']))
                                                            {{ $group['patient']['code'] }}
                                                        @endif
                                                    </span>
                    
                                                </td>
                                                <td width="50%">
                                                    <span class="title">{{__('Patient Name')}} :</span> <span
                                                        class="data">
                                                        @if(isset($group['patient']))
                                                            {{ $group['patient']['name'] }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="title">{{__('Age')}} :</span> 
                                                    <span class="data">
                                                        @if(isset($group['patient']))
                                                            {{$group['patient']['age']}}
                                                        @endif
                                                    </span>
                    
                                                </td>
                                                <td>
                                                    <span class="title">{{__('Gender')}} :</span> <span
                                                        class="data">
                                                        @if(isset($group['patient']))
                                                            {{ __($group['patient']['gender']) }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="title">{{__('Doctor')}} :</span> <span
                                                        class="data">
                                                        @if(isset($group['doctor']))
                                                            {{ $group['doctor']['name'] }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="title">{{__('Contract')}} :</span> <span
                                                        class="data">
                                                        @if(isset($group['contract']))
                                                            {{ $group['contract']['title'] }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="title">{{__('Registration Date')}} :</span> 
                                                    <span class="data">
                                                        {{ date('Y-m-d H:i',strtotime($group['created_at'])) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="title">{{__('Sample collection')}} :</span> 
                                                    <span class="data">
                                                        {{ $group['sample_collection_date'] }}
                                                    </span>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </th>

                            </tr>
                        </thead>

                    </table>

                </div>
                <!-- /.col -->
            </div>

            <br>

            <div class="row">
                <!-- /.col -->
                <div class="col-lg-12 table-responsive">
                    <p class="lead">{{__('Due Date')}} : {{date('d/m/Y',strtotime($group['created_at']))}}</p>
                    <table class="table table-bordered">
                        <thead  >
                            <tr>
                                <th colspan="2" width="85%">{{__('Test Name')}}</th>
                                <th width="15%">{{__('Price')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group['tests'] as $test)
                            <tr>
                                <td colspan="2" class="print_title">
                                    @if(isset($test['test'])) 
                                        {{$test['test']['name']}}
                                    @endif
                                </td>
                                <td>{{formated_price($test['price'])}}</td>
                            </tr>
                            @endforeach
                
                            @foreach($group['cultures'] as $culture)
                            <tr>
                                <td colspan="2" class="print_title">
                                    @if(isset($culture['culture']))
                                        {{$culture['culture']['name']}}
                                    @endif
                                </td>
                                <td>{{formated_price($culture['price'])}}</td>
                            </tr>
                            @endforeach

                            @foreach($group['packages'] as $package)
                            <tr>
                                <td colspan="2" class="print_title">
                                    @if(isset($package['package']))
                                        {{$package['package']['name']}}
                                    @endif
                                    <ul>
                                        @foreach($package['tests'] as $test)
                                            <li>
                                                {{$test['test']['name']}}
                                            </li>
                                        @endforeach
                                        @foreach($package['cultures'] as $culture)
                                            <li>
                                                {{$culture['culture']['name']}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{formated_price($package['price'])}}</td>
                            </tr>
                            @endforeach
                
                            
                
                        </tbody>
                        <tfoot>
                            <tr class="receipt_title border-top">
                                <td width="60%" class="no-right-border"></td>
                                <td class="total">
                                    <b>{{__('Subtotal')}}</b>
                                </td>
                                <td class="total">{{formated_price($group['subtotal'])}}</td>
                            </tr>
                
                            <tr class="receipt_title">
                                <td width="60%" class="no-right-border"></td>
                                <td class="total">
                                    <b>
                                        {{__('Discount')}}
                                    </b>
                                </td>
                                <td class="total">{{formated_price($group['discount'])}}</td>
                            </tr>
                
                            <tr class="receipt_title">
                                <td width="60%" class="no-right-border"></td>
                                <td class="total">
                                    <b>{{__('Total')}}</b>
                                </td>
                                <td class="total">{{formated_price($group['total'])}}</td>
                            </tr>
                
                            <tr class="receipt_title">
                                <td width="60%" class="no-right-border"></td>
                                <td class="total">
                                    <b>
                                        {{__('Paid')}}
                                    </b>
                                    <br>
                                    @foreach($group['payments'] as $payment)
                                        {{formated_price($payment['amount'])}} 
                                        <b>{{__('On')}}</b>  
                                        {{$payment['date']}}
                                        <b>{{__('By')}}</b>
                                        {{$payment['payment_method']['name']}}
                                        <br>
                                    @endforeach
                                </td>
                                <td class="total">
                                    @if(count($group['payments']))
                                        {{formated_price($group['paid'])}}
                                    @else 
                                        {{formated_price(0)}}
                                    @endif
                                </td>
                            </tr>
                
                            <tr class="receipt_title">
                                <td width="60%" class="no-right-border"></td>
                                <td class="total">
                                    <b>{{__('Due')}}</b>
                                </td>
                                <td class="total">{{formated_price($group['due'])}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </div>

    <div class="card-footer">
        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <a target="_blank" href="{{$group['receipt_pdf']}}" class="btn btn-danger">
                    <i class="fa fa-file-pdf"></i> {{__('Print receipt')}}
                </a>

                <a style="cursor: pointer" class="btn btn-warning print_barcode" data-toggle="modal" data-target="#print_barcode_modal" group_id="{{$group['id']}}">
                    <i class="fa fa-barcode" aria-hidden="true"></i>
                    {{__('Print barcode')}}
                </a>

                @if($whatsapp['receipt']['active']&&isset($group['receipt_pdf']))
                    @php 
                        $message=str_replace(['{patient_name}','{receipt_link}'],[$group['patient']['name'],$group['receipt_pdf']],$whatsapp['receipt']['message']);
                    @endphp
                    <a target="_blank" href="https://wa.me/{{$group['patient']['phone']}}?text={{$message}}" class="btn btn-success">
                        <i class="fab fa-whatsapp" aria-hidden="true" class="text-success"></i>
                        {{__('Send Receipt')}}
                    </a>
                @endif

                @if($email['receipt']['active']&&isset($group['receipt_pdf']))
                <form action="{{route('admin.groups.send_receipt_mail',$group['id'])}}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary d-inline">
                    <i class="fa fa-envelope" aria-hidden="true" class="text-success"></i>
                    {{__('Send Receipt')}}
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

@include('admin.groups.modals.print_barcode')

@endsection

@section('scripts')
    <script src="{{url('js/admin/groups.js')}}"></script>
@endsection