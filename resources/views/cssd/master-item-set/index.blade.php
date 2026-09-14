@extends('layouts.app')

{{-- Pastikan ada meta csrf-token di layouts.app Anda, atau tambahkan ini di head --}}
@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('title')
    CSSD Item Set
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    CSSD Item Set
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="{{ route('cssd-item-set.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i> Tambah
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <table class="table table-striped table-bordered table-hover table-checkable" id="kt_table_1">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th width="12%">Nama</th>
                        <th>Detail Instrumen</th>
                        <th>Kode RS</th>
                        <th width="8%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('css')
    {{-- Perbaikan path asset agar lebih bersih --}}
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>

    <script>
        // 1. Setup Global CSRF Token agar tidak perlu dikirim manual di setiap AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 2. Notifikasi yang lebih aman dari error quote/string breaking
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Berhasil");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.warning(@json($error), "Gagal");
            @endforeach
        @endif

        // 3. Simpan instance DataTable di variabel global agar bisa di-reload tanpa rebuild total
        let dtInstance;

        function initDataTable() {
            dtInstance = $('#kt_table_1').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                deferRender: true, // 🔥 KUNCI PERFORMA: Hanya merender baris yang terlihat di layar
                bDestroy: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only"> Loading...</span>'
                },
                ajax: "{{ route('cssd-item-set.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'Kode', name: 'Kode' },
                    { data: 'get_namaset.Nama', name: 'get_namaset.Nama' },
                    { data: 'Item', name: 'Item', orderable: false, searchable: false },
                    { data: 'getrs.nama', name: 'getrs.nama' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });
        }

        function delete_data(e, id) {
            e.preventDefault();
            const url = "{{ route('cssd-item-set.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Kamu yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning', // Perbaikan: 'type' deprecated di SweetAlert2, gunakan 'icon'
                showCancelButton: true,
                confirmButtonText: '<i class="la la-check"></i> Ya, Hapus!',
                confirmButtonClass: "btn btn-danger",
                cancelButtonText: '<i class="la la-close"></i> Tidak, Batal!',
                cancelButtonClass: "btn btn-default",
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) { // Perbaikan: result.isConfirmed (Swal2 v11+)
                    KTApp.block('.kt-portlet__body', {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'Sedang menghapus...'
                    });

                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "json",
                        success: function (res) {
                            // 🔥 KUNCI PERFORMA: Reload data saja, JANGAN hancurkan dan buat ulang tabel
                            dtInstance.ajax.reload(null, false); // false = tetap di halaman yang sama

                            Swal.fire({
                                title: 'Terhapus!',
                                text: res.msg || 'Data berhasil dihapus.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        },
                        complete: function () {
                            KTApp.unblock('.kt-portlet__body');
                        }
                    });
                }
            });
        }

        jQuery(document).ready(function () {
            initDataTable();
        });
    </script>
@endpush
