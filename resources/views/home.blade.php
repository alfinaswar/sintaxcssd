@extends('layouts.app')

@push('title')
    Dashboard
@endpush

@push('sub-title')
    Ringkasan Aset
@endpush

@push('css')
    <style>
        .dash-card .kt-portlet__body {
            padding: 1.5rem;
        }

        .dash-label {
            font-size: 1.15rem;
            font-weight: 500;
            color: #74788d;
            margin-bottom: .4rem;
        }

        .dash-value {
            font-size: 2.2rem;
            font-weight: 600;
            line-height: 1.1;
            color: #2c2e3e;
        }

        .dash-note {
            margin-top: .5rem;
            font-size: 1.1rem;
            color: #9aa0ac;
        }

        .dash-icon {
            width: 4.2rem;
            height: 4.2rem;
            border-radius: .8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .dash-icon--brand { background: #e8eeff; color: #5d78ff; }
        .dash-icon--success { background: #e2f8f5; color: #1dc9b7; }
        .dash-icon--warning { background: #fff5e1; color: #ffb822; }
        .dash-icon--danger { background: #ffe8ef; color: #fd397a; }

        .dash-mini {
            background: #f7f8fa;
            border-radius: .6rem;
            padding: .9rem 1rem;
            height: 100%;
        }

        .dash-mini__value {
            font-size: 1.7rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .dash-mini__label {
            font-size: 1.05rem;
            color: #74788d;
        }

        .dash-progress {
            height: .7rem;
            border-radius: 1rem;
            background: #ebedf2;
        }

        .dash-progress .progress-bar {
            border-radius: 1rem;
        }

        .dash-chart {
            position: relative;
            height: 26rem;
        }

        .dash-table td,
        .dash-table th {
            vertical-align: middle;
            font-size: 1.15rem;
        }

        .dash-empty {
            color: #9aa0ac;
            font-style: italic;
        }
    </style>
@endpush

@section('content')
    @php
        $nilaiLabel = $nilaiAset >= 1000000000
            ? 'Rp ' . number_format($nilaiAset / 1000000000, 2, ',', '.') . ' M'
            : 'Rp ' . number_format($nilaiAset, 0, ',', '.');
        $kalibrasiAman = max(0, $kalibrasiTercatat - $kalibrasiExpired - $kalibrasi30 - $kalibrasi90);
    @endphp

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="kt-portlet kt-portlet--height-fluid dash-card">
                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="dash-label">Total Aset Aktif</div>
                            <div class="dash-value">{{ number_format($totalInv, 0, ',', '.') }}</div>
                            <div class="dash-note">Seluruh data inventaris</div>
                        </div>
                        <span class="dash-icon dash-icon--brand"><i class="fa fa-boxes"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kt-portlet kt-portlet--height-fluid dash-card">
                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="dash-label">Medis / Non Medis</div>
                            <div class="dash-value">{{ number_format($invMedis, 0, ',', '.') }}</div>
                            <div class="dash-note">Non medis {{ number_format($invUmum, 0, ',', '.') }} aset</div>
                        </div>
                        <span class="dash-icon dash-icon--success"><i class="fa fa-heartbeat"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kt-portlet kt-portlet--height-fluid dash-card">
                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="dash-label">Nilai Aset Tercatat</div>
                            <div class="dash-value">{{ $nilaiLabel }}</div>
                            <div class="dash-note">Dari {{ number_format($hargaTerisi, 0, ',', '.') }} aset berharga</div>
                        </div>
                        <span class="dash-icon dash-icon--warning"><i class="fa fa-coins"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kt-portlet kt-portlet--height-fluid dash-card">
                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="dash-label">Pengguna RS</div>
                            <div class="dash-value">{{ number_format($pengguna, 0, ',', '.') }}</div>
                            <div class="dash-note">{{ $namaRs }}</div>
                        </div>
                        <span class="dash-icon dash-icon--brand"><i class="fa fa-users"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Kepatuhan Kalibrasi</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('kalibrasi.perlu-dikalibrasi') }}" class="btn btn-sm btn-brand btn-elevate">
                            Lihat daftar
                        </a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    @if ($kalibrasiExpired > 0)
                        <div class="alert alert-danger alert-bold mb-4" role="alert">
                            <strong>{{ number_format($kalibrasiExpired, 0, ',', '.') }} aset</strong> sudah melewati masa kalibrasi.
                        </div>
                    @else
                        <div class="alert alert-success alert-bold mb-4" role="alert">
                            Tidak ada aset yang melewati masa kalibrasi.
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-6 col-md mb-3">
                            <div class="dash-mini">
                                <div class="dash-mini__value">{{ number_format($wajibKalibrasi, 0, ',', '.') }}</div>
                                <div class="dash-mini__label">Wajib kalibrasi</div>
                            </div>
                        </div>
                        <div class="col-6 col-md mb-3">
                            <div class="dash-mini">
                                <div class="dash-mini__value">{{ number_format($kalibrasiTercatat, 0, ',', '.') }}</div>
                                <div class="dash-mini__label">Punya catatan</div>
                            </div>
                        </div>
                        <div class="col-6 col-md mb-3">
                            <div class="dash-mini">
                                <div class="dash-mini__value text-danger">{{ number_format($kalibrasiExpired, 0, ',', '.') }}</div>
                                <div class="dash-mini__label">Jatuh tempo</div>
                            </div>
                        </div>
                        <div class="col-6 col-md mb-3">
                            <div class="dash-mini">
                                <div class="dash-mini__value text-warning">{{ number_format($kalibrasi30, 0, ',', '.') }}</div>
                                <div class="dash-mini__label">&le; 30 hari</div>
                            </div>
                        </div>
                        <div class="col-6 col-md mb-3">
                            <div class="dash-mini">
                                <div class="dash-mini__value text-warning">{{ number_format($kalibrasi90, 0, ',', '.') }}</div>
                                <div class="dash-mini__label">31&ndash;90 hari</div>
                            </div>
                        </div>
                        <div class="col-6 col-md mb-3">
                            <div class="dash-mini">
                                <div class="dash-mini__value text-muted">{{ number_format($kalibrasiBelumTercatat, 0, ',', '.') }}</div>
                                <div class="dash-mini__label">Belum tercatat</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="dash-note">Cakupan catatan kalibrasi</span>
                        <strong>{{ $kelengkapan['kalibrasi'] }}%</strong>
                    </div>
                    <div class="progress dash-progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $kelengkapan['kalibrasi'] }}%"
                            aria-valuenow="{{ $kelengkapan['kalibrasi'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="dash-note mt-2">
                        {{ number_format($kalibrasiAman, 0, ',', '.') }} aset tercatat masih dalam masa berlaku.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Top 8 Departemen</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="dash-chart">
                        <canvas id="chartDepartemen"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Klasifikasi Risiko</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="dash-chart">
                        <canvas id="chartKlasifikasi"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Tren Penambahan Aset</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="dash-chart">
                        <canvas id="chartTrend"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="dash-label">Preventif Maintenance</div>
                            <div class="dash-value">{{ number_format($maintenance12Bulan, 0, ',', '.') }}</div>
                            <div class="dash-note">Dalam 12 bulan terakhir</div>
                        </div>
                        <span class="dash-icon dash-icon--brand"><i class="fa fa-tools"></i></span>
                    </div>
                    <a href="{{ route('maintanance.index') }}" class="btn btn-sm btn-brand btn-elevate mt-4">Buka maintenance</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="dash-label">Laporan Masalah</div>
                            <div class="dash-value">{{ number_format($masalah, 0, ',', '.') }}</div>
                            <div class="dash-note">Total laporan tercatat</div>
                        </div>
                        <span class="dash-icon dash-icon--warning"><i class="fa fa-exclamation-triangle"></i></span>
                    </div>
                    <a href="{{ route('masalah.index') }}" class="btn btn-sm btn-warning btn-elevate mt-4">Buka masalah</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="dash-label">Penghapusan Aset</div>
                            <div class="dash-value">{{ number_format(($penghapusan['pengajuan'] ?? 0) + ($penghapusan['disetujui'] ?? 0), 0, ',', '.') }}</div>
                            <div class="dash-note">
                                {{ number_format($penghapusan['pengajuan'] ?? 0, 0, ',', '.') }} pengajuan,
                                {{ number_format($penghapusan['disetujui'] ?? 0, 0, ',', '.') }} disetujui
                            </div>
                        </div>
                        <span class="dash-icon dash-icon--danger"><i class="fa fa-trash-alt"></i></span>
                    </div>
                    <a href="{{ route('pa.index') }}" class="btn btn-sm btn-danger btn-elevate mt-4">Buka penghapusan</a>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Kalibrasi Paling Mendesak</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <span class="dash-note">10 aset dengan tanggal kalibrasi paling lama</span>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="table-responsive">
                <table class="table table-striped table-hover dash-table mb-0">
                    <thead>
                        <tr>
                            <th>Kode Item</th>
                            <th>Nama Aset</th>
                            <th>RS</th>
                            <th>Unit</th>
                            <th>Kalibrasi Terakhir</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kalibrasiMendesak as $row)
                            <tr>
                                <td><span class="font-weight-bold">{{ $row->kode_item }}</span></td>
                                <td>{{ $row->nama ?: '-' }}</td>
                                <td>{{ $rsNames[$row->nama_rs] ?? ('RS ' . $row->nama_rs) }}</td>
                                <td>{{ $row->unit ?: '-' }}</td>
                                <td>{{ $row->tgl_kalibrasi ? \Carbon\Carbon::parse($row->tgl_kalibrasi)->format('d/m/Y') : '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->exp_date)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($row->sisa_hari < 0)
                                        <span class="badge badge-danger">{{ abs($row->sisa_hari) }} hari lewat</span>
                                    @elseif ($row->sisa_hari <= 30)
                                        <span class="badge badge-warning text-dark">{{ $row->sisa_hari }} hari lagi</span>
                                    @else
                                        <span class="badge badge-success">{{ $row->sisa_hari }} hari lagi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center dash-empty">Belum ada data kalibrasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            Chart.defaults.global.defaultFontFamily = "Poppins";

            var numberFormat = function(value) {
                return Number(value).toLocaleString('id-ID');
            };

            var deptCanvas = document.getElementById('chartDepartemen');
            if (deptCanvas) {
                new Chart(deptCanvas.getContext('2d'), {
                    type: 'horizontalBar',
                    data: {
                        labels: @json($departemenChart['labels']),
                        datasets: [{
                            label: 'Jumlah aset',
                            data: @json($departemenChart['data']),
                            backgroundColor: '#1dc9b7',
                            borderRadius: 4,
                            maxBarThickness: 26
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { display: false },
                        tooltips: {
                            callbacks: {
                                label: function(item) {
                                    return numberFormat(item.xLabel) + ' aset';
                                }
                            }
                        },
                        scales: {
                            xAxes: [{
                                ticks: { beginAtZero: true, callback: numberFormat },
                                gridLines: { color: '#f0f1f6' }
                            }],
                            yAxes: [{
                                gridLines: { display: false }
                            }]
                        }
                    }
                });
            }

            var klasifikasiCanvas = document.getElementById('chartKlasifikasi');
            if (klasifikasiCanvas) {
                new Chart(klasifikasiCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($klasifikasiChart['labels']),
                        datasets: [{
                            data: @json($klasifikasiChart['data']),
                            backgroundColor: @json($klasifikasiChart['colors']),
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutoutPercentage: 62,
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 14,
                                fontStyle: 'normal'
                            }
                        },
                        tooltips: {
                            callbacks: {
                                label: function(item, data) {
                                    var total = data.datasets[0].data.reduce(function(a, b) {
                                        return a + b;
                                    }, 0);
                                    var percent = total ? Math.round(item.value / total * 100) : 0;
                                    return ' ' + numberFormat(item.value) + ' aset (' + percent + '%)';
                                }
                            }
                        }
                    }
                });
            }

            var trendCanvas = document.getElementById('chartTrend');
            if (trendCanvas) {
                new Chart(trendCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($trendChart['labels']),
                        datasets: [{
                            label: 'Aset baru',
                            data: @json($trendChart['data']),
                            borderColor: '#5d78ff',
                            backgroundColor: 'rgba(93, 120, 255, .12)',
                            pointBackgroundColor: '#5d78ff',
                            pointRadius: 3,
                            borderWidth: 2,
                            fill: true,
                            lineTension: .3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: { display: false },
                        tooltips: {
                            callbacks: {
                                label: function(item) {
                                    return numberFormat(item.yLabel) + ' aset';
                                }
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: { beginAtZero: true, callback: numberFormat },
                                gridLines: { color: '#f0f1f6' }
                            }],
                            xAxes: [{
                                gridLines: { display: false }
                            }]
                        }
                    }
                });
            }
        });
    </script>
@endpush
