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
            font-size: 12px;
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

        #maintable {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #maintable td,
        #maintable th {
            border: 1px solid #000000;
            padding: 8px;
        }

        #maintable th {
            padding-top: 12px;
            text-align: center;
            padding-bottom: 12px;
            text-align: left;
            background-color: #2BA767;
            color: rgb(255, 255, 255);
        }
    </style>
</head>

<body>
    <img src="{{ asset('') }}assets/img/logors.png">
    <center><b><span style="font-size: 16px">Data Inventaris {{$header}} Rumah Sakit Awal bros </span></b></center>
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
    <table id="maintable" width="100%">
        <thead>
            <tr class="text-center">
                <th width="3%">No</th>
                <th width="30%">Nama</th>
                <th>Merk</th>
                <th>SN</th>
                <th>Tahun Beli</th>
                <th width="20%">Gambar</th>
            </tr>
        <tbody>
            </thead>
            @if ($query->count() === 0)
                <tr style="text-align: center; font-weight: bold; font-size: 14px;">
                    <td colspan="6">Data Tidak Ditemukan</td>
                </tr>
            @else
                @foreach ($query as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->merk }}</td>
                        <td>{{ $item->no_sn }}</td>
                        <td>{{ date('Y', strtotime($item->tanggal_beli)) }}</td>
                        <td><img src="{{ url('storage/gambar/' . $item->gambar) }}" alt="Gambar Item"
                                style="width: 80; height: 80;"></td>
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>

</body>

</html>