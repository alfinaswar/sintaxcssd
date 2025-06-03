@foreach ( $query as $key=> $val )
<tr onclick="data(this)">
    <td id="dataid">{{ $val->kode_item }}</td>
    <td id="datanoinv">{{ $val->no_inventaris }}</td>
    <td id="datanama">{{ $val->nama }}</td>
    <td id="datadepartemen">{{ $val->departemen }}</td>
    <td id="dataunit">{{ $val->unit }}</td>
    <td id="datajenis">{{ $val->pengguna }}</td>
</tr>
@endforeach
