@extends('layouts.app')
@push('title')
    Data Penghapusan Aset
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-file"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Data Penghapusan Aset
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('pa.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Tambah
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filter_rs">Filter Rumah Sakit</label>
                    <select class="form-control" id="filter_rs" name="filter_rs">
                        <option value="">Semua Rumah Sakit</option>
                        @foreach (\App\Models\MasterRs::all() as $rs)
                            <option value="{{ $rs->kodeRS }}">{{ $rs->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--begin: Datatable -->
            <table class="table table-striped table-bordered table-hover" id="penghapusan_aset_table">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nomor Pengajuan</th>
                        <th>Departemen</th>
                        <th>Unit</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Diajukan Oleh</th>
                        <th>Persetujuan 1</th>
                        <th>Kode RS</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
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
@endsection

@push('css')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        // Inisialisasi DataTable
        function initDataTable() {
            // Hancurkan instance lama jika ada
            if ($.fn.DataTable.isDataTable('#penghapusan_aset_table')) {
                $('#penghapusan_aset_table').DataTable().destroy();
            }

            $('#penghapusan_aset_table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pa.index') }}",
                    data: function(d) {
                        d.rs = $('#filter_rs').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'NomorPengajuan',
                        name: 'NomorPengajuan'
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
                        data: 'Tanggal',
                        name: 'Tanggal'
                    },
                    {
                        data: 'Status',
                        name: 'Status'
                    },
                    {
                        data: 'get_diajukan.name',
                        name: 'get_diajukan.name'
                    },
                    {
                        data: 'Sign1',
                        name: 'Sign1'
                    },
                    {
                        data: 'get_rs.nama',
                        name: 'get_rs.nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        }

        function delete_data(e, id) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah event bubble ke DataTable row

            var url = "{{ route('pa.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data penghapusan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => { // ← gunakan didOpen, bukan willOpen
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            $('#penghapusan_aset_table').DataTable().ajax.reload(null, false);

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.msg || 'Data berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan saat menghapus data.';

                            if (xhr.responseJSON && xhr.responseJSON.msg) {
                                message = xhr.responseJSON.msg;
                            } else if (xhr.status === 404) {
                                message = 'Data tidak ditemukan.';
                            } else if (xhr.status === 403) {
                                message = 'Anda tidak memiliki akses untuk menghapus data ini.';
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: message,
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            initDataTable();

            $('#filter_rs').on('change', function() {
                initDataTable(); // destroy + reinit sudah di dalam fungsi, cukup panggil ini saja
            });
        });
    </script>
@endpush
