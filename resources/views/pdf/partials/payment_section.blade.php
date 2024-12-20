@isset($payments)
    <div class="receipt_section">
        <div class="receipt_title">
            <div class="no-right-border" style="width: 50%;"></div>
            <div class="total">
                <b>{{ __('Paid') }}</b><br>
                @foreach($payments as $payment)
                    <div>
                        {{ formated_price($payment['amount']) }} 
                        <b>{{ __('On') }}</b> {{ $payment['date'] }} 
                        <b>{{ __('By') }}</b> {{ $payment['payment_method']['name'] }}
                    </div>
                @endforeach
            </div>
            <div class="total">
                <b>{{ __('Total Paid') }}: </b>
                @if(count($payments))
                {{ formated_price($group['paid']) }}
                @else
                    {{ formated_price(0) }}
                @endif
            </div>
        </div>
    </div>
@endisset
