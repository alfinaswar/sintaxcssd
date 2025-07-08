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
                        @foreach(\App\Models\MasterRs::all() as $rs)
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
                        <th>Persetujuan 2</th>
                        <th>Persetujuan 3</th>
                        <th>Persetujuan 4</th>
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
        var dataTable = function (rs = '') {
            $('#penghapusan_aset_table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('pa.index') }}",
                    data: function (d) {
                        d.rs = $('#filter_rs').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'NomorPengajuan', name: 'NomorPengajuan' },
                    { data: 'get_departemen.nama', name: 'get_departemen.nama' },
                    { data: 'Unit', name: 'Unit' },
                    { data: 'Tanggal', name: 'Tanggal' },
                    { data: 'Status', name: 'Status' },
                    { data: 'get_diajukan.name', name: 'get_diajukan.name' },
                    { data: 'Sign1', name: 'Sign1' },
                    { data: 'Sign2', name: 'Sign2' },
                    { data: 'Sign3', name: 'Sign3' },
                    { data: 'Sign4', name: 'Sign4' },
                    { data: 'get_rs.nama', name: 'get_rs.nama' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        };

        var delete_data = function (e, id) {
            e.preventDefault();
            var url = "{{ route('pa.destroy', ':id') }}".replace(':id', id);
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
                            $('#penghapusan_aset_table').DataTable().ajax.reload();
                            Swal.fire('Berhasil!', 'Data telah dihapus.', 'success');
                        }
                    });
                }
            });
        };

        $(document).ready(function () {
            dataTable();

            $('#filter_rs').change(function () {
                dataTable($(this).val());
                $('#penghapusan_aset_table').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
