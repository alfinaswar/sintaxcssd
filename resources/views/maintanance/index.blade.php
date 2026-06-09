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
                ajax: {
                    url: "{{ route('maintanance.index') }}",
                    data: function(d) {
                        d.filter_tanggal = $('#filter_tanggal').val(),
                            d.filter_status = $('#filter_status').val(),
                            d.filter_rs = $('#filter_rs').val(),
                            d.search = $('input[type="search"]').val()
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
                        name: 'kode_item'
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
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

        jQuery(document).ready(function() {
            dataTable();
        });
    </script>
@endpush
