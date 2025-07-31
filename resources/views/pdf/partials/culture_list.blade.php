@foreach($cultures as $culture)
    <tr>
        <td colspan="2" class="print_title test_name">
            @isset($culture['culture'])
                {{ $culture['culture']['name'] }}
            @endisset
        </td>
        <td>{{ formated_price($culture['price']) }}</td>
    </tr>
@endforeach
