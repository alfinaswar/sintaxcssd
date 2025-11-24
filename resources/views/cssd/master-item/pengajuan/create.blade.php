@extends('layouts.app')
@push('title')
    Pengajuan Nama Item Baru
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-plus"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Pengajuan Nama Item Baru
                </h3>
            </div>
        </div>

        <div class="kt-portlet__body">
            <form method="post" action="{{ route('pengajuan-nama-item-cssd.store') }}" id="form-pengajuan-item">
                @csrf
                <!-- Header Form -->
                <div class="row col-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="tanggal" class="col-form-label">Tanggal</label>
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <input type="date" class="form-control" name="Tanggal" id="tanggal"
                                        value="{{ old('Tanggal', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>

                <!-- Table Pengajuan Item Baru -->
                <div class="row col-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label">Daftar Item Baru</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table-item-baru">
                                    <thead>
                                        <tr>
                                            <th width="25%">Kode Instrumen Dari Katalog</th>
                                            <th width="25%">Nama Barang Di PO Ro / Katalog </th>
                                            <th width="25%">Merek</th>
                                            <th width="25%">Tipe / Kategori</th>
                                            <th width="25%">Supplier</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-item-baru">
                                        @php
                                            $oldKodeInstrumen = old('KodeInstrumen', ['']);
                                            $oldNama = old('Nama', ['']);
                                            $oldMerk = old('Merk', ['']);
                                            $oldType = old('TypeKategori', ['']);
                                            $oldSupplier = old('Supplier', ['']);
                                            $rowCount = max(
                                                count($oldKodeInstrumen),
                                                count($oldNama),
                                                count($oldMerk),
                                                count($oldType),
                                                count($oldSupplier),
                                            );
                                            $rowCount = $rowCount == 0 ? 1 : $rowCount;

                                            // Cek error KodeInstrumen yang duplikat dari backend (dari redirect->withErrors)
                                            $dupKodeInstrumenErrors = $errors->get('KodeInstrumen') ?? [];
                                        @endphp
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            @php
                                                $isDuplicate = false;
                                                if (is_array($dupKodeInstrumenErrors)) {
                                                    if (isset($dupKodeInstrumenErrors[$i])) {
                                                        $isDuplicate = true;
                                                    } elseif (
                                                        isset($dupKodeInstrumenErrors[0]) &&
                                                        is_array($dupKodeInstrumenErrors[0]) &&
                                                        isset($dupKodeInstrumenErrors[0][$i])
                                                    ) {
                                                        $isDuplicate = true;
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="text"
                                                        class="form-control{{ $isDuplicate ? ' is-invalid border-danger text-danger font-weight-bold' : '' }}"
                                                        name="KodeInstrumen[]" placeholder="Kode Instrumen" required
                                                        value="{{ old('KodeInstrumen.' . $i) }}">
                                                    @if ($isDuplicate)
                                                        <span class="invalid-feedback d-block" style="color:#dc3545;">
                                                            {{ is_array($dupKodeInstrumenErrors[$i] ?? null) ? implode(', ', $dupKodeInstrumenErrors[$i]) : $dupKodeInstrumenErrors[$i] ?? 'Kode sudah terdaftar di master item group' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="Nama[]"
                                                        placeholder="Nama Instrumen" required
                                                        value="{{ old('Nama.' . $i) }}">
                                                </td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <select class="form-control kt-select2" name="Merk[]" required
                                                            style="width: 100%;">
                                                            <option value="">-- Pilih Merek --</option>
                                                            @foreach ($masterMerek as $merek)
                                                                <option value="{{ $merek->id }}"
                                                                    @if (old('Merk.' . $i) == $merek->id) selected @endif>
                                                                    {{ $merek->Merk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <select class="form-control kt-select2" name="TypeKategori[]"
                                                            required style="width: 100%;">
                                                            <option value="">-- Pilih Tipe --</option>
                                                            @foreach ($tipe as $tp)
                                                                <option value="{{ $tp->id }}"
                                                                    @if (old('TypeKategori.' . $i) == $tp->id) selected @endif>
                                                                    {{ $tp->Tipe }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <select class="form-control kt-select2" name="Supplier[]" required
                                                            style="width: 100%;">
                                                            <option value="">-- Pilih Supplier --</option>
                                                            @foreach ($masterSupplier as $supp)
                                                                <option value="{{ $supp->id }}"
                                                                    @if (old('Supplier.' . $i) == $supp->id) selected @endif>
                                                                    {{ $supp->Nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"
                                                        @if ($rowCount == 1) disabled @endif>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success btn-sm" id="add-row">
                                    <i class="fa fa-plus"></i> Tambah Baris
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row col-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="catatan" class="col-form-label">Catatan Tambahan</label>
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <textarea class="form-control" name="Catatan" id="catatan" placeholder="Masukkan catatan tambahan jika diperlukan"
                                        rows="8">{{ old('Catatan') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary btn-block" type="submit" form="form-pengajuan-item">
                <i class="fa fa-paper-plane"></i> Ajukan Pengajuan
            </button>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet"
        type="text/css" />
    <style>
        .table td {
            vertical-align: middle;
        }

        .remove-row:disabled {
            opacity: 0.3;
        }
    </style>
@endpush

@push('after-js')
    <script src="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <script>
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Berhasil");
        @endif
        // @if ($errors->any())
        //     @foreach ($errors->all() as $error)
        //         toastr.warning(`{{ $error }}`, "Gagal");
        //     @endforeach
        // @endif
    </script>
    <script>
        // Penjelasan:
        // Select2 tidak bekerja pada baris yang baru ditambahkan karena select2 hanya diinisialisasi pada elemen yang sudah ada saat halaman pertama kali dimuat.
        // Solusi: Setelah menambah baris baru, panggil kembali $('.kt-select2').select2() pada elemen select yang baru.

        var masterMerek = @json($masterMerek);
        var masterSupplier = @json($masterSupplier);
        var tipeData = @json($tipe);

        var getSelectOptions = function(data, oldVal, key, label) {
            let html = `<option value="">-- Pilih ${label} --</option>`;
            data.forEach(function(item) {
                let selected = (item.id == oldVal) ? 'selected' : '';
                html += `<option value="${item.id}" ${selected}>${item[key]}</option>`;
            });
            return html;
        };

        var addRow = function() {
            $('#add-row').on('click', function() {
                var rowCount = $('#tbody-item-baru tr').length;
                var kodeInstrumenVal = '';
                var namaVal = '';
                var merkVal = '';
                var tipeVal = '';
                var supplierVal = '';
                var newRow =
                    `<tr>
                        <td>
                            <input type="text" class="form-control" name="KodeInstrumen[]" placeholder="Kode Instrumen" required value="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="Nama[]" placeholder="Nama Instrumen" required value="">
                        </td>
                        <td>
                            <div class="form-group mb-0">
                                <select class="form-control kt-select2" name="Merk[]" required style="width: 100%;">
                                    ${getSelectOptions(masterMerek, '', 'Merk', 'Merek')}
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group mb-0">
                                <select class="form-control kt-select2" name="TypeKategori[]" required style="width: 100%;">
                                    ${getSelectOptions(tipeData, '', 'Tipe', 'Tipe')}
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group mb-0">
                                <select class="form-control kt-select2" name="Supplier[]" required style="width: 100%;">
                                    ${getSelectOptions(masterSupplier, '', 'Nama', 'Supplier')}
                                </select>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>`;

                $('#tbody-item-baru').append(newRow);

                // Inisialisasi select2 pada select yang baru ditambahkan
                $('#tbody-item-baru tr:last .kt-select2').select2({
                    width: '100%'
                });

                updateRemoveButtons();
            });
        }

        // Fungsi untuk menghapus baris
        var removeRow = function() {
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateRemoveButtons();
            });
        }

        // Fungsi untuk update status tombol remove
        var updateRemoveButtons = function() {
            var rowCount = $('#tbody-item-baru tr').length;
            if (rowCount === 1) {
                $('.remove-row').prop('disabled', true);
            } else {
                $('.remove-row').prop('disabled', false);
            }
        }

        $(document).ready(function() {
            $('.kt-select2').select2({
                width: '100%'
            });
            addRow();
            removeRow();
            updateRemoveButtons();
        });
    </script>
@endpush
