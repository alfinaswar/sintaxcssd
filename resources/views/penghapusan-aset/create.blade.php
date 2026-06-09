@extends('layouts.app')
@push('title')
    Pengajuan Penghapusan Aset
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-trash"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Pengajuan Penghapusan Aset
                </h3>
            </div>
        </div>

        <form method="post" action="{{ route('pa.store') }}" id="form-penghapusan">
            @csrf
            <div class="kt-portlet__body">
                {{-- Header Form --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="departemen" class="col-form-label">Departemen <span
                                    class="text-danger">*</span></label>
                            <select class="form-control kt-select2" name="Departemen" id="departemen" required>
                                <option value="">Pilih Departemen</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unit" class="col-form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-control kt-select2" id="unit" name="Unit" required>
                                <option value="">Pilih Unit</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal" class="col-form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="Tanggal" id="tanggal"
                                value="{{ date('Y-m-d') }}" required>
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
                                            <th width="34%">Nama Item</th>
                                            <th width="25%">Metode Penghapusan</th>
                                            <th width="31%">Keterangan Penghapusan</th>
                                            <th width="10%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-aset">
                                        {{-- Row pertama akan di-generate via JS --}}
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
                            <textarea class="form-control" name="Catatan" id="catatan" placeholder="Masukkan catatan tambahan jika diperlukan"
                                rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="kt-portlet__foot">
                <button class="btn btn-primary btn-block" type="submit" id="btn-submit">
                    <i class="fa fa-paper-plane"></i> Ajukan Penghapusan
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
        // Notifikasi
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Berhasil");
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.warning(`{{ $error }}`, "Gagal");
            @endforeach
        @endif

        let rowIndex = 0; // Counter untuk unique ID select2

        // Generate template row baru
        function createRowTemplate() {
            rowIndex++;
            const uniqueId = 'asset_' + rowIndex;
            const uniqueMetodeId = 'metode_' + rowIndex;
            return `
                <tr data-row-id="${rowIndex}">
                    <td>
                        <select class="form-control kt-select2 asset-select" name="AssetId[]"
                            id="${uniqueId}" data-placeholder="Ketik untuk cari item..." required>
                            <option value=""></option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control metode-penghapusan-select" name="MetodePenghapusan[]" id="${uniqueMetodeId}" required>
                            <option value="">Pilih Metode</option>
                            <option value="dimusnahkan">Dimusnahkan</option>
                            <option value="dijual">Dijual</option>
                            <option value="dihibahkan">Dihibahkan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control" name="Keterangan[]"
                            placeholder="Masukkan keterangan alasan penghapusan" rows="2" required></textarea>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row" title="Hapus baris">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Inisialisasi Select2 untuk asset dengan AJAX
        function initAssetSelect2(element) {
            $(element).select2({
                placeholder: "Ketik untuk cari item...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('inventaris.get-item-penghapusan') }}', // ✅ Route baru
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data // Data sudah dalam format yang benar
                        };
                    },
                    cache: true
                }
            });

            // Validasi: cegah pilih item yang sudah ada di baris lain
            $(element).on('select2:select', function(e) {
                const selectedId = e.params.data.id;
                const currentRow = $(this).closest('tr');

                // Cek duplikasi di baris lain
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

        // Tambah baris baru
        function addRow() {
            const newRow = createRowTemplate();
            $('#tbody-aset').append(newRow);

            // Inisialisasi select2 di baris baru
            const newSelect = $('#tbody-aset tr:last .asset-select');
            initAssetSelect2(newSelect);
            // Optional: bisa tambahkan select2 untuk metode jika ingin select2, tapi select native saja cukup

            updateRemoveButtons();
        }

        // Hapus baris
        function removeRow() {
            $(document).on('click', '.remove-row', function() {
                const rowCount = $('#tbody-aset tr').length;
                if (rowCount <= 1) {
                    toastr.warning('Minimal harus ada 1 baris!', 'Peringatan');
                    return;
                }

                if (confirm('Yakin ingin menghapus baris ini?')) {
                    $(this).closest('tr').remove();
                    updateRemoveButtons();
                }
            });
        }

        // Update status tombol remove
        function updateRemoveButtons() {
            const rowCount = $('#tbody-aset tr').length;
            $('.remove-row').prop('disabled', rowCount <= 1);
        }

        // Select2 Departemen (AJAX)
        function selectDepartemen() {
            $('#departemen').select2({
                placeholder: "Ketik untuk cari departemen...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('inventaris.get-departemen-penghapusan') }}', // ✅ Route baru
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
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

        // Select2 Unit (AJAX)
        function selectUnit() {
            $('#unit').select2({
                placeholder: "Ketik untuk cari unit...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('inventaris.get-unit-his') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item,
                                    id: item
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        // Validasi sebelum submit
        function validateForm() {
            $('#form-penghapusan').on('submit', function(e) {
                let isValid = true;
                let errorMsg = '';

                // Cek apakah ada AssetId yang kosong
                let emptyAsset = false;
                $('.asset-select').each(function() {
                    if (!$(this).val()) {
                        emptyAsset = true;
                        return false;
                    }
                });

                if (emptyAsset) {
                    errorMsg = 'Semua item harus dipilih!';
                    isValid = false;
                }

                // Cek apakah ada Metode Penghapusan yang kosong
                let emptyMetode = false;
                $('.metode-penghapusan-select').each(function() {
                    if (!$(this).val()) {
                        emptyMetode = true;
                        return false;
                    }
                });

                if (emptyMetode) {
                    errorMsg = 'Setiap aset harus memiliki metode penghapusan!';
                    isValid = false;
                }

                // Cek duplikasi (double check)
                const selectedItems = [];
                let hasDuplicate = false;
                $('.asset-select').each(function() {
                    const val = $(this).val();
                    if (selectedItems.includes(val)) {
                        hasDuplicate = true;
                        return false;
                    }
                    selectedItems.push(val);
                });

                if (hasDuplicate) {
                    errorMsg = 'Terdapat item yang dipilih lebih dari sekali!';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    toastr.error(errorMsg, 'Validasi Gagal');
                    return false;
                }

                // Loading state
                $('#btn-submit').prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Mengirim...'
                );
            });
        }

        // Init semua saat DOM ready
        $(document).ready(function() {
            // Init select2 header
            selectDepartemen();
            selectUnit();

            // Tambah baris pertama
            addRow();

            // Init event handlers
            removeRow();
            validateForm();

            // Event add row
            $('#add-row').on('click', function() {
                addRow();
            });
        });
    </script>
@endpush
