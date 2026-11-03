@extends('layouts.header')
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

<style>
    /* ─── Tokens ─────────────────────────────────────────── */
    :root {
        --bg: #F2F4F8;
        --surface: #FFFFFF;
        --border: #E4E8F0;
        --text-1: #1B2437;
        --text-2: #6B7896;
        --text-3: #9FA8BD;
        --brand: #3D6BE5;
        --brand-bg: #EEF2FD;
        --radius: 12px;
        --radius-sm: 8px;
    }

    /* ─── Base ────────────────────────────────────────────── */
    body {
        background: var(--bg) !important;
    }

    .page-wrap {
        max-width: 680px;
        margin: 0 auto;
        padding: 12px 12px 80px;
    }

    /* ─── Hero Card ───────────────────────────────────────── */
    .hero-card {
        background: var(--surface);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 12px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
    }

    .hero-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
        background: var(--bg);
    }

    .hero-img-placeholder {
        width: 100%;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg);
    }

    .hero-img-placeholder img {
        width: 80px;
        opacity: .35;
    }

    @media (min-width: 540px) {
        .hero-top {
            display: flex;
            align-items: flex-start;
        }

        .hero-img,
        .hero-img-placeholder {
            width: 150px;
            height: 150px;
            flex-shrink: 0;
        }
    }

    .hero-body {
        padding: 16px;
    }

    .hero-name {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-1);
        line-height: 1.3;
        margin: 0 0 8px;
    }

    /* ─── Risk Badge ──────────────────────────────────────── */
    .risk-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .3px;
        margin-bottom: 14px;
    }

    .risk-pill.high {
        background: #FDECEA;
        color: #D0362A;
    }

    .risk-pill.medium {
        background: #FFF8E6;
        color: #B07B0E;
    }

    .risk-pill.ltm {
        background: #E8F4FF;
        color: #1D6FA4;
    }

    .risk-pill.low {
        background: #E8F6F0;
        color: #1A7A52;
    }

    .risk-pill.def {
        background: #F0F2F5;
        color: #6B7896;
    }

    /* ─── Info Grid ───────────────────────────────────────── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 16px;
        margin-bottom: 14px;
    }

    .info-item label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 2px;
    }

    .info-item span {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-1);
        word-break: break-word;
    }

    .info-item.full {
        grid-column: 1 / -1;
    }

    /* ─── Cal Status Strip ────────────────────────────────── */
    .cal-strip {
        display: flex;
        border-top: 1px solid var(--border);
    }

    .cal-strip-item {
        flex: 1;
        padding: 12px 14px;
        text-align: center;
    }

    .cal-strip-item:first-child {
        border-right: 1px solid var(--border);
    }

    .cal-strip-item .cal-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 3px;
    }

    .cal-strip-item .cal-value {
        font-size: 14px;
        font-weight: 700;
    }

    .cal-tgl {
        color: var(--brand);
    }

    .cal-exp {
        color: #D0362A;
    }

    .cal-none {
        color: var(--text-3);
        font-weight: 500;
        font-size: 12px;
    }

    /* ─── Doc Links ───────────────────────────────────────── */
    .doc-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px 16px;
        border-top: 1px solid var(--border);
    }

    .doc-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: var(--radius-sm);
        font-size: 12px;
        font-weight: 600;
        background: var(--brand-bg);
        color: var(--brand);
        text-decoration: none;
        transition: background .15s;
    }

    .doc-link:hover {
        background: #DDE5FB;
        color: var(--brand);
        text-decoration: none;
    }

    .doc-link.disabled {
        background: var(--bg);
        color: var(--text-3);
        pointer-events: none;
    }

    /* ─── Sticky Tabs ─────────────────────────────────────── */
    .tab-wrapper {
        background: var(--surface);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
    }

    .sticky-tabs {
        position: sticky;
        top: 0;
        z-index: 120;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
        scrollbar-width: none;
    }

    .sticky-tabs::-webkit-scrollbar {
        display: none;
    }

    .sticky-tabs .nav {
        flex-wrap: nowrap;
        display: inline-flex;
        min-width: 100%;
    }

    .sticky-tabs .nav-link {
        color: var(--text-2);
        font-size: 12px;
        font-weight: 600;
        padding: 13px 14px;
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        background: transparent;
        white-space: nowrap;
        transition: color .15s, border-color .15s;
    }

    .sticky-tabs .nav-link:hover {
        color: var(--brand);
    }

    .sticky-tabs .nav-link.active {
        color: var(--brand);
        border-bottom-color: var(--brand);
    }

    .tab-content-area {
        padding: 16px;
    }

    /* ─── Timeline ────────────────────────────────────────── */
    .timeline {
        padding-left: 4px;
    }

    .tl-item {
        display: flex;
        gap: 12px;
        padding-bottom: 18px;
        position: relative;
    }

    .tl-item:last-child {
        padding-bottom: 0;
    }

    .tl-dot-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
        padding-top: 4px;
    }

    .tl-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--brand);
        border: 2px solid var(--surface);
        box-shadow: 0 0 0 2px var(--brand);
        flex-shrink: 0;
    }

    .tl-line {
        width: 2px;
        background: var(--border);
        flex: 1;
        margin-top: 4px;
    }

    .tl-item:last-child .tl-line {
        display: none;
    }

    .tl-meta {
        font-size: 11px;
        color: var(--text-3);
        margin-bottom: 6px;
        font-weight: 500;
    }

    /* ─── Record Card ─────────────────────────────────────── */
    .rec-card {
        background: #F7F8FC;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        flex: 1;
    }

    .rec-card .rc-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-1);
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .rc-meta {
        font-size: 12px;
        color: var(--text-2);
        line-height: 1.5;
    }

    /* ─── Cleaning Card ───────────────────────────────────── */
    .cl-card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 14px;
        background: var(--surface);
    }

    .cl-head {
        padding: 10px 14px;
        background: #F7F8FC;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cl-date {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-1);
    }

    .cl-body {
        padding: 14px;
    }

    .cl-user {
        font-size: 13px;
        color: var(--text-2);
        margin-bottom: 4px;
    }

    .cl-user b {
        color: var(--text-1);
    }

    .cl-note {
        font-size: 12px;
        color: var(--text-2);
        margin-bottom: 12px;
    }

    .photo-pair {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .photo-col .photo-label {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-3);
        text-align: center;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .photo-col img {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
        border-radius: var(--radius-sm);
        cursor: zoom-in;
        display: block;
    }

    .photo-col .photo-empty {
        width: 100%;
        aspect-ratio: 4/3;
        background: var(--bg);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: var(--text-3);
    }

    /* ─── Status Badges ───────────────────────────────────── */
    .status-pill {
        display: inline-block;
        padding: 3px 9px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
    }

    .sp-bersih {
        background: #E8F6F0;
        color: #1A7A52;
    }

    .sp-tidak {
        background: #FDECEA;
        color: #D0362A;
    }

    .sp-lainnya {
        background: #E8F4FF;
        color: #1D6FA4;
    }

    /* ─── Empty State ─────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-3);
    }

    .empty-state i {
        font-size: 40px;
        display: block;
        margin-bottom: 10px;
        opacity: .4;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    /* ─── Lightbox ────────────────────────────────────────── */
    .lb-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .92);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .lb-overlay.open {
        display: flex;
    }

    .lb-overlay img {
        max-width: 95vw;
        max-height: 88vh;
        object-fit: contain;
        border-radius: 6px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, .4);
    }

    .lb-close {
        position: absolute;
        top: 16px;
        right: 20px;
        color: #fff;
        font-size: 32px;
        line-height: 1;
        cursor: pointer;
        opacity: .8;
    }

    .lb-close:hover {
        opacity: 1;
    }

    /* ─── Month Filter ────────────────────────────────────── */
    .filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 16px;
    }

    .filter-bar select {
        font-size: 13px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 6px 10px;
        color: var(--text-1);
        background: var(--surface);
        max-width: 160px;
        flex: 1;
    }

    /* ─── Modal Clean-up ──────────────────────────────────── */
    .modal-content {
        border: none;
        border-radius: var(--radius);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid var(--border);
        padding: 16px 20px;
    }

    .modal-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-1);
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid var(--border);
        padding: 12px 20px;
    }

    .modal-body .form-group {
        margin-bottom: 14px;
    }

    .modal-body .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-2);
        margin-bottom: 6px;
    }

    .modal-body .form-control {
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        color: var(--text-1);
        padding: 9px 12px;
        height: auto;
    }

    .modal-body .form-control:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(61, 107, 229, .12);
    }

    /* ─── Image Preview in Modal ──────────────────────────── */
    .img-preview {
        display: none;
        width: 100%;
        max-height: 160px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        margin-top: 8px;
    }

    /* ─── Section Header ──────────────────────────────────── */
    .section-action {
        margin-bottom: 16px;
    }
