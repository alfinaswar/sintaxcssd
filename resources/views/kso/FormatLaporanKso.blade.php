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
    <center><b><span style="font-size: 16px">Laporan KSO Rumah Sakit Awal bros </span></b></center>
    <br>
    <table id="maintable" width="100%">
        <thead>
            <tr class="text-center">
                <th width="3%">No</th>
                <th width="30%">Nama</th>
                <th>Merk / Type</th>
                <th>SN</th>
                <th>Nama Perusahaan</th>
                <th>KSO</th>
            </tr>
        <tbody>
            </thead>
            @if ($data->count() === 0)
                <tr style="text-align: center; font-weight: bold; font-size: 14px;">
                    <td colspan="6">Data Tidak Ditemukan</td>
                </tr>
            @else
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->getNamaBarang->nama }}</td>
                        <td>{{ $item->Merk }}<br>
                            <hr>{{ $item->Type }}
                        </td>
                        <td>{{ $item->Sn }}</td>
                        <td>{{ $item->NamaPerusahaan }}</td>
                        <td>{{ $item->TanggalKerjaSama }}<br>
                            <hr>{{ $item->DueDateKerjaSama }}
                        </td>
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>

</body>

</html>
