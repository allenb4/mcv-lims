<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{__('Report')}}-#{{$group['id']}}-{{date('Y-m-d')}}</title>
    <style>
        @page {
            margin-top: 0px;
            margin-right: 0px;
            margin-left: 0px;
            margin-bottom: 0px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            margin-top: 0px;
            width: 100%;
            margin-top: 0;
        }
        .invoice-container {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 15px;
            margin-top: 0;
        }
        .invoice-header h1 {
            font-size: 28px;
            margin: 0;
            color: #444444;
        }
        .invoice-header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .invoice-details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .invoice-details-table td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }

        .invoice-details-table td strong {
            display: block;
            font-weight: bold;
        }

        .invoice-details-table-value {
            text-align: left !important;
        }

        /* Constants for styling */
        .test_title {
            font-size: 20px;
            border-top: 1px solid #e0e0e0 !important;
            border-bottom: 1px solid #e0e0e0 !important;

        }

        .test_name {
            color: #333333 !important;
            font-size: 14px !important;
            font-family: Arial, sans-serif !important;
        }

        .test_head th {
            color: #000000 !important;
            font-size: 16px !important;
            font-family: Arial, sans-serif !important;
        }

        .unit {
            color: #0000ff !important;
            font-size: 12px !important;
            font-family: Arial, sans-serif !important;
        }

        .reference_range {
            color: #008000 !important;
            font-size: 12px !important;
            font-family: Arial, sans-serif !important;
        }

        .result {
            color: #444444 !important;
            font-size: 14px !important;
            font-family: Arial, sans-serif !important;
        }

        .status {
            color: #444444 !important;
            font-size: 14px !important;
            font-family: Arial, sans-serif !important;
        }

        .comment th,
        .comment td {
            color: #333333 !important;
            font-size: 12px !important;
            font-family: Arial, sans-serif !important;
        }

        .antibiotic_name {
            color: #666666 !important;
            font-size: 12px !important;
            font-family: Arial, sans-serif !important;
        }

        .sensitivity {
            color: #cc0000 !important;
            font-size: 12px !important;
            font-family: Arial, sans-serif !important;
        }

        .commercial_name {
            color: #0066cc !important;
            font-size: 12px !important;
            font-family: Arial, sans-serif !important;
        }

        .break-page {
            page-break-inside: avoid !important;
        }

        .subtitle {
            font-size: 15px;
        }

        .test {
            margin-top: 20px;
            width: 100%;
        }

        .transparent {
            border-color: white;
        }

        .transparent th {
            border-color: white;
        }

        .no-border {
            border-color: white;
        }

        .comment tr th,
        .comment tr td {
            border-color: white !important;
            vertical-align: top !important;
            text-align: left;
            padding: 0px !important;
        }

        .sensitivity {
            margin-top: 20px;
        }

        .table-max-width {
            width: 100% !important;
        }

    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1><img src="{{public_path('img/logo.png')}}" alt="{{ $info_settings['name'] ?? '' }}" width='200'></h1>
            <p>{{ $group['branch']['address'] ?? '' }}</p>
        </div>

        <table class="invoice-details-table">
            <tbody>
                <tr>
                    <td>
                        <strong>{{ __('Barcode') }}:</strong>
                    </td>
                    <td class='invoice-details-table-value'>
                        {{$group['barcode']}}
                    </td>
                    <td class='invoice-details-table-value'>
                        @if($group['barcode'])
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($group['barcode'], $barcode_settings['type']) }}" alt="barcode" width="100" />
                        @else
                            ''
                        @endif
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{ __('Patient Code') }}:</strong>
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ $group['patient']['code'] ?? '' }}
                    </td>

                    <td>
                        <strong>{{ __('Patient Name') }}:</strong>
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ $group['patient']['name'] ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{ __('Age') }}:</strong>
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ $group['patient']['age'] ?? '' }}
                    </td>

                    <td>
                        <strong>{{ __('Gender') }}:</strong> {{ __($group['patient']['gender'] ?? '') }}
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ __($group['patient']['gender'] ?? '') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{ __('Doctor') }}:</strong>
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ $group['doctor']['name'] ?? '' }}
                    </td>
                    <td>
                        <strong>{{ __('Contract') }}:</strong> {{ $group['contract']['title'] ?? '' }}
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ $group['contract']['title'] ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{ __('Sample Collection') }}:</strong>
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ $group['sample_collection_date'] ?? '' }}
                    </td>
                    <td>
                        <strong>{{ __('Registration Date') }}:</strong>
                    </td>
                    <td class='invoice-details-table-value'>
                        {{ date('Y-m-d H:i', strtotime($group['created_at'])) }}
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="printable">
            @foreach($categories as $index => $category) 
                <h4 class="test_title" align="center">{{ $category['name'] }}</h4>
                
                @foreach($category['tests'] as $test)
                    @include('pdf.partials.test', ['test' => $test, 'reportSettings' => $reports_settings])
                @endforeach
        
                @foreach($category['cultures'] as $culture)
                    @include('pdf.partials.culture', ['culture' => $culture, 'reportSettings' => $reports_settings])
                @endforeach
        
                @if($index < count($categories) - 1)
                    <pagebreak></pagebreak>
                @endif
            @endforeach
        </div>


    </div>
</body>
</html>
