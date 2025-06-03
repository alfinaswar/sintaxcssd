<table width="100%">
    <tr>
        <th colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            LAPORAN PEMAKAIAN ITEM ANTAR DEPARTEMEN
        </th>
    </tr>
    <tr>
        <td colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">Rumah Sakit Awal Bros</td>
    </tr>
</table>
<table width="100%">
    <tr>
        <th style="text-align: center; font-style:bold;">No</th>
        <th style="text-align: center; font-style:bold;">ItemID</th>
        <th style="text-align: center; font-style:bold;">Nama Item</th>
        <th style="text-align: center; font-style:bold;">Satuan</th>
        <th style="text-align: center; font-style:bold;">Tgl Disetujui</th>
        <th style="text-align: center; font-style:bold;">No Mutasi</th>
        <th style="text-align: center; font-style:bold;">Status</th>
        <th style="text-align: center; font-style:bold;">Qty</th>
         <th style="text-align: center; font-style:bold;">Saldo</th>
    </tr>
    @foreach ($pemakaian as $key => $item)
        <tr>
            <td width="35px" style="font-family: 'Times New Roman', Times, serif; text-align:center;">{{ $key + 1 }}</td>
            <td width="175px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->TanggalBuat }}</td>
            <td width="135px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->ItemID }}</td>
            <td width="475px" style="font-family: 'Times New Roman', Times, serif; text-align:left;">{{ $item->Nama }}</td>
            <td width="125px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->Harga }}</td>
             <td width="75px" style="font-family: 'Times New Roman', Times, serif; text-align:left;">{{ $item->JumlahDiterima }}</td>
             <td width="475px" style="font-family: 'Times New Roman', Times, serif; text-align:left;">{{ $item->Nama }}</td>
            <td width="125px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->Harga }}</td>
             <td width="75px" style="font-family: 'Times New Roman', Times, serif; text-align:left;">{{ $item->JumlahDiterima }}</td>
        </tr>
    @endforeach
</table>
<style>

</style>
