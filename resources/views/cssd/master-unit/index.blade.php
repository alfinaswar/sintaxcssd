@extends('layouts.app')
@push('title')
    Master Unit
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Master Unit
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">

                        <button type="button" class="btn btn-info btn-elevate btn-icon-sm" data-toggle="modal"
                            data-target="#modalSinkronRS">
                            <i class="la la-sync"></i>
                            Sinkron Data RS
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Sinkron RS -->
            <div class="modal fade" id="modalSinkronRS" tabindex="-1" role="dialog" aria-labelledby="modalSinkronRSLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formSinkronRS" method="POST" action="{{ route('cssd-master-unit.store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalSinkronRSLabel">Sinkron Data Unit dari RS</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="rsSelect">Pilih Rumah Sakit</label>
                                    <select class="form-control" id="rsSelect" name="kode_rs" required>
                                        <option value="">-- Pilih RS --</option>
                                        @foreach($rs as $r)
                                            <option value="{{ $r->kodeRS }}">{{ $r->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Progress bar -->
                                <div class="progress mt-3" id="syncProgress" style="height: 20px; display: none;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                        role="progressbar" style="width: 100%">
                                        Memproses sinkronisasi...
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Sinkronkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Unit</th>
                        <th>Id Unit</th>
                        <th>Nama</th>
                        <th>Rumah Sakit</th>

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
            var table = $('#kt_table_1');
            table.DataTable({
                responsive: true,
                serverSide: true,
                bDestroy: true,
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                ajax: "{{ route('cssd-master-unit.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'KodeUnit',
                        name: 'KodeUnit'
                    },
                    {
                        data: 'IdUnit',
                        name: 'IdUnit'
                    },
                    {
                        data: 'Nama',
                        name: 'Nama'
                    },
                    {
                        data: 'KodeRs',
                        name: 'KodeRs'
                    },

                ]
            })
        };
        var delete_data = function (e, id) {
            e.preventDefault()
            var url = "{{ route('cssd-master-unit.destroy', 'id') }}"
            url = url.replace('id', id)

            swal.fire({
                title: 'kamu yakin?',
                text: "Kamu akan menghapus data ini!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: "<i class='la la-check'></i> Ya, Hapus!",
                confirmButtonClass: "btn btn-danger",
                cancelButtonText: "<i class='la la-close'></i>Tidak, cancel!",
                cancelButtonClass: "btn btn-default",
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        cache: false,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        dataType: "json",
                        beforeSend: function () {
                            KTApp.block('.kt-portlet__body', {
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: 'Please wait...'
                            });
                            $('.progress').show()
                        },
                        success: function (res) {
                            dataTable()
                            if (res.msg) {
                                swal.fire(
                                    'Deleted!',
                                    'Data berhasil di hapus.',
                                    'success'
                                )
                            }
                        },
                        complete: function () {
                            KTApp.unblock('.kt-portlet__body');
                            $('.progress').hide()
                        }
                    });
                }
            });
        }
        jQuery(document).ready(function () {
            dataTable()
            $('.progress').hide();

            $(function () {
                $('#formSinkronRS').on('submit', function (e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const data = form.serialize();

                    // Tampilkan progress bar
                    $('#syncProgress').show();

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        beforeSend: function () {
                            // Optional: block modal agar user tidak klik apa-apa
                            if (typeof KTApp !== 'undefined') {
                                KTApp.block('.modal-content', {
                                    overlayColor: '#000000',
                                    type: 'v2',
                                    state: 'primary',
                                    message: 'Sinkronisasi...'
                                });
                            }
                        },
                        success: function (res) {
                            if (res.success) {
                                toastr.success(res.message);
                                $('#modalSinkronRS').modal('hide');
                                if (typeof dataTable === 'function') {
                                    dataTable();
                                }
                            } else {
                                toastr.warning(res.message);
                            }
                        },
                        error: function (xhr) {
                            toastr.error('Sinkronisasi gagal: ' + xhr.responseJSON?.message || 'Terjadi kesalahan');
                        },
                        complete: function () {
                            $('#syncProgress').hide();
                            if (typeof KTApp !== 'undefined') {
                                KTApp.unblock('.modal-content');
                            }
                        }
                    });
                });

                // Reset progress bar setiap kali modal dibuka ulang
                $('#modalSinkronRS').on('show.bs.modal', function () {
                    $('#syncProgress').hide();
                });
            });
        });
    </script>
@endpush
