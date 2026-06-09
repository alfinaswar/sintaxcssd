<table border="1">
    <thead>
        <tr>
            <th rowspan="2" style="width: 30px;">No</th>
            <th rowspan="2" style="width: 120px;">Serial Number</th>
            <th rowspan="2" style="width: 200px;">Nama</th>
            <th colspan="{{ $bulan_akhir - $bulan_mulai + 1 }}" style="width: 720px;">Bulan</th>
        </tr>
        <tr>
            @php
                $mulai = $bulan_mulai ?? 1;
                $akhir = $bulan_akhir ?? 12;
                $namaBulan = [
                    '',
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember',
                ];
            @endphp
            @for ($i = $mulai; $i <= $akhir; $i++)
                <th style="width: 60px;">{{ $namaBulan[$i] }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td style="width: 30px;">{{ $index + 1 }}</td>
                <td style="width: 120px;">{{ $item->no_inventaris }}</td>
                <td style="width: 200px;">{{ $item->nama }}</td>

                @for ($i = $mulai; $i <= $akhir; $i++)
                    @php
                        $adaPM = $item->DataMaintenance->first(function ($pm) use ($i) {
                            return $pm->bulan == $i;
                        });
                    @endphp
                    <td style="width: 60px; text-align: center;">
                        @if ($adaPM)
                            {{ \Carbon\Carbon::parse($adaPM->tanggal)->format('d M') }}
                        @else
                            -
                        @endif
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
