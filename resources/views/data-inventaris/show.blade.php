@extends('layouts.app')

@section('content')
    @php
        // Inisialisasi R2 Client sekali di awal
        $r2 = new \App\Helpers\R2Client();

        // Tentukan warna badge klasifikasi
        $bgwarna = 'badge-secondary';
        if ($item->klasifikasi == 'High Risk') {
            $bgwarna = 'badge-danger';
        } elseif ($item->klasifikasi == 'Medium Risk') {
            $bgwarna = 'badge-warning';
        } elseif ($item->klasifikasi == 'Low to Medium Risk') {
            $bgwarna = 'badge-info';
        } elseif ($item->klasifikasi == 'Low Risk') {
            $bgwarna = 'badge-success';
        }

        // Siapkan URL R2 di awal agar kode HTML lebih bersih
        $gambarUrl = $item->gambar ? $r2->getUrl('gambar/' . $item->gambar) : asset('imagenotfound.png');
        $manualbookUrl = $item->manualbook ? $r2->getUrl('manualbook/' . $item->manualbook) : null;

        // ==========================================
        // LOGIKA STATUS KALIBRASI TERBARU
        // ==========================================
        $latestKalibrasi = $item->getKalibrasi ? $item->getKalibrasi->sortByDesc('tgl_kalibrasi')->first() : null;
        $kalibrasiStatus = 'safe'; // safe, warning, danger
        $kalibrasiMessage = 'Belum ada data';
        $kalibrasiDaysLeft = 0;
        $kalibrasiBg = '#f0fdf4'; // Default hijau muda
        $kalibrasiBorder = '#1bc5bd'; // Default hijau

        if ($latestKalibrasi && $latestKalibrasi->exp_date) {
            $expDate = \Carbon\Carbon::parse($latestKalibrasi->exp_date);
            $now = \Carbon\Carbon::now();
            // diffInDays dengan parameter false agar bernilai negatif jika sudah lewat
            $kalibrasiDaysLeft = $now->diffInDays($expDate, false);

            if ($kalibrasiDaysLeft < 0) {
                $kalibrasiStatus = 'danger';
                $kalibrasiMessage = 'Sudah Kadaluarsa!';
                $kalibrasiBg = '#fff5f5';
                $kalibrasiBorder = '#f64e60';
            } elseif ($kalibrasiDaysLeft <= 30) {
                // Peringatan jika <= 30 hari
                $kalibrasiStatus = 'warning';
                $kalibrasiMessage = 'Mendekati Kadaluarsa (' . abs($kalibrasiDaysLeft) . ' hari lagi)';
                $kalibrasiBg = '#fff8dd';
                $kalibrasiBorder = '#ffa800';
            } else {
                $kalibrasiStatus = 'success';
                $kalibrasiMessage = 'Masih Berlaku (' . $kalibrasiDaysLeft . ' hari lagi)';
                $kalibrasiBg = '#f0fdf4';
                $kalibrasiBorder = '#1bc5bd';
            }
        }
    @endphp

    <style>
        .detail-img-container {
            background: #f3f6f9;
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            border: 1px dashed #ebedf2;
        }

        .detail-img {
            max-height: 280px;
            width: 100%;
            object-fit: contain;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }

        .info-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #ebedf2;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 150px;
            font-size: 0.9rem;
            color: #b5b5c3;
            font-weight: 600;
        }

        .info-value {
            flex: 1;
            font-size: 0.95rem;
            color: #3f4254;
            font-weight: 500;
        }

        .kt-portlet {
            border: none !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
            border-radius: 0.75rem !important;
            margin-bottom: 1.5rem;
        }
    </style>

    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">

        <!-- 1. HEADER & ACTION BUTTONS -->
        <div class="kt-subheader kt-grid__item mb-4">
            <div class="kt-container kt-container--fluid">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">Detail Inventaris</h3>
                    <span class="kt-subheader__desc">Informasi lengkap, riwayat maintenance, dan dokumentasi alat.</span>
                </div>
                <div class="kt-subheader__toolbar">
                    <div class="kt-subheader__wrapper">
                        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-brand btn-sm ml-2">
                            <i class="fa fa-edit"></i> Edit Data
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. MAIN PROFILE CARD -->
        <div class="kt-portlet">
            <div class="kt-portlet__body">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-5 mb-4 mb-md-0 text-center">
                        <div class="detail-img-container">
                            <img src="{{ $gambarUrl }}" alt="Gambar Alat" class="detail-img" />
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div class="d-flex flex-wrap align-items-center mb-3">
                            <h3 class="text-dark font-weight-bold mb-0 mr-3">{{ $item->nama }}</h3>
                            <span class="badge {{ $bgwarna }} px-3 py-2" style="font-size: 0.9rem;">
                                <i class="flaticon-warning mr-1"></i> {{ strtoupper($item->klasifikasi ?? 'Unknown') }}
                            </span>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-row"><span class="info-label">No. Inventaris</span><span
                                        class="info-value">{{ $item->no_inventaris }}</span></div>
                                <div class="info-row"><span class="info-label">Merk / Tipe</span><span
                                        class="info-value">{{ $item->merk }} {{ $item->real_name }}</span></div>
                                <div class="info-row"><span class="info-label">Serial Number</span><span
                                        class="info-value">{{ $item->no_sn ?: '-' }}</span></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row"><span class="info-label">Departemen</span><span
                                        class="info-value">{{ $item->departemen }}</span></div>
                                <div class="info-row"><span class="info-label">Unit / Lokasi</span><span
                                        class="info-value">{{ $item->unit }}</span></div>
                                <div class="info-row"><span class="info-label">Pengguna</span><span
                                        class="info-value">{{ $item->pengguna }}</span></div>
                            </div>
                        </div>

                        @if ($manualbookUrl)
                            <a href="{{ $manualbookUrl }}" target="_blank" class="btn btn-outline-success btn-sm">
                                <i class="fa fa-file-pdf-o mr-2"></i> Download / Lihat Manual Book (SPO)
                            </a>
                        @else
                            <span class="text-muted font-italic"><i class="fa fa-info-circle mr-1"></i> Manual Book belum
                                diupload</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. TABBED CONTENT -->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand tab-remember" role="tablist">
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_general" role="tab"><i
                                    class="flaticon-information mr-1"></i> Informasi Umum</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_kalibrasi" role="tab"><i
                                    class="flaticon-clipboard mr-1"></i> Riwayat Kalibrasi</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_pm" role="tab"><i
                                    class="flaticon-calendar-1 mr-1"></i> Preventive Maintenance</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_pembersihan" role="tab"><i
                                    class="flaticon-interface-8 mr-1"></i> Formulir Pembersihan</a></li>
                    </ul>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="tab-content">

                    <!-- TAB 1: Informasi Umum -->
                    <div class="tab-pane" id="tab_general" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row"><span class="info-label">ROID / RO2ID</span><span
                                        class="info-value">{{ $item->ROID }} / {{ $item->RO2ID }}</span></div>
                                <div class="info-row"><span class="info-label">Tanggal Pembelian</span><span
                                        class="info-value">{{ \Carbon\Carbon::parse($item->tanggal_beli)->format('d F Y') }}</span>
                                </div>
                                <div class="info-row"
                                    style="background-color: {{ $kalibrasiBg }}; border-radius: 8px; padding: 12px; margin-top: 8px; border-left: 4px solid {{ $kalibrasiBorder }};">
                                    <span class="info-label" style="color: #3f4254; width: 130px;">
                                        <i class="flaticon-clipboard mr-1"></i> Kalibrasi
                                    </span>
                                    <div class="info-value">
                                        @if ($latestKalibrasi)
                                            <div class="d-flex align-items-center flex-wrap mb-1">
                                                <span class="font-weight-bold mr-3" style="color: #3f4254;">
                                                    Exp:
                                                    {{ \Carbon\Carbon::parse($latestKalibrasi->exp_date)->format('d M Y') }}
                                                </span>
                                                <span
                                                    class="label label-inline label-{{ $kalibrasiStatus }} font-weight-bold">
                                                    @if ($kalibrasiStatus == 'danger')
                                                        <i class="fa fa-exclamation-triangle mr-1"></i>
                                                    @elseif($kalibrasiStatus == 'warning')
                                                        <i class="fa fa-clock mr-1"></i>
                                                    @else
                                                        <i class="fa fa-check-circle mr-1"></i>
                                                    @endif
                                                    {{ $kalibrasiMessage }}
                                                </span>
                                            </div>
                                            @if ($latestKalibrasi->dokumen)
                                                <a href="{{ $r2->getUrl('dokumen/' . $latestKalibrasi->dokumen) }}"
                                                    target="_blank" class="btn btn-xs btn-{{ $kalibrasiStatus }} mt-1">
                                                    <i class="fa fa-file-pdf-o"></i> Lihat Sertifikat Terbaru
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted font-italic">Belum ada riwayat kalibrasi.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-row"><span class="info-label">Keterangan</span><span
                                        class="info-value text-wrap">{{ $item->keterangan ?: 'Tidak ada keterangan tambahan.' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Riwayat Kalibrasi (DITAMBAHKAN TOMBOL MODAL DI SINI) -->
                    <!-- TAB 2: Riwayat Kalibrasi -->
                    <div class="tab-pane" id="tab_kalibrasi" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-dark font-weight-bold mb-0">Daftar Riwayat Kalibrasi</h5>
                            <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal"
                                data-target="#modalKalibrasi">
                                <i class="flaticon2-plus mr-1"></i> Tambah Kalibrasi
                            </button>
                        </div>

                        @if ($item->getKalibrasi && $item->getKalibrasi->count() > 0)
                            <div class="table-responsive">
                                <!-- Class Metronic Professional Table -->
                                <table
                                    class="table table-head-custom table-head-bg table-borderless table-hover align-middle">
                                    <thead>
                                        <tr class="text-left">
                                            <th class="pl-7" style="width: 60px;">No</th>
                                            <th style="width: 160px;">Tanggal Kalibrasi</th>
                                            <th style="width: 160px;">Expire Date</th>
                                            <th>Keterangan</th>
                                            <th class="text-center" style="width: 100px;">Dokumen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Di dalam <tbody> tabel Riwayat Kalibrasi -->
                                        @foreach ($item->getKalibrasi as $index => $kal)
                                            @php
                                                // Cek apakah baris ini adalah yang terbaru
                                                $isLatest = $latestKalibrasi && $kal->id == $latestKalibrasi->id;

                                                // Tentukan warna baris jika sudah kadaluarsa atau mendekati
                                                $rowClass = '';
                                                $rowStyle = '';
                                                if ($isLatest) {
                                                    if ($kalibrasiStatus == 'danger') {
                                                        $rowClass = 'table-danger';
                                                        $rowStyle = 'border-left: 4px solid #f64e60 !important;';
                                                    } elseif ($kalibrasiStatus == 'warning') {
                                                        $rowClass = 'table-warning';
                                                        $rowStyle = 'border-left: 4px solid #ffa800 !important;';
                                                    } else {
                                                        $rowClass = 'table-active';
                                                        $rowStyle = 'border-left: 4px solid #5d78ff !important;';
                                                    }
                                                }
                                            @endphp

                                            <tr class="{{ $rowClass }}" style="{{ $rowStyle }}">
                                                <!-- Nomor Urut + Badge Terbaru -->
                                                <td class="pl-7 font-weight-bold text-muted">
                                                    {{ $index + 1 }}
                                                    @if ($isLatest)
                                                        <span
                                                            class="label label-inline label-light-primary font-weight-bold ml-2">
                                                            <i class="fa fa-star text-primary mr-1"></i> TERBARU
                                                        </span>
                                                    @endif
                                                </td>

                                                <!-- Tanggal -->
                                                <td>
                                                    <span class="font-weight-bold text-dark">
                                                        {{ \Carbon\Carbon::parse($kal->tgl_kalibrasi)->format('d M Y') }}
                                                    </span>
                                                </td>

                                                <!-- Expire Date dengan Badge Dinamis -->
                                                <td>
                                                    @php
                                                        $badgeClass = 'label-light-success';
                                                        $icon = 'fa-check-circle';
                                                        if ($kal->exp_date) {
                                                            $days = \Carbon\Carbon::now()->diffInDays(
                                                                \Carbon\Carbon::parse($kal->exp_date),
                                                                false,
                                                            );
                                                            if ($days < 0) {
                                                                $badgeClass = 'label-light-danger';
                                                                $icon = 'fa-exclamation-triangle';
                                                            } elseif ($days <= 30) {
                                                                $badgeClass = 'label-light-warning';
                                                                $icon = 'fa-clock';
                                                            }
                                                        }
                                                    @endphp
                                                    <span
                                                        class="label label-lg {{ $badgeClass }} label-inline font-weight-bold">
                                                        <i class="fa {{ $icon }} mr-1"></i>
                                                        {{ $kal->exp_date ? \Carbon\Carbon::parse($kal->exp_date)->format('d M Y') : '-' }}
                                                    </span>
                                                </td>

                                                <!-- Keterangan -->
                                                <td>
                                                    <span class="text-muted">{{ $kal->keterangan ?: '-' }}</span>
                                                </td>

                                                <!-- Tombol Dokumen -->
                                                <td class="text-center">
                                                    @if ($kal->dokumen)
                                                        <a href="{{ $r2->getUrl('dokumen/' . $kal->dokumen) }}"
                                                            target="_blank" class="btn btn-sm btn-clean btn-icon"
                                                            data-toggle="tooltip" title="Lihat Dokumen PDF">
                                                            <i class="flaticon2-download text-brand font-size-lg"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Empty State yang Lebih Cantik -->
                            <div class="text-center py-5">
                                <div class="symbol symbol-60 symbol-light-primary mr-5 mb-3 mx-auto">
                                    <span class="symbol-label">
                                        <i class="flaticon-clipboard font-size-2x text-primary"></i>
                                    </span>
                                </div>
                                <h5 class="text-dark font-weight-bold mb-1">Belum Ada Riwayat</h5>
                                <p class="text-muted mb-0">Klik tombol "Tambah Kalibrasi" untuk menambahkan data kalibrasi
                                    pertama.</p>
                            </div>
                        @endif
                    </div>

                    <!-- TAB 3: Preventive Maintenance -->
                    <div class="tab-pane" id="tab_pm" role="tabpanel">
                        @if ($item->DataMaintenance && $item->DataMaintenance->count() > 0)
                            @foreach ($item->DataMaintenance as $pm)
                                <div class="kt-widget2 mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center mb-2">
                                        <span
                                            class="badge {{ $bgwarna }} mr-2">{{ date('M Y', mktime(0, 0, 0, $pm->bulan, 10)) }}</span>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($pm->created_at)->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <span class="d-block mb-1"><i class="fa fa-user-check text-success mr-1"></i>
                                            Oleh: <b>{{ $pm->getUser->name ?? 'Unknown' }}</b></span>
                                        <span class="text-muted d-block">{{ $pm->keterangan }}</span>
                                        @if ($pm->dokumentasi)
                                            <a href="{{ $r2->getUrl('dokumen/' . $pm->dokumentasi) }}" target="_blank"
                                                class="btn btn-sm btn-outline-info mt-2">
                                                <i class="fa fa-paperclip"></i> Lihat Dokumentasi
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="flaticon-calendar-1 font-size-3x mb-3"></i>
                                <p class="mb-0">Belum ada jadwal Preventive Maintenance.</p>
                            </div>
                        @endif
                    </div>

                    <!-- TAB 4: Formulir Pembersihan -->
                    <div class="tab-pane" id="tab_pembersihan" role="tabpanel">
                        @if ($item->getLaporanMonitoring && $item->getLaporanMonitoring->count() > 0)
                            <div class="row">
                                @foreach ($item->getLaporanMonitoring as $bersih)
                                    <div class="col-md-6 mb-4">
                                        <div class="card border-0 shadow-sm">
                                            <div
                                                class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-3">
                                                <span
                                                    class="badge badge-primary">{{ \Carbon\Carbon::parse($bersih->Tanggal)->format('d M Y') }}</span>
                                                <span
                                                    class="badge {{ $bersih->Status == 'Bersih' ? 'badge-success' : 'badge-danger' }} px-3">{{ $bersih->Status }}</span>
                                            </div>
                                            <div class="card-body pt-0">
                                                <h6 class="font-weight-bold mb-1">Petugas: <span
                                                        class="font-weight-normal">{{ $bersih->idUser ?? 'Tanpa Nama' }}</span>
                                                </h6>
                                                <p class="text-muted small mb-3">{{ $bersih->Keterangan }}</p>
                                                <div class="row">
                                                    <div class="col-6 text-center">
                                                        <small class="text-muted d-block mb-2">Sebelum</small>
                                                        @if ($bersih->Before)
                                                            <img src="{{ $r2->getUrl('gambar/Pembersihan/Before/' . $bersih->Before) }}"
                                                                class="img-fluid rounded border"
                                                                style="max-height: 120px; width: 100%; object-fit: cover;"
                                                                alt="Before">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 120px;"><span class="text-muted small">No
                                                                    Image</span></div>
                                                        @endif
                                                    </div>
                                                    <div class="col-6 text-center">
                                                        <small class="text-muted d-block mb-2">Sesudah</small>
                                                        @if ($bersih->After)
                                                            <img src="{{ $r2->getUrl('gambar/Pembersihan/After/' . $bersih->After) }}"
                                                                class="img-fluid rounded border"
                                                                style="max-height: 120px; width: 100%; object-fit: cover;"
                                                                alt="After">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 120px;"><span class="text-muted small">No
                                                                    Image</span></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="flaticon-interface-8 font-size-3x mb-3"></i>
                                <p class="mb-0">Belum ada data formulir pembersihan.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH KALIBRASI (METRONIC STYLE)    -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalKalibrasi" tabindex="-1" role="dialog" aria-labelledby="modalKalibrasiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold" id="modalKalibrasiLabel">
                        <i class="flaticon-clipboard text-brand mr-2"></i> Tambah Data Kalibrasi
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-kalibrasi" action="{{ route('inventaris.store-kalibrasi') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Hidden ID Alat --}}
                        <input type="hidden" name="idalat" id="idalat" value="{{ $item->kode_item }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="nama" class="col-4 col-form-label font-weight-bold">* Nama
                                        Alat</label>
                                    <div class="col-8">
                                        <input type="text" readonly name="nama" id="nama"
                                            class="form-control form-control-solid" value="{{ $item->nama }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label font-weight-bold">* Kepemilikan</label>
                                    <div class="col-8">
                                        <select class="form-control form-control-solid kt-select2" id="kepemilikan"
                                            name="kepemilikan" required>
                                            <option value="" selected disabled>-- Pilih Kepemilikan --</option>
                                            <option value="Rumah Sakit">Rumah Sakit</option>
                                            <option value="Dokter">Dokter</option>
                                            <option value="Vendor">Vendor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label font-weight-bold">Tanggal Kalibrasi</label>
                                    <div class="col-8">
                                        <input type="date" class="form-control form-control-solid"
                                            name="tgl_kalibrasi" id="tgl_kalibrasi">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label font-weight-bold">Expire Date</label>
                                    <div class="col-8">
                                        <input type="date" class="form-control form-control-solid" name="exp_date"
                                            id="exp_date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-4 col-form-label font-weight-bold">* Upload Dokumen</label>
                                    <div class="col-8">
                                        <div class="custom-file">
                                            <!-- Dibatasi hanya accept .pdf -->
                                            <input type="file" class="custom-file-input" name="dokumen"
                                                id="dokumen" accept=".pdf" required>
                                            <label class="custom-file-label" for="dokumen">Pilih file PDF (Max
                                                5MB)...</label>
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fa fa-info-circle mr-1"></i> Hanya format <strong>PDF</strong> yang
                                            diperbolehkan.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-4 col-form-label font-weight-bold">Keterangan</label>
                                    <div class="col-8">
                                        <textarea name="keterangan" id="keterangan" class="form-control form-control-solid" rows="4"
                                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0 pt-0 mt-3">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                            <button type="button" id="btn-simpan-kalibrasi" class="btn btn-brand font-weight-bold px-5">
                                <i class="la la-save"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('after-js')
    <script>
        $(document).ready(function() {
            // ==========================================
            // 1. LOGIKA PERSISTEN TAB (STICKY TAB)
            // ==========================================
            const TAB_KEY = "inventaris_detail_active_tab_{{ $item->id }}";

            // Prioritas 1: Cek URL Hash (misal: user buka link .../1#tab_kalibrasi)
            let targetTab = window.location.hash;

            // Prioritas 2: Cek LocalStorage jika tidak ada hash di URL
            if (!targetTab) {
                targetTab = localStorage.getItem(TAB_KEY);
            }

            // Prioritas 3: Default ke tab pertama jika tidak ada keduanya
            if (!targetTab || !$(targetTab).length) {
                targetTab = '#tab_general';
            }

            // Fungsi untuk mengaktifkan tab secara programatik (Bootstrap 4 compatible)
            function activateSpecificTab(hash) {
                // Nonaktifkan semua tab link dan pane terlebih dahulu
                $('.nav-tabs .nav-link').removeClass('active');
                $('.tab-content .tab-pane').removeClass('active show');

                // Aktifkan tab link dan pane yang sesuai
                $('.nav-tabs .nav-link[href="' + hash + '"]').addClass('active');
                $(hash).addClass('active show'); // 'show' wajib di Bootstrap 4 agar konten muncul
            }

            // Jalankan aktivasi tab saat halaman pertama kali dimuat
            activateSpecificTab(targetTab);

            // Event listener: Setiap kali tab diklik/diubah, simpan ke localStorage & update URL
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let activeTabHash = $(e.target).attr('href');
                localStorage.setItem(TAB_KEY, activeTabHash);

                // Update URL browser tanpa me-reload halaman (agar link bisa di-copy/share)
                if (history.replaceState) {
                    history.replaceState(null, null, activeTabHash);
                }
            });

            // ==========================================
            // 2. SCRIPT CUSTOM FILE INPUT
            // ==========================================
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // ==========================================
            // 3. AJAX SIMPAN KALIBRASI
            // ==========================================
            $('#btn-simpan-kalibrasi').on('click', function(e) {
                e.preventDefault();

                var btn = $(this);
                var form = $('#form-kalibrasi')[0];

                // Validasi sederhana di frontend
                if ($('#dokumen').val() === '') {
                    Swal.fire('Peringatan', 'Silakan pilih file dokumen terlebih dahulu.', 'warning');
                    return;
                }

                var formData = new FormData(form);

                // Ubah tombol jadi loading state
                var originalBtnText = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                );

                $.ajax({
                    url: $('#form-kalibrasi').attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message ||
                                'Data kalibrasi berhasil ditambahkan.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#modalKalibrasi').modal('hide');

                            // Set hash ke tab kalibrasi SEBELUM reload,
                            // agar saat halaman reload, script di atas akan membaca hash ini dan membuka tab kalibrasi
                            window.location.hash = '#tab_kalibrasi';
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Kembalikan tombol ke keadaan semula
                        btn.prop('disabled', false).html(originalBtnText);

                        let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.status === 419) {
                            errorMsg =
                                'Sesi Anda telah berakhir (CSRF Mismatch). Silakan refresh halaman.';
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: errorMsg
                        });
                    }
                });
            });
        });
    </script>
@endpush
