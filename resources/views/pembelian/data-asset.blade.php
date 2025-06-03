@foreach ( $query as $key=> $val )
<tr onclick="data(this)">
   
    <td id="dataNama">{{ $val->nama }}</td>
    <td id="dataIp">{{ $val->no_sn }}</td>
    <td id="dataDepartemen">{{ $val->departemen }}</td>
</tr>

@endforeach