@foreach($tests as $test)
    <tr>
        <td colspan="2" class="print_title test_name">
            @isset($test['test']) 
                {{ $test['test']['name'] }}
            @endisset
        </td>
        <td>{{ formated_price($test['price']) }}</td>
    </tr>
@endforeach
