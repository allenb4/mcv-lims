<table>
    <thead>
        <tr>
            <th align="center" width="30"><b>id</b></th>
            <th align="center" width="30"><b>Category</b></th>
            <th align="center" width="30"><b>Name</b></th>
            <th align="center" width="30"><b>Sample type</b></th>
            <th align="center" width="30"><b>precautions</b></th>
            <th align="center" width="30"><b>Price</b></th>
        </tr>
    </thead>
    <tbody>
    @foreach($cultures as $culture)
        <tr>
            <td align="center">{{ $culture['id'] }}</td>
            <td align="center">{{ $culture['category']['name'] }}</td>
            <td align="center">{{ $culture['name'] }}</td>
            <td align="center">{{ $culture['sample_type'] }}</td>
            <td align="center">{{ $culture['precautions'] }}</td>
            <td align="center">{{ $culture['price'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>