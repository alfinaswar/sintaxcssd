<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
</head>

<body>

    {{-- ════════════ KOP SURAT ════════════ --}}
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <tr>
            <td width="12%" align="center" rowspan="2">
                <img src="{{ asset('assets/img/logors.png') }}" height="60">
            </td>
            <td align="center" style="font-size:15px; font-weight:bold;" colspan="1">
                Laporan Pembersihan Alat &mdash; Rumah Sakit Awal Bros
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size:11px;">
                @if (!empty($rs))
                    {{ $rs->namaRS ?? '' }}
                @endif
            </td>
        </tr>
    </table>
    <br>

    {{-- ════════════ META INFO ════════════ --}}
    <table border="1" cellpadding="4" cellspacing="0" width="60%">
        <tr>
            <td width="20%">Unit</td>
            <td width="2%">:</td>
            <td width="38%">{{ $unit ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis</td>
            <td>:</td>
            <td>{{ $jenis ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alat</td>
            <td>:</td>
            <td>{{ !empty($alat) ? $alat : '-' }}</td>
        </tr>
        <tr>
            <td>Bulan / Tahun</td>
            <td>:</td>
            <td>
                @if (!empty($bulan) && !empty($tahun))
                    {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
                @elseif (!empty($bulan))
                    {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }}
                @elseif (!empty($tahun))
                    {{ $tahun }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{ now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Total Alat</td>
            <td>:</td>
            <td><b>{{ $query->count() }}</b> data ditemukan</td>
        </tr>
    </table>
    <br>

    {{-- ════════════ TIDAK ADA DATA ════════════ --}}
    @if ($query->count() === 0)
        <div align="center" style="color:#888; font-size:13px; margin-top:40px;">
            Tidak ada data yang sesuai dengan filter yang dipilih.
        </div>
    @else
        {{-- ════════════ LOOP TIAP ALAT ════════════ --}}
        @foreach ($query as $key => $item)
            <table border="1" cellpadding="5" cellspacing="0" width="100%" style="margin-bottom:15px;">
                {{-- Header alat --}}
                <tr style="background-color:#e8f7f0;">
                    <td colspan="5" style="font-weight:bold;font-size:12px;">
                        {{ $key + 1 }}.&nbsp; {{ $item->nama ?? '-' }}
                        @if (!empty($item->no_inventaris))
                            &nbsp;/&nbsp; {{ $item->no_inventaris }}
                        @endif
                        @if (!empty($item->merk))
                            &nbsp;<span>({{ $item->merk }})</span>
                        @endif
                        @if (!empty($item->unit))
                            &nbsp;&mdash;&nbsp;
                            <span>{{ $item->unit }}</span>
                        @endif
                    </td>
                </tr>
                {{-- Tabel monitoring --}}
                <tr style="background-color:#d3ead8;font-weight:bold;font-size:11px;">
                    <th width="4%">No</th>
                    <th width="20%">Tanggal</th>
                    <th width="14%">Status</th>
                    <th width="16%">Petugas</th>
                    <th>Keterangan</th>
                </tr>
                @if ($item->getLaporanMonitoring && $item->getLaporanMonitoring->count())
                    @foreach ($item->getLaporanMonitoring as $no => $lap)
                        <tr>
                            <td align="center">{{ $no + 1 }}</td>
                            <td align="center">
                                {{ !empty($lap->created_at) ? \Carbon\Carbon::parse($lap->created_at)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td align="center">
                                @php $st = strtolower($lap->Status ?? ''); @endphp
                                @if ($st === 'bersih')
                                    <b style="color:#15803d;">{{ $lap->Status }}</b>
                                @elseif ($st === 'kotor')
                                    <b style="color:#b91c1c;">{{ $lap->Status }}</b>
                                @else
                                    {{ $lap->Status ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $lap->idUser ?? '-' }}</td>
                            <td>{{ $lap->Keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" align="center" style="color:#aaa; font-style:italic;">
                            Belum ada data monitoring
                        </td>
                    </tr>
                @endif
            </table>
        @endforeach
    @endif

    {{-- ════════════ FOOTER ════════════ --}}
    <table border="0" width="100%" style="font-size:10px; color:#888; margin-top:30px;">
        <tr>
            <td>Dicetak otomatis oleh sistem</td>
            <td align="right">{{ now()->format('d F Y H:i') }} WIB</td>
        </tr>
    </table>

</body>

</html>
