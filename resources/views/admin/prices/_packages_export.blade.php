<table>
    <thead>
        <tr>
            <th align="center" width="30">{{__('Package id')}}</th>
            <th align="center" width="30">{{__('Name')}}</th>
            <th align="center" width="20">{{__('Price')}}</th>
        </tr>
    </thead>
    <tbody>

    @foreach($packages as $package)
        <tr>
            <td align="center">{{ $package['id'] }}</td>
            <td align="center">{{ $package['package']['name'] }}</td>
            <td align="center">{{ $package['price'] }}</td>
        </tr>
    @endforeach

    </tbody>
</table>