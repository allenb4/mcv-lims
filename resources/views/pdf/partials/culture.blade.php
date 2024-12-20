<h5 class="test_title" align="center">{{ $culture['culture']['name'] }}</h5>
<table class="table">
    <tbody>
        @foreach($culture['culture_options'] as $option)
            @if(isset($option['value']) && isset($option['culture_option']))
                <tr>
                    <th class="no-border test_name" align="left">
                        <span class="option_title">{{ $option['culture_option']['value'] }}:</span>
                    </th>
                    <td class="no-border result">{{ $option['value'] }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
<table class="table table-bordered sensitivity table-max-width">
    <thead class="test_head">
        <tr>
            <th>Name</th>
            <th>Sensitivity</th>
            <th>Commercial Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach($culture['high_antibiotics'] as $antibiotic)
            <tr>
                <td class="antibiotic_name">{{ $antibiotic['antibiotic']['name'] }}</td>
                <td class="sensitivity">{{ $antibiotic['sensitivity'] }}</td>
                <td class="commercial_name">{{ $antibiotic['antibiotic']['commercial_name'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@if(isset($culture['comment']))
    <table class="comment">
        <tbody>
            <tr>
                <td><b>Comment:</b></td>
                <td>{!! nl2br(e($culture['comment'])) !!}</td>
            </tr>
        </tbody>
    </table>
@endif