</style>

<body
    class="kt-app__aside--left kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="page-wrap">

            @php
                // Risk badge mapping
                $klasifikasi = $data_alat->klasifikasi ?? '';
                if ($klasifikasi === 'High Risk') {
                    $bgwarna = 'badge-danger';
                    $riskClass = 'high';
                } elseif ($klasifikasi === 'Medium Risk') {
                    $bgwarna = 'badge-warning';
                    $riskClass = 'medium';
                } elseif ($klasifikasi === 'Low to Medium Risk') {
                    $bgwarna = 'badge-info';
                    $riskClass = 'ltm';
                } elseif ($klasifikasi === 'Low Risk') {
                    $bgwarna = 'badge-success';
                    $riskClass = 'low';
                } else {
                    $bgwarna = 'badge-secondary';
                    $riskClass = 'def';
                }

                // R2 client — satu instance, dipakai di seluruh view
                $r2 = new \App\Helpers\R2Client();
            @endphp

            {{-- ══════════════════════════════════════════════════════
     HERO CARD — foto, nama, info, status kalibrasi, dokumen
     ══════════════════════════════════════════════════════ --}}
            <div class="hero-card">

                {{-- Foto + Nama --}}
                <div class="hero-top">
                    @if (!empty($data_alat->gambar))
                        <img class="hero-img" src="{{ $r2->getUrl('gambar/' . $data_alat->gambar) }}"
                            alt="Foto {{ $data_alat->nama }}">
                    @else
                        <div class="hero-img-placeholder">
                            <img src="{{ asset('imagenotfound.png') }}" alt="Foto tidak tersedia">
                        </div>
                    @endif

                    <div class="hero-body" style="flex:1;">
                        <h1 class="hero-name">{{ $data_alat->nama }}</h1>

                        <span class="risk-pill {{ $riskClass }}">
                            <i class="flaticon-warning" style="font-size:11px;"></i>
                            {{ strtoupper($klasifikasi ?: 'Tidak Terklasifikasi') }}
                        </span>

                        <div class="info-grid">
                            <div class="info-item">
                                <label>No. Inventaris</label>
                                <span>{{ $data_alat->no_inventaris ?: '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Serial Number</label>
                                <span>{{ $data_alat->no_sn ?: '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>ROID</label>
                                <span>{{ $data_alat->ROID ?: '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>RO2ID</label>
                                <span>{{ $data_alat->RO2ID ?: '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Jenis</label>
                                <span>{{ $data_alat->pengguna ?: '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Unit</label>
                                <span>{{ $data_alat->unit ?: '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Rumah Sakit</label>
                                <span>{{ $data_alat->rumahsakit ?: '—' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Tanggal Beli</label>
                                <span>{{ date('d/m/Y', strtotime($data_alat->tanggal_beli)) }}</span>
                            </div>
                            @if (!empty($data_alat->keterangan))
                                <div class="info-item full">
                                    <label>Keterangan</label>
                                    <span>{{ $data_alat->keterangan }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Status Kalibrasi --}}
                <div class="cal-strip">
                    <div class="cal-strip-item">
                        <div class="cal-label">Tgl. Kalibrasi</div>
                        <div class="cal-value {{ isset($data_kalibrasi->tgl_kalibrasi) ? 'cal-tgl' : 'cal-none' }}">
                            {{ isset($data_kalibrasi->tgl_kalibrasi)
                                ? date('d/m/Y', strtotime($data_kalibrasi->tgl_kalibrasi))
                                : 'Belum dikalibrasi' }}
                        </div>
                    </div>
                    <div class="cal-strip-item">
                        <div class="cal-label">Berlaku s.d.</div>
                        <div class="cal-value {{ isset($data_kalibrasi->exp_date) ? 'cal-exp' : 'cal-none' }}">
                            {{ isset($data_kalibrasi->exp_date) ? date('d/m/Y', strtotime($data_kalibrasi->exp_date)) : 'Belum dikalibrasi' }}
                        </div>
                    </div>
                </div>

                {{-- Dokumen --}}
                <div class="doc-bar">
                    @if (!empty($data_kalibrasi->dokumen))
                        <a href="{{ $r2->getUrl('dokumen/' . $data_kalibrasi->dokumen) }}" target="_blank"
                            class="doc-link">
                            <i class="flaticon-file-2"></i> Sertifikat Kalibrasi
                        </a>
                    @else
                        <span class="doc-link disabled"><i class="flaticon-file-2"></i> Sertifikat Kalibrasi</span>
                    @endif

                    @if (!empty($data_alat->manualbook))
                        <a href="{{ $r2->getUrl('manualbook/' . $data_alat->manualbook) }}" target="_blank"
                            class="doc-link">
                            <i class="flaticon-file-2"></i> SPO / Manual
                        </a>
                    @else
                        <span class="doc-link disabled"><i class="flaticon-file-2"></i> SPO / Manual</span>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════
     TAB PANEL
     ══════════════════════════════════════════════════════ --}}
            <div class="tab-wrapper">

                <div class="sticky-tabs">
                    <ul class="nav" id="alatTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-perbaikan" role="tab">
                                Perbaikan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-pm" role="tab">
                                Prev. Maintenance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-kalibrasi" role="tab">
                                Kalibrasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-bersih" role="tab">
                                Pembersihan
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content tab-content-area">

                    {{-- ── Tab: Riwayat Perbaikan ────────────────────── --}}
                    <div class="tab-pane fade show active" id="tab-perbaikan" role="tabpanel">
                        @if (count($detail_masalah) > 0)
                            <div class="timeline">
                                @foreach ($detail_masalah as $item)
                                    <div class="tl-item">
                                        <div class="tl-dot-col">
                                            <div class="tl-dot"></div>
                                            <div class="tl-line"></div>
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            <div class="tl-meta">{{ $item->created_at }}</div>
                                            <div class="rec-card">
                                                <div class="rc-title">{{ $item->kasus }}</div>
                                                <div class="rc-meta">
                                                    @if ($item->prioritas)
                                                        <span class="badge badge-light mr-1"
                                                            style="font-size:11px;">{{ $item->prioritas }}</span>
                                                    @endif
                                                    {{ $item->tindakan }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="flaticon-warning"></i>
                                <p>Belum ada riwayat perbaikan</p>
                            </div>
                        @endif
                    </div>

                    {{-- ── Tab: Preventive Maintenance ──────────────── --}}
                    <div class="tab-pane fade" id="tab-pm" role="tabpanel">
                        @if (Auth::check())
                            <div class="section-action">
                                <button type="button" class="btn btn-sm"
                                    style="background: var(--brand); color:#fff; border-radius: var(--radius-sm); font-size:13px; font-weight:600; padding: 7px 14px;"
                                    data-toggle="modal" data-target="#modal-pm">
                                    <i class="fa fa-plus mr-1"></i> Tambah PM
                                </button>
                            </div>
                        @endif

                        @if (count($data_mtnc) > 0)
                            <div class="timeline">
                                @foreach ($data_mtnc as $pm)
                                    <div class="tl-item">
                                        <div class="tl-dot-col">
                                            <div class="tl-dot"
                                                style="background: {{ $riskClass == 'high' ? '#D0362A' : ($riskClass == 'medium' ? '#B07B0E' : ($riskClass == 'low' ? '#1A7A52' : 'var(--brand)')) }};
                             box-shadow: 0 0 0 2px {{ $riskClass == 'high' ? '#D0362A' : ($riskClass == 'medium' ? '#B07B0E' : ($riskClass == 'low' ? '#1A7A52' : 'var(--brand)')) }};">
                                            </div>
                                            <div class="tl-line"></div>
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            <div class="tl-meta">
                                                {{ date('F', mktime(0, 0, 0, $pm->bulan, 10)) }}
                                                — {{ $pm->created_at ?? '—' }}
                                            </div>
                                            <div class="rec-card">
                                                <div class="rc-title">
                                                    PM oleh <b>{{ $pm->getUser->name ?? 'Tidak diketahui' }}</b>
                                                </div>
                                                @if ($pm->keterangan)
                                                    <div class="rc-meta">{{ $pm->keterangan }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="flaticon-calendar-1"></i>
                                <p>Belum ada data preventive maintenance</p>
                            </div>
                        @endif
                    </div>

                    {{-- ── Tab: Riwayat Kalibrasi ────────────────────── --}}
                    <div class="tab-pane fade" id="tab-kalibrasi" role="tabpanel">
                        @if (isset($kalibrasi) && count($kalibrasi) > 0)
                            <div class="timeline">
                                @foreach ($kalibrasi as $kal)
                                    <div class="tl-item">
                                        <div class="tl-dot-col">
                                            <div class="tl-dot"></div>
                                            <div class="tl-line"></div>
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            <div class="tl-meta">{{ $kal->created_at }}</div>
                                            <div class="rec-card">
                                                <div class="d-flex justify-content-between align-items-start"
                                                    style="gap:8px; flex-wrap:wrap;">
                                                    <div>
                                                        <div class="rc-meta" style="margin-bottom:2px;">
                                                            Kalibrasi: <b
                                                                style="color:var(--text-1)">{{ $kal->tgl_kalibrasi }}</b>
                                                        </div>
                                                        <div class="rc-meta">
                                                            Berlaku s.d.: <b
                                                                style="color:#D0362A">{{ $kal->exp_date }}</b>
                                                        </div>
                                                    </div>
                                                    @if (!empty($kal->dokumen))
                                                        {{-- PERBAIKAN: gunakan R2 bukan storage lokal --}}
                                                        <a href="{{ $r2->getUrl('dokumen/' . $kal->dokumen) }}"
                                                            target="_blank" class="doc-link"
                                                            style="font-size:12px; padding:6px 12px;">
                                                            <i class="flaticon-file-2" style="font-size:12px;"></i>
                                                            Dokumen
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="flaticon-file-2"></i>
                                <p>Belum ada riwayat kalibrasi</p>
                            </div>
                        @endif
                    </div>

                    {{-- ── Tab: Formulir Pembersihan ─────────────────── --}}
                    <div class="tab-pane fade" id="tab-bersih" role="tabpanel">

                        @php $currentMonth = \Carbon\Carbon::now()->format('m'); @endphp

                        <div class="filter-bar">
                            <select id="filter-month" onchange="filterByMonth()">
                                <option value="">Semua Bulan</option>
                                @foreach (range(1, 12) as $m)
                                    @php $mPad = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                    <option value="{{ $mPad }}"
                                        {{ $currentMonth === $mPad ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="button" class="btn btn-sm"
                                style="background: var(--brand); color:#fff; border-radius: var(--radius-sm); font-size:13px; font-weight:600; padding: 7px 14px; flex-shrink:0;"
                                data-toggle="modal" data-target="#modal-bersih">
                                <i class="fa fa-plus mr-1"></i> Tambah
                            </button>
                        </div>

                        @if (isset($pembersihan) && count($pembersihan) > 0)
                            @foreach ($pembersihan as $p)
                                <div class="cl-card"
                                    data-month="{{ \Carbon\Carbon::parse($p->Tanggal)->format('m') }}">
                                    <div class="cl-head">
                                        <span class="cl-date">
                                            {{ \Carbon\Carbon::parse($p->Tanggal)->translatedFormat('d M Y') }}
                                        </span>
                                        @php
                                            $spClass = match ($p->Status) {
                                                'Bersih' => 'sp-bersih',
                                                'Lainnya' => 'sp-lainnya',
                                                default => 'sp-tidak',
                                            };
                                        @endphp
                                        <span class="status-pill {{ $spClass }}">{{ $p->Status }}</span>
                                    </div>
                                    <div class="cl-body">
                                        <p class="cl-user">Petugas: <b>{{ $p->idUser ?? '—' }}</b></p>
                                        @if (!empty($p->Keterangan))
                                            <p class="cl-note">{{ $p->Keterangan }}</p>
                                        @endif

                                        <div class="photo-pair">
                                            <div class="photo-col">
                                                <div class="photo-label">Sebelum</div>
                                                @if ($p->Before)
                                                    <img src="{{ $r2->getUrl('gambar/Pembersihan/Before/' . $p->Before) }}"
                                                        alt="Before" onclick="lbOpen(this.src)" />
                                                @else
                                                    <div class="photo-empty">Tidak ada</div>
                                                @endif
                                            </div>
                                            <div class="photo-col">
                                                <div class="photo-label">Sesudah</div>
                                                @if ($p->After)
                                                    <img src="{{ $r2->getUrl('gambar/Pembersihan/After/' . $p->After) }}"
                                                        alt="After" onclick="lbOpen(this.src)" />
                                                @else
                                                    <div class="photo-empty">Tidak ada</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="flaticon-warning"></i>
                                <p>Belum ada data pembersihan</p>
                            </div>
                        @endif
                    </div>

                </div>{{-- /tab-content --}}
            </div>{{-- /tab-wrapper --}}

        </div>{{-- /page-wrap --}}
    </div>{{-- /kt-container --}}

    {{-- ══════════════════════════════════════════════════════
     LIGHTBOX
     ══════════════════════════════════════════════════════ --}}
    <div class="lb-overlay" id="lb" onclick="lbClose()">
        <span class="lb-close" onclick="lbClose()">&times;</span>
        <img id="lb-img" src="" alt="Preview foto">
    </div>

    {{-- ══════════════════════════════════════════════════════
     MODAL: Preventive Maintenance
     ══════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modal-pm" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengecekan Alat — PM Baru</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('maintanance.AddPm') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="nama" value="{{ request()->segment(2) }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Bulan <span class="text-danger">*</span></label>
                            <select class="form-control" name="bulan" required>
                                <option value="">— Pilih Bulan —</option>
                                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $bln)
                                    <option value="{{ $i + 1 }}">{{ $bln }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kategori Risk</label>
                            <select class="form-control" name="kategori">
                                <option value="">— Pilih Kategori —</option>
                                <option value="LAIK">Laik</option>
                                <option value="TIDAKLAIK">Tidak Laik</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="">— Pilih Status —</option>
                                <option value="1">Sudah Maintenance</option>
                                <option value="2">Belum Maintenance</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                Dokumentasi
                                @if (isset($data_alat->klasifikasi) && $data_alat->klasifikasi === 'High Risk')
                                    <span class="text-danger">* (wajib untuk High Risk)</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="dokumentasi" accept="image/*,.pdf"
                                @if (isset($data_alat->klasifikasi) && $data_alat->klasifikasi === 'High Risk') required @endif>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" id="keterangan-pm" class="form-control" rows="3"
                                placeholder="Catatan hasil pemeriksaan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"
                            style="border-radius: var(--radius-sm);">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     MODAL: Formulir Pembersihan
     ══════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modal-bersih" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Formulir Pembersihan Alat</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('formulir-pembersihan.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Petugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="idUser"
                                placeholder="Nama petugas yang melakukan pembersihan" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="Tanggal" required>
                        </div>
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="Status" required>
                                <option value="">— Pilih Status —</option>
                                <option value="Bersih">Bersih</option>
                                <option value="Tidak">Tidak Bersih</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Foto Sebelum Dibersihkan</label>
                                    <input type="file" class="form-control" name="Before" id="inp-before"
                                        accept="image/*" capture="environment">
                                    <img id="prev-before" class="img-preview" alt="Preview sebelum">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Foto Setelah Dibersihkan</label>
                                    <input type="file" class="form-control" name="After" id="inp-after"
                                        accept="image/*" capture="environment">
                                    <img id="prev-after" class="img-preview" alt="Preview sesudah">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Keterangan <span class="text-danger">*</span></label>
                            <textarea name="Keterangan" id="keterangan-bersih" class="form-control" rows="3"
                                placeholder="Catatan kondisi alat..." required></textarea>
                        </div>

                        <input type="hidden" name="idAlat" value="{{ $data_alat->id }}">
                        <input type="hidden" name="kode_item" value="{{ $data_alat->kode_item }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"
                            style="border-radius: var(--radius-sm);">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
        <div class="kt-container kt-container--fluid">
            <div class="kt-footer__copyright">
                2023&nbsp;&copy;&nbsp;<a href="#" class="kt-link">Digital Indonesia Hebat</a>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
     VENDOR SCRIPTS
     ══════════════════════════════════════════════════════ --}}
    <script src="{{ asset('') }}assets/vendors/general/jquery/dist/jquery.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/popper.js/dist/umd/popper.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/js-cookie/src/js.cookie.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/moment/min/moment.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/sticky-js/dist/sticky.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/wnumb/wNumb.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/jquery-form/dist/jquery.form.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/block-ui/jquery.blockUI.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/select2/dist/js/select2.full.js"></script>
    <script src="{{ asset('') }}assets/vendors/general/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/sweetalert2.init.js"></script>
    <script src="{{ asset('') }}assets/js/demo1/scripts.bundle.js"></script>
    <script src="{{ asset('') }}assets/js/demo1/pages/dashboard.js"></script>

    <script>
        /* ── SweetAlert untuk flash session ─────────────────── */
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: true
            });
        @endif

        /* ── Lightbox ─────────────────────────────────────────── */
        function lbOpen(src) {
            document.getElementById('lb-img').src = src;
            document.getElementById('lb').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function lbClose() {
            document.getElementById('lb').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') lbClose();
        });

        /* ── Filter pembersihan per bulan ─────────────────────── */
        function filterByMonth() {
            var sel = document.getElementById('filter-month').value;
            document.querySelectorAll('.cl-card[data-month]').forEach(function(c) {
                c.style.display = (!sel || c.dataset.month === sel) ? '' : 'none';
            });
        }
        document.addEventListener('DOMContentLoaded', filterByMonth);

        /* ── Preview foto di modal pembersihan ────────────────── */
        function previewFile(inputId, previewId) {
            var file = document.getElementById(inputId).files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById(previewId);
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('inp-before').addEventListener('change', function() {
                previewFile('inp-before', 'prev-before');
            });
            document.getElementById('inp-after').addEventListener('change', function() {
                previewFile('inp-after', 'prev-after');
            });
        });
    </script>

</body>

</html>
