@extends('layouts.pdf')

@section('title')
    {{__('Receipt')}}-{{$group['id']}}-{{date('Y-m-d')}}
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/pdf/receipts.css') }}">
<div class="invoice">
    <table class="table table-bordered" width="100%">
        <thead>
            <tr>
                <th colspan="2" width="85%">{{__('Test Name')}}</th>
                <th width="15%">{{__('Price')}}</th>
            </tr>
        </thead>
        <tbody>
            @include('pdf.partials.test_list', ['tests' => $group['tests']])
            @include('pdf.partials.culture_list', ['cultures' => $group['cultures']])
            @include('pdf.partials.package_list', ['packages' => $group['packages']])

            @foreach(['subtotal', 'discount', 'total', 'due'] as $field)
                <tr class="receipt_title">
                    <td width="50%" class="no-right-border"></td>
                    <td class="total"><b>{{ __(ucfirst($field)) }}</b></td>
                    <td class="total">{{ formated_price($group[$field]) }}</td>
                </tr>
            @endforeach

            {{-- Include payment section --}}
            @include('pdf.partials.payment_section', ['payments' => $group['payments']])
        </tbody>
    </table>
</div>
@endsection
