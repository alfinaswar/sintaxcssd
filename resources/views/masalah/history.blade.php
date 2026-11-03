@extends('layouts.header')
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

@php
    use Carbon\Carbon;

    $r2 = new \App\Helpers\R2Client();

    $bulanNama = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];
    $bulanIni = (int) now()->format('n');

    // Klasifikasi risiko
    $klasifikasi = $data_alat->klasifikasi ?? '';
    [$riskClass, $riskLabel] = match ($klasifikasi) {
        'High Risk' => ['high', 'High Risk'],
        'Medium Risk' => ['medium', 'Medium Risk'],
        'Low to Medium Risk' => ['ltm', 'Low to Medium Risk'],
        'Low Risk' => ['low', 'Low Risk'],
        default => ['none', 'Belum diklasifikasi'],
    };

    // Status kalibrasi — dihitung dari tanggal expire
    $tglKal = !empty($data_kalibrasi->tgl_kalibrasi) ? Carbon::parse($data_kalibrasi->tgl_kalibrasi) : null;
    $expKal = !empty($data_kalibrasi->exp_date) ? Carbon::parse($data_kalibrasi->exp_date) : null;

    if (!$expKal) {
        $calState = 'none';
        $calText = 'Belum dikalibrasi';
        $calSub = 'Belum ada data kalibrasi';
    } else {
        $sisa = now()
            ->startOfDay()
            ->diffInDays($expKal->copy()->startOfDay(), false);
        if ($sisa < 0) {
            $calState = 'expired';
            $calText = 'Kalibrasi kedaluwarsa';
            $calSub = 'Lewat ' . abs($sisa) . ' hari · ' . $expKal->format('d/m/Y');
        } elseif ($sisa <= 30) {
            $calState = 'soon';
            $calText = 'Kalibrasi segera berakhir';
            $calSub = $sisa === 0 ? 'Berakhir hari ini' : 'Sisa ' . $sisa . ' hari · ' . $expKal->format('d/m/Y');
        } else {
            $calState = 'ok';
            $calText = 'Kalibrasi berlaku';
            $calSub = 'Sampai ' . $expKal->format('d/m/Y');
        }
    }

    $fmt = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '—';
    $jmlBersih = isset($pembersihan) ? count($pembersihan) : 0;
    $jmlKal = isset($kalibrasi) ? count($kalibrasi) : 0;
@endphp

