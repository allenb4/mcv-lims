<table>
    <thead>
        <tr>
            <th align="center" width="10">id</th>
            <th align="center" width="20">Code</th>
            <th align="center" width="20">Name</th>
            <th align="center" width="20">Phone</th>
            <th align="center" width="20">Email</th>
            <th align="center" width="20">Address</th>
            <th align="center" width="20">Commission</th>
            <th align="center" width="20">Total</th>
            <th align="center" width="20">Paid</th>
            <th align="center" width="20">Due</th>
        </tr>
    </thead>
    <tbody>
    @foreach($doctors as $doctor)
        <tr>
            <td align="center">{{ $doctor['id'] }}</td>
            <td align="center">{{ $doctor['code'] }}</td>
            <td align="center">{{ $doctor['name'] }}</td>
            <td align="center">{{ (string) $doctor['phone'] }}</td>
            <td align="center">{{ $doctor['email'] }}</td>
            <td align="center">{{ $doctor['address'] }}</td>
            <th align="center">{{ $doctor['commission']}}</th>
            <td align="center">{{ formated_price($doctor['total']) }}</td>
            <td align="center">{{ formated_price($doctor['paid']) }}</td>
            <td align="center" style="@if($doctor['due']>0) color:red; @else color:#28a745; @endif">{{ formated_price($doctor['due']) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>