@extends('layouts.app')
@push('title')
    Master Gudang
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Master Gudang
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <!-- Tombol Tambah diubah menjadi trigger modal -->
                        <a href="javascript:void(0)" onclick="openAddModal()" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            Tambah Gudang
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
                        <th width="5%">No</th>
                        <th>Nama Gudang</th>
                        <th>Dibuat Oleh</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>

    <!--begin::Modal-->
    <div class="modal fade" id="MasterGudang" tabindex="-1" role="dialog" aria-labelledby="MasterGudangLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MasterGudangLabel">Form Gudang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formMasterGudang" method="POST" action="{{ route('master.master-gudang.store') }}">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="Nama" class="form-control-label">Nama Gudang</label>
                            <input type="text" class="form-control" name="Nama" id="Nama"
                                placeholder="Masukkan nama gudang" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::Modal-->
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
                ajax: "{{ route('master.master-gudang.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'Nama',
                        name: 'Nama'
                    },
                    {
                        data: 'UserCreate',
                        name: 'UserCreate'
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
            var url = "{{ route('master.master-gudang.destroy', 'id') }}"
            url = url.replace('id', id)

            swal.fire({
                title: 'Kamu yakin?',
                text: "Kamu akan menghapus data gudang ini!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "<i class='la la-check'></i> Ya, Hapus!",
                confirmButtonClass: "btn btn-danger",
                cancelButtonText: "<i class='la la-close'></i> Tidak, batal!",
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
                                    'Data berhasil dihapus.',
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

        // Function to open modal for adding
        function openAddModal() {
            $('#MasterGudangLabel').text('Tambah Gudang');
            $('#formMasterGudang')[0].reset();
            $('#id').val('');
            $('#formMasterGudang').attr('action', "{{ route('master.master-gudang.store') }}");
            $('#formMasterGudang').find('input[name="_method"]').remove();
            $('#MasterGudang').modal('show');
        }

        // Function to open modal for editing using data attributes
        $(document).on('click', '.btn-edit-gudang', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var usercreate = $(this).data('usercreate');
            var userupdate = $(this).data('userupdate');

            $('#MasterGudangLabel').text('Edit Gudang');
            $('#id').val(id);
            $('#Nama').val(nama);
            $('#UserCreate').val(usercreate);
            $('#UserUpdate').val(userupdate);
            $('#formMasterGudang').attr('action', "{{ url('master/master-gudang') }}/" + id);

            $('#formMasterGudang').find('input[name="_method"]').remove();
            $('#formMasterGudang').append('<input type="hidden" name="_method" value="PUT">');

            $('#MasterGudang').modal('show');
        });

        // Handle form submission via AJAX
        $('#formMasterGudang').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var method = form.find('input[name="_method"]').val() || 'POST';
            var data = form.serialize();

            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function(response) {
                    $('#MasterGudang').modal('hide');
                    dataTable(); // Reload datatable
                    if (method === 'POST') {
                        toastr.success("Data berhasil ditambahkan", "Berhasil");
                    } else {
                        toastr.success("Data berhasil diupdate", "Berhasil");
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if (res && res.errors) {
                        var errorMsg = Object.values(res.errors).join('<br>');
                        toastr.error(errorMsg, "Validasi Gagal");
                    } else {
                        toastr.error("Terjadi kesalahan pada server", "Error");
                    }
                }
            });
        });

        jQuery(document).ready(function() {
            dataTable()
            $('.progress').hide();
        });
    </script>
@endpush
