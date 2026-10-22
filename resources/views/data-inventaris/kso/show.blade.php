@extends('layouts.app')
@push('title')
    Detail Inventaris KSO
@endpush

@section('content')
    @php
        // Inisialisasi R2 Client sekali di awal
        $r2 = new \App\Helpers\R2Client();

        // Tentukan warna badge klasifikasi
        $bgwarna = 'badge-secondary';
        if ($item->Klasifikasi == 'High Risk') {
            $bgwarna = 'badge-danger';
        } elseif ($item->Klasifikasi == 'Medium Risk') {
            $bgwarna = 'badge-warning';
        } elseif ($item->Klasifikasi == 'Low to Medium Risk') {
            $bgwarna = 'badge-info';
        } elseif ($item->Klasifikasi == 'Low Risk') {
            $bgwarna = 'badge-success';
        }

        // URL R2 untuk gambar & manual book (folder khusus KSO)
        $gambarUrl = $item->Gambar
            ? $r2->getUrl('inventariskso/gambar/' . $item->Gambar)
            : asset('imagenotfound.png');
        $manualbookUrl = $item->Manualbook ? $r2->getUrl('inventariskso/manualbook/' . $item->Manualbook) : null;

        // ==========================================
        // LOGIKA STATUS KALIBRASI TERBARU
        // ==========================================
        $latestKalibrasi = $item->getKalibrasi ? $item->getKalibrasi->sortByDesc('TglKalibrasi')->first() : null;
        $kalibrasiStatus = 'safe'; // safe, warning, danger
        $kalibrasiMessage = 'Belum ada data';
        $kalibrasiDaysLeft = 0;
        $kalibrasiBg = '#f0fdf4';
        $kalibrasiBorder = '#1bc5bd';

        if ($latestKalibrasi && $latestKalibrasi->TglExpire) {
            $expDate = \Carbon\Carbon::parse($latestKalibrasi->TglExpire);
            $now = \Carbon\Carbon::now();
            $kalibrasiDaysLeft = $now->diffInDays($expDate, false);

            if ($kalibrasiDaysLeft < 0) {
                $kalibrasiStatus = 'danger';
                $kalibrasiMessage = 'Sudah Kadaluarsa!';
                $kalibrasiBg = '#fff5f5';
                $kalibrasiBorder = '#f64e60';
            } elseif ($kalibrasiDaysLeft <= 30) {
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

    @push('css')
        <style>
            .detail-img-container {
                background: #f7f8fa;
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
    @endpush

    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">

        <!-- 1. HEADER & ACTION BUTTONS -->
        <div class="kt-subheader kt-grid__item mb-4">
            <div class="kt-container kt-container--fluid">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">Detail Inventaris KSO</h3>
                    <span class="kt-subheader__desc">Informasi lengkap, riwayat kalibrasi, preventive maintenance, dan
                        formulir pembersihan alat KSO.</span>
                </div>
                <div class="kt-subheader__toolbar">
                    <div class="kt-subheader__wrapper">
                        <a href="{{ route('inventaris.index-kso') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('inventariskso.label', $item->id) }}" target="_blank"
                            class="btn btn-success btn-sm ml-2">
                            <i class="fa fa-qrcode"></i> Print Label
                        </a>
                        <a href="{{ route('inventariskso.edit', $item->id) }}" class="btn btn-brand btn-sm ml-2">
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
                            <h3 class="text-dark font-weight-bold mb-0 mr-3">{{ $item->Nama }}</h3>
                            <span class="badge {{ $bgwarna }} px-3 py-2" style="font-size: 0.9rem;">
                                <i class="flaticon-warning mr-1"></i> {{ strtoupper($item->Klasifikasi ?? 'Unknown') }}
                            </span>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-row"><span class="info-label">Kode Barang</span><span
                                        class="info-value">{{ $item->KodeBarang ?: '-' }}</span></div>
                                <div class="info-row"><span class="info-label">Merk / Tipe</span><span
                                        class="info-value">{{ $item->Merk }} {{ $item->Tipe }}</span></div>
                                <div class="info-row"><span class="info-label">Serial Number</span><span
                                        class="info-value">{{ $item->NoSn ?: '-' }}</span></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row"><span class="info-label">Vendor KSO</span><span
                                        class="info-value">{{ $item->Vendor ?: '-' }}</span></div>
                                <div class="info-row"><span class="info-label">Departemen</span><span
                                        class="info-value">{{ $item->Departemen }}</span></div>
                                <div class="info-row"><span class="info-label">Unit / Lokasi</span><span
                                        class="info-value">{{ $item->Unit }}</span></div>
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
                                <div class="info-row"><span class="info-label">Asset ID</span><span
                                        class="info-value">{{ $item->AssetID ?: '-' }}</span></div>
                                <div class="info-row"><span class="info-label">Tgl Kerjasama</span><span
                                        class="info-value">{{ $item->TanggalKerjasama ? \Carbon\Carbon::parse($item->TanggalKerjasama)->format('d F Y') : '-' }}</span>
                                </div>
                                <div class="info-row"><span class="info-label">Akhir Kerjasama</span><span
                                        class="info-value">{{ $item->AkhirKerjasama ? \Carbon\Carbon::parse($item->AkhirKerjasama)->format('d F Y') : '-' }}</span>
                                </div>
                                <div class="info-row"><span class="info-label">Jenis / Pengguna</span><span
                                        class="info-value">{{ $item->Pengguna }}</span></div>
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
                                                    {{ \Carbon\Carbon::parse($latestKalibrasi->TglExpire)->format('d M Y') }}
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
                                            @if ($latestKalibrasi->Dokumen)
                                                <a href="{{ $r2->getUrl('inventariskso/dokumen/' . $latestKalibrasi->Dokumen) }}"
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
                                        class="info-value text-wrap">{{ $item->Keterangan ?: 'Tidak ada keterangan tambahan.' }}</span>
                                </div>
                                <div class="info-row"><span class="info-label">Dokumen KSO</span><span
                                        class="info-value">
                                        @if ($item->Dokumen)
                                            <a href="{{ $r2->getUrl('inventariskso/dokumen/' . $item->Dokumen) }}"
                                                target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fa fa-paperclip"></i> Lihat Dokumen
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </span></div>
                            </div>
                        </div>
                    </div>

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
                                        @foreach ($item->getKalibrasi->sortByDesc('TglKalibrasi') as $index => $kal)
                                            @php
                                                $isLatest = $latestKalibrasi && $kal->id == $latestKalibrasi->id;
                                                $rowStyle = '';
                                                if ($isLatest) {
                                                    if ($kalibrasiStatus == 'danger') {
                                                        $rowStyle = 'background-color: #fff5f5;';
                                                    } elseif ($kalibrasiStatus == 'warning') {
                                                        $rowStyle = 'background-color: #fff8dd;';
                                                    } else {
                                                        $rowStyle = 'background-color: #f0fdf4;';
                                                    }
                                                }
                                            @endphp
                                            <tr style="{{ $rowStyle }}">
                                                <td class="pl-7">{{ $index + 1 }}</td>
                                                <td class="font-weight-bold">
                                                    {{ $kal->TglKalibrasi ? \Carbon\Carbon::parse($kal->TglKalibrasi)->format('d M Y') : '-' }}
                                                    @if ($isLatest)
                                                        <span
                                                            class="label label-inline label-{{ $kalibrasiStatus }} ml-1 font-weight-bold">Terbaru</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $kal->TglExpire ? \Carbon\Carbon::parse($kal->TglExpire)->format('d M Y') : '-' }}
                                                </td>
                                                <td class="text-muted">{{ $kal->Keterangan ?: '-' }}</td>
                                                <td class="text-center">
                                                    @if ($kal->Dokumen)
                                                        <a href="{{ $r2->getUrl('inventariskso/dokumen/' . $kal->Dokumen) }}"
                                                            target="_blank" class="btn btn-icon btn-sm btn-light-info"
                                                            title="Lihat Dokumen">
                                                            <i class="flaticon2-file font-size-lg"></i>
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
                            <div class="text-center py-5">
                                <div class="symbol symbol-60 symbol-light-primary mr-5 mb-3 mx-auto">
                                    <span class="symbol-label">
                                        <i class="flaticon-clipboard font-size-2x text-primary"></i>
                                    </span>
                                </div>
                                <p class="text-muted mb-0">Belum ada riwayat kalibrasi untuk alat ini.</p>
                            </div>
                        @endif
                    </div>

                    <!-- TAB 3: Preventive Maintenance -->
                    <div class="tab-pane" id="tab_pm" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-dark font-weight-bold mb-0">Riwayat Preventive Maintenance</h5>
                            <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal"
                                data-target="#modalMaintenance">
                                <i class="flaticon2-plus mr-1"></i> Tambah PM
                            </button>
                        </div>

                        @if ($item->DataMaintenance && $item->DataMaintenance->count() > 0)
                            @foreach ($item->DataMaintenance->sortByDesc('created_at') as $pm)
                                <div class="kt-widget2 mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center mb-2">
                                        <span
                                            class="badge {{ $bgwarna }} mr-2">{{ date('M Y', mktime(0, 0, 0, $pm->Bulan, 10)) }}</span>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($pm->created_at)->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div class="kt-widget2__info">
                                        <span class="d-block mb-1"><i class="fa fa-user-check text-success mr-1"></i>
                                            Oleh: <b>{{ $pm->getUser->name ?? 'Unknown' }}</b></span>
                                        <span class="text-muted d-block">{{ $pm->Keterangan }}</span>
                                        @if ($pm->Dokumentasi)
                                            <a href="{{ $r2->getUrl('inventariskso/dokumentasi/' . $pm->Dokumentasi) }}"
                                                target="_blank" class="btn btn-sm btn-outline-info mt-2">
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
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-dark font-weight-bold mb-0">Formulir Pembersihan (7 Hari Terakhir)</h5>
                            <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal"
                                data-target="#modalPembersihan">
                                <i class="flaticon2-plus mr-1"></i> Tambah Pembersihan
                            </button>
                        </div>

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
                                                            <img src="{{ $r2->getUrl('gambar/PembersihanKso/Before/' . $bersih->Before) }}"
                                                                class="img-fluid rounded border"
                                                                style="max-height: 120px; width: 100%; object-fit: cover;"
                                                                alt="Before">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 120px;">
                                                                <span class="text-muted small">Tidak ada foto</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-6 text-center">
                                                        <small class="text-muted d-block mb-2">Sesudah</small>
                                                        @if ($bersih->After)
                                                            <img src="{{ $r2->getUrl('gambar/PembersihanKso/After/' . $bersih->After) }}"
                                                                class="img-fluid rounded border"
                                                                style="max-height: 120px; width: 100%; object-fit: cover;"
                                                                alt="After">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 120px;">
                                                                <span class="text-muted small">Tidak ada foto</span>
                                                            </div>
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
                                <p class="mb-0">Belum ada formulir pembersihan dalam 7 hari terakhir.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH KALIBRASI -->
        <div class="modal fade" id="modalKalibrasi" tabindex="-1" role="dialog" aria-labelledby="modalKalibrasiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold" id="modalKalibrasiLabel">
                            <i class="flaticon-clipboard text-primary mr-2"></i> Tambah Data Kalibrasi
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form-kalibrasi" action="{{ route('inventariskso.store-kalibrasi') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="inventaris_kso_id" value="{{ $item->id }}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">Nama Alat</label>
                                        <div class="col-8">
                                            <input type="text" class="form-control form-control-solid"
                                                value="{{ $item->Nama }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">* Kepemilikan</label>
                                        <div class="col-8">
                                            <select class="form-control form-control-solid" name="Kepemilikan" required>
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
                                                name="TglKalibrasi" id="TglKalibrasi">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">Expire Date</label>
                                        <div class="col-8">
                                            <input type="date" class="form-control form-control-solid" name="TglExpire"
                                                id="TglExpire">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">* Upload Dokumen</label>
                                        <div class="col-8">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="Dokumen"
                                                    id="Dokumen" accept=".pdf" required>
                                                <label class="custom-file-label" for="Dokumen">Pilih file PDF (Max
                                                    5MB)...</label>
                                            </div>
                                            <small class="text-muted mt-1 d-block">
                                                <i class="fa fa-info-circle mr-1"></i> Hanya format <strong>PDF</strong>
                                                yang diperbolehkan.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">Keterangan</label>
                                        <div class="col-8">
                                            <textarea name="Keterangan" id="Keterangan" class="form-control form-control-solid" rows="4"
                                                placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                        </div>
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

        <!-- MODAL TAMBAH PREVENTIVE MAINTENANCE -->
        <div class="modal fade" id="modalMaintenance" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">
                            <i class="flaticon-calendar-1 text-primary mr-2"></i> Tambah Preventive Maintenance
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form-maintenance" action="{{ route('inventariskso.store-maintenance') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="inventaris_kso_id" value="{{ $item->id }}">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Nama Alat</label>
                                <div class="col-8">
                                    <input type="text" class="form-control form-control-solid"
                                        value="{{ $item->Nama }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">* Bulan</label>
                                <div class="col-8">
                                    <select class="form-control form-control-solid" name="Bulan" required>
                                        <option value="" selected disabled>-- Pilih Bulan --</option>
                                        @for ($b = 1; $b <= 12; $b++)
                                            <option value="{{ $b }}">
                                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Status</label>
                                <div class="col-8">
                                    <select class="form-control form-control-solid" name="Status">
                                        <option value="1">Selesai</option>
                                        <option value="0">Belum</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Dokumentasi</label>
                                <div class="col-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="Dokumentasi"
                                            id="Dokumentasi" accept=".jpg,.jpeg,.png,.pdf">
                                        <label class="custom-file-label" for="Dokumentasi">Pilih file (Max
                                            5MB)...</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Keterangan</label>
                                <div class="col-8">
                                    <textarea name="Keterangan" class="form-control form-control-solid" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 mt-3">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                            <button type="button" id="btn-simpan-maintenance"
                                class="btn btn-brand font-weight-bold px-5">
                                <i class="la la-save"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH PEMBERSIHAN -->
        <div class="modal fade" id="modalPembersihan" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">
                            <i class="flaticon-interface-8 text-primary mr-2"></i> Tambah Formulir Pembersihan
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form-pembersihan" action="{{ route('inventariskso.store-pembersihan') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="inventaris_kso_id" value="{{ $item->id }}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">Nama Alat</label>
                                        <div class="col-8">
                                            <input type="text" class="form-control form-control-solid"
                                                value="{{ $item->Nama }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">* Tanggal</label>
                                        <div class="col-8">
                                            <input type="date" class="form-control form-control-solid" name="Tanggal"
                                                value="{{ now()->toDateString() }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">* Status</label>
                                        <div class="col-8">
                                            <select class="form-control form-control-solid" name="Status" required>
                                                <option value="Bersih">Bersih</option>
                                                <option value="Tidak">Tidak</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">Keterangan</label>
                                        <div class="col-8">
                                            <textarea name="Keterangan" class="form-control form-control-solid" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">Foto Sebelum</label>
                                        <div class="col-8">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="Before"
                                                    id="Before" accept="image/*">
                                                <label class="custom-file-label" for="Before">Pilih gambar...</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label font-weight-bold">Foto Sesudah</label>
                                        <div class="col-8">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="After"
                                                    id="After" accept="image/*">
                                                <label class="custom-file-label" for="After">Pilih gambar...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 mt-3">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                            <button type="button" id="btn-simpan-pembersihan"
                                class="btn btn-brand font-weight-bold px-5">
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
            const TAB_KEY = "inventaris_kso_detail_active_tab_{{ $item->id }}";

            let targetTab = window.location.hash;
            if (!targetTab) {
                targetTab = localStorage.getItem(TAB_KEY);
            }
            if (!targetTab || !$(targetTab).length) {
                targetTab = '#tab_general';
            }

            function activateSpecificTab(hash) {
                $('.nav-tabs .nav-link').removeClass('active');
                $('.tab-content .tab-pane').removeClass('active show');
                $('.nav-tabs .nav-link[href="' + hash + '"]').addClass('active');
                $(hash).addClass('active show');
            }

            activateSpecificTab(targetTab);

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let activeTabHash = $(e.target).attr('href');
                localStorage.setItem(TAB_KEY, activeTabHash);
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
            // 3. HELPER AJAX SIMPAN (dipakai 3 form)
            // ==========================================
            function simpanAjax(formSelector, btn, targetTab, requiredFileSelector) {
                var form = $(formSelector)[0];
                var formData = new FormData(form);

                if (requiredFileSelector && $(requiredFileSelector).val() === '') {
                    Swal.fire('Peringatan', 'Silakan pilih file dokumen terlebih dahulu.', 'warning');
                    return;
                }

                var originalBtnText = btn.html();
                btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                );

                $.ajax({
                    url: $(formSelector).attr('action'),
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
                            text: response.message || 'Data berhasil disimpan.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.hash = targetTab;
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(originalBtnText);

                        let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.status === 419) {
                            errorMsg = 'Sesi Anda telah berakhir (CSRF Mismatch). Silakan refresh halaman.';
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
            }

            // ==========================================
            // 4. BINDING TOMBOL SIMPAN
            // ==========================================
            $('#btn-simpan-kalibrasi').on('click', function(e) {
                e.preventDefault();
                simpanAjax('#form-kalibrasi', $(this), '#tab_kalibrasi', '#Dokumen');
            });

            $('#btn-simpan-maintenance').on('click', function(e) {
                e.preventDefault();
                simpanAjax('#form-maintenance', $(this), '#tab_pm');
            });

            $('#btn-simpan-pembersihan').on('click', function(e) {
                e.preventDefault();
                simpanAjax('#form-pembersihan', $(this), '#tab_pembersihan');
            });
        });
    </script>
@endpush
