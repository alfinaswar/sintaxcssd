<table border="1">
    <thead>
        <tr>
            <th rowspan="2" style="width: 30px;">No</th>
            <th rowspan="2" style="width: 120px;">Serial Number</th>
            <th rowspan="2" style="width: 200px;">Nama</th>
            <th colspan="12" style="width: 720px;">Bulan</th>
        </tr>
        <tr>
            <th style="width: 60px;">Januari</th>
            <th style="width: 60px;">Februari</th>
            <th style="width: 60px;">Maret</th>
            <th style="width: 60px;">April</th>
            <th style="width: 60px;">Mei</th>
            <th style="width: 60px;">Juni</th>
            <th style="width: 60px;">Juli</th>
            <th style="width: 60px;">Agustus</th>
            <th style="width: 60px;">September</th>
            <th style="width: 60px;">Oktober</th>
            <th style="width: 60px;">November</th>
            <th style="width: 60px;">Desember</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td style="width: 30px;">{{ $index + 1 }}</td>
                <td style="width: 120px;">{{ $item->no_inventaris }}</td>
                <td style="width: 200px;">{{ $item->nama }}</td>

                @for ($i = 1; $i <= 12; $i++)
                    @php
                        $bulanIni = str_pad($i, 2, '0', STR_PAD_LEFT);
                        $adaPM = $item->DataMaintenance->first(function ($pm) use ($bulanIni) {
                            return \Carbon\Carbon::parse($pm->bulan)->format('m') == $bulanIni;
                        });
                    @endphp
                    <td style="width: 60px;">
                        @if ($adaPM)
                            {{ \Carbon\Carbon::parse($adaPM->tanggal)->format('d M') }}
                        @endif
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>