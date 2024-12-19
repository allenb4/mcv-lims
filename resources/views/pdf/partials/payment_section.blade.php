@isset($payments)
    <tr class="receipt_title">
        <td width="50%" class="no-right-border"></td>
        <td class="total">
            <b>{{ __('Paid') }}</b><br>
            @foreach($payments as $payment)
                {{ formated_price($payment['amount']) }}
                <b>{{ __('On') }}</b> {{ $payment['date'] }}
                <b>{{ __('By') }}</b> {{ $payment['payment_method']['name'] }}
                <br>
            @endforeach
        </td>
        <td class="total">
            @if(count($payments))
                {{ formated_price($group['paid']) }}
            @else
                {{ formated_price(0) }}
            @endif
        </td>
    </tr>
@endisset
