@extends('layouts.app')
@push('title')
Master Alat (KSO)
@endpush
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Master Alat (KSO)
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('master-alat-kso.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i>
                        Tambah
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
            <thead class="table-primary">
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Alat (Klik untuk lihat Merk & Tipe)</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Merk & Tipe -->
<div class="modal fade" id="modalDetailAlatKso" tabindex="-1" role="dialog" aria-labelledby="modalDetailAlatKsoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailAlatKsoLabel">Detail Alat: <span id="detailAlatNama"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="kt-font-bold mb-0">Daftar Merk (KSO)</h6>
                            <a href="{{ route('master-merk-kso.create') }}" class="btn btn-sm btn-outline-brand">+ Tambah Merk</a>
                        </div>
                        <ul class="list-group" id="listMerkKso">
                            <li class="list-group-item text-muted">Memuat...</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="kt-font-bold mb-0">Daftar Tipe (KSO)</h6>
                            <a href="{{ route('master-tipe-kso.create') }}" class="btn btn-sm btn-outline-brand">+ Tambah Tipe</a>
                        </div>
                        <ul class="list-group" id="listTipeKso">
                            <li class="list-group-item text-muted">Memuat...</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
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
            ajax: "{{ route('master-alat-kso.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'nama', name: 'nama' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    };

    var showDetail = function(id) {
        var url = "{{ route('master-alat-kso.show', ':id') }}".replace(':id', id);
        $('#detailAlatNama').text('...');
        $('#listMerkKso').html('<li class="list-group-item text-muted">Memuat...</li>');
        $('#listTipeKso').html('<li class="list-group-item text-muted">Memuat...</li>');
        $('#modalDetailAlatKso').modal('show');

        $.get(url, function(res) {
            $('#detailAlatNama').text(res.alat.nama);

            if (res.merks && res.merks.length > 0) {
                var merkHtml = '';
                res.merks.forEach(function(m) {
                    merkHtml += '<li class="list-group-item">' + $('<div>').text(m.nama).html() + '</li>';
                });
                $('#listMerkKso').html(merkHtml);
            } else {
                $('#listMerkKso').html('<li class="list-group-item text-muted">Belum ada merk</li>');
            }

            if (res.tipes && res.tipes.length > 0) {
                var tipeHtml = '';
                res.tipes.forEach(function(t) {
                    var merkLabel = t.merk ? ' <small class="text-muted">(' + $('<div>').text(t.merk.nama).html() + ')</small>' : '';
                    tipeHtml += '<li class="list-group-item">' + $('<div>').text(t.nama).html() + merkLabel + '</li>';
                });
                $('#listTipeKso').html(tipeHtml);
            } else {
                $('#listTipeKso').html('<li class="list-group-item text-muted">Belum ada tipe</li>');
            }
        }).fail(function() {
            $('#listMerkKso').html('<li class="list-group-item text-danger">Gagal memuat data</li>');
            $('#listTipeKso').html('<li class="list-group-item text-danger">Gagal memuat data</li>');
        });
    };

    var delete_data = function(e, id) {
        e.preventDefault();
        var url = "{{ route('master-alat-kso.destroy', 'id') }}".replace('id', id);

        swal.fire({
            title: 'kamu yakin?',
            text: "Kamu akan menghapus data ini!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "<i class='la la-check'></i> Ya, Hapus!",
            confirmButtonClass: "btn btn-danger",
            cancelButtonText: "<i class='la la-close'></i> Tidak",
            cancelButtonClass: "btn btn-secondary",
            reverseButtons: true
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function() {
                        toastr.success("Data berhasil dihapus", "Berhasil");
                        $('#kt_table_1').DataTable().ajax.reload();
                    },
                    error: function() {
                        toastr.error("Gagal menghapus data", "Gagal");
                    }
                });
            }
        });
    };

    jQuery(document).ready(function() {
        dataTable();
    });
</script>
@endpush
