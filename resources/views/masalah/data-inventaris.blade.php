
@foreach ( $query as $key=> $val )

<tr onclick="data(this)">
    <td id="dataNomorRo">{{ $val->ROID }}</td>
    <td id="dataItemID">{{ $val->NamaItem }}</td>
    <td id="dataDepartemen">{{ $val->ItemID }}</td>
    <td id="dataTanggal">{{ $val->TanggalBuat }}</td>
</tr>

@endforeach
