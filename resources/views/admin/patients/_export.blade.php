<table>
    <thead>
        <tr>
            <th align="center" width="10">id</th>
            <th align="center" width="20">Code</th>
            <th align="center" width="20">Name</th>
            <th align="center" width="20">National ID</th>
            <th align="center" width="20">Passport No</th>
            <th align="center" width="20">Gender</th>
            <th align="center" width="20">DOB</th>
            <th align="center" width="20">Age</th>
            <th align="center" width="20">Phone</th>
            <th align="center" width="20">Email</th>
            <th align="center" width="20">Address</th>
            <th align="center" width="20">Contract</th>
            <th align="center" width="20">Total</th>
            <th align="center" width="20">Paid</th>
            <th align="center" width="20">Due</th>
        </tr>
    </thead>
    <tbody>
    @foreach($patients as $patient)
        <tr>
            <td align="center">{{ $patient['id'] }}</td>
            <td align="center">{{ $patient['code'] }}</td>
            <td align="center">{{ $patient['name'] }}</td>
            <td align="center">{{ $patient['national_id'] }}</td>
            <td align="center">{{ $patient['passport_no'] }}</td>
            <td align="center">{{ $patient['gender'] }}</td>
            <td align="center">{{ date('Y-m-d',strtotime($patient['dob'])) }}</td>
            <td align="center">{{ $patient['age'] }}</td>
            <td align="center">{{ (string) $patient['phone'] }}</td>
            <td align="center">{{ $patient['email'] }}</td>
            <td align="center">{{ $patient['address'] }}</td>
            <td align="center">{{ $patient['contract']['title'] }}</td>
            <td align="center">{{ formated_price($patient['total']) }}</td>
            <td align="center">{{ formated_price($patient['paid']) }}</td>
            <td align="center" style="@if($patient['due']>0) color:red; @else color:#28a745; @endif">{{ formated_price($patient['due']) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>