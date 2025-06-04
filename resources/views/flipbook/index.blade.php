@extends('layouts.app')
@push('title')
    Data Flipbook
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-file"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Data Flipbook
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('flipbook.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Tambah
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped table-bordered table-hover" id="flipbook_table">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nama Item</th>
                        <th>Jenis</th>
                        <th>Rumah Sakit</th>
                        <th>Departemen</th>
                        <th>Nama File</th>
                        <th>Tanggal Pembelian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        var dataTable = function () {
            $('#flipbook_table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('flipbook.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'Nama', name: 'Nama' },
                    { data: 'NamaItem', name: 'NamaItem' },
                    { data: 'Jenis', name: 'Jenis' },
                    { data: 'RumahSakit', name: 'RumahSakit' },
                    { data: 'Departemen', name: 'Departemen' },
                    { data: 'NamaFile', name: 'NamaFile' },
                    { data: 'TanggalPembelian', name: 'TanggalPembelian' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        };

        var delete_data = function (e, id) {
            e.preventDefault();
            var url = "{{ route('flipbook.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            $('#flipbook_table').DataTable().ajax.reload();
                            Swal.fire('Berhasil!', 'Data telah dihapus.', 'success');
                        }
                    });
                }
            });
        };

        $(document).ready(function () {
            dataTable();
        });
    </script>
@endpush