@extends('layouts.app')
@push('title')
    KSO
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    KSO
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">


                        <a href="{{ route('kso.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Tambah</a>

                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <small>Filter By Jenis:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-search"></i></span></div>
                            <select class="form-control" name="filter_pengguna" id="filter_pengguna">
                                <option value="" selected>Semua</option>
                                <option value="Non Medis">Non Medis / Umum</option>
                                <option value="Medis">Medis</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if (auth()->user()->role == 'admin')
                    <div class="col-md-3">
                        <small>Filter By Rumah Sakit:</small>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-hospital-alt"></i></span></div>
                                <select name="filter_rs" class="custom-select form-control filterRS" id="filter_rs">
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
                @else
                @endif
                <div class="col-md-3">
                    <small>Filter By Departemen:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-users"></i></span></div>
                            <select name="filter_departemen" class="custom-select form-control" id="filter_departemen">
                                <option value="" selected>--Pilih Departemen--</option>
                                @foreach ($dept as $item)
                                    <option value="{{ $item->nama }}" {{ old('nama') == $item->nama ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <small>Filter By Unit:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-users"></i></span></div>
                            <select class="form-control kt-select2" id="filter_unit" name="filter_unit">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">

                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Type</th>
                            <th>Merk</th>
                            <th>Sn</th>
                            <th>Perusahaan</th>
                            <th>Mulai / AKhir KerjaSama</th>
                            <th>Jumlah</th>
                            <th>Unit</th>
                            <th>Dokumen Kalibrasi</th>
                            <th>Dokumen Kso</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
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
        var dataTable = function() {
            var table = $('#kt_table_1');
            table.DataTable({
                responsive: true,
                serverSide: true,
                bDestroy: true,
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                ajax: {
                    url: "{{ route('kso.index') }}",
                    data: function(d) {
                        d.filter_rs = $('#filter_rs').val(),
                            d.filter_unit = $('#filter_unit').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false

                    },
                    {
                        data: 'get_nama_barang.nama',
                        name: 'get_nama_barang.nama'
                    },

                    {
                        data: 'Merk',
                        name: 'Merk'
                    },
                    {
                        data: 'Sn',
                        name: 'Sn'
                    },
                    {
                        data: 'NamaPerusahaan',
                        name: 'NamaPerusahaan'
                    },
                    {
                        data: 'TanggalKSO',
                        name: 'TanggalKSO'
                    },
                    {
                        data: 'Jumlah',
                        name: 'Jumlah'
                    },
                    {
                        data: 'Unit',
                        name: 'Unit'
                    },
                    {
                        data: 'DokumenKalibrasi',
                        name: 'DokumenKalibrasi'
                    },
                    {
                        data: 'DokumenKso',
                        name: 'DokumenKso'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            })
        };
        var delete_data = function(e, id) {
            e.preventDefault()
            var url = "{{ route('kso.destroy', 'id') }}"
            url = url.replace('id', id)

            swal.fire({
                title: 'kamu yakin?',
                text: "Kamu akan menghapus data ini!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "<i class='la la-check'></i> Ya, Hapus file!",
                confirmButtonClass: "btn btn-danger",
                cancelButtonText: "<i class='la la-close'></i>Tidak, cancel!",
                cancelButtonClass: "btn btn-default",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        cache: false,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        beforeSend: function() {
                            KTApp.block('.kt-portlet__body', {
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: 'Please wait...'
                            });
                            $('.progress').show()
                        },
                        success: function(res) {
                            dataTable()
                            if (res.msg) {
                                swal.fire(
                                    'Deleted!',
                                    'Data berhasil di hapus.',
                                    'success'
                                )
                            }
                        },
                        complete: function() {
                            KTApp.unblock('.kt-portlet__body');
                            $('.progress').hide()
                        }
                    });
                }
            });
        }
        var select_unit = function() {
            $('#filter_unit').select2({
                placeholder: "--Pilih Unit--",
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
            dataTable()
            select_unit(),
                $('[data-switch=true]').bootstrapSwitch();
            $('.progress').hide();

        });

        $('#filter_pengguna,#filter_rs,#filter_departemen,#filter_unit').change(function() {
            var table = $('#kt_table_1').DataTable();
            table.draw();
        });
    </script>
@endpush
