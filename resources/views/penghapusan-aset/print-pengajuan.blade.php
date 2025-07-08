<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Purchase Order</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: 'Arial ', sans-serif;
            /* font-size: 12px; */
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
            /* width: 21cm;
            height: 29.7cm; */
            width: 21cm;
            height: 29.7cm;
            z-index: -10;
        }




        #tabelalat {
            border-collapse: collapse;
            width: 100%;
            padding: 1px;
            vertical-align: middle;
            line-height: 15px;
        }

        #tabelalat th {
            border: 1px solid #000000;
            text-align: center;
            vertical-align: middle;
        }

        #tabelalat td {
            border: 1px solid #000000;
            padding: 5px;
            text-align: left;
            vertical-align: middle;
        }

        #infoumum {
            border-collapse: collapse;
            width: 100%;
            padding: 1px;
            vertical-align: middle;
            line-height: 15px;
        }

        #infoumum th {
            border: 1px solid #000000;
            text-align: left;
            vertical-align: middle;
        }

        #infoumum td {
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
            <span style="font-size: 14pt; font-weight: bold;">PENGAJUAN PENGHAPUSAN DATA INVENTARIS</span>
        </center>
    </div>


    <div class="mb-4">
        <p>
            Pada tanggal <strong>{{ \Carbon\Carbon::parse($data->Tanggal)->translatedFormat('d F Y') }}</strong>,
            departemen <strong>{{ $data->getDepartemen->nama ?? '-' }}</strong> melalui unit
            <strong>{{ $data->Unit ?? '-' }}</strong> telah mengajukan permohonan resmi untuk
            melakukan penghapusan terhadap satu atau beberapa aset inventaris yang sudah tidak
            digunakan, rusak, atau tidak layak pakai.
        </p>

        <p>
            Pengajuan ini diajukan sebagai bagian dari upaya penertiban dan pembaruan data aset,
            agar tercipta efisiensi dalam pengelolaan inventaris di lingkungan unit kerja terkait.
            Saat ini, pengajuan tersebut tercatat dengan status:
            @if($data->Status == 'pengajuan')
                <strong class="text-warning">Menunggu Persetujuan</strong>,
            @elseif($data->Status == 'disetujui')
                <strong class="text-success">Disetujui</strong>,
            @elseif($data->Status == 'ditolak')
                <strong class="text-danger">Ditolak</strong>,
            @else
                <strong>{{ ucfirst($data->Status) }}</strong>,
            @endif
            sesuai dengan proses evaluasi yang sedang atau telah dilakukan oleh pihak yang berwenang.
        </p>

        @if($data->Catatan)
            <p>
                Catatan tambahan dari pengusul: <em>"{{ $data->Catatan }}"</em>
            </p>
        @endif
    </div>



    <center>
        <p style="font-weight :bold;">Daftar inventaris yang akan dihapus</p>
    </center>


    <table id="tabelalat">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 60%;">Nama</th>
                <th style="width: 10%;">No Inventaris</th>
                <th style="width: 40%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data->getDetail as $index => $detail)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->getItem->nama ?? '' }}

                    </td>
                    <td>
                        {{ $detail->getItem->no_inventaris ?? '' }}

                    </td>
                    <td>{{ $detail->Keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data aset</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Section Title -->
    <table>
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
                        <td class="signature-title">Manager </td>
                    </tr>
                    <tr>
                        <td class="signature-box">
                            @if($data->AccManager === 'Y')
                                @if(!empty($data->getManager->ttd))
                                    <img src="{{ asset('storage/tandatangan/' . $data->getManager->ttd) }}" alt="Tanda Tangan">
                                @else
                                    <span class="no-signature">Tidak ada tanda tangan</span>
                                @endif
                            @elseif($data->AccManager === 'N')
                                <table style="width:100%;border:none;background:none;">
                                    <tr>
                                        <td class="rejected" style="font-size: 40px;">&#10007;</td>
                                    </tr>
                                    <tr>
                                        <td class="rejected">DITOLAK</td>
                                    </tr>
                                </table>
                            @else
                                <span class="no-signature">Menunggu persetujuan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="signature-line"></td>
                    </tr>
                    <tr>
                        <td class="signature-name">( {{ $data->getManager->name ?? '-' }} )</td>
                    </tr>
                </table>
            </td>

            <!-- SMI -->
            <td>
                <table>
                    <tr>
                        <td class="signature-title">SMI</td>
                    </tr>
                    <tr>
                        <td class="signature-box">
                            @if($data->AccSmi === 'Y')
                                @if(!empty($data->getSmi->ttd))
                                    <img src="{{ asset('storage/tandatangan/' . $data->getSmi->ttd) }}" alt="Tanda Tangan">
                                @else
                                    <span class="no-signature">Tidak ada tanda tangan</span>
                                @endif
                            @elseif($data->AccSmi === 'N')
                                <table style="width:100%;border:none;background:none;">
                                    <tr>
                                        <td class="rejected" style="font-size: 40px;">&#10007;</td>
                                    </tr>
                                    <tr>
                                        <td class="rejected">DITOLAK</td>
                                    </tr>
                                </table>
                            @else
                                <span class="no-signature">Menunggu persetujuan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="signature-line"></td>
                    </tr>
                    <tr>
                        <td class="signature-name">( {{ $data->getSmi->name ?? '-' }} )</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



    </section>
</body>

</html>
