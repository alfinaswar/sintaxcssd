<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tanda Tangan {{ $nomorTahap }} - {{ $konfigurasi['label'] }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        html, body { overflow-x: hidden; width: 100%; }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            padding: 15px;
            margin: 0;
        }

        .approval-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
        }

        .approval-header {
            background: linear-gradient(135deg, #2c5f8d 0%, #3a7ca5 100%);
            color: #fff;
            padding: 20px;
        }

        .approval-header h3 { margin: 0; font-weight: 600; font-size: 1.3rem; }

        .approval-header p { margin: 5px 0 0; opacity: 0.9; font-size: 0.9rem; word-break: break-word; }

        .approval-body { padding: 20px; }

        .info-section {
            background: #f8f9fa;
            border-left: 4px solid #2c5f8d;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .info-section h5 {
            color: #2c5f8d;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            padding: 6px 0;
            border-bottom: 1px dashed #e0e0e0;
            gap: 8px;
        }

        .info-row:last-child { border-bottom: none; }

        .info-label { min-width: 140px; max-width: 180px; font-weight: 500; color: #555; flex-shrink: 0; }

        .info-value { flex: 1; color: #333; word-break: break-word; min-width: 0; }

        .table-detail { font-size: 13px; margin-bottom: 0; }

        .table-detail thead { background: #2c5f8d; color: #fff; }

        .table-detail th, .table-detail td { padding: 8px; vertical-align: middle; white-space: nowrap; }

        .table-responsive-custom { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 0 -15px; padding: 0 15px; }

        .tahap-list { display: flex; flex-wrap: wrap; gap: 6px; margin: 0; padding: 0; list-style: none; }

        .tahap-list li {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            background: #e9ecef;
            color: #495057;
            white-space: nowrap;
        }

        .tahap-list li.done { background: #d4edda; color: #155724; }
        .tahap-list li.active { background: #2c5f8d; color: #fff; font-weight: 600; }
        .tahap-list li.rejected { background: #f8d7da; color: #721c24; }

        .signature-section {
            background: #fff;
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .signature-section h5 { color: #2c5f8d; font-weight: 600; margin-bottom: 10px; font-size: 1rem; }

        .signature-section p { font-size: 0.85rem; margin-bottom: 10px; }

        #signature-pad {
            width: 100%;
            height: 180px;
            border: 2px solid #2c5f8d;
            border-radius: 6px;
            background: #fff;
            cursor: crosshair;
            touch-action: none;
            display: block;
        }

        .signature-actions { margin-top: 10px; }

        .btn-approval {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .btn-approve { background: #28a745; color: #fff; border: none; }
        .btn-approve:hover, .btn-approve:focus { background: #218838; color: #fff; }

        .btn-reject { background: #dc3545; color: #fff; border: none; }
        .btn-reject:hover, .btn-reject:focus { background: #c82333; color: #fff; }

        .approval-footer {
            background: #f8f9fa;
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-done { background: #d4edda; color: #155724; }
        .status-reject { background: #f8d7da; color: #721c24; }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.show { display: flex; }

        .spinner-border-lg { width: 3rem; height: 3rem; }

        .form-control { font-size: 16px; }

        .link-box {
            background: #fff;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px;
            font-family: Consolas, monospace;
            font-size: 12px;
            word-break: break-all;
            margin-bottom: 10px;
        }

        .sign-next {
            background: #e7f5ff;
            border: 1px solid #74c0fc;
            border-radius: 8px;
            padding: 15px;
        }

        @media (min-width: 768px) {
            body { padding: 30px; }
            .approval-header { padding: 25px 30px; }
            .approval-header h3 { font-size: 1.5rem; }
            .approval-body { padding: 30px; }
            .info-section { padding: 20px; }
            .info-label { min-width: 180px; max-width: 220px; }
            .signature-section { padding: 20px; }
            #signature-pad { height: 200px; }
            .approval-footer { flex-direction: row; justify-content: flex-end; padding: 20px 30px; }
            .btn-approval { width: auto; margin-bottom: 0; padding: 10px 30px; }
        }

        @media (min-width: 992px) {
            .approval-header h3 { font-size: 1.6rem; }
            .info-label { width: 200px; min-width: 200px; max-width: 200px; }
            .table-detail { font-size: 14px; }
        }

        @media (max-width: 480px) {
            body { padding: 10px; }
            .approval-container { border-radius: 8px; }
            .approval-header { padding: 15px; }
            .approval-header h3 { font-size: 1.1rem; }
            .approval-body { padding: 15px; }
            .info-section { padding: 12px; }
            .info-label { min-width: 100%; max-width: 100%; font-size: 13px; }
            .info-value { width: 100%; padding-left: 10px; font-size: 13px; }
            #signature-pad { height: 150px; }
            .btn-approval { font-size: 13px; padding: 10px 15px; }
        }
    </style>
</head>

<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary spinner-border-lg" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Menyimpan tanda tangan...</p>
        </div>
    </div>

    <div class="approval-container">
        <div class="approval-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px;">
                <div style="min-width: 0; flex: 1;">
                    <h3>
                        <i class="fas fa-file-signature mr-2"></i>
                        Tanda Tangan {{ $nomorTahap }} - {{ $konfigurasi['label'] }}
                    </h3>
                    <p>No. Pengajuan: <strong>{{ $data->NomorPengajuan ?? '-' }}</strong></p>
                </div>
                <div>
                    @if ($data->Status === 'disetujui')
                        <span class="status-badge status-done"><i class="fas fa-check"></i> Selesai</span>
                    @elseif ($data->Status === 'ditolak')
                        <span class="status-badge status-reject"><i class="fas fa-times"></i> Ditolak</span>
                    @elseif ($bolehProses)
                        <span class="status-badge status-pending"><i class="fas fa-pen"></i> Menunggu tanda tangan</span>
                    @elseif ($sudahDitandatangani)
                        <span class="status-badge status-done"><i class="fas fa-check"></i> Sudah ditandatangani</span>
                    @else
                        <span class="status-badge status-pending"><i class="fas fa-hourglass-half"></i>
                            {{ $data->labelStatus() }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="approval-body">
            {{-- Urutan Sign --}}
            <div class="info-section">
                <h5><i class="fas fa-tasks mr-2"></i>Urutan Tanda Tangan</h5>
                <ul class="tahap-list">
                    @foreach (\App\Models\PenghapusanAset::TAHAP as $key => $config)
                        @php
                            $acc = $data->accTahap($key);
                            $kelas = '';
                            if ($acc === 'Y') {
                                $kelas = 'done';
                            } elseif ($acc === 'N') {
                                $kelas = 'rejected';
                            } elseif ($key === $tahapAktif) {
                                $kelas = 'active';
                            }
                        @endphp
                        <li class="{{ $kelas }}">
                            @if ($acc === 'Y')
                                <i class="fas fa-check"></i>
                            @elseif ($acc === 'N')
                                <i class="fas fa-times"></i>
                            @endif
                            {{ $config['sign'] }} - {{ $config['label'] }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Informasi Pengajuan --}}
            <div class="info-section">
                <h5><i class="fas fa-info-circle mr-2"></i>Informasi Pengajuan</h5>
                <div class="info-row">
                    <div class="info-label">No. Pengajuan</div>
                    <div class="info-value">: {{ $data->NomorPengajuan ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Pengajuan</div>
                    <div class="info-value">:
                        {{ $data->Tanggal ? \Carbon\Carbon::parse($data->Tanggal)->format('d M Y') : '-' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gudang Tujuan</div>
                    <div class="info-value">: {{ $data->getGudang->Nama ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Departemen</div>
                    <div class="info-value">: {{ $data->getDepartemen->nama ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Unit</div>
                    <div class="info-value">: {{ $data->Unit ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Diajukan Oleh</div>
                    <div class="info-value">: {{ $data->getDiajukan->name ?? '-' }}</div>
                </div>
                @if ($data->Catatan)
                    <div class="info-row">
                        <div class="info-label">Catatan</div>
                        <div class="info-value">: {{ $data->Catatan }}</div>
                    </div>
                @endif
            </div>

            {{-- Detail Aset --}}
            <div class="info-section">
                <h5><i class="fas fa-boxes mr-2"></i>Detail Aset yang Dihapus</h5>
                <div class="table-responsive-custom">
                    <table class="table table-bordered table-detail">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>SN</th>
                                <th>Nama Item</th>
                                <th>Tujuan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data->getDetail as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->NoInventaris ?? '-' }}</td>
                                    <td>{{ $detail->getItem->nama ?? '-' }}</td>
                                    <td>{{ $detail->Metode ?? '-' }}</td>
                                    <td>{{ $detail->Keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada detail aset</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($bolehProses)
                <form id="formApproval" action="{{ route('pa.approval.publik.proses', ['id' => $data->id, 'tahap' => $tahap]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="action_type" id="action_type" value="">
                    <input type="hidden" name="signature" id="signature">

                    <div class="signature-section">
                        <h5><i class="fas fa-signature mr-2"></i>Tanda Tangan {{ $konfigurasi['sign'] }}
                            ({{ $konfigurasi['label'] }})</h5>
                        <p class="text-muted">Silakan tanda tangani pada kotak di bawah ini menggunakan mouse atau jari
                            (touch screen).</p>

                        <canvas id="signature-pad"></canvas>

                        <div class="signature-actions">
                            <button type="button" class="btn btn-sm btn-secondary" id="clear-signature">
                                <i class="fas fa-eraser"></i> Hapus Tanda Tangan
                            </button>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="nama_penandatangan">Nama Penandatangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_penandatangan" name="nama_penandatangan"
                            placeholder="Masukkan nama lengkap Anda" required autocomplete="name">
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan (wajib bila menolak)</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="2"
                            placeholder="Alasan penolakan atau catatan tambahan"></textarea>
                    </div>
                </form>
            @else
                <div class="alert alert-info">
                    @if ($data->Status === 'disetujui')
                        <i class="fas fa-check-circle mr-1"></i> Pengajuan ini sudah selesai.
                    @elseif ($data->Status === 'ditolak')
                        <i class="fas fa-times-circle mr-1"></i> Pengajuan ini sudah ditolak.
                        @if ($data->CatatanApproval)
                            <div class="mt-2"><strong>Alasan:</strong> {{ $data->CatatanApproval }}</div>
                        @endif
                    @elseif ($sudahDitandatangani)
                        <i class="fas fa-check-circle mr-1"></i> {{ $konfigurasi['sign'] }}
                        ({{ $konfigurasi['label'] }}) sudah ditandatangani oleh
                        <strong>{{ $data->namaTahap($tahap) ?? '-' }}</strong>.
                    @else
                        <i class="fas fa-hourglass-half mr-1"></i> {{ $konfigurasi['sign'] }}
                        ({{ $konfigurasi['label'] }}) belum bisa ditandatangani. Pengajuan masih menunggu:
                        <strong>{{ $data->labelStatus() }}</strong>.
                    @endif
                </div>
            @endif

            {{-- Setelah Sign ini terisi, link yang disalin otomatis untuk Sign berikutnya --}}
            @if ($tahapAktif && $tahapAktif !== $tahap && $urlTahapAktif)
                <div class="sign-next">
                    <h5 class="mb-2">
                        <i class="fas fa-link mr-1"></i>
                        Link untuk {{ \App\Models\PenghapusanAset::TAHAP[$tahapAktif]['sign'] }}
                        ({{ \App\Models\PenghapusanAset::konfigurasiTahap($tahapAktif)['label'] }})
                    </h5>
                    <div class="link-box" id="linkBerikut">{{ $urlTahapAktif }}</div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="salinLinkBerikut()">
                        <i class="fas fa-copy"></i> Salin Link
                    </button>
                    <a href="{{ $urlTahapAktif }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt"></i> Buka
                    </a>
                </div>
            @endif
        </div>

        <div class="approval-footer">
            @if ($bolehProses)
                <button type="button" class="btn btn-reject btn-approval" onclick="submitApproval('reject')">
                    <i class="fas fa-times-circle"></i> Tolak
                </button>
                <button type="button" class="btn btn-approve btn-approval" onclick="submitApproval('approve')">
                    <i class="fas fa-check-circle"></i> Tanda Tangani {{ $konfigurasi['sign'] }}
                </button>
            @else
                <button type="button" class="btn btn-secondary btn-approval" onclick="window.location.reload()">
                    <i class="fas fa-sync"></i> Muat Ulang
                </button>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const prosesUrl = "{{ route('pa.approval.publik.proses', ['id' => $data->id, 'tahap' => $tahap]) }}";

        function salinLinkBerikut() {
            const teks = document.getElementById('linkBerikut').innerText.trim();

            function berhasil() {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersalin',
                    text: 'Link tanda tangan berikutnya sudah disalin.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            if (navigator.clipboard) {
                navigator.clipboard.writeText(teks).then(berhasil);
            } else {
                const el = document.createElement('textarea');
                el.value = teks;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                berhasil();
            }
        }

        @if ($bolehProses)
            const canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                minWidth: 1,
                maxWidth: 2.5
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const data = signaturePad.toData();

                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);

                signaturePad.clear();
                if (data && data.length > 0) {
                    signaturePad.fromData(data);
                }
            }

            let resizeTimeout;
            window.addEventListener("resize", function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(resizeCanvas, 200);
            });

            setTimeout(resizeCanvas, 100);

            document.getElementById('clear-signature').addEventListener('click', function() {
                signaturePad.clear();
            });

            function submitApproval(actionType) {
                const nama = $('#nama_penandatangan').val().trim();
                const catatan = $('#catatan').val().trim();
                const isEmpty = signaturePad.isEmpty();

                if (!nama) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nama Kosong',
                        text: 'Silakan isi nama penandatangan terlebih dahulu.',
                        confirmButtonColor: '#2c5f8d'
                    });
                    $('#nama_penandatangan').focus();
                    return;
                }

                if (isEmpty) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanda Tangan Kosong',
                        text: 'Silakan buat tanda tangan terlebih dahulu.',
                        confirmButtonColor: '#2c5f8d'
                    });
                    return;
                }

                if (actionType === 'reject' && !catatan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Catatan Kosong',
                        text: 'Silakan isi alasan penolakan.',
                        confirmButtonColor: '#2c5f8d'
                    });
                    $('#catatan').focus();
                    return;
                }

                const actionText = actionType === 'approve' ? 'menandatangani' : 'menolak';
                const actionColor = actionType === 'approve' ? '#28a745' : '#dc3545';

                Swal.fire({
                    title: `Yakin ingin ${actionText}?`,
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: actionType === 'approve' ? 'question' : 'warning',
                    showCancelButton: true,
                    confirmButtonColor: actionColor,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#action_type').val(actionType);
                        $('#signature').val(signaturePad.toDataURL('image/png'));

                        $('#loadingOverlay').addClass('show');

                        $.ajax({
                            url: prosesUrl,
                            type: 'POST',
                            data: $('#formApproval').serialize(),
                            success: function(response) {
                                $('#loadingOverlay').removeClass('show');
                                Swal.fire({
                                    icon: 'success',
                                    title: actionType === 'approve' ? 'Berhasil!' : 'Ditolak!',
                                    text: response.message || `Pengajuan berhasil ${actionText}.`,
                                    confirmButtonColor: actionColor
                                }).then(() => {
                                    window.location.href = response.redirect || window.location.href;
                                });
                            },
                            error: function(xhr) {
                                $('#loadingOverlay').removeClass('show');
                                let errorMsg = 'Terjadi kesalahan saat menyimpan tanda tangan.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: errorMsg,
                                    confirmButtonColor: '#2c5f8d'
                                });
                            }
                        });
                    }
                });
            }
        @endif
    </script>
</body>

</html>
