<table width="100%">
    <tr>
        <th colspan="6"
            style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            LAPORAN MAINTENANCE ALAT
        </th>
    </tr>
    <tr>
        <td colspan="6"
            style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            Rumah Sakit Awal Bros</td>
    </tr>
</table>
<table width="100%">
    <tr>
        <th style="text-align: center; font-style:bold;">No</th>
        <th style="text-align: center; font-style:bold;">Nama Perangkat</th>
        <th style="text-align: center; font-style:bold;">Kasus</th>
        <th style="text-align: center; font-style:bold;">Tindakan</th>
        <th style="text-align: center; font-style:bold;">Keterangan</th>
    </tr>
    @foreach ($maintenance as $key => $item)
    <tr>
        <td width="35px" style="font-family: 'Times New Roman', Times, serif; text-align:center;">{{ $key + 1 }}</td>
        <td width="355px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->nama_perangkat }}</td>
        <td width="355px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->kasus }}</td>
        <td width="200px" style="font-family: 'Times New Roman', Times, serif; text-align:center;">{{ $item->tindakan }}</td>
        <td width="355px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->keterangan }}</td>
    </tr>
    @endforeach
</table>
<style>

</style>
