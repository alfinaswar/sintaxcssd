
@foreach ( $query as $key=> $val )

<tr onclick="data(this)">
    <td id="dataJudul">{{ $val->Judul }}</td>
    <td id="dataKasus">{{ $val->Kasus }}</td>
    <td id="dataTanggal">{{ $val->Tanggal }}</td>
    <td id="dataKeterangan">{{ $val->Keterangan }}</td>
</tr>

@endforeach
