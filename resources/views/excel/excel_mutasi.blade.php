<table width="100%">
    <tr>
        <th colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            LAPORAN MUTASI ITEM KE RUANGAN
        </th>
    </tr>
    <tr>
        <td colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">Rumah Sakit Awal Bros</td>
    </tr>
</table>
<table width="100%">
    <tr>
        <th style="text-align: center; font-style:bold;">No</th>
        <th style="text-align: center; font-style:bold;">Departemen Tujuan</th>
        <th style="text-align: center; font-style:bold;">Saldo Akhir</th>
    </tr>
    @foreach ($mutasi as $key => $item)
        <tr>
            <td width="35px" style="font-family: 'Times New Roman', Times, serif; text-align:center;">{{ $key + 1 }}</td>
            <td width="355px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->KeDepartemenID }}</td>
            <td width="355px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->KeDepartemenID }}</td>
        </tr>
    @endforeach
</table>
<style>
  
</style>
