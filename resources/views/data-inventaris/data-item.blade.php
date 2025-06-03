@foreach ( $query as $key=> $val )
<tr onclick="data(this)">
    <td id="dataNomorRo2">{{ $val->RO2ID }}</td>
    <td id="dataNomorRo">{{ $val->ROID }}</td>
    <td id="dataItemID">{{ $val->ItemID }}</td>
    <td id="dataNama">{{ $val->NamaItem }}</td>
    <td id="dataGroupItemID">{{ $val->GroupItemID }}</td>
    {{-- <td id="dataHarga">{{ $val->Harga }}</td> --}}
    <td id="dataPrice">Rp. {{ number_format($val->Harga, 0, ',', '.') }}</td>
    <td id="dataTanggal">{{ date('d-m-Y', strtotime($val->TanggalBuat)) }}</td>
</tr>
@endforeach
