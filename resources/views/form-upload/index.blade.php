@extends('layouts.app')
@push('title')
Dashboard
@endpush
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Dashboard
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    @can('upload-file')
                    <a href="{{ route('index.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i>
                        Tambah
                    </a>
                    @endcan
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
                    <th>Dokumen</th>
                    <th>Nama Dokumen</th>
                    <th>Jenis FIle</th>
                    <th>Requester</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <!--end: Datatable -->
    </div>
</div>
@include('form-upload.modal-verifikasi')
@include('form-upload.modal-show')
@include('form-upload.modal-update-file')
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
                ajax: "{{ route('index.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'dokumen',
                        name: 'dokumen'
                    },
                    {
                        data: 'namaDokumen',
                        name: 'namaDokumen'
                    },
                    {
                        data: 'jenisFile',
                        name: 'jenisFile'
                    },
                    {
                        data: 'requester',
                        name: 'requester'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        function setIdIzin(id) {
            $('#idDokIzin').val(id);
            $('[name=verifikasi]').bootstrapSwitch('state', false);
            $('[name=keterangan]').val('');
        }
        function setIzin(id) {
            $('#idIzin').val(id);
            
        }
        $('#setShowData').click(function(e) {
            e.preventDefault();
            console.log('qq');
        });
        var setShowData = function(e, id) {
            e.preventDefault();
            var url = "{{ route('index.show', 'id') }}"
            url = url.replace('id', id)
            $.ajax({
                type: "GET",
                url: url,
                cache: false,
                dataType: "json",
                beforeSend: function() {
                    KTApp.block('#showData', {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'Please wait...'
                    });
                },
                success: function(res) {
                    $('#showData').html(res.view);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    toastr.warning(xhr.responseJSON.massage, xhr.status)
                },
                complete: function() {
                    KTApp.unblock('#showData');
                }
            });
        }
        jQuery(document).ready(function() {
            dataTable()
            $('[data-switch=true]').bootstrapSwitch();
        });
</script>
@endpush