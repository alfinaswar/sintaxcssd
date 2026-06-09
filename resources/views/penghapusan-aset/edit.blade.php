@extends('layouts.app')
@push('title')
    Edit Pengajuan Penghapusan Aset
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-trash"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Edit Pengajuan Penghapusan Aset
                </h3>
            </div>
        </div>

        <form method="post" action="{{ route('pa.update', $data->id) }}" id="form-penghapusan">
            @csrf
            @method('PUT') {{-- Penting untuk method PUT --}}
            <div class="kt-portlet__body">
                {{-- Header Form --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="departemen" class="col-form-label">Departemen <span
                                    class="text-danger">*</span></label>
                            <select class="form-control kt-select2" name="Departemen" id="departemen" required>
                                <option value="{{ $data->Departemen }}" selected>{{ $data->getDepartemen->nama }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unit" class="col-form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-control kt-select2" id="unit" name="Unit" required>
                                <option value="{{ $data->Unit }}" selected>{{ $data->Unit }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal" class="col-form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="Tanggal" id="tanggal"
                                value="{{ $data->Tanggal }}" required>
                        </div>
                    </div>
                </div>

                <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>

                {{-- Table Aset --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label">Daftar Aset yang Akan Dihapus</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table-aset">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50%">Nama Item</th>
                                            <th width="40%">Keterangan Penghapusan</th>
                                            <th width="10%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-aset">
                                        {{-- Render baris yang sudah ada dari database --}}
                                        @foreach ($data->getDetail as $index => $detail)
                                            <tr data-row-id="{{ $index }}">
                                                <td>
                                                    <select class="form-control kt-select2 asset-select" name="AssetId[]"
                                                        required>
                                                        {{-- Opsi ini akan menjadi nilai terpilih awal di Select2 --}}
                                                        @if ($detail->getItem)
                                                            <option value="{{ $detail->AssetId }}" selected>
                                                                {{ $detail->getItem->no_inventaris ?? $detail->AssetId }} -
                                                                {{ $detail->getItem->nama ?? 'Item Tidak Ditemukan' }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $detail->AssetId }}" selected>
                                                                {{ $detail->AssetId }} - Item Tidak Ditemukan
                                                            </option>
                                                        @endif
                                                    </select>

                                                </td>
                                                <td>
                                                    <textarea class="form-control" name="Keterangan[]" rows="2" required>{{ $detail->Keterangan }}</textarea>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"
                                                        title="Hapus baris">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="add-row">
                                <i class="fa fa-plus"></i> Tambah Baris
                            </button>
                        </div>
                    </div>
                </div>

                <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>

                {{-- Catatan --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="catatan" class="col-form-label">Catatan Tambahan</label>
                            <textarea class="form-control" name="Catatan" id="catatan" rows="3">{{ $data->Catatan }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-portlet__foot">
                <button class="btn btn-primary btn-block" type="submit" id="btn-submit">
                    <i class="fa fa-save"></i> Update Pengajuan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('css')
    <style>
        #table-aset td {
            vertical-align: middle;
        }

        #table-aset .select2-container {
            width: 100% !important;
        }

        .remove-row:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
    </style>
@endpush

@push('after-js')
    <script>
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Berhasil");
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.warning(`{{ $error }}`, "Gagal");
            @endforeach
        @endif

        let rowIndex = {{ $data->getDetail->count() }}; // Mulai dari jumlah data yang sudah ada

        // Template row baru
        function createRowTemplate() {
            rowIndex++;
            return `
                <tr data-row-id="${rowIndex}">
                    <td>
                        <select class="form-control kt-select2 asset-select" name="AssetId[]" required>
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control" name="Keterangan[]" placeholder="Masukkan keterangan alasan penghapusan" rows="2" required></textarea>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row" title="Hapus baris">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Inisialisasi Select2 Asset
        function initAssetSelect2(element) {
            $(element).select2({
                placeholder: "Ketik untuk cari item...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('inventaris.get-item-penghapusan') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Validasi duplikasi
            $(element).on('select2:select', function(e) {
                const selectedId = e.params.data.id;
                let isDuplicate = false;
                $('.asset-select').not(this).each(function() {
                    if ($(this).val() == selectedId) {
                        isDuplicate = true;
                        return false;
                    }
                });

                if (isDuplicate) {
                    toastr.warning('Item ini sudah dipilih di baris lain!', 'Duplikat');
                    $(this).val(null).trigger('change');
                }
            });
        }

        function addRow() {
            $('#tbody-aset').append(createRowTemplate());
            initAssetSelect2($('#tbody-aset tr:last .asset-select'));
            updateRemoveButtons();
        }

        function removeRow() {
            $(document).on('click', '.remove-row', function() {
                if ($('#tbody-aset tr').length <= 1) {
                    toastr.warning('Minimal harus ada 1 baris!', 'Peringatan');
                    return;
                }
                if (confirm('Yakin ingin menghapus baris ini?')) {
                    $(this).closest('tr').remove();
                    updateRemoveButtons();
                }
            });
        }

        function updateRemoveButtons() {
            $('.remove-row').prop('disabled', $('#tbody-aset tr').length <= 1);
        }

        function selectDepartemen() {
            $('#departemen').select2({
                placeholder: "Ketik untuk cari departemen...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('inventaris.get-departemen-penghapusan') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        }

        function selectUnit() {
            $('#unit').select2({
                placeholder: "Ketik untuk cari unit...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('inventaris.get-unit-his') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        }

        $(document).ready(function() {
            // Inisialisasi Select2 untuk header
            selectDepartemen();
            selectUnit();

            // Inisialisasi Select2 untuk baris aset yang SUDAH ADA dari database
            $('.asset-select').each(function() {
                initAssetSelect2(this);
            });

            // Event handlers
            removeRow();
            updateRemoveButtons();

            $('#add-row').on('click', addRow);

            // Validasi submit
            $('#form-penghapusan').on('submit', function(e) {
                let emptyAsset = false;
                $('.asset-select').each(function() {
                    if (!$(this).val()) {
                        emptyAsset = true;
                        return false;
                    }
                });

                if (emptyAsset) {
                    e.preventDefault();
                    toastr.error('Semua item harus dipilih!', 'Validasi Gagal');
                    return false;
                }
                $('#btn-submit').prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Mengupdate...');
            });
        });
    </script>
@endpush
