@extends('layouts.header')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

<body
    class="kt-app__aside--left kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        @php
            $warnabadge = ''; // default value
            if ($data_alat->klasifikasi == 'High Risk') {
                $bgwarna = 'badge-danger';
            } elseif ($data_alat->klasifikasi == 'Medium Risk') {
                $bgwarna = 'badge-warning';
            } elseif ($data_alat->klasifikasi == 'Low to Medium Risk') {
                $bgwarna = 'badge-info';
            } elseif ($data_alat->klasifikasi == 'Low Risk') {
                $bgwarna = 'badge-success';
            } else {
                $bgwarna = 'badge-secondary'; // default value
            }
        @endphp
        <div class="kt-portlet ">
            <div class="kt-portlet__body">
                <div class="kt-widget kt-widget--user-profile-3">
                    <div class="kt-widget__top">
                        <div class="kt-widget__media kt-hidden-">
                            <?php if ($data_alat->gambar == null) {
    $gambar = 'imagenotfound.png';
} else {
    $gambar = $data_alat->gambar;
}
                            ?>
                            <img src="{{ url('storage/gambar/' . $gambar) }}" />
                        </div>
                        <div
                            class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                            JM
                        </div>
                        <div class="kt-widget__content">
                            <div class="kt-widget__head">
                                <a href="#" class="kt-widget__username kt-hidden">
                                    Jason Muller
                                    <i class="flaticon2-correct"></i>
                                </a>

                                <span href="" class="kt-widget__title">{{ $data_alat->nama }}</span>
                                <span class="badge {{ $bgwarna }} ml-2"
                                    style="font-size:14px; padding:8px; font-weight: bold;">
                                    <i class="flaticon-warning"></i>
                                    {{ strtoupper($data_alat->klasifikasi) }}
                                </span>
                            </div>
                            <div class="kt-widget__info">
                                <div class="kt-widget__desc">

                                    <table style="font-size: 12px" width="100%">
                                        <tr>
                                            <td width="30%">No inventaris</td>
                                            <td width="1%">:</td>
                                            <td><b>{{ $data_alat->no_inventaris }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>SN</td>
                                            <td width="1%">:</td>
                                            <td><b>{{ $data_alat->no_sn }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>ROID / RO2ID</td>
                                            <td width="1%">:</td>
                                            <td><b>{{ $data_alat->ROID }} / {{ $data_alat->RO2ID }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Jenis</td>
                                            <td width="1%">:</td>
                                            <td> <b>{{ $data_alat->pengguna }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td width="1%">:</td>
                                            <td> <b>{{ $data_alat->keterangan }}</b></td>
                                        </tr>
                                    </table>
                                </div>


                                <div class="kt-widget__stats d-flex align-items-left flex-fill">
                                    <div class="kt-widget__item">
                                        <span class="kt-widget__date">
                                            Tanggal Kalibrasi
                                        </span>
                                        <div class="kt-widget__label">

                                            <span class="btn btn-label-brand btn-sm btn-bold btn-upper"><?php if (isset($data_kalibrasi->tgl_kalibrasi)) {
    echo date('d/m/Y', strtotime($data_kalibrasi->tgl_kalibrasi));
} else {
    echo 'Tidak Dikalibrasi';
} ?></span>
                                        </div>
                                    </div>

                                    <div class="kt-widget__item">
                                        <span class="kt-widget__date">
                                            Expire
                                        </span>
                                        <div class="kt-widget__label">
                                            <span class="btn btn-label-danger btn-sm btn-bold btn-upper">
                                                @if (isset($data_kalibrasi->exp_date))
                                                    {{ date('d/m/Y', strtotime($data_kalibrasi->exp_date)) }}
                                                @else
                                                    Tidak Dikalibrasi
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget__bottom">
                        <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="flaticon2-hospital"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Awalbros</span>
                                <span class="kt-widget__title">{{ $data_alat->rumahsakit }}</span>
                            </div>
                        </div>

                        <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="fa flaticon-app"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Unit</span>
                                <span class="kt-widget__value">{{ $data_alat->unit }}</span>
                            </div>
                        </div>

                        <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="flaticon-calendar-1"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Beli</span>
                                <span
                                    class="kt-widget__value"><?= date('d/m/Y', strtotime($data_alat->tanggal_beli)) ?></span>
                            </div>
                        </div>

                        <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="flaticon-file-2"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">
                                    @if (isset($data_kalibrasi->tgl_kalibrasi))
                                                                    {!! '<a href="' .
                                        url('storage/dokumen/') .
                                        '/' .
                                        $data_kalibrasi->dokumen .
                                        '" target="_blank">Dokumen Kalibrasi</a>' !!}
                                    @else
                                        Tidak Dikalibrasi
                                    @endif
                                </span>
                                {{-- <span class="kt-widget__value">{!!$file!!}</span> --}}
                            </div>

                        </div>
                        <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="flaticon-file-2"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">
                                    @if (isset($data_alat->manualbook))
                                                                    {!! '<a href="' .
                                        url('storage/manualbook/') .
                                        '/' .
                                        $data_alat->manualbook .
                                        '" target="_blank">Dokumen SPO Alat</a>' !!}
                                    @else
                                        Manual Book Belum Tersedia
                                    @endif
                                </span>
                                {{-- <span class="kt-widget__value">{!!$file!!}</span> --}}
                            </div>

                        </div>

                        {{-- <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="flaticon-chat-1"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">Maintenance</span>
                                <a class="kt-widget__value kt-font-brand"></a>
                            </div>
                        </div> --}}


                    </div>
                </div>
                <!--end:: Portlet-->
            </div>
        </div>
        <div class="row">
            @if (session()->has('success'))
                <script>
                    swal.fire({
                        title: "{{ __('Success!') }}",
                        text: "{!! session('success') !!}",
                        type: "success",
                        icon: "success"
                    });
                </script>
            @endif
            <div class="col-xl-6">
                <!--begin:: Widgets/Tasks -->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Riwayat Perbaikan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                @if (count($detail_masalah) > 0)
                                    @foreach ($detail_masalah as $masalah_detail)
                                        <div class="kt-widget2">

                                            <div class="kt-widget__label">
                                                <span class="btn btn-label-brand btn-sm btn-bold btn-upper"
                                                    style="font-size: 12px;">{{ $masalah_detail->created_at }}</span>
                                            </div>
                                            <div class="kt-widget2__item kt-widget2__item--primary">
                                                <div class="kt-widget2__checkbox">

                                                </div>
                                                <div class="kt-widget2__info">
                                                    <span class="kt-widget2__title" style="font-size: 12px;">
                                                        {{ $masalah_detail->kasus }}
                                                    </span>
                                                    <span class="kt-widget2__username">
                                                        {{ $masalah_detail->prioritas }}
                                                    </span>
                                                    <span class="kt-widget2__username">
                                                        {{ $masalah_detail->tindakan }}
                                                    </span>
                                                </div>
                                                <div class="kt-widget2__actions">
                                                    <span class="btn btn-clean btn-sm btn-icon btn-icon-md"
                                                        data-toggle="dropdown">
                                                        <i class="flaticon-more-1"></i>
                                                    </span>

                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                        <div class="alert-text"><strong>Whoops!, Tidak ada data yang ditemukan</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>


                        </div>


                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <!--begin:: Widgets/Tasks -->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Preventif Maintenance
                            </h3>
                        </div>

                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-label">
                                @if (Auth::check())
                                    <button type="button" class="btn btn-bold btn-label-brand btn-sm" data-toggle="modal"
                                        data-target="#kt_modal_4"><i class="fa fa-plus"></i> Tambah</button>
                                @else
                                    <div class="alert alert-danger mt-3" role="alert">
                                        <i class="fa fa-exclamation-circle"></i> <strong>Anda Belum Login</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                @if (count($data_mtnc) > 0)
                                    @foreach ($data_mtnc as $data)
                                        <div class="kt-widget2">

                                            <div class="kt-widget__label">
                                                <span class="badge {{$bgwarna}}"
                                                    style="font-size: 12px;">{{ date('F', mktime(0, 0, 0, $data->bulan, 10)) }}</span>
                                            </div>
                                            <div class="kt-widget2__item kt-widget2__item--primary">
                                                <div class="kt-widget2__checkbox">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="currentColor"
                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-square-check">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-2.626 7.293a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                                    </svg>
                                                    Telah Di Lakukan PM Oleh <b>{{$data->getUser->name}}</b> Pada <b>
                                                        {{$data->created_at}}</b>
                                                </div>
                                                <div class="kt-widget2__info">
                                                    <span class="kt-widget2__title" style="font-size: 12px;">

                                                    </span>

                                                    <span class="kt-widget2__username">
                                                        {{ $data->keterangan }}
                                                    </span>
                                                </div>

                                                <div class="kt-widget2__actions">
                                                    <span
                                                        class="badge {{$bgwarna}}">{{ $data_alat->klasifikasi ?? 'Tidak Ada' }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                        <div class="alert-text"><strong>Whoops!, Tidak ada data yang ditemukan</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>


                        </div>


                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <!--begin:: Widgets/Tasks -->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Riwayat Kalibrasi
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                @if (isset($kalibrasi))
                                    @foreach ($kalibrasi as $item)
                                        <div class="kt-widget2">

                                            <div class="kt-widget__label">
                                                <span class="badge {{$bgwarna}}"
                                                    style="font-size: 12px; font-weight: bold;">{{ $item->created_at }}</span>
                                            </div>
                                            <div class="kt-widget2__item kt-widget2__item--primary">
                                                <div class="kt-widget2__checkbox">

                                                </div>
                                                <div class="kt-widget2__info">
                                                    <span class="kt-widget2__title" style="font-size: 12px;">
                                                        Tangal Kalibrasi <b>{{ $item->tgl_kalibrasi }}</b>
                                                        <br>
                                                        Tangal Akhir Kalibrasi <b>{{ $item->exp_date }}</b>
                                                    </span>

                                                </div>
                                                <div class="kt-widget2__actions">
                                                    <a href="{{ url('storage/dokumen/') . '/' . $item->dokumen }}"
                                                        target="_blank"><button type="button" data-skin="brand"
                                                            data-toggle="kt-tooltip" data-placement="top" title="Lihat Dokumen"
                                                            class="btn btn-outline-primary">Lihat
                                                            Dokumen</button></a>

                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                        <div class="alert-text"><strong>Whoops!, Tidak ada data yang ditemukan</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>


                        </div>


                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <!--begin:: Widgets/Tasks -->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Formulir Pembersihan
                            </h3>
                        </div>

                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-label">
                                {{-- @if (Auth::check()) --}}
                                <button type="button" class="btn btn-bold btn-label-brand btn-sm" data-toggle="modal"
                                    data-target="#kt_modal_5">Tambah</button>
                                {{-- @else
                                @endif --}}

                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <label for="filter-month">Filter Bulan:</label>
                            <select id="filter-month" class="form-control" onchange="filterByMonth()">
                                <option value="">Semua Bulan</option>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                        {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                @if (isset($pembersihan) && count($pembersihan) > 0)
                                    @foreach ($pembersihan as $p)
                                        <div class="card shadow-sm mb-4"
                                            data-month="{{ \Carbon\Carbon::parse($p->Tanggal)->format('m') }}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <span class="badge badge-primary" style="font-size: 12px;">
                                                    {{ \Carbon\Carbon::parse($p->Tanggal)->format('d-m-Y H:i:s') }}
                                                </span>
                                                <span
                                                    class="badge {{ $p->Status == 'Bersih' ? 'badge-success' : ($p->Status == 'Lainnya' ? 'badge-info' : 'badge-danger') }}">
                                                    {{ $p->Status }}
                                                </span>
                                            </div>
                                            <div class="card-body">
                                                <h6 class="mb-2">Nama: <b>{{ $p->idUser ?? 'Tanpa Nama' }}</b></h6>
                                                <p class="mb-2">Keterangan: <b>{{ $p->Keterangan }}</b></p>
                                                <div class="row">
                                                    <div class="col-md-6 text-center">
                                                        <h6>Before</h6>
                                                        <img src="{{ url('storage/gambar/Pembersihan/Before/' . $p->Before) }}"
                                                            class="img-fluid rounded shadow" alt="Before Image">
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        <h6>After</h6>
                                                        <img src="{{ url('storage/gambar/Pembersihan/After/' . $p->After) }}"
                                                            class="img-fluid rounded shadow" alt="After Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <i class="flaticon-warning mr-2"></i>
                                        <strong>Whoops!, Tidak ada data yang ditemukan</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Tasks -->
        </div>
    </div>

    </div>
    <!-- end:: Content --> </div>

    <!-- Button trigger modal-->


    <!-- Modal-->
    <div class="modal fade" id="kt_modal_5" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulir Pembersihan Alat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myform" action="{{ route('formulir-pembersihan.store') }}" method="POST"
                        accept-charset="utf-8" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Nama Petugas</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <input type="text" class="form-control" placeholder="Nama Petugas" name="idUser"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Tanggal</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <input type="date" class="form-control" name="Tanggal" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Status</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <select class="form-control kt-select2" id="status" name="Status" required>
                                            <option value="" selected>--Select Status--</option>
                                            <option value="Bersih">Bersih</option>
                                            <option value="Tidak">Tidak</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Sebelum Dibersihkan</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <input type="file" class="form-control" name="Before" id="before">
                                        <img id="before-preview" src="#" alt="Preview Sebelum"
                                            style="display: none; max-width: 100px; margin-top: 10px;" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Setelah Dibersihkan</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <input type="file" class="form-control" name="After" id="after">
                                        <img id="after-preview" src="#" alt="Preview Setelah"
                                            style="display: none; max-width: 100px; margin-top: 10px;" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Keterangan</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <textarea name="Keterangan" id="keterangan"
                                            placeholder="Tambahkan Keterangan Jika Diperlukan" class="form-control"
                                            required></textarea>
                                    </div>
                                </div>

                                <input type="hidden" value="{{ $data_alat->id }}" name="idAlat">
                                <input type="hidden" value="{{ $data_alat->kode_item }}" name="kode_item">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-block btn-info" name="btn-save"
                                id="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- begin:: Footer -->
    <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-footer__copyright">
                2023&nbsp;&copy;&nbsp;<a href="#" target="" class="kt-link">Digital Indonesia Hebat</a>
            </div>

        </div>
    </div>
    <!-- end:: Footer --> </div>
    </div>
    </div>

    <!-- end:: Page -->


    <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pengecekan Alat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myform" action="{{ route('maintanance.AddPm') }}" method="POST" accept-charset="utf-8"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" name="nama" placeholder="Nama"
                                    value="{{ request()->segment(2) }}">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">* Bulan</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <select class="form-control kt-select2" id="bulan" name="bulan">
                                            <option value="" selected>--Select Bulan--</option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Kategori Risk</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <select class="form-control kt-select2" id="bulan" name="kategori">
                                            <option value="" selected>--Kategori Risk--</option>
                                            <option value="LAIK">Laik</option>
                                            <option value="TIDAKLAIK">Tidak Laik</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Status</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <select class="form-control kt-select2" id="status" name="status">
                                            <option value="" selected>--Select Status--</option>
                                            <option value="1">Sudah Maintanance</option>
                                            <option value="2">Belum Maintanance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Dokumentasi</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <div></div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile"
                                                name="dokumentasi">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Keterangan</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                        <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-block btn-info" name="btn-save"
                                id="btn-save">Simpan</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>





    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>
    <!-- end::Scrolltop -->

    <!-- begin::Demo Panel -->

    <script>
        function filterByMonth() {
            var selectedMonth = document.getElementById("filter-month").value;
            var cards = document.querySelectorAll(".card.shadow-sm");

            cards.forEach(function (card) {
                var cardMonth = card.getAttribute("data-month");
                if (selectedMonth === "" || cardMonth === selectedMonth) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>

    <script>
        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).style.display = 'block';
                    document.getElementById(previewId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('before').addEventListener('change', function () {
            readURL(this, 'before-preview');
        });

        document.getElementById('after').addEventListener('change', function () {
            readURL(this, 'after-preview');
        });
    </script>
    <!--Begin:: Chat-->

    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#5d78ff",
                    "dark": "#282a3c",
                    "light": "#ffffff",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>
    <!-- end::Global Config -->

    <!--begin:: Global Mandatory Vendors -->
    <script src="{{ asset('') }}assets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <script src="{{ asset('') }}assets/vendors/general/jquery-form/dist/jquery.form.min.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-datepicker.init.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-timepicker.init.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js"
        type="text/javascript"></script>
    <script
        src="{{ asset('') }}assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-switch.init.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/select2/dist/js/select2.full.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/ion-rangeslider/js/ion.rangeSlider.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/typeahead.js/dist/typeahead.bundle.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/handlebars/dist/handlebars.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/nouislider/distribute/nouislider.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/owl.carousel/dist/owl.carousel.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/autosize/dist/autosize.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/clipboard/dist/clipboard.min.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/dropzone/dist/dropzone.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/dropzone.init.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/quill/dist/quill.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/@yaireo/tagify/dist/tagify.polyfills.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/@yaireo/tagify/dist/tagify.min.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/markdown/lib/markdown.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-markdown.init.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/bootstrap-notify/bootstrap-notify.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-notify.init.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/jquery-validation/dist/jquery.validate.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/jquery-validation/dist/additional-methods.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/jquery-validation.init.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/dual-listbox/dist/dual-listbox.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/raphael/raphael.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/morris.js/morris.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
    <script
        src="{{ asset('') }}assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js"
        type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/custom/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
    <script src="{{ asset('') }}assets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript">
    </script>
    <script src="{{ asset('') }}assets/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>
    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Bundle(used by all pages) -->

    <script src="{{ asset('') }}assets/js/demo1/scripts.bundle.js" type="text/javascript"></script>
    <!--end::Global Theme Bundle -->


    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ asset('') }}assets/js/demo1/pages/dashboard.js" type="text/javascript"></script>
    <!--end::Page Scripts -->
</body>
<!-- end::Body -->

</html>