<style>
    /* ─── Tokens ─────────────────────────────────────────── */
    :root {
        --bg: #F3F5F9;
        --surface: #FFFFFF;
        --line: #E3E7EF;
        --ink: #131A2A;
        --ink-2: #5B6579;
        --ink-3: #98A1B3;
        --brand: #3556D8;
        --brand-soft: #EBEFFD;

        --ok: #178055;
        --ok-bg: #E2F5EC;
        --warn: #A66300;
        --warn-bg: #FFF0D1;
        --bad: #C8352B;
        --bad-bg: #FDE9E7;
        --info: #1C6CA3;
        --info-bg: #E2EFF8;
        --mute: #667085;
        --mute-bg: #ECEFF4;

        --r: 16px;
        --r-sm: 10px;
        --sticky-top: 0px;
        /* isi mis. 55px jika header mobile Metronic menutupi tab bar */
        --shadow: 0 1px 2px rgba(19, 26, 42, .04), 0 4px 16px rgba(19, 26, 42, .05);
    }

    body {
        background: var(--bg) !important;
    }

    .page-wrap {
        max-width: 720px;
        margin: 0 auto;
        padding: 12px 12px 72px;
        color: var(--ink);
    }

    .page-wrap :focus-visible {
        outline: 3px solid var(--brand);
        outline-offset: 2px;
    }

    /* ─── Lazy-load: skeleton shimmer ────────────────────── */
    .lazy-img {
        background: linear-gradient(100deg, #E8ECF3 30%, #F4F6FA 50%, #E8ECF3 70%);
        background-size: 200% 100%;
        opacity: 0;
        transition: opacity .35s ease;
    }

    .lazy-img.is-loaded {
        opacity: 1;
        background: none;
    }

    .lazy-img.is-error {
        opacity: 1;
    }

    .skel {
        background: #E8ECF3;
    }

    @media (prefers-reduced-motion: no-preference) {

        .skel,
        .lazy-img:not(.is-loaded) {
            animation: shimmer 1.4s linear infinite;
        }

        @keyframes shimmer {
            to {
                background-position: -200% 0;
            }
        }
    }

    /* ─── Kartu alat ─────────────────────────────────────── */
    .unit {
        background: var(--surface);
        border-radius: var(--r);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 14px;
    }

    /* Pita status kalibrasi */
    .calband {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 13px 16px;
    }

    .calband-main {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .calband-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        flex: none;
        display: grid;
        place-items: center;
        background: rgba(255, 255, 255, .7);
    }

    .calband-icon svg {
        width: 18px;
        height: 18px;
    }

    .calband-title {
        font-weight: 700;
        font-size: 14px;
        line-height: 1.25;
    }

    .calband-sub {
        font-size: 12px;
        opacity: .85;
        margin-top: 1px;
    }

    .calband .cert {
        flex: none;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        padding: 7px 12px;
        border-radius: 99px;
        background: #fff;
        color: inherit;
    }

    .calband .cert:hover {
        text-decoration: none;
        color: inherit;
        filter: brightness(.96);
    }

    .cal-ok {
        background: var(--ok-bg);
        color: var(--ok);
    }

    .cal-soon {
        background: var(--warn-bg);
        color: var(--warn);
    }

    .cal-expired {
        background: var(--bad-bg);
        color: var(--bad);
    }

    .cal-none {
        background: var(--mute-bg);
        color: var(--mute);
    }

    .unit-main {
        display: block;
    }

    .photo {
        position: relative;
        aspect-ratio: 16 / 10;
        background: var(--mute-bg);
    }

    .photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        cursor: zoom-in;
    }

    .photo.empty {
        display: grid;
        place-items: center;
        text-align: center;
        color: var(--ink-3);
        font-size: 12px;
    }

    .photo.empty img {
        width: 64px;
        height: auto;
        opacity: .35;
        cursor: default;
        margin: 0 auto 6px;
    }

    @media (min-width: 600px) {
        .unit-main {
            display: grid;
            grid-template-columns: 240px 1fr;
        }

        .photo {
            aspect-ratio: auto;
            min-height: 240px;
        }
    }

    .unit-info {
        padding: 16px;
    }

    .unit-info h1 {
        font-size: 20px;
        line-height: 1.25;
        font-weight: 700;
        letter-spacing: -.01em;
        margin: 0 0 8px;
        color: var(--ink);
    }

    .risk {
        display: inline-block;
        padding: 3px 11px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 700;
    }

    .risk.high {
        background: var(--bad-bg);
        color: var(--bad);
    }

    .risk.medium {
        background: var(--warn-bg);
        color: var(--warn);
    }

    .risk.ltm {
        background: var(--info-bg);
        color: var(--info);
    }

    .risk.low {
        background: var(--ok-bg);
        color: var(--ok);
    }

    .risk.none {
        background: var(--mute-bg);
        color: var(--mute);
    }

    .facts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 16px;
        margin: 16px 0 0;
    }

    .facts>div {
        min-width: 0;
    }

    .facts dt {
        font-size: 12px;
        font-weight: 500;
        color: var(--ink-3);
        margin: 0 0 1px;
    }

    .facts dd {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: var(--ink);
        overflow-wrap: anywhere;
    }

    .facts .full {
        grid-column: 1 / -1;
    }

    .dates {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-top: 1px solid var(--line);
    }

    .dates>div {
        padding: 13px 16px;
    }

    .dates>div+div {
        border-left: 1px solid var(--line);
    }

    .dates .k {
        font-size: 12px;
        color: var(--ink-3);
    }

    .dates .v {
        font-size: 15px;
        font-weight: 700;
        margin-top: 1px;
    }

    .docs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px 16px 16px;
        border-top: 1px solid var(--line);
    }

    .doc-link {
        flex: 1 1 150px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 11px 14px;
        border-radius: var(--r-sm);
        font-size: 14px;
        font-weight: 600;
        background: var(--brand);
        color: #fff;
        text-decoration: none;
    }

    .doc-link:hover {
        color: #fff;
        text-decoration: none;
        filter: brightness(1.08);
    }

    .doc-link.disabled {
        background: var(--mute-bg);
        color: var(--ink-3);
        pointer-events: none;
    }

    .doc-link.sm {
        flex: none;
        padding: 8px 14px;
        font-size: 13px;
    }

    .doc-link svg {
        width: 16px;
        height: 16px;
        flex: none;
    }

    /* ─── Tab ────────────────────────────────────────────── */
    .tab-nav {
        position: sticky;
        top: var(--sticky-top);
        z-index: 100;
        margin: 0 -12px 12px;
        padding: 8px 12px;
        background: rgba(243, 245, 249, .92);
        -webkit-backdrop-filter: blur(8px);
        backdrop-filter: blur(8px);
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .tab-nav::-webkit-scrollbar {
        display: none;
    }

    .tab-nav .nav {
        display: flex;
        flex-wrap: nowrap;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .tab-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        padding: 8px 14px;
        border-radius: 99px;
        font-size: 14px;
        font-weight: 600;
        color: var(--ink-2);
        background: var(--surface);
        border: 1px solid var(--line);
        transition: background .15s, color .15s, border-color .15s;
    }

    .tab-nav .nav-link:hover {
        color: var(--ink);
        text-decoration: none;
    }

    .tab-nav .nav-link.active {
        background: var(--ink);
        border-color: var(--ink);
        color: #fff;
    }

    .tab-nav .count {
        min-width: 20px;
        padding: 0 6px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        line-height: 20px;
        text-align: center;
        background: var(--mute-bg);
        color: var(--ink-2);
    }

    .tab-nav .nav-link.active .count {
        background: rgba(255, 255, 255, .2);
        color: #fff;
    }

    /* ─── Toolbar & tombol ───────────────────────────────── */
    .toolbar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .toolbar select {
        flex: 1;
        min-width: 0;
    }

    .btn-main {
        border: 0;
        border-radius: var(--r-sm);
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 700;
        background: var(--brand);
        color: #fff;
        white-space: nowrap;
        cursor: pointer;
    }

    .btn-main:hover {
        filter: brightness(1.08);
    }

    .btn-main:disabled {
        opacity: .6;
        cursor: wait;
    }

    .btn-main.block {
        width: 100%;
    }

    .btn-soft {
        border: 0;
        border-radius: var(--r-sm);
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        background: var(--mute-bg);
        color: var(--ink);
        cursor: pointer;
    }

    /* ─── Kartu riwayat ──────────────────────────────────── */
    .entry {
        background: var(--surface);
        border-radius: var(--r);
        box-shadow: var(--shadow);
        padding: 14px 16px;
        margin-bottom: 10px;
        border-left: 4px solid var(--brand);
    }

    .entry.high {
        border-left-color: var(--bad);
    }

    .entry.medium {
        border-left-color: var(--warn);
    }

    .entry.ltm {
        border-left-color: var(--info);
    }

    .entry.low {
        border-left-color: var(--ok);
    }

    .entry .when {
        font-size: 12px;
        color: var(--ink-3);
        margin-bottom: 3px;
    }

    .entry .title {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 3px;
        color: var(--ink);
    }

    .entry .text {
        font-size: 14px;
        color: var(--ink-2);
        overflow-wrap: anywhere;
    }

    .entry .row-between {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin: 0;
    }

    .tag {
        display: inline-block;
        padding: 1px 9px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
        background: var(--mute-bg);
        color: var(--ink-2);
        margin-right: 4px;
    }

    /* ─── Pembersihan ────────────────────────────────────── */
    .clean {
        background: var(--surface);
        border-radius: var(--r);
        box-shadow: var(--shadow);
        padding: 14px 16px 16px;
        margin-bottom: 12px;
    }

    .clean-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 4px;
    }

    .clean-head b {
        font-size: 15px;
    }

    .pill {
        padding: 3px 11px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 700;
    }

    .pill.sp-bersih {
        background: var(--ok-bg);
        color: var(--ok);
    }

    .pill.sp-lainnya {
        background: var(--info-bg);
        color: var(--info);
    }

    .pill.sp-tidak {
        background: var(--bad-bg);
        color: var(--bad);
    }

    .clean .by {
        font-size: 13px;
        color: var(--ink-2);
    }

    .clean .by b {
        color: var(--ink);
    }

    .clean .note {
        font-size: 14px;
        color: var(--ink-2);
        margin-top: 2px;
    }

    .shots {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 12px;
    }

    .shot span {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-3);
        margin-bottom: 4px;
    }

    .shot img,
    .shot .none {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        border-radius: var(--r-sm);
        display: block;
    }

    .shot img {
        cursor: zoom-in;
    }

    .shot .none {
        display: grid;
        place-items: center;
        background: var(--bg);
        font-size: 12px;
        color: var(--ink-3);
    }

    .empty-state {
        text-align: center;
        padding: 44px 16px;
        color: var(--ink-3);
        font-size: 14px;
    }

    .empty-state svg {
        width: 40px;
        height: 40px;
        margin: 0 auto 8px;
        display: block;
        opacity: .5;
    }

    .empty-state[hidden] {
        display: none;
    }

    /* ─── Form (modal Bootstrap, tampil seperti bottom-sheet di HP) ─── */
    .page-wrap select.form-control,
    .modal select.form-control,
    .modal input.form-control,
    .modal textarea.form-control,
    #filter-month {
        font-size: 16px;
        /* cegah auto-zoom di iOS */
        border: 1px solid var(--line);
        border-radius: var(--r-sm);
        padding: 10px 12px;
        height: auto;
        color: var(--ink);
        background-color: #fff;
    }

    #filter-month {
        width: 100%;
    }

    .modal .form-control:focus,
    #filter-month:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(53, 86, 216, .14);
    }

    .modal-content {
        border: 0;
        border-radius: 18px;
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid var(--line);
        padding: 14px 18px;
        align-items: center;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--ink);
    }

    .modal-header .close {
        margin: 0;
        padding: 0;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--mute-bg);
        opacity: 1;
        font-size: 22px;
        line-height: 34px;
    }

    .modal-body {
        padding: 4px 18px 8px;
    }

    .modal-footer {
        border-top: 1px solid var(--line);
        padding: 12px 18px;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .modal-footer>* {
        flex: 1;
        margin: 0;
    }

    .modal-body .form-group {
        margin: 14px 0 0;
    }

    .modal-body label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink-2);
        margin-bottom: 5px;
    }

    .img-preview {
        display: none;
        width: 100%;
        max-height: 160px;
        object-fit: cover;
        border-radius: var(--r-sm);
        margin-top: 8px;
    }

    .modal form {
        display: flex;
        flex-direction: column;
        max-height: 92vh;
    }

    .modal .modal-body {
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 575.98px) {
        .modal.fade .modal-dialog {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0;
            max-width: none;
            transform: translateY(100%);
            transition: transform .28s ease-out;
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        .modal-content {
            border-radius: 20px 20px 0 0;
        }

        .modal-footer {
            padding-bottom: calc(12px + env(safe-area-inset-bottom));
        }
    }

    /* ─── Lightbox ───────────────────────────────────────── */
    .lb-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(8, 12, 22, .94);
        align-items: center;
        justify-content: center;
    }

    .lb-overlay.open {
        display: flex;
    }

    .lb-overlay img {
        max-width: 96vw;
        max-height: 88vh;
        object-fit: contain;
        border-radius: 8px;
    }

    .lb-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 0;
        background: rgba(255, 255, 255, .16);
        color: #fff;
        font-size: 26px;
        line-height: 1;
        cursor: pointer;
    }

    .foot-note {
        text-align: center;
        font-size: 12px;
        color: var(--ink-3);
        margin-top: 20px;
    }
