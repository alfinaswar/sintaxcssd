<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin-top: 2cm;
            margin-bottom: 2.0cm;
            margin-left: 1cm;
            margin-right: 1cm;
        }

        .header {
            text-align: center;
        }

        .watermark {
            position: fixed;
            bottom: 0px;
            left: 0px;
            top: 0px;
            right: 0px;
            width: 21cm;
            height: 29.7cm;
            z-index: -10;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
        }

        .content {
            margin-bottom: 10px;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
        }

        small {
            font-size: 8px;
            font-style: italic;
        }

        .info-table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 70%;
            margin-bottom: 10px;
        }

        .info-table td,
        .info-table th {
            border: none;
            padding: 4px 6px;
            font-size: 12px;
        }

        .alat-table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 8px;
        }

        .alat-table th,
        .alat-table td {
            border: 1px solid #000000;
            padding: 8px;
        }

        .alat-table th {
            background-color: #2BA767;
            color: #fff;
        }

        .table-nested {
            border-collapse: collapse;
            width: 100%;
        }

        .table-nested td,
        .table-nested th {
            border: 1px solid #e0e0e0;
            padding: 4px 6px;
            font-size: 11px;
        }

        .status-bersih {
            color: green;
        }

        .status-kotor {
            color: #c0392b;
        }
    </style>
</head>

<body>
    <img src="{{ asset('') }}assets/img/logors.png">
    <center>
        <b>
            <span style="font-size: 16px">Laporan Pembersihan Alat {{ $header }} Rumah Sakit Awal bros </span>
        </b>
    </center>
    <br>
    <table width="100%" style="margin-bottom: 0.5cm;">
        <tr>
            <td width="10%">Unit</td>
            <td width="1%">:</td>
            <td style="font-weight: bold;">{{ $unit }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ now()->format('d-F-y') }}</td>
        </tr>
        <tr>
            <td>Total Inventaris</td>
            <td>:</td>
            <td>{{ count($query) }} Data Ditemukan</td>
        </tr>
    </table>
    @if ($query->count() === 0)
        <div style="text-align: center; font-weight: bold; font-size: 14px; margin-top:30px;">
            Data Tidak Ditemukan
        </div>
    @else
        @foreach ($query as $key => $item)
            <div style="margin-bottom: 3px; page-break-inside:avoid;">
                <!-- ATAS: Nama alat & Nomor inventaris -->
                <div>
                    <div style="flex:1;">
                        <div><b>{{ $key + 1 }}. {{ $item->nama }} / {{ $item->no_inventaris }}</b></div>
                    </div>


                </div>
            </div>
            <div>
                <table class="alat-table">
                    <thead>
                        <tr>
                            <th style="width:5%">No</th>
                            <th style="width:30%">Tanggal</th>
                            <th style="width:15%">Status</th>
                            <th style="width:20%">Petugas</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($item->getLaporanMonitoring && $item->getLaporanMonitoring->count())
                            @foreach ($item->getLaporanMonitoring as $no => $monitor)
                                <tr>
                                    <td>{{ $no + 1 }}</td>
                                    <td>
                                        @if (!empty($monitor->created_at))
                                            {{ \Carbon\Carbon::parse($monitor->created_at)->isoFormat('D MMMM Y HH:mm') }}
                                        @else
                                            -
                                        @endif

                                    </td>
                                    <td>
                                        @if (strtolower($monitor->Status) == 'bersih')
                                            <span class="status-bersih">{{ $monitor->Status }}</span>
                                        @elseif(strtolower($monitor->Status) == 'kotor')
                                            <span class="status-kotor">{{ $monitor->Status }}</span>
                                        @else
                                            {{ $monitor->Status }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $monitor->idUser ?? '-' }}
                                    </td>
                                    <td>{{ $monitor->Keterangan }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" style="text-align:center; color:#888; font-size:12px;">Belum Ada
                                    Monitoring</td>
                            </tr>
                        @endif
                    </tbody>
                </table>


            </div>
            </div>
        @endforeach
    @endif
</body>

</html>
