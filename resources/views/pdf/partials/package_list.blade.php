@isset($packages)
    @foreach($packages as $package)
        <tr>
            <td colspan="2" class="print_title test_name">
                @isset($package['package'])
                    {{ $package['package']['name'] }}
                @endisset
                <ul>
                    @foreach($package['tests'] as $test)
                        <li>{{ $test['test']['name'] }}</li>
                    @endforeach
                    @foreach($package['cultures'] as $culture)
                        <li>{{ $culture['culture']['name'] }}</li>
                    @endforeach
                </ul>
            </td>
            <td>{{ formated_price($package['price']) }}</td>
        </tr>
    @endforeach
@endisset
