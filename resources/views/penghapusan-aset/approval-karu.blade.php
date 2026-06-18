<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Approval Penghapusan Aset - {{ $data->no_pengajuan ?? 'Detail' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            overflow-x: hidden;
            width: 100%;
        }

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

        .approval-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.3rem;
        }

        .approval-header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
            word-break: break-word;
        }

        .approval-body {
            padding: 20px;
        }

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

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            min-width: 140px;
            max-width: 180px;
            font-weight: 500;
            color: #555;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            color: #333;
            word-break: break-word;
            min-width: 0;
        }

        .table-detail {
            font-size: 13px;
            margin-bottom: 0;
        }

        .table-detail thead {
            background: #2c5f8d;
            color: #fff;
        }

        .table-detail th,
        .table-detail td {
            padding: 8px;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -15px;
            padding: 0 15px;
        }

        .signature-section {
            background: #fff;
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .signature-section h5 {
            color: #2c5f8d;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .signature-section p {
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

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

        .signature-actions {
            margin-top: 10px;
        }

        .btn-approval {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .btn-approve {
            background: #28a745;
            color: #fff;
            border: none;
        }

        .btn-approve:hover,
        .btn-approve:focus {
            background: #218838;
            color: #fff;
        }

        .btn-reject {
            background: #dc3545;
            color: #fff;
            border: none;
        }

        .btn-reject:hover,
        .btn-reject:focus {
            background: #c82333;
            color: #fff;
        }

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

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-overlay.show {
            display: flex;
        }

        .spinner-border-lg {
            width: 3rem;
            height: 3rem;
        }

        .form-control {
            font-size: 16px;
            /* Mencegah auto-zoom di iOS */
        }

        /* Tablet (768px ke atas) */
        @media (min-width: 768px) {
            body {
                padding: 30px;
            }

            .approval-header {
                padding: 25px 30px;
            }

            .approval-header h3 {
                font-size: 1.5rem;
            }

            .approval-body {
                padding: 30px;
            }

            .info-section {
                padding: 20px;
            }

            .info-label {
                min-width: 180px;
                max-width: 220px;
            }

            .signature-section {
                padding: 20px;
            }

            #signature-pad {
                height: 200px;
            }

            .approval-footer {
                flex-direction: row;
                justify-content: flex-end;
                padding: 20px 30px;
            }

            .btn-approval {
                width: auto;
                margin-bottom: 0;
                padding: 10px 30px;
            }
        }

        /* Desktop (992px ke atas) */
        @media (min-width: 992px) {
            .approval-header h3 {
                font-size: 1.6rem;
            }

            .info-label {
                width: 200px;
                min-width: 200px;
                max-width: 200px;
            }

            .table-detail {
                font-size: 14px;
            }
        }

        /* Mobile kecil (max 480px) */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .approval-container {
                border-radius: 8px;
            }

            .approval-header {
                padding: 15px;
            }

            .approval-header h3 {
                font-size: 1.1rem;
            }

            .approval-body {
                padding: 15px;
            }

            .info-section {
                padding: 12px;
            }

            .info-label {
                min-width: 100%;
                max-width: 100%;
                font-size: 13px;
            }

            .info-value {
                width: 100%;
                padding-left: 10px;
                font-size: 13px;
            }

            #signature-pad {
                height: 150px;
            }

            .btn-approval {
                font-size: 13px;
                padding: 10px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary spinner-border-lg" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memproses approval...</p>
        </div>
    </div>

    <div class="approval-container">
        <div class="approval-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center" style="gap: 10px;">
                <div style="min-width: 0; flex: 1;">
                    <h3><i class="fas fa-file-signature mr-2"></i>Approval Penghapusan Aset</h3>
                    <p>No. Pengajuan: <strong>{{ $data->no_pengajuan ?? '-' }}</strong></p>
                </div>
                <div>
                    <span class="status-badge status-pending">
                        <i class="fas fa-clock"></i> Menunggu Approval
                    </span>
                </div>
            </div>
        </div>

        <div class="approval-body">
            <!-- Informasi Pengajuan -->
            <div class="info-section">
                <h5><i class="fas fa-info-circle mr-2"></i>Informasi Pengajuan</h5>
                <div class="info-row">
                    <div class="info-label">No. Pengajuan</div>
                    <div class="info-value">: {{ $data->NomorPengajuan ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Pengajuan</div>
                    <div class="info-value">:
                        {{ $data->Tanggal ? \Carbon\Carbon::parse($data->tanggal_pengajuan)->format('d M Y') : '-' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gudang</div>
                    <div class="info-value">: {{ $data->getGudang->Nama ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Departemen</div>
                    <div class="info-value">: {{ $data->getDepartemen->Nama ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Unit</div>
                    <div class="info-value">: {{ $data->Unit ?? '-' }}</div>
                </div>

            </div>

            <!-- Detail Aset yang Dihapus -->
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
                                    <td colspan="7" class="text-center">Tidak ada detail aset</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Approval -->
            <form id="formApproval" action="{{ route('penghapusan-aset.approval-karu.submit', $data->id) }}"
                method="POST">
                @csrf
                <input type="hidden" name="action_type" id="action_type" value="">
                <input type="hidden" name="signature" id="signature">

                <div class="signature-section">
                    <h5><i class="fas fa-signature mr-2"></i>Tanda Tangan Digital</h5>
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
                        placeholder="Masukkan nama lengkap Anda" required autocomplete="name"
                        value="{{ $data->NamaKaru ?? '' }}">

                </div>
            </form>
        </div>

        <div class="approval-footer">
            <button type="button" class="btn btn-secondary btn-approval" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            <button type="button" class="btn btn-reject btn-approval" onclick="submitApproval('reject')">
                <i class="fas fa-times-circle"></i> Tolak
            </button>
            <button type="button" class="btn btn-approve btn-approval" onclick="submitApproval('approve')">
                <i class="fas fa-check-circle"></i> Approve
            </button>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Signature Pad -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize Signature Pad
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 1,
            maxWidth: 2.5
        });

        // Resize canvas to fit container (responsive)
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const data = signaturePad.toData(); // Simpan data sebelum resize

            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            signaturePad.clear();
            if (data && data.length > 0) {
                signaturePad.fromData(data);
            }
        }

        // Debounce resize untuk performa
        let resizeTimeout;
        window.addEventListener("resize", function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(resizeCanvas, 200);
        });

        // Initial resize
        setTimeout(resizeCanvas, 100);

        // Clear signature button
        document.getElementById('clear-signature').addEventListener('click', function() {
            signaturePad.clear();
        });

        // Submit approval
        function submitApproval(actionType) {
            const nama = $('#nama_penandatangan').val().trim();
            const isEmpty = signaturePad.isEmpty();

            // Validation
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

            // Confirmation
            const actionText = actionType === 'approve' ? 'menyetujui' : 'menolak';
            const actionIcon = actionType === 'approve' ? 'question' : 'warning';
            const actionColor = actionType === 'approve' ? '#28a745' : '#dc3545';

            Swal.fire({
                title: `Yakin ingin ${actionText}?`,
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: actionIcon,
                showCancelButton: true,
                confirmButtonColor: actionColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set form data
                    const signatureData = signaturePad.toDataURL('image/png');
                    $('#action_type').val(actionType);
                    $('#signature').val(signatureData);

                    // Show loading
                    $('#loadingOverlay').addClass('show');

                    // Submit via AJAX
                    $.ajax({
                        url: $('#formApproval').attr('action'),
                        type: 'POST',
                        data: $('#formApproval').serialize(),
                        success: function(response) {
                            $('#loadingOverlay').removeClass('show');
                            Swal.fire({
                                icon: 'success',
                                title: actionType === 'approve' ? 'Disetujui!' : 'Ditolak!',
                                text: response.message || `Pengajuan berhasil ${actionText}.`,
                                confirmButtonColor: actionColor
                            }).then(() => {
                                if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    window.location.reload();
                                }
                            });
                        },
                        error: function(xhr) {
                            $('#loadingOverlay').removeClass('show');
                            let errorMsg = 'Terjadi kesalahan saat memproses approval.';
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
    </script>
</body>

</html>
