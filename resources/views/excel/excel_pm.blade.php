<table width="100%">
    <tr>
        <th colspan="6"
            style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            LAPORAN PREVENTIF ALAT
        </th>
    </tr>
    <tr>
        <td colspan="6"
            style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            Rumah Sakit Awal Bros</td>
    </tr>
</table>
<table width="100%">
<thead>
  <tr class="text-center>">
    <td rowspan="2">No</td>
    <td rowspan="2" width="20">Serial Number</td>
    <td width="30">Nama</td>
    <td colspan="12">Bulan</td>
  </tr>
  <tr>
    <td></td>
    <td>Januari</td>
    <td>Februari</td>
    <td>Maret</td>
    <td>April</td>
    <td>Mei</td>
    <td>Juni</td>
    <td>Juli</td>
    <td>Agustus</td>
    <td>September</td>
    <td>Oktober</td>
    <td>November</td>
    <td>Desember</td>
  </tr>
</thead>
<tbody>
    @foreach ($data as $key => $item )
    <tr>
      <td>{{$key + 1}}</td>
<td>{{$item->assetID}}</td>
<td>{{$item->nama}}</td>
@foreach ($item->DataMaintenance as $month )

<td>{{DateTime::createFromFormat('!m', $month->bulan)->format('F');}}</td>
@endforeach
    </tr>
    @endforeach

</tbody>
</table>
<style>

</style>
