@extends('layouts.app')
@push('title')
    Edit Pengajuan Nama Item Baru
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg position-relative">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-edit"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Edit Pengajuan Nama Item Baru
                </h3>
            </div>
            {{-- Ribbon Status Pengajuan --}}
            @php
                $status = $data->Status;
                $statusText = 'Proses Pengajuan';
                $statusClass = 'bg-secondary text-white';
                if ($status == 'Y') {
                    $statusText = 'Disetujui';
                    $statusClass = 'bg-success text-white';
                } elseif ($status == 'N') {
                    $statusText = 'Ditolak';
                    $statusClass = 'bg-danger text-white';
                }
            @endphp
            <div style="position: absolute; top: 20px; right: -30px; z-index: 10; transform: rotate(45deg); width: 180px; text-align: center;">
                <span class="d-block py-2 {{ $statusClass }}" style="font-weight: bold; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    {{ $statusText }}
                </span>
            </div>
        </div>

        <div class="kt-portlet__body">

            @if ($data->Status == 'N' && $data->Revisi != null)
                <div class="card mb-4 border-info">
                    <div class="card-header bg-info text-white">
                        <strong>Catatan yang Perlu Diperhatikan</strong>
                    </div>
                    <div class="card-body">
                        {!! $data->Revisi !!}
                    </div>
                </div>
            @endif

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
                                        value="{{ old('Tanggal', $data->Tanggal ?? date('Y-m-d')) }}" required
                                        min="{{ date('Y-m-d') }}">
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
                                            <th width="35%">Nama</th>
                                            <th width="25%">Merek</th>
                                            <th width="30%">Keterangan</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-item-baru">
                                        @if(isset($data->getDetail) && count($data->getDetail) > 0)
                                            @foreach($data->getDetail as $key => $detail)
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" name="Nama[]"
                                                            placeholder="Nama Item" required
                                                            value="{{ old('Nama.' . $key, $detail->NamaItem) }}">
                                                    </td>
                                                    <td>
                                                        <select class="form-control kt-select2" name="Merk[]" required
                                                            style="width: 100%;">
                                                            <option value="">-- Pilih Merek --</option>
                                                            @foreach($masterMerek as $merek)
                                                                <option value="{{ $merek->id }}" {{ $detail->Merk == $merek->id ? 'selected' : '' }}>{{ $merek->Merk }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="Keterangan[]" placeholder="Keterangan"
                                                            rows="2">{{ old('Keterangan.' . $key, $detail->Keterangan) }}</textarea>
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
                                                    <select class="form-control kt-select2" name="Merk[]" required
                                                        style="width: 100%;">
                                                        <option value="">-- Pilih Merek --</option>
                                                        @foreach($masterMerek as $merek)
                                                            <option value="{{ $merek->id }}">{{ $merek->Merk }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="form-control" name="Keterangan[]" placeholder="Keterangan"
                                                        rows="2"></textarea>
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
                                        rows="3">{{ old('Catatan', $data->Keterangan) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary btn-block" type="submit" form="form-pengajuan-item">
                <i class="fa fa-paper-plane"></i> Simpan Perubahan
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
                                                                                                                <input type="text" class="form-control" name="Nama[]" placeholder="Nama Item" required>
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                <select class="form-control kt-select2" name="Merk[]" required style="width: 100%;">
                                                                                                                    <option value="">-- Pilih Merek --</option>
                                                                                                                    @foreach($masterMerek as $merek)
                                                                                                                        <option value="{{ $merek->id }}">{{ $merek->Merk }}</option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                <textarea class="form-control" name="Keterangan[]" placeholder="Keterangan" rows="2"></textarea>
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
