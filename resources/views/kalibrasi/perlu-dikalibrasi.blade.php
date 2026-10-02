@extends('layouts.app')
@push('title')
    Laporan Kalibrasi Perlu Dikalibrasi
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Laporan Item yang Perlu Dikalibrasi</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                {{-- Tombol Export Excel (Membawa parameter filter via Form tersembunyi) --}}
                <form action="{{ route('kalibrasi.download-perlu-dikalibrasi') }}" method="GET" id="form-export"
                    style="display:inline;">
                    <input type="hidden" name="rs" id="export_rs">
                    <input type="hidden" name="unit" id="export_unit">
                    <input type="hidden" name="nama" id="export_nama">
                    <input type="hidden" name="eskalasi" id="export_eskalasi">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </button>
                </form>
            </div>
        </div>

        <div class="kt-portlet__body">
            {{-- Filter Form --}}
            <form id="form-filter-kalibrasi">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label">Rumah Sakit</label>
                            <select class="form-control" name="rs" id="filter_rs">
                                <option value="">Semua Rumah Sakit</option>
                                @foreach ($listRS as $kode => $nama)
                                    <option value="{{ $kode }}" {{ request('rs') == $kode ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label">Unit</label>
                            <select class="form-control kt-select2" id="unit" name="unit">
                                <option value="">Pilih Unit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label">Nama Alat</label>
                            <select class="form-control kt-select2" id="nama" name="nama">
                                <option value="">Pilih Nama Alat</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label">Periode Kedaluwarsa</label>
                            <select class="form-control" id="eskalasi" name="eskalasi">
                                <option value="">Semua (Default: Belum/Sudah Expired)</option>
                                @for ($i = 6; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('eskalasi') == $i ? 'selected' : '' }}>
                                        Akan kedaluwarsa dalam {{ $i }} Bulan
                                    </option>
                                @endfor
                                <option value="0" {{ request('eskalasi') == '0' ? 'selected' : '' }}>Sudah Kedaluwarsa
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="flaticon2-search"></i> Tampilkan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>

    {{-- Data Preview Table --}}
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="table-responsive">
                <table class="table table-head-custom table-head-bg table-borderless table-hover align-middle"
                    id="tabel-kalibrasi" style="width:100%">
                    <thead>
                        <tr class="text-left">
                            <th width="5%">No</th>
                            <th>No. Inventaris</th>
                            <th>Kode Item</th>
                            <th>Nama Alat</th>
                            <th>Unit / Departemen</th>
                            <th>Info Kalibrasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            let dataTable = $('#tabel-kalibrasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kalibrasi.perlu-dikalibrasi') }}",
                    data: function(d) {
                        d.rs = $('#filter_rs').val();
                        d.unit = $('#unit').val();
                        d.nama = $('#nama').val();
                        d.eskalasi = $('#eskalasi').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_inventaris',
                        name: 'no_inventaris'
                    },
                    {
                        data: 'kode_item',
                        name: 'kode_item'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        render: function(data, type, row) {
                            return (data ? data : '-') + (row.departemen ? ' / ' + row.departemen :
                                '');
                        }
                    },
                    {
                        data: 'kalibrasi_info',
                        name: 'kalibrasi_info',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json'
                }
            });

            // 1. Handle Filter Submit
            $('#form-filter-kalibrasi').on('submit', function(e) {
                e.preventDefault();
                updateExportHiddenFields(); // Update hidden fields untuk export
                dataTable.ajax.reload();
            });

            // 2. Update Hidden Fields untuk Export Excel agar sesuai filter
            function updateExportHiddenFields() {
                $('#export_rs').val($('#filter_rs').val());
                $('#export_unit').val($('#unit').val());
                $('#export_nama').val($('#nama').val());
                $('#export_eskalasi').val($('#eskalasi').val());
            }

            // 3. Select2 AJAX Logic
            function initSelect2(elementId, url, placeholder) {
                $(elementId).select2({
                    placeholder: placeholder,
                    minimumInputLength: 1,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item,
                                        text: item
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            $('#filter_rs').on('change', function() {
                const rs = $(this).val();
                $('#unit, #nama').empty().append('<option value="">Pilih...</option>').trigger('change');

                if (rs) {
                    initSelect2('#unit', "{{ route('inventaris.getDeptHis') }}?rs=" + rs,
                        "-- Pilih Unit --");
                    initSelect2('#nama', "{{ route('kalibrasi.get-item') }}?rs=" + rs,
                        "-- Pilih Nama Alat --");
                }
            });

            // Trigger change jika ada parameter dari URL (misal setelah reload)
            @if (request('rs'))
                $('#filter_rs').trigger('change');
            @endif

            // Inisialisasi hidden fields saat load pertama
            updateExportHiddenFields();
        });
    </script>
@endpush
