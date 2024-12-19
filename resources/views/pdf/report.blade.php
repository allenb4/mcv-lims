@extends('layouts.pdf')

@section('title')
    {{__('Report')}}-#{{$group['id']}}-{{date('Y-m-d')}}
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/pdf/reports.css') }}">
<div class="printable">
    @foreach($categories as $index => $category) 
        <h4 class="test_title" align="center">{{ $category['name'] }}</h4>
        
        @foreach($category['tests'] as $test)
            @include('pdf.partials.test', ['test' => $test, 'reportSettings' => $reportsSettings])
        @endforeach

        @foreach($category['cultures'] as $culture)
            @include('pdf.partials.culture', ['culture' => $culture, 'reportSettings' => $reportsSettings])
        @endforeach

        @if($index < count($categories) - 1)
            <pagebreak></pagebreak>
        @endif
    @endforeach
</div>
@endsection