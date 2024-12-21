<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{__('Receipt')}}-{{$group['id']}}-{{date('Y-m-d')}}</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;

            margin-top: 25%;
            margin-right: 5%;
            margin-left: 5%;
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
            padding: 20px 25px;
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
            color: #444;
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

        .invoice-table {
            width: 100%;
            margin-top: 20px;
            font-size: 14px;
        }
        .invoice-table th, .invoice-table td {
            text-align: left;
            padding: 10px 0;
        }
        .invoice-table th {
            font-weight: bold;
            color: #444;
        }
        .invoice-total {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
            color: #444;
        }
        .invoice-total table {
            margin-left: auto;
            font-size: 16px;
        }
        .invoice-total td {
            padding: 5px 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <htmlpageheader name="page-header">
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
        </htmlpageheader>
        

        <table class="invoice-table">
            <thead>
                <tr>
                    <th colspan="2" width="85%">{{ __('Test Name') }}</th>
                    <th width="15%">{{ __('Price') }}</th>
                </tr>
            </thead>
            <tbody>
                @include('pdf.partials.test_list', ['tests' => $group['tests']])
                @include('pdf.partials.culture_list', ['cultures' => $group['cultures']])
                @include('pdf.partials.package_list', ['packages' => $group['packages']])
            </tbody>
        </table>

        <div class="invoice-total">
            <table>
                <tbody>
                    @foreach(['subtotal', 'discount', 'total', 'due'] as $field)
                        <tr>
                            <td style="text-align: right;"><strong>{{ ucfirst($field) }}:</strong></td>
                            <td style="text-align: right;">{{ formated_price($group[$field]) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Include payment section --}}
        @include('pdf.partials.payment_section', ['payments' => $group['payments']])

        <div class="footer">
            <p>Thank you for trusting our diagnostic services!</p>
        </div>
    </div>


    <htmlpagefooter name="page-footer" class="page-footer">
        <hr>
        <p>{{ $info_settings['name'] ?? '' }} | Address: {{ $group['branch']['address'] ?? '' }} | Phone: {{ $group['branch']['phone'] ?? '' }} | Email: {{$info_settings['email'] ?? ''}}</p>
        <p class="page-number"></p>
    </htmlpagefooter>
</body>
</html>