</style>

<body
    class="kt-app__aside--left kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="page-wrap">

            {{-- ═════════ KARTU ALAT ═════════ --}}
            <section class="unit">

                {{-- Status kalibrasi --}}
                <div class="calband cal-{{ $calState }}">
                    <div class="calband-main">
                        <div class="calband-icon" aria-hidden="true">
                            @if ($calState === 'ok')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            @elseif ($calState === 'none')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round">
                                    <path d="M5 12h14" />
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 8v5M12 17h.01" />
                                    <circle cx="12" cy="12" r="9" />
                                </svg>
                            @endif
                        </div>
                        <div style="min-width:0">
                            <div class="calband-title">{{ $calText }}</div>
                            <div class="calband-sub">{{ $calSub }}</div>
                        </div>
                    </div>
                    @if (!empty($data_kalibrasi->dokumen))
                        <a class="cert" href="{{ $r2->getUrl('dokumen/' . $data_kalibrasi->dokumen) }}"
                            target="_blank" rel="noopener">Sertifikat</a>
                    @endif
                </div>

                <div class="unit-main">
                    {{-- Foto utama: dimuat langsung (di atas fold), prioritas tinggi --}}
                    @if (!empty($data_alat->gambar))
                        <div class="photo">
                            <img src="{{ $r2->getUrl('gambar/' . $data_alat->gambar) }}"
                                alt="Foto {{ $data_alat->nama }}" fetchpriority="high" decoding="async" data-zoom>
                        </div>
                    @else
                        <div class="photo empty">
                            <div>
                                <img src="{{ asset('imagenotfound.png') }}" alt="">
                                Foto belum tersedia
                            </div>
                        </div>
                    @endif

                    <div class="unit-info">
                        <h1>{{ $data_alat->nama }}</h1>
                        <span class="risk {{ $riskClass }}">{{ $riskLabel }}</span>

                        <dl class="facts">
                            <div>
                                <dt>No. inventaris</dt>
                                <dd>{{ $data_alat->no_inventaris ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>Serial number</dt>
                                <dd>{{ $data_alat->no_sn ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>ROID</dt>
                                <dd>{{ $data_alat->ROID ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>RO2ID</dt>
                                <dd>{{ $data_alat->RO2ID ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>Jenis</dt>
                                <dd>{{ $data_alat->pengguna ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>Unit</dt>
                                <dd>{{ $data_alat->unit ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>Rumah sakit</dt>
                                <dd>{{ $data_alat->rumahsakit ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>Tanggal beli</dt>
                                <dd>{{ $fmt($data_alat->tanggal_beli ?? null) }}</dd>
                            </div>
                            @if (!empty($data_alat->keterangan))
                                <div class="full">
                                    <dt>Keterangan</dt>
                                    <dd>{{ $data_alat->keterangan }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <div class="dates">
                    <div>
                        <div class="k">Terakhir dikalibrasi</div>
                        <div class="v">{{ $tglKal ? $tglKal->format('d/m/Y') : '—' }}</div>
                    </div>
                    <div>
                        <div class="k">Berlaku sampai</div>
                        <div class="v">{{ $expKal ? $expKal->format('d/m/Y') : '—' }}</div>
                    </div>
                </div>

                <div class="docs">
                    @if (!empty($data_alat->manualbook))
                        <a class="doc-link" href="{{ $r2->getUrl('manualbook/' . $data_alat->manualbook) }}"
                            target="_blank" rel="noopener">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                                <path d="M14 3v5h5M9 13h6M9 17h6" />
                            </svg>
                            Buka SPO alat
                        </a>
                    @else
                        <span class="doc-link disabled">SPO belum tersedia</span>
                    @endif
                </div>
            </section>

            {{-- ═════════ TAB ═════════ --}}
            <div class="tab-nav">
                <ul class="nav" id="alatTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab-perbaikan" role="tab">Perbaikan
                            <span class="count">{{ count($detail_masalah) }}</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-pm" role="tab">Maintenance <span
                                class="count">{{ count($data_mtnc) }}</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-kalibrasi" role="tab">Kalibrasi <span
                                class="count">{{ $jmlKal }}</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-bersih" role="tab">Pembersihan <span
                                class="count">{{ $jmlBersih }}</span></a>
                    </li>
                </ul>
            </div>

            <div class="tab-content">

                {{-- ── Perbaikan ── --}}
                <div class="tab-pane fade show active" id="tab-perbaikan" role="tabpanel">
                    @forelse ($detail_masalah as $item)
                        <article class="entry">
                            <div class="when">{{ $item->created_at }}</div>
                            <div class="title">{{ $item->kasus }}</div>
                            <div class="text">
                                @if ($item->prioritas)
                                    <span class="tag">{{ $item->prioritas }}</span>
                                @endif
                                {{ $item->tindakan }}
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.5 2.5-2.4-.6-.6-2.4z" />
                            </svg>
                            Belum ada riwayat perbaikan
                        </div>
                    @endforelse
                </div>

                {{-- ── Preventive Maintenance ── --}}
                <div class="tab-pane fade" id="tab-pm" role="tabpanel">
                    @if (Auth::check())
                        <div class="toolbar">
                            <button type="button" class="btn-main block" data-toggle="modal"
                                data-target="#modal-pm">+ Catat maintenance</button>
                        </div>
                    @else
                        <article class="entry high">
                            <div class="text">Login diperlukan untuk mencatat maintenance.</div>
                        </article>
                    @endif

                    @forelse ($data_mtnc as $pm)
                        <article class="entry {{ $riskClass }}">
                            <div class="when">{{ $bulanNama[(int) $pm->bulan] ?? '—' }} · dicatat
                                {{ $pm->created_at ?? '—' }}</div>
                            <div class="title">Maintenance oleh
                                {{ $pm->getUser->name ?? 'pengguna tidak diketahui' }}</div>
                            @if ($pm->keterangan)
                                <div class="text">{{ $pm->keterangan }}</div>
                            @endif
                        </article>
                    @empty
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="17" rx="2" />
                                <path d="M8 2v4M16 2v4M3 10h18" />
                            </svg>
                            Belum ada catatan maintenance
                        </div>
                    @endforelse
                </div>

                {{-- ── Kalibrasi ── --}}
                <div class="tab-pane fade" id="tab-kalibrasi" role="tabpanel">
                    @forelse ($kalibrasi ?? [] as $kal)
                        <article class="entry">
                            <div class="when">Dicatat {{ $kal->created_at }}</div>
                            <div class="row-between">
                                <div class="text">
                                    Kalibrasi: <b style="color:var(--ink)">{{ $fmt($kal->tgl_kalibrasi) }}</b><br>
                                    Berlaku s.d.: <b style="color:var(--bad)">{{ $fmt($kal->exp_date) }}</b>
                                </div>
                                @if (!empty($kal->dokumen))
                                    <a class="doc-link sm" href="{{ $r2->getUrl('dokumen/' . $kal->dokumen) }}"
                                        target="_blank" rel="noopener">Lihat dokumen</a>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                                <path d="M14 3v5h5" />
                            </svg>
                            Belum ada riwayat kalibrasi
                        </div>
                    @endforelse
                </div>

                {{-- ── Pembersihan ── --}}
                <div class="tab-pane fade" id="tab-bersih" role="tabpanel">
                    <div class="toolbar">
                        <select id="filter-month" aria-label="Filter bulan">
                            <option value="">Semua bulan</option>
                            @foreach ($bulanNama as $i => $nm)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                    {{ $i === $bulanIni ? 'selected' : '' }}>{{ $nm }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn-main" data-toggle="modal" data-target="#modal-bersih">+
                            Tambah</button>
                    </div>

                    @foreach ($pembersihan ?? [] as $p)
                        @php
                            $spClass = match ($p->Status) {
                                'Bersih' => 'sp-bersih',
                                'Lainnya' => 'sp-lainnya',
                                default => 'sp-tidak',
                            };
                        @endphp
                        <article class="clean" data-month="{{ Carbon::parse($p->Tanggal)->format('m') }}">
                            <div class="clean-head">
                                <b>{{ Carbon::parse($p->Tanggal)->format('d/m/Y') }}</b>
                                <span
                                    class="pill {{ $spClass }}">{{ $p->Status === 'Tidak' ? 'Tidak bersih' : $p->Status }}</span>
                            </div>
                            <div class="by">Petugas: <b>{{ $p->idUser ?? '—' }}</b></div>
                            @if (!empty($p->Keterangan))
                                <div class="note">{{ $p->Keterangan }}</div>
                            @endif

                            <div class="shots">
                                <div class="shot">
                                    <span>Sebelum</span>
                                    @if ($p->Before)
                                        <img class="lazy-img" loading="lazy" decoding="async"
                                            src="{{ $r2->getUrl('gambar/Pembersihan/Before/' . $p->Before) }}"
                                            alt="Foto sebelum dibersihkan" data-zoom>
                                    @else
                                        <div class="none">Tidak ada foto</div>
                                    @endif
                                </div>
                                <div class="shot">
                                    <span>Sesudah</span>
                                    @if ($p->After)
                                        <img class="lazy-img" loading="lazy" decoding="async"
                                            src="{{ $r2->getUrl('gambar/Pembersihan/After/' . $p->After) }}"
                                            alt="Foto sesudah dibersihkan" data-zoom>
                                    @else
                                        <div class="none">Tidak ada foto</div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <div class="empty-state" id="clean-empty" {{ $jmlBersih ? 'hidden' : '' }}>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="9" cy="9" r="2" />
                            <path d="m21 15-5-5L5 21" />
                        </svg>
                        <span id="clean-empty-text">Belum ada data pembersihan</span>
                    </div>
                </div>

            </div>{{-- /tab-content --}}

            <div class="foot-note">2023 &copy; Digital Indonesia Hebat</div>
        </div>{{-- /page-wrap --}}
    </div>{{-- /kt-container --}}

    {{-- ═════════ LIGHTBOX ═════════ --}}
    <div class="lb-overlay" id="lb" role="dialog" aria-label="Pratinjau foto">
        <button type="button" class="lb-close" aria-label="Tutup">&times;</button>
        <img id="lb-img" src="" alt="">
    </div>

    {{-- ═════════ MODAL: Maintenance ═════════ --}}
    <div class="modal fade" id="modal-pm" tabindex="-1" role="dialog" aria-labelledby="modal-pm-title">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('maintanance.AddPm') }}" method="POST" enctype="multipart/form-data"
                    data-lock>
                    @csrf
                    <input type="hidden" name="nama" value="{{ request()->segment(2) }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-pm-title">Catat maintenance</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Tutup"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="pm-bulan">Bulan <span class="text-danger">*</span></label>
                            <select id="pm-bulan" class="form-control" name="bulan" required>
                                <option value="">Pilih bulan</option>
                                @foreach ($bulanNama as $i => $nm)
                                    <option value="{{ $i }}" {{ $i === $bulanIni ? 'selected' : '' }}>
                                        {{ $nm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pm-kat">Kelaikan alat</label>
                            <select id="pm-kat" class="form-control" name="kategori">
                                <option value="">Pilih</option>
                                <option value="LAIK">Laik</option>
                                <option value="TIDAKLAIK">Tidak laik</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pm-status">Status</label>
                            <select id="pm-status" class="form-control" name="status">
                                <option value="">Pilih</option>
                                <option value="1">Sudah maintenance</option>
                                <option value="2">Belum maintenance</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pm-file">
                                Dokumentasi
                                @if ($klasifikasi === 'High Risk')
                                    <span class="text-danger">* wajib untuk High Risk</span>
                                @endif
                            </label>
                            <input id="pm-file" type="file" class="form-control" name="dokumentasi"
                                accept="image/*,.pdf" {{ $klasifikasi === 'High Risk' ? 'required' : '' }}>
                        </div>
                        <div class="form-group">
                            <label for="pm-ket">Keterangan</label>
                            <textarea id="pm-ket" name="keterangan" class="form-control" rows="3"
                                placeholder="Catatan hasil pemeriksaan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-soft" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-main">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═════════ MODAL: Pembersihan ═════════ --}}
    <div class="modal fade" id="modal-bersih" tabindex="-1" role="dialog" aria-labelledby="modal-bersih-title">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('formulir-pembersihan.store') }}" method="POST"
                    enctype="multipart/form-data" data-lock>
                    @csrf
                    <input type="hidden" name="idAlat" value="{{ $data_alat->id }}">
                    <input type="hidden" name="kode_item" value="{{ $data_alat->kode_item }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-bersih-title">Formulir pembersihan</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Tutup"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="cl-user">Nama petugas <span class="text-danger">*</span></label>
                            <input id="cl-user" type="text" class="form-control" name="idUser"
                                autocomplete="name" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cl-tgl">Tanggal <span class="text-danger">*</span></label>
                                    <input id="cl-tgl" type="date" class="form-control" name="Tanggal"
                                        value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cl-status">Status <span class="text-danger">*</span></label>
                                    <select id="cl-status" class="form-control" name="Status" required>
                                        <option value="">Pilih</option>
                                        <option value="Bersih">Bersih</option>
                                        <option value="Tidak">Tidak bersih</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inp-before">Foto sebelum</label>
                                    <input id="inp-before" type="file" class="form-control" name="Before"
                                        accept="image/*" capture="environment">
                                    <img id="prev-before" class="img-preview" alt="Pratinjau foto sebelum">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inp-after">Foto sesudah</label>
                                    <input id="inp-after" type="file" class="form-control" name="After"
                                        accept="image/*" capture="environment">
                                    <img id="prev-after" class="img-preview" alt="Pratinjau foto sesudah">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cl-ket">Keterangan <span class="text-danger">*</span></label>
                            <textarea id="cl-ket" name="Keterangan" class="form-control" rows="3"
                                placeholder="Kondisi alat saat dibersihkan" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-soft" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-main">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═════════ VENDOR SCRIPTS ═════════ --}}
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
        (function() {
            /* ── Flash message ── */
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json(session('error')),
                    showConfirmButton: true
                });
            @endif
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json($errors->first()),
                    showConfirmButton: true
                });
            @endif

            /* ── Lazy-load: hilangkan skeleton setelah gambar selesai dimuat ── */
            function markLoaded(img) {
                img.classList.add('is-loaded');
            }

            function markError(img) {
                img.classList.add('is-error');
                img.alt = 'Foto gagal dimuat';
            }
            document.querySelectorAll('img.lazy-img').forEach(function(img) {
                if (img.complete && img.naturalWidth) {
                    markLoaded(img);
                    return;
                }
                img.addEventListener('load', function() {
                    markLoaded(img);
                });
                img.addEventListener('error', function() {
                    markError(img);
                });
            });

            /* ── Lightbox (delegasi: semua <img data-zoom>) ── */
            var lb = document.getElementById('lb'),
                lbImg = document.getElementById('lb-img');

            function lbOpen(src) {
                lbImg.src = src;
                lb.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function lbClose() {
                lb.classList.remove('open');
                lbImg.removeAttribute('src');
                document.body.style.overflow = '';
            }
            document.addEventListener('click', function(e) {
                var z = e.target.closest('img[data-zoom]');
                if (z) {
                    lbOpen(z.currentSrc || z.src);
                    return;
                }
                if (e.target === lb || e.target.closest('.lb-close')) lbClose();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') lbClose();
            });

            /* ── Filter pembersihan per bulan ── */
            var sel = document.getElementById('filter-month');

            function filterByMonth() {
                var shown = 0,
                    total = 0;
                document.querySelectorAll('.clean[data-month]').forEach(function(c) {
                    total++;
                    var ok = !sel.value || c.dataset.month === sel.value;
                    c.style.display = ok ? '' : 'none';
                    if (ok) shown++;
                });
                document.getElementById('clean-empty').hidden = shown > 0;
                document.getElementById('clean-empty-text').textContent =
                    (total && sel.value) ? 'Tidak ada data pembersihan di bulan ini' : 'Belum ada data pembersihan';
            }
            sel.addEventListener('change', filterByMonth);
            filterByMonth();

            /* ── Pratinjau foto di form pembersihan ── */
            function preview(inputId, imgId) {
                document.getElementById(inputId).addEventListener('change', function() {
                    var img = document.getElementById(imgId),
                        f = this.files && this.files[0];
                    if (!f) {
                        img.style.display = 'none';
                        return;
                    }
                    var r = new FileReader();
                    r.onload = function(e) {
                        img.src = e.target.result;
                        img.style.display = 'block';
                    };
                    r.readAsDataURL(f);
                });
            }
            preview('inp-before', 'prev-before');
            preview('inp-after', 'prev-after');

            /* ── Cegah submit ganda saat upload foto di jaringan lambat ── */
            document.querySelectorAll('form[data-lock]').forEach(function(f) {
                f.addEventListener('submit', function() {
                    var b = f.querySelector('button[type=submit]');
                    b.disabled = true;
                    b.textContent = 'Menyimpan…';
                });
            });

            /* ── Ingat tab terakhir lewat hash URL ── */
            if (window.jQuery) {
                var $ = window.jQuery;
                if (location.hash && $('#alatTabs a[href="' + location.hash + '"]').length) {
                    $('#alatTabs a[href="' + location.hash + '"]').tab('show');
                }
                $('#alatTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    history.replaceState(null, '', e.target.getAttribute('href'));
                    e.target.scrollIntoView({
                        inline: 'center',
                        block: 'nearest',
                        behavior: 'smooth'
                    });
                });
            }
        })();
    </script>

</body>

</html>
