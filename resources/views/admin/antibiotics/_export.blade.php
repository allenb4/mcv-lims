<table>
    <thead>
        <tr>
            <th align="center" width="10">{{__('Name')}}</th>
            <th align="center" width="10">{{__('Shortcut')}}</th>
            <th align="center" width="10">{{__('Commercial name')}}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($antibiotics as $antibiotic)
        <tr>
            <td align="center">{{ $antibiotic['name'] }}</td>
            <td align="center">{{ $antibiotic['shortcut'] }}</td>
            <td align="center">{{ $antibiotic['commercial_name'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>