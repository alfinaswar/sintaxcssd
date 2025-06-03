
@foreach ( $query as $key=> $val )

<tr onclick="dataAlat(this)">
    <td id="dataId">{{ $val->ItemID }}</td>
    <td id="dataNama">{{ $val->Nama }}</td>
    <td id="dataGroupItem">{{ $val->GroupItemID }}</td>
</tr>

@endforeach