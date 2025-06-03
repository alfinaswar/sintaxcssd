@extends('layouts.app')
@push('title')
Permission
@endpush
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Permission
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('Permission.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i>
                        Tambah
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="table-permission">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Permission</th>
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
</script>
<script>
    var dataTable = function() {
                var table = $('#table-permission');
                table.DataTable({
                    responsive: true,
                    serverSide: true,
                    bDestroy: true,
                    processing: true,
                    language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
                    ajax: "{{ route('Permission.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'name', name: 'name'},
                    ]
                })
            };
        jQuery(document).ready(function() {
           dataTable()
        });
</script>
@endpush