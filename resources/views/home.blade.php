@extends('layouts.app')

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::Total Profit-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Pengguna
                                </h4>
                                <span class="kt-widget24__desc">
                                    Jumlah Pengguna
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-brand">
                                {{ $pengguna }}
                            </span>
                        </div>



                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change">

                            </span>
                            <span class="kt-widget24__number">

                            </span>
                        </div>
                    </div>
                    <!--end::Total Profit-->
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Feedbacks-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Data Inventaris Umum
                                </h4>
                                <span class="kt-widget24__desc">
                                    Jumlah Data
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-warning">
                                {{ $inv_umum }}
                            </span>
                        </div>

                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change">

                            </span>
                            <span class="kt-widget24__number">

                            </span>
                        </div>
                    </div>
                    <!--end::New Feedbacks-->
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Orders-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Data Inventaris Medis
                                </h4>
                                <span class="kt-widget24__desc">
                                    Jumlah Data
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-danger">
                                {{ $inv_medis }}
                            </span>
                        </div>



                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change">

                            </span>
                            <span class="kt-widget24__number">

                            </span>
                        </div>
                    </div>
                    <!--end::New Orders-->
                </div>

                <div class="col-md-12 col-lg-6 col-xl-3">
                    <!--begin::New Users-->
                    <div class="kt-widget24">
                        <div class="kt-widget24__details">
                            <div class="kt-widget24__info">
                                <h4 class="kt-widget24__title">
                                    Seluruh Inventaris
                                </h4>
                                <span class="kt-widget24__desc">
                                    Semua Inventaris
                                </span>
                            </div>

                            <span class="kt-widget24__stats kt-font-success">
                                {{ $total_inv }}
                            </span>
                        </div>


                        <div class="kt-widget24__action">
                            <span class="kt-widget24__change">

                            </span>
                            <span class="kt-widget24__number">

                            </span>
                        </div>
                    </div>
                    <!--end::New Users-->
                </div>

            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="alert alert-primary left-icon-big alert-dismissible fade show">

                <div class="media">
                    <div class="alert-left-icon-big">
                        <span><i class="mdi mdi-email-alert"></i></span>
                    </div>
                    <div class="media-body">
                        <h6 class="mt-1 mb-2">Selamat datang di akun Anda, {{ auth()->user()->name }} !</h6>
                        <p class="mb-0">Semoga hari Anda menyenangkan dan penuh semangat dalam menjalankan aktivitas.
                            Terima
                            kasih telah menjadi bagian dari sistem inventaris kami.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet kt-portlet--height-fluid">
            <div class="kt-widget14">
                <div class="kt-widget14__header">
                    <h3 class="kt-widget14__title">
                        Persentase kategori Inventaris
                    </h3>
                    <span class="kt-widget14__desc">
                        Perbandingan Medis dan Non Medis
                    </span>
                </div>
                <div class="kt-widget14__content">
                    <div class="kt-widget14__chart">
                        <canvas id="pieChart" style="height: 250px; width: 250px;"></canvas>
                    </div>
                    <div class="kt-widget14__legends">
                        <div class="kt-widget14__legend">
                            <span class="kt-widget14__bullet kt-bg-success"></span>
                            <span class="kt-widget14__stats">Medis</span>
                        </div>
                        <div class="kt-widget14__legend">
                            <span class="kt-widget14__bullet kt-bg-warning"></span>
                            <span class="kt-widget14__stats">Non Medis</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($jadwal_imsak))
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card card-custom">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Jadwal Imsakiyah - {{ $jadwal_imsak['provinsi'] ?? '-' }},
                            {{ $jadwal_imsak['kabkota'] ?? '-' }}</h5>
                        <small>Tahun Hijriyah: {{ $jadwal_imsak['hijriah'] ?? '-' }}, Masehi:
                            {{ $jadwal_imsak['masehi'] ?? '-' }}</small>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Imsak</th>
                                    <th class="text-center">Subuh</th>
                                    <th class="text-center">Terbit</th>
                                    <th class="text-center">Dhuha</th>
                                    <th class="text-center">Dzuhur</th>
                                    <th class="text-center">Ashar</th>
                                    <th class="text-center">Maghrib</th>
                                    <th class="text-center">Isya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwal_imsak['imsakiyah'] ?? [] as $row)
                                    <tr>
                                        <td class="text-center">{{ $row['tanggal'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['imsak'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['subuh'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['terbit'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['dhuha'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['dzuhur'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['ashar'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['maghrib'] ?? '-' }}</td>
                                        <td class="text-center">{{ $row['isya'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                @if (empty($jadwal_imsak['imsakiyah']))
                                    <tr>
                                        <td colspan="9" class="text-center">Jadwal tidak tersedia.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var ctx = document.getElementById('pieChart').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Medis', 'Non Medis'],
                    datasets: [{
                        data: [{{ $inv_medis }}, {{ $inv_umum }}],
                        backgroundColor: [
                            '#1dc9b7',
                            '#ffb822'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        });
    </script>
@endpush
