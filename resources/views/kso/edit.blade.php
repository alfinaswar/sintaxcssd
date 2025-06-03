@extends('layouts.app')
@push('title')
    Kerjasama Operasional
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Tambah KSO
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold"
                    role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#" role="tab">
                            <i class="flaticon2-heart-rate-monitor" aria-hidden="true"></i>KSO
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="tab-content">
                <div class="tab-pane active" id="kt_portlet_base_demo_3_3_tab_content" role="tabpanel">
                    <form class="kt-form kt-form--label-right" id="form-kso" action="{{ route('kso.update', $data->id) }}"
                        method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="kt-portlet__body">
                            @if ($errors->any())
                                <div class="alert alert-warning fade show" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                    <div class="alert-text">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="alert-close">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true"><i class="la la-close"></i></span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if (session('warning'))
                                <div class="alert alert-warning">
                                    {{ session('warning') }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="nama" class="col-3 col-form-label">Nama Alat</label>
                                        <div class="col-lg-9 col-md-4 col-sm-12">
                                            <select class="form-control kt-select2" id="KodeAlat" name="KodeAlat"
                                                onchange="select_item(this.value)">
                                                <option value="{{ $data->KodeAlat }}" selected>
                                                    {{ $data->getNamaBarang->nama }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Type</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <input class="form-control" name="Type"
                                                value="{{ old('Type', $data->Type) }}" placeholder="Tipe Alat"
                                                type="text" id="Type">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Merk</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <input class="form-control" name="Merk"
                                                value="{{ old('Merk', $data->Merk) }}" placeholder="Merk" type="text"
                                                id="Merk">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="departemen" class="col-3 col-form-label">* Nomor SN</label>
                                        <div class="col-lg-6">
                                            <input class="form-control" name="Sn" value="{{ old('Sn', $data->Sn) }}"
                                                placeholder="Nomor SN" type="text" id="no_sn" required>
                                        </div>
                                        <div class="col-lg-3">
                                            <input type="checkbox" class="form-check-input" id="checkbox">
                                            <label class="form-check-label" for="checkbox">Tidak Ada</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="NamaPerusahaan" class="col-3 col-form-label">Nama Perusahaan</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="NamaPerusahaan"
                                                value="{{ old('NamaPerusahaan', $data->NamaPerusahaan) }}"
                                                placeholder="Nama Perusahaan" type="text" id="NamaPerusahaan">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="TanggalKerjasama" class="col-3 col-form-label">Tanggal Kerjasama</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="TanggalKerjaSama"
                                                value="{{ old('TanggalKerjasama', $data->TanggalKerjasama) }}"
                                                placeholder="Tanggal Kerjasama" type="date" id="TanggalKerjasama">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="DueDateKerjaSama" class="col-3 col-form-label">Tanggal
                                            Berakhir</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="DueDateKerjaSama"
                                                value="{{ old('DueDateKerjaSama', $data->DueDateKerjaSama) }}"
                                                placeholder="Tanggal Berakhir" type="date" id="DueDateKerjaSama">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Jumlah" class="col-3 col-form-label">Jumlah</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" name="Jumlah"
                                                value="{{ old('Jumlah', $data->Jumlah) }}" placeholder="Jumlah"
                                                type="number" id="Jumlah">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="Unit" class="col-3 col-form-label">Unit</label>
                                        <div class="col-lg-9">

                                            <select class="form-control kt-select2" id="Unit" name="Unit">
                                                <option value="{{ $data->Unit }}" selected>
                                                    {{ $data->Unit }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Dokumen KSO</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <input type="file" class="custom-file" value="{{ old('DokumenKso') }}"
                                                name="DokumenKso" id="DokumenKso" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">Dokumen Kalibrasi</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                            <input type="file" class="custom-file"
                                                value="{{ old('DokumenKalibrasi') }}" name="DokumenKalibrasi"
                                                id="DokumenKalibrasi" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                            </div>
                        </div>
                    </form>
                @endsection
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
                        var simpan = function(e, id) {
                            e.preventDefault();
                            KTApp.block('.kt-portlet', {
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: 'Please wait...'
                            });
                            $('.progress').show()
                            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
                            $("#form-kso").submit();
                        }
                        var select_item = function() {
                            $('#KodeAlat').select2({
                                placeholder: "--Select Alat--",
                                minimumInputLength: 1,
                                ajax: {
                                    url: '{{ route('inventaris.get-master-item') }}',
                                    dataType: 'json',
                                    delay: 0,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.nama + ' - ' + item.no_sn + ' - ' + item.merk +
                                                        ' - ' + item.unit,
                                                    id: item.kode_item
                                                }
                                            })
                                        };
                                    },
                                    cache: true
                                },
                                templateResult: function(data) {
                                    if (data.loading) {
                                        return data.text;
                                    }
                                    var $result = $(
                                        '<div>' + data.text + '</div>'
                                    );
                                    return $result;
                                },
                                templateSelection: function(data) {
                                    $('#no_sn').val(data.text.split(' - ')[1]);
                                    $('#Merk').val(data.text.split(' - ')[2]);
                                    return data.text;
                                }
                            });
                        }
                        var select_unit = function() {
                            $('#Unit').select2({
                                placeholder: "Select Data",
                                minimumInputLength: 1,
                                ajax: {
                                    url: '{{ route('inventaris.get-unit-his') }}',
                                    dataType: 'json',
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
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
                        }
                        jQuery(document).ready(function() {
                            $('.progress').hide()
                            select_item()
                            select_unit()
                            dataTable()

                        });
                    </script>
                @endpush
