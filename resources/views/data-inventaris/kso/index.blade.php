@extends('layouts.app')
@push('title')
    Inventaris KSO
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">Inventaris KSO</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('inventariskso.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i> Tambah Data
                        </a>

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

                @if (auth()->user()->role == 'admin' || auth()->user()->role == 'DKH')
                    <div class="col-md-3">
                        <small>Filter By Rumah Sakit:</small>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-hospital-alt"></i></span></div>
                                <select name="filter_rs" class="custom-select form-control filterRS" id="filter_rs">
                                    <option value="" selected>--Pilih RS--</option>
                                    @foreach ($rs as $item)
                                        <option value="{{ $item->kodeRS }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-3">
                        <small>Filter By Rumah Sakit:</small>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-hospital-alt"></i></span></div>
                                <select name="filter_rs" class="custom-select form-control filterRS" id="filter_rs">
                                    <option value="" selected>--Pilih RS--</option>
                                    @foreach ($rs->where('kodeRS', auth()->user()->kodeRS) as $item)
                                        <option value="{{ $item->kodeRS }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-3">
                    <small>Filter By Departemen:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-users"></i></span></div>
                            <select name="filter_departemen" class="form-control kt-select2"
                                id="filter_departemen"></select>
                        </div>
                    </div>
                </div>

                @if (auth()->user()->role == 'admin')
                    <div class="col-md-3">
                        <small>Filter By Unit:</small>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-users"></i></span></div>
                                <select class="form-control kt-select2" id="filter_unit" name="filter_unit"></select>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-3">
                        <small>Filter By Unit:</small>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="fa fa-users"></i></span></div>
                                <select class="form-control kt-select2" id="unit" name="filter_unit"></select>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-3">
                    <small>Filter By Tahun Kerjasama:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-calendar"></i></span></div>
                            <select class="custom-select form-control" id="filter_tahun_kerjasama"
                                name="filter_tahun_kerjasama">
                                <option value="">Pilih Tahun</option>
                                @php
                                    $currentYear = date('Y');
                                    for ($year = $currentYear; $year >= 2010; $year--) {
                                        echo "<option value='$year'>$year</option>";
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <small>Ketik Kata Kunci</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-search"></i></span></div>
                            <input type="search" class="form-control" name="filter_pencarian" id="filter_pencarian"
                                placeholder="Cari">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <small>Cetak Data:</small>
                    <button type="button" class="btn btn-primary btn-block" id="btn_cetak">
                        <i class="fa fa-print"></i> Cetak Data
                    </button>
                </div>
            </div>

            <div class="kt-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama</th>
                            <th>Merk</th>
                            <th>Tipe</th>
                            <th>No SN</th>
                            <th>Vendor</th>
                            <th>Tgl Kerjasama</th>
                            <th>Akhir Kerjasama</th>
                            <th>RS</th>
                            <th>Departemen</th>
                            <th>Unit</th>
                            <th>Jenis</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
                searching: false,
                bDestroy: true,
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                ajax: {
                    url: "{{ route('inventaris.index-kso') }}",
                    data: function(d) {
                        d.filter_pengguna = $('#filter_pengguna').val();
                        d.filter_rs = $('#filter_rs').val();
                        d.filter_departemen = $('#filter_departemen').val();
                        d.filter_unit = $('#filter_unit,#unit').val();
                        d.filter_tahun_kerjasama = $('#filter_tahun_kerjasama').val();
                        d.filter_pencarian = $('#filter_pencarian').val();
                        d.search = $('input[type="search"]').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'KodeBarang',
                        name: 'KodeBarang'
                    },

                    {
                        data: 'Nama',
                        name: 'Nama'
                    },
                    {
                        data: 'Merk',
                        name: 'Merk'
                    },
                    {
                        data: 'Tipe',
                        name: 'Tipe'
                    },
                    {
                        data: 'NoSn',
                        name: 'NoSn'
                    },
                    {
                        data: 'Vendor',
                        name: 'Vendor'
                    },
                    {
                        data: 'TanggalKerjasama',
                        name: 'TanggalKerjasama'
                    },
                    {
                        data: 'AkhirKerjasama',
                        name: 'AkhirKerjasama'
                    },
                    {
                        data: 'NamaRS',
                        name: 'NamaRS'
                    },
                    {
                        data: 'Departemen',
                        name: 'Departemen'
                    },
                    {
                        data: 'Unit',
                        name: 'Unit'
                    },
                    {
                        data: 'Pengguna',
                        name: 'Pengguna'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        };

        var delete_data = function(e, id) {
            e.preventDefault();
            var url = "{{ route('inventariskso.destroy', 'id') }}";
            url = url.replace('id', id);

            swal.fire({
                title: 'Kamu yakin?',
                text: "Kamu akan menghapus data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "<i class='la la-check'></i> Ya, Hapus data!",
                confirmButtonClass: "btn btn-danger",
                cancelButtonText: "<i class='la la-close'></i> Tidak, cancel!",
                cancelButtonClass: "btn btn-default",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        cache: false,
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        beforeSend: function() {
                            KTApp.block('.kt-portlet__body', {
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: 'Please wait...'
                            });
                        },
                        success: function(res) {
                            dataTable();
                            if (res.msg) {
                                swal.fire('Deleted!', 'Data berhasil dihapus.', 'success');
                            }
                        },
                        complete: function() {
                            KTApp.unblock('.kt-portlet__body');
                        }
                    });
                }
            });
        }

        var select_unit = function() {
            $('#filter_rs').on('change', function() {
                let rs = $('#filter_rs').val();
                $('#filter_unit').select2({
                    placeholder: "Select Data",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('inventaris.getDeptHis') }}?rs=" + rs,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item, key) {
                                    return {
                                        text: item,
                                        id: key
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
                $('#filter_departemen').select2({
                    placeholder: "Select Data",
                    minimumInputLength: 1,
                    ajax: {
                        url: "{{ route('master.AmbilDepartemen') }}?rs=" + rs,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item, key) {
                                    return {
                                        text: item,
                                        id: key
                                    }
                                })

                            };
                        },
                        cache: true
                    }
                });
            });
        }

        var select_unit_non = function() {
            $('#unit').select2({
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
            dataTable();
            select_unit_non();
            select_unit();
            $('[data-switch=true]').bootstrapSwitch();
        });

        $('#filter_pengguna,#filter_rs,#filter_departemen,#filter_unit,#unit,#filter_tahun_kerjasama').change(function() {
            $('#kt_table_1').DataTable().draw();
        });

        $('#filter_pencarian').keyup(function() {
            $('#kt_table_1').DataTable().draw();
        });

        $('#btn_cetak').click(function(e) {
            e.preventDefault();
            var url = "{{ route('inventaris.laporan-kso') }}";
            var params = {
                filter_jenis: $('#filter_pengguna').val(),
                filter_rs: $('#filter_rs').val(),
                filter_departemen: $('#filter_departemen').val(),
                filter_unit: $('#filter_unit,#unit').val(),
                filter_tahun_kerjasama: $('#filter_tahun_kerjasama').val(),
                filter_pencarian: $('#filter_pencarian').val(),
                search: $('input[type="search"]').val()
            };
            var form = $('<form/>').attr('action', url).attr('method', 'GET');
            $.each(params, function(key, value) {
                form.append($('<input/>').attr('type', 'hidden').attr('name', key).attr('value', value));
            });
            form.appendTo('body').submit().remove();
        });
    </script>
@endpush
