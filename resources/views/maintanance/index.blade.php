@extends('layouts.app')
@push('title')
    Preventif Maintenance
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Preventif Maintenance Alat
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    {{-- Tombol Download Laporan --}}
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#modalDownloadLaporan">
                        <i class="la la-download"></i> Download Laporan
                    </button>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Bulan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Download Laporan --}}
    <div class="modal fade" id="modalDownloadLaporan" tabindex="-1" role="dialog"
        aria-labelledby="modalDownloadLaporanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDownloadLaporanLabel">Download Laporan Preventif Maintenance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('maintanance.export') }}" method="GET" target="_blank">
                    <div class="modal-body">
                        <div class="row">
                            {{-- Filter RS (Hanya tampil untuk Admin) --}}
                            @if (auth()->user()->role == 'admin')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="filter_rs">Rumah Sakit <span class="text-danger">*</span></label>
                                        <select name="filter_rs" id="filter_rs" class="form-control" required>
                                            <option value="">-- Semua RS --</option>
                                            @foreach ($rs as $r)
                                                <option value="{{ $r->kodeRS }}">{{ $r->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="filter_rs" value="{{ auth()->user()->kodeRS }}">
                            @endif

                            {{-- Filter Tahun --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tahun">Tahun <span class="text-danger">*</span></label>
                                    <select name="tahun" id="tahun" class="form-control" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            {{-- Filter Bulan Awal --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bulan_awal">Bulan Awal <span class="text-danger">*</span></label>
                                    <select name="bulan_awal" id="bulan_awal" class="form-control" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Filter Bulan Akhir --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bulan_akhir">Bulan Akhir <span class="text-danger">*</span></label>
                                    <select name="bulan_akhir" id="bulan_akhir" class="form-control" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="la la-file-excel-o"></i> Download Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Edit Maintenance --}}
    <div class="modal fade" id="modalEditMaintanance" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Preventif Maintenance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="la la-close"></i>
                    </button>
                </div>
                <form id="formEditMaintanance" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Alat</label>
                                    <input type="text" class="form-control" id="edit_nama_alat" disabled>
                                    <small class="form-text text-muted">Nama alat tidak dapat diubah</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kode Item</label>
                                    <input type="text" class="form-control" id="edit_kode_item" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_bulan">* Bulan</label>
                                    <select class="form-control" id="edit_bulan" name="bulan" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_status">* Status</label>
                                    <select class="form-control" id="edit_status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="1">Sudah Maintenance</option>
                                        <option value="2">Belum Maintenance</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_keterangan">Keterangan</label>
                            <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnUpdateMaintanance">
                            <i class="la la-check"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                searchDelay: 500, // ✅ Tambahkan: delay 500ms agar tidak spam request
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                ajax: {
                    url: "{{ route('maintanance.index') }}",
                    data: function(d) {
                        d.filter_tanggal = $('#filter_tanggal').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_rs = $('#filter_rs').val();
                        // ✅ HAPUS baris ini: d.search = $('input[type="search"]').val()
                        // Karena DataTables sudah otomatis kirim parameter search[value]
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_item',
                        name: 'kode_item',
                        searchable: true
                    },
                    {
                        data: 'bulan',
                        name: 'bulan',
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        };

        jQuery(document).ready(function() {
            dataTable();
        });
    </script>
    <script>
        // Fungsi membuka modal edit & fetch data via AJAX
        function openEditModal(id) {
            $('#btnUpdateMaintanance').prop('disabled', true).html('<i class="la la-spinner la-spin"></i> Loading...');

            $.ajax({
                url: '{{ route('maintanance.edit.ajax', ':id') }}'.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        // Isi form dengan data
                        $('#edit_id').val(data.id);
                        $('#formEditMaintanance').attr('action', '{{ route('maintanance.update', ':id') }}'
                            .replace(':id', data.id));
                        $('#edit_nama_alat').val(data.get_inventaris ? data.get_inventaris.nama : data
                            .kode_item);
                        $('#edit_kode_item').val(data.kode_item);
                        $('#edit_bulan').val(data.bulan);
                        $('#edit_status').val(data.status);
                        $('#edit_keterangan').val(data.keterangan);

                        // Tampilkan modal
                        $('#modalEditMaintanance').modal('show');

                        // Reset tombol
                        $('#btnUpdateMaintanance').prop('disabled', false).html(
                            '<i class="la la-check"></i> Update Data');
                    }
                },
                error: function(xhr) {
                    toastr.error('Gagal memuat data: ' + xhr.responseText, 'Error');
                    $('#btnUpdateMaintanance').prop('disabled', false).html(
                        '<i class="la la-check"></i> Update Data');
                }
            });
        }

        // Handle submit form edit via AJAX
        $('#formEditMaintanance').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = $('#btnUpdateMaintanance');

            btn.prop('disabled', true).html('<i class="la la-spinner la-spin"></i> Updating...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Berhasil');
                        $('#modalEditMaintanance').modal('hide');
                        $('#kt_table_1').DataTable().draw(false); // Refresh table tanpa reset paging
                    } else {
                        toastr.error(response.message || 'Gagal update data', 'Error');
                    }
                },
                error: function(xhr) {
                    // Handle validation errors
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += value[0] + '<br>';
                        });
                        toastr.warning(errorMsg, 'Validasi Gagal');
                    } else {
                        toastr.error('Terjadi kesalahan: ' + xhr.responseText, 'Error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="la la-check"></i> Update Data');
                }
            });
        });

        // Reset form saat modal ditutup
        $('#modalEditMaintanance').on('hidden.bs.modal', function() {
            $('#formEditMaintanance')[0].reset();
            $('#formEditMaintanance').attr('action', '');
        });
    </script>
@endpush
