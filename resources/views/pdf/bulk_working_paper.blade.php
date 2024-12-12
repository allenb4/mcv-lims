@extends('layouts.pdf')

@section('content')
<style>
    .receipt_title td,th{
        border-color: white;
    }
    .receipt_title .total{
        background-color: #ddd;
    }
    .table th{
        color:{{$reports_settings['test_head']['color']}}!important;
        font-size:{{$reports_settings['test_head']['font-size']}}!important;
        font-family:{{$reports_settings['test_head']['font-family']}}!important;
    }
    .total{
        font-family:{{$reports_settings['test_head']['font-family']}}!important;
    }
    .due_date{
        font-family:{{$reports_settings['test_head']['font-family']}}!important;
    }
    .test_name{
        color:{{$reports_settings['test_name']['color']}}!important;
        font-size:{{$reports_settings['test_name']['font-size']}}!important;
        font-family:{{$reports_settings['test_name']['font-family']}}!important;
    }
    .text-center{
        text-align: center;
    }
   
</style>

@php 
    $count=0;
@endphp
@foreach($groups as $group)
    @if($count>0)
    <pagebreak>
    @endif
        <div class="invoice">
            <h2 class="text-center">{{__('Working paper')}}</h2>
            @if(isset($group['patient']))
                <table width="100%" class="table table-bordered pdf-header">
                    <tbody>
                        <tr>
                            <td width="50%">
                                <span class="title">{{__('Barcode')}} :</span> 
                                <span class="data">
                                    {{$group['barcode']}}
                                </span>

                            </td>
                            <td width="50%">
                                <span class="title">{{__('Order id')}} :</span> 
                                <span class="data">
                                    {{$group['id']}}
                                </span>
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
                                <span class="title">{{__('Sample collection date')}} :</span> 
                                <span class="data">
                                    {{ $group['sample_collection_date'] }}
                                </span>
                            </td>
                            <td>
                                <span class="title">{{__('Date')}} :</span> <span
                                    class="data">
                                <span class="data">
                                    {{ get_system_date() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="title">{{__('Employee id')}} :</span> 
                                <span class="data">
                                    {{ auth()->guard('admin')->user()->id }}
                                </span>
                            </td>
                            <td>
                                <span class="title">{{__('Employee name')}} :</span> <span
                                    class="data">
                                <span class="data">
                                    {{ auth()->guard('admin')->user()->name }}
                                </span>
                            </td>
                        </tr>

                    </tbody>
                </table>    

                <h4>{{__('Required tests')}}</h4>
                <ul>
                    @foreach($group['tests'] as $test)
                    <li>
                        {{$test['test']['name']}}
                    </li>
                    @endforeach
                    @foreach($group['cultures'] as $culture)
                    <li>
                        {{$culture['culture']['name']}}
                    </li>
                    @endforeach
                    @foreach($group['packages'] as $package)
                    <li>
                        {{$package['package']['name']}}
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
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @if($count>0)
    </pagebreak>
    @endif
    @php 
        $count++;
    @endphp
@endforeach 

@endsection