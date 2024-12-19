<table class="table test beak-page">
    <thead>
        <tr>
            <th class="test_title" align="center" colspan="5">
                <h5>{{ $test['test']['name'] }}</h5>
            </th>
        </tr>
        <tr class="transparent">
            <th colspan="5"></th>
        </tr>
        <tr class="test_head">
            <th>Test</th>
            <th>Result</th>
            <th>Unit</th>
            <th>Normal Range</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($test['results'] as $result)
            @if(isset($result['component']))
                @if($result['component']['title'])
                    <tr>
                        <td colspan="5" class="component_title test_name">
                            <b>{{ $result['component']['name'] }}</b>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td class="text-capitalize test_name">{{ $result['component']['name'] }}</td>
                        <td align="center" class="result">{{ $result['result'] }}</td>
                        <td align="center" class="unit">{{ $result['component']['unit'] }}</td>
                        <td align="left" class="reference_range">{!! $result['component']['reference_range'] !!}</td>
                        <td align="center" class="status">{{ $result['status'] }}</td>
                    </tr>
                @endif
            @endif
        @endforeach
    </tbody>
</table>