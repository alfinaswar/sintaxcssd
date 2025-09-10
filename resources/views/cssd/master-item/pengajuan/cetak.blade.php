<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Pengajuan Item Baru</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin-top: 4.0cm;
            margin-bottom: 1.0cm;
            margin-left: 2.54cm;
            margin-right: 2.54cm;
            font-size: 14px;
            text-align: justify;
            line-height: 0.7cm;
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

        #tabelitem {
            border-collapse: collapse;
            width: 100%;
            padding: 1px;
            vertical-align: middle;
            line-height: 15px;
        }

        #tabelitem th {
            border: 1px solid #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabelitem td {
            border: 1px solid #000000;
            padding: 5px;
            text-align: left;
            vertical-align: middle;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: separate;
            border-spacing: 30px 0;
        }

        .signature-table>tbody>tr>td {
            vertical-align: top;
            width: 33%;
        }

        .signature-table table {
            width: 100%;
            border: none;
            background: none;
        }

        .signature-title {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            padding-bottom: 10px;
        }

        .signature-box {
            height: 100px;
            text-align: center;
            vertical-align: middle;
            padding: 10px 0;
        }

        .signature-box img {
            max-height: 90px;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        .no-signature {
            color: #888;
            font-style: italic;
            font-size: 13px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 20px;
            margin: 0 20px;
        }

        .signature-name {
            text-align: center;
            font-weight: bold;
            padding-top: 8px;
            font-size: 14px;
        }

        .rejected {
            color: #d9534f;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            padding: 0;
        }

        @media print {
            .signature-table {
                border-spacing: 20px 0;
            }

            .signature-box {
                height: 80px;
            }
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ asset('assets/img/bgpengajuan.png') }}" alt="" width="100%" height="100%">
    </div>
    <div style="margin-top: 0.3cm; margin-bottom: 0.3cm; align-content:center;">
        <center>
            <span style="font-size: 14pt; font-weight: bold;">PENGAJUAN PENAMBAHAN ITEM BARU</span>
        </center>
    </div>

    <div class="mb-4">
        <p>
            Pada tanggal <strong>{{ \Carbon\Carbon::parse($data->Tanggal)->translatedFormat('d F Y') }}</strong>,
            user <strong>{{ $data->getDiajukan->name ?? '-' }}</strong>
            <strong>{{ $data->Unit ?? '-' }}</strong> telah mengajukan permohonan penambahan item baru ke dalam master
            item group CSSD.
        </p>

        <p>
            Pengajuan ini dilakukan untuk memenuhi kebutuhan operasional dan mendukung efisiensi kerja di unit yang
            bersangkutan. Status pengajuan saat ini adalah:
            @if($data->Status == 'Y')
                <strong class="text-success">Disetujui</strong>,
            @elseif($data->Status == 'N')
                <strong class="text-danger">Ditolak</strong>,
            @else
                <strong class="text-warning">Menunggu Persetujuan</strong>,
            @endif
            sesuai dengan proses evaluasi yang sedang atau telah dilakukan oleh pihak terkait.
        </p>

        @if($data->Catatan)
            <p>
                Catatan tambahan dari pengusul: <em>"{{ $data->Catatan }}"</em>
            </p>
        @endif
    </div>

    <center>
        <p style="font-weight :bold;">Daftar Item Baru yang Diajukan</p>
    </center>

    <table id="tabelitem">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Kode Instrumen</th>
                <th style="width: 30%;">Nama Item</th>
                <th style="width: 10%;">Merk</th>
                <th style="width: 10%;">Tipe / Kategori</th>
                <th style="width: 30%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data->getDetail as $index => $detail)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $detail->KodeInstrumen ?? '-' }}</td>
                    <td>{{ $detail->NamaItem ?? '-' }}</td>
                    <td>{{ $detail->getMerk->Merk ?? '-' }}</td>
                    <td>{{ $detail->getType->Tipe ?? '-' }}</td>
                    <td>{{ $detail->Keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data item baru</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Section Title -->
    <table style="margin-top: 20px;">
        <tr>
            <td class="section-title">Catatan Tambahan</td>
        </tr>
    </table>

    <!-- Catatan Tambahan -->
    <table class="catatan-table">
        <tr>
            <td>{{ $data->Catatan ?? 'Tidak ada catatan tambahan' }}</td>
        </tr>
    </table>

    <table class="signature-table" id="ttd">
        <tr>
            <!-- Diajukan Oleh -->
            <td>
                <table>
                    <tr>
                        <td class="signature-title">Diajukan Oleh</td>
                    </tr>
                    <tr>
                        <td class="signature-box">
                            @if(!empty($data->getDiajukan->ttd))
                                <img src="{{ asset('storage/tandatangan/' . $data->getDiajukan->ttd) }}" alt="Tanda Tangan">
                            @else
                                <span class="no-signature">Tidak ada tanda tangan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="signature-line"></td>
                    </tr>
                    <tr>
                        <td class="signature-name">( {{ $data->getDiajukan->name ?? '-' }} )</td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr>
                        <td class="signature-title">Manager</td>
                    </tr>
                    <tr>
                        <td class="signature-box" style="text-align: center;">
                            @if($data->Status === 'Y')
                                @if(!empty($data->getManager->ttd))
                                    <img src="{{ asset('storage/tandatangan/' . $data->getManager->ttd) }}" alt="Tanda Tangan"
                                        style="max-width:120px;max-height:60px;display:block;margin:0 auto;">
                                @else
                                    <span class="no-signature" style="color: #888;">Tidak ada tanda tangan</span>
                                @endif
                            @elseif($data->Status === 'N')
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <span class="rejected" style="font-size: 40px; color: #d9534f;">&#10007;</span>
                                    <span class="rejected" style="color: #d9534f; font-weight: bold;">DITOLAK</span>
                                </div>
                            @else
                                <span class="no-signature" style="color: #888;">Menunggu persetujuan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="signature-line" style="border-bottom: 1px solid #000; height: 20px;"></td>
                    </tr>
                    <tr>
                        <td class="signature-name" style="text-align: center; padding-top: 5px;">
                            ( {{ $data->getManager->name ?? '-' }} )
                        </td>
                    </tr>
                </table>
            </td>
            <!-- SMI -->

        </tr>
    </table>
</body>

</html>