@extends('layouts.app')
@push('title')
    Riwayat Pengajuan Item CSSD
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info mb-4" role="alert">
                <strong>Informasi:</strong> Halaman ini menampilkan <b>riwayat pengajuan nama item CSSD</b> yang pernah
                diajukan. Anda dapat melihat detail pengajuan yang telah dilakukan sebelumnya pada tabel di bawah ini.
            </div>
        </div>
    </div>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Riwayat Pengajuan Item CSSD
                </h3>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="table_riwayat_pengajuan">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Instrumen</th>
                        <th>Nama Item</th>
                        <th>Merk</th>
                        <th>Supplier</th>
                        <th>Type Kategori</th>
                        <th>Kode RS</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
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
            var dataTable = function () {
            var table = $('#table_riwayat_pengajuan');
            table.DataTable({
                responsive: true,
                serverSide: true,
                bDestroy: true,
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                ajax: "{{ route('pengajuan-nama-item-cssd.history') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'KodeInstrumen', name: 'KodeInstrumen' },
                    { data: 'NamaItem', name: 'NamaItem' },
                    { data: 'Merk', name: 'Merk' },
                    { data: 'Supplier', name: 'Supplier' },
                    { data: 'TypeKategori', name: 'TypeKategori' },
                    { data: 'KodeRs', name: 'KodeRs' },
                    { data: 'get_header.Keterangan', name: 'get_header.Keterangan', defaultContent: '-' },
                ]
            })
        };
        jQuery(document).ready(function () {
            dataTable()
        });
    </script>
@endpush