@extends('layouts.app')
@push('title')
    Laporan Monitoring / Pembersihan Alat
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Laporan Monitoring / Pembersihan Alat
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
            <form method="GET" action="{{ route('laporan.monitoring.import') }}" id="cetak-laporan">

                <div class="row col-12">
                    {{-- @can('print-pilih-rs') --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="UserGroupID" class="col-form-label">Pilih RS</label>
                            <div class="form-group row">
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <select class="form-control" name="rs" id="filter_rs">
                                        <option value="">Pilih Rumah Sakit</option>
                                        @foreach ($data as $item)
                                            <option value="{{ $item->kodeRS }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @endcan --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="UserGroupID" class="col-form-label">Unit</label>
                            <div class="form-group row">
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <select class="form-control kt-select2" id="unit" name="unit">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="UserGroupID" class="col-form-label">Nama Alat</label>
                            <div class="form-group row">
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <select class="form-control kt-select2" id="nama" name="nama">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="UserGroupID" class="col-form-label">Jenis</label>
                            <div class="form-group row">
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="">Pilih Salah Satu</option>
                                        <option value="Medis">Medis</option>
                                        <option value="Non Medis">Non Medis</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="UserGroupID" class="col-form-label">Format Cetak</label>
                            <select class="form-control" id="format-laporan" name="format">
                                <option value="">Pilih Salah Satu</option>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>



                </div>
                <div class="row col-12">


                </div>



                <div class="modal fade " id="cari-perangkat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">

                </div>
                <!-- Modal Cari Alat -->
                <!-- modal laporan -->
        </div>
        <div class="card-footer">
            <button class="btn btn-primary btn-block" type="submit">Download</button>
        </div>
        </form>
    </div>
    <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>
    <!--begin: Datatable -->

    <!--end: Datatable -->
    </div>
    </div>
@endsection
@push('css')
    <link href="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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
                                                                    var select_item = function () {
            $('#filter_rs').on('change', function () {
                let rs = $('#filter_rs').val();
                $('#nama').select2({
                    placeholder: "--Select Alat--",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('kalibrasi.get-item') }}?rs=" + rs,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item, key) {
                                    return {
                                        text: item + ' (' + key + ')',
                                        id: key
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            })
        }
        var select_unit = function () {
            $('#filter_rs').on('change', function () {
                let rs = $('#filter_rs').val();
                $('#unit').select2({
                    placeholder: "Select Data",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('inventaris.getDeptHis') }}?rs=" + rs,
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item, key) {
                                    return {
                                        text: item,
                                        id: item
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            })
        }
        jQuery(document).ready(function () {
            $('.progress').hide()
            select_unit()
            select_item()

        });
    </script>
@endpush
