@extends('layouts.app')
@push('title')
Master Rs
@endpush
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Master RS
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <a href="{{ route('master-rs.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i>
                        Tambah
                    </a>
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
                    <th>Nama</th>
                    <th>Actions</th>
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
                ajax: "{{ route('master-rs.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
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
            var url = "{{ route('master-rs.destroy', 'id') }}"
            url = url.replace('id', id)

            swal.fire({
                title: 'kamu yakin?',
                text: "Kamu akan menghapus data rs ini!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "<i class='la la-check'></i> Ya, Hapus!",
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
        jQuery(document).ready(function() {
            dataTable()
        $('.progress').hide();

        });
</script>
@endpush