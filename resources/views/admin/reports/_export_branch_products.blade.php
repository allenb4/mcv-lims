<table>
    <thead>
        <tr>
            <th align="center" width="10">{{__('SKU')}}</th>
            <th align="center" width="20">{{__('Name')}}</th>
            <th align="center" width="20">{{__('Quantity')}}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td align="center">{{ $product['product']['sku'] }}</td>
            <td align="center">{{ $product['product']['name'] }}</td>
            <td align="center">{{ $product['quantity'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>