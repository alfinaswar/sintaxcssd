@extends('layouts.app')
@push('title')
    Laporan Preventif (Maintnance)
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Laporan Preventif (Maintnance)
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">

                    </div>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <form method="GET" action="{{ route('laporan.maintenance.excel_pm') }}" id="cetak-laporan">

                <div class="row col-12">
                    @if (auth()->user()->role == 'admin')
                        <div class="col-md-3">
                            <label for="UserGroupID" class="col-form-label">Dari RS</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fa fa-hospital-alt"></i></span></div>
                                    <select name="filter_rs" class="custom-select form-control" id="filter_rs">
                                        <option value="" selected>--Pilih RS--</option>
                                        @foreach ($rs as $item)
                                            <option value="{{ $item->kodeRS }}"
                                                {{ old('kodeRs') == $item->kodeRS ? 'selected' : '' }}>
                                                {{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="UserGroupID" class="col-form-label">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nama" class="col-form-label">Sampai</label>
                            <input type="date" name="tgl_akhir" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <br><br>
                            <input type="submit" name="cetak" id="cetak" class="btn btn-info btn-md"
                                value="Cetak Laporan">
                        </div>
                    </div>
                </div>

            </form>
            <div class="modal fade " id="cari-perangkat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">

            </div>
            <!-- Modal Cari Alat -->
            <!-- modal laporan -->
        </div>
    </div>
    <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>
    <!--begin: Datatable -->

    <!--end: Datatable -->
    </div>
    </div>
@endsection
@push('css')
    <link href="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet"
        type="text/css" />
@endpush
@push('js')
    <script src="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <script>
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Berhasil");
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.warning(`{{ $error }}`, "Gagal");
            @endforeach
        @endif
    </script>
    <script>
        jQuery(document).ready(function() {
            $('.progress').hide()
        });
    </script>
@endpush
