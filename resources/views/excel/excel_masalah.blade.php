<table width="100%">
    <tr>
        <th colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            LAPORAN KASUS ASSET INVENTARIS
        </th>
    </tr>
    <tr>
        <td colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">Rumah Sakit Awal Bross Ahmad Yani</td>
    </tr>
</table>
<table width="100%">
    <tr>
        <th style="text-align: center; font-style:bold;">No</th>
        <th style="text-align: center; font-style:bold;">Nama Alat</th>
        <th style="text-align: center; font-style:bold;">Kasus</th>
        <th style="text-align: center; font-style:bold;">Jumlah Kasus</th>
        <th style="text-align: center; font-style:bold;">Tindakan</th>
        <th style="text-align: center; font-style:bold;">Prioritas</th>
    </tr>
    @foreach ($masalah as $key => $item)
        <tr>
            <td width="35px" style="font-family: 'Times New Roman', Times, serif; text-align:center;">{{ $key + 1 }}</td>
            <td width="355px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->nama_perangkat }}</td>
            <td width="355px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->kasus }}</td>
            <td width="128px" style="font-family: 'Times New Roman', Times, serif; text-align:center;">{{ $item->jumlah_masalah }}</td>
            <td width="186px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->tindakan }}</td>
            <td width="150px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->prioritas }}</td>
        </tr>
    @endforeach
</table>
<style>
  
</style>
