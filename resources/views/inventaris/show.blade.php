@extends('layouts.app') {{-- Sesuaikan dengan layout utama Metronic Anda --}}

@section('content')
@php
    // Inisialisasi R2 Client sekali di awal
    $r2 = new \App\Helpers\R2Client();

    // Tentukan warna badge klasifikasi
    $bgwarna = 'badge-secondary';
    if ($item->klasifikasi == 'High Risk') $bgwarna = 'badge-danger';
    elseif ($item->klasifikasi == 'Medium Risk') $bgwarna = 'badge-warning';
    elseif ($item->klasifikasi == 'Low to Medium Risk') $bgwarna = 'badge-info';
    elseif ($item->klasifikasi == 'Low Risk') $bgwarna = 'badge-success';

    // Siapkan URL R2 di awal agar kode HTML lebih bersih
    $gambarUrl = $item->gambar ? $r2->getUrl('gambar/' . $item->gambar) : asset('imagenotfound.png');
    $manualbookUrl = $item->manualbook ? $r2->getUrl('manualbook/' . $item->manualbook) : null;
@endphp

<style>
    /* Custom CSS untuk mempercantik detail view di Metronic */
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
    .info-row:last-child { border-bottom: none; }
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
                <!-- Kolom Gambar -->
                <div class="col-lg-4 col-md-5 mb-4 mb-md-0 text-center">
                    <div class="detail-img-container">
                        <img src="{{ $gambarUrl }}" alt="Gambar Alat" class="detail-img" />
                    </div>
                </div>

                <!-- Kolom Informasi Utama -->
                <div class="col-lg-8 col-md-7">
                    <div class="d-flex flex-wrap align-items-center mb-3">
                        <h3 class="text-dark font-weight-bold mb-0 mr-3">{{ $item->nama }}</h3>
                        <span class="badge {{ $bgwarna }} px-3 py-2" style="font-size: 0.9rem;">
                            <i class="flaticon-warning mr-1"></i> {{ strtoupper($item->klasifikasi ?? 'Unknown') }}
                        </span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">No. Inventaris</span>
                                <span class="info-value">{{ $item->no_inventaris }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Merk / Tipe</span>
                                <span class="info-value">{{ $item->merk }} {{ $item->real_name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Serial Number</span>
                                <span class="info-value">{{ $item->no_sn ?: '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Departemen</span>
                                <span class="info-value">{{ $item->departemen }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Unit / Lokasi</span>
                                <span class="info-value">{{ $item->unit }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Pengguna</span>
                                <span class="info-value">{{ $item->pengguna }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Manual Book -->
                    @if($manualbookUrl)
                        <a href="{{ $manualbookUrl }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="fa fa-file-pdf-o mr-2"></i> Download / Lihat Manual Book (SPO)
                        </a>
                    @else
                        <span class="text-muted font-italic"><i class="fa fa-info-circle mr-1"></i> Manual Book belum diupload</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABBED CONTENT (Riwayat & Detail) -->
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_general" role="tab">
                            <i class="flaticon-information mr-1"></i> Informasi Umum
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_kalibrasi" role="tab">
                            <i class="flaticon-clipboard mr-1"></i> Riwayat Kalibrasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_pm" role="tab">
                            <i class="flaticon-calendar-1 mr-1"></i> Preventive Maintenance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_pembersihan" role="tab">
                            <i class="flaticon-interface-8 mr-1"></i> Formulir Pembersihan
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="tab-content">

                <!-- TAB 1: Informasi Umum -->
                <div class="tab-pane active" id="tab_general" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">ROID / RO2ID</span>
                                <span class="info-value">{{ $item->ROID }} / {{ $item->RO2ID }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tanggal Pembelian</span>
                                <span class="info-value">{{ \Carbon\Carbon::parse($item->tanggal_beli)->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Keterangan</span>
                                <span class="info-value text-wrap">{{ $item->keterangan ?: 'Tidak ada keterangan tambahan.' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Riwayat Kalibrasi -->
                <div class="tab-pane" id="tab_kalibrasi" role="tabpanel">
                    @if($item->getKalibrasi && $item->getKalibrasi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tanggal Kalibrasi</th>
                                        <th>Expire Date</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->getKalibrasi as $kal)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($kal->tgl_kalibrasi)->format('d/m/Y') }}</td>
                                            <td><span class="badge badge-danger">{{ \Carbon\Carbon::parse($kal->exp_date)->format('d/m/Y') }}</span></td>
                                            <td>{{ $kal->keterangan }}</td>
                                            <td class="text-center">
                                                @if($kal->dokumen)
                                                    <a href="{{ $r2->getUrl('dokumen/' . $kal->dokumen) }}" target="_blank" class="btn btn-sm btn-primary btn-icon">
                                                        <i class="fa fa-eye"></i>
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
                        <div class="text-center py-5 text-muted">
                            <i class="flaticon-clipboard font-size-3x mb-3"></i>
                            <p class="mb-0">Belum ada riwayat kalibrasi untuk alat ini.</p>
                        </div>
                    @endif
                </div>

                <!-- TAB 3: Preventive Maintenance -->
                <div class="tab-pane" id="tab_pm" role="tabpanel">
                    @if($item->DataMaintenance && $item->DataMaintenance->count() > 0)
                        @foreach($item->DataMaintenance as $pm)
                            <div class="kt-widget2 mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge {{ $bgwarna }} mr-2">{{ date('M Y', mktime(0, 0, 0, $pm->bulan, 10)) }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($pm->created_at)->format('d M Y, H:i') }}</small>
                                </div>
                                <div class="kt-widget2__info">
                                    <span class="d-block mb-1"><i class="fa fa-user-check text-success mr-1"></i> Oleh: <b>{{ $pm->getUser->name ?? 'Unknown' }}</b></span>
                                    <span class="text-muted d-block">{{ $pm->keterangan }}</span>
                                    @if($pm->dokumentasi)
                                        <a href="{{ $r2->getUrl('dokumen/' . $pm->dokumentasi) }}" target="_blank" class="btn btn-sm btn-outline-info mt-2">
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
                    @if($item->getFormPembersihan && $item->getFormPembersihan->count() > 0)
                        <div class="row">
                            @foreach($item->getFormPembersihan as $bersih)
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center pt-3">
                                            <span class="badge badge-primary">
                                                {{ \Carbon\Carbon::parse($bersih->Tanggal)->format('d M Y') }}
                                            </span>
                                            <span class="badge {{ $bersih->Status == 'Bersih' ? 'badge-success' : 'badge-danger' }} px-3">
                                                {{ $bersih->Status }}
                                            </span>
                                        </div>
                                        <div class="card-body pt-0">
                                            <h6 class="font-weight-bold mb-1">Petugas: <span class="font-weight-normal">{{ $bersih->idUser ?? 'Tanpa Nama' }}</span></h6>
                                            <p class="text-muted small mb-3">{{ $bersih->Keterangan }}</p>

                                            <div class="row">
                                                <div class="col-6 text-center">
                                                    <small class="text-muted d-block mb-2">Sebelum</small>
                                                    @if($bersih->Before)
                                                        <img src="{{ $r2->getUrl('gambar/Pembersihan/Before/' . $bersih->Before) }}" class="img-fluid rounded border" style="max-height: 120px; width: 100%; object-fit: cover;" alt="Before">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                                            <span class="text-muted small">No Image</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-6 text-center">
                                                    <small class="text-muted d-block mb-2">Sesudah</small>
                                                    @if($bersih->After)
                                                        <img src="{{ $r2->getUrl('gambar/Pembersihan/After/' . $bersih->After) }}" class="img-fluid rounded border" style="max-height: 120px; width: 100%; object-fit: cover;" alt="After">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                                            <span class="text-muted small">No Image</span>
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
                            <p class="mb-0">Belum ada data formulir pembersihan.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
