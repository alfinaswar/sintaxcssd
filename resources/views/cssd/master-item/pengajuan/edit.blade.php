@extends('layouts.app')
@push('title')
    Edit Pengajuan Nama Item Baru
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-edit"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Edit Pengajuan Nama Item Baru
                </h3>
            </div>
        </div>
        @if($data->Status == 'N' && !empty($data->Revisi))
            <div class="card mt-4 mx-4 border-info">
                <div class="card-header bg-info text-dark font-weight-bold">
                    Catatan Revisi
                </div>
                <div class="card-body">
                    {!! $data->Revisi !!}
                </div>
            </div>
        @endif

        <div class="kt-portlet__body">
            <form method="post" action="{{ route('pengajuan-nama-item-cssd.update', $data->id) }}" id="form-pengajuan-item">
                @csrf
                @method('PUT')
                <!-- Header Form -->
                <div class="row col-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="tanggal" class="col-form-label">Tanggal</label>
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <input type="date" class="form-control" name="Tanggal" id="tanggal"
                                        value="{{ old('Tanggal', $data->Tanggal ? date('Y-m-d', strtotime($data->Tanggal)) : date('Y-m-d')) }}"
                                        required min="{{ date('Y-m-d') }}">
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
                                        @if(isset($data->getDetail) && count($data->getDetail) > 0)
                                            @foreach($data->getDetail as $key => $detail)
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" name="KodeInstrumen[]"
                                                            placeholder="Kode Instrumen" required
                                                            value="{{ old('KodeInstrumen.' . $key, $detail->KodeInstrumen) }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="Nama[]"
                                                            placeholder="Nama Item" required
                                                            value="{{ old('Nama.' . $key, $detail->NamaItem) }}">
                                                    </td>
                                                    <td>
                                                        <div class="form-group mb-0">
                                                            <select class="form-control kt-select2" name="Merk[]" required
                                                                style="width: 100%;">
                                                                <option value="">-- Pilih Merek --</option>
                                                                @foreach($masterMerek as $merek)
                                                                    <option value="{{ $merek->id }}" {{ (old('Merk.' . $key, $detail->Merk) == $merek->id) ? 'selected' : '' }}>
                                                                        {{ $merek->Merk }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group mb-0">
                                                            <select class="form-control kt-select2" name="TypeKategori[]" required
                                                                style="width: 100%;">
                                                                <option value="">-- Pilih Tipe --</option>
                                                                @foreach($tipe as $tp)
                                                                    <option value="{{ $tp->id }}" {{ (old('TypeKategori.' . $key, $detail->TypeKategori) == $tp->id) ? 'selected' : '' }}>
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
                                                                @foreach($masterSupplier as $supp)
                                                                    <option value="{{ $supp->id }}" {{ (old('Supplier.' . $key, $detail->Supplier) == $supp->id) ? 'selected' : '' }}>
                                                                        {{ $supp->Nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row" {{ $loop->first && count($data->getDetail) == 1 ? 'disabled' : '' }}>
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control" name="Nama[]"
                                                        placeholder="Nama Item" required>
                                                </td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <select class="form-control kt-select2" name="Merk[]" required
                                                            style="width: 100%;">
                                                            <option value="">-- Pilih Merek --</option>
                                                            @foreach($masterMerek as $merek)
                                                                <option value="{{ $merek->id }}">{{ $merek->Merk }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <select class="form-control kt-select2" name="Supplier[]" required
                                                            style="width: 100%;">
                                                            <option value="">-- Pilih Supplier --</option>
                                                            @foreach($masterSupplier as $supp)
                                                                <option value="{{ $supp->id }}">{{ $supp->Nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
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
                                    <textarea class="form-control" name="Catatan" id="catatan"
                                        placeholder="Masukkan catatan tambahan jika diperlukan"
                                        rows="10">{{ old('Catatan', $data->Keterangan) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary btn-block" type="submit" form="form-pengajuan-item">
                <i class="fa fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.warning(`{{ $error }}`, "Gagal");
            @endforeach
        @endif
    </script>
    <script>
                                                                                                                                // Penjelasan:
                                                                                                                                // Select2 tidak bekerja pada baris yang baru ditambahkan karena select2 hanya diinisialisasi pada elemen yang sudah ada saat halaman pertama kali dimuat.
                                                                                                                                // Solusi: Setelah menambah baris baru, panggil kembali $('.kt-select2').select2() pada elemen select yang baru.

                                                                                                                                var addRow = function () {
            $('#add-row').on('click', function () {
                var newRow =
                    `
                                                                                                                                            <tr>
                                                                                                                                                <td>
                                                                                                                                                    <input type="text" class="form-control" name="KodeInstrumen[]" placeholder="Kode Instrumen" required>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <input type="text" class="form-control" name="Nama[]" placeholder="Nama Item" required>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <div class="form-group mb-0">
                                                                                                                                                        <select class="form-control kt-select2" name="Merk[]" required style="width: 100%;">
                                                                                                                                                            <option value="">-- Pilih Merek --</option>
                                                                                                                                                            @foreach($masterMerek as $merek)
                                                                                                                                                                <option value="{{ $merek->id }}">{{ $merek->Merk }}</option>
                                                                                                                                                            @endforeach
                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <div class="form-group mb-0">
                                                                                                                                                        <select class="form-control kt-select2" name="TypeKategori[]" required style="width: 100%;">
                                                                                                                                                            <option value="">-- Pilih Merek --</option>
                                                                                                                                                            @foreach($tipe as $tp)
                                                                                                                                                                <option value="{{ $tp->id }}">{{ $tp->Tipe }}</option>
                                                                                                                                                            @endforeach
                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <div class="form-group mb-0">
                                                                                                                                                        <select class="form-control kt-select2" name="Supplier[]" required style="width: 100%;">
                                                                                                                                                            <option value="">-- Pilih Supplier --</option>
                                                                                                                                                            @foreach($masterSupplier as $supp)
                                                                                                                                                                <option value="{{ $supp->id }}">{{ $supp->Nama }}</option>
                                                                                                                                                            @endforeach
                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                                </td>
                                                                                                                                                <td>
                                                                                                                                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                                                                                                                                        <i class="fa fa-trash"></i>
                                                                                                                                                    </button>
                                                                                                                                                </td>
                                                                                                                                            </tr>
                                                                                                                                            `;

                $('#tbody-item-baru').append(newRow);

                // Inisialisasi select2 pada select yang baru ditambahkan
                $('#tbody-item-baru tr:last .kt-select2').select2({
                    width: '100%'
                });

                updateRemoveButtons();
            });
        }

        // Fungsi untuk menghapus baris
        var removeRow = function () {
            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
                updateRemoveButtons();
            });
        }

        // Fungsi untuk update status tombol remove
        var updateRemoveButtons = function () {
            var rowCount = $('#tbody-item-baru tr').length;
            if (rowCount === 1) {
                $('.remove-row').prop('disabled', true);
            } else {
                $('.remove-row').prop('disabled', false);
            }
        }

        $(document).ready(function () {
            // Inisialisasi select2 pada select yang sudah ada
            $('.kt-select2').select2({
                width: '100%'
            });
            addRow();
            removeRow();
            updateRemoveButtons();
        });
    </script>
@endpush