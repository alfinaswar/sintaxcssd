@extends('layouts.app')
@push('title')
    Edit Pengajuan Penghapusan Aset
@endpush
{{-- @php
   dd($data)
@endphp --}}
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
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">

                    </div>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <form method="post" action="{{ route('pa.update', $data->id) }}" id="form-penghapusan">
                @csrf
                @method('PUT')
                <!-- Header Form -->
                <div class="row col-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="departemen" class="col-form-label">Departemen</label>
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <select class="form-control kt-select2" name="Departemen" id="departemen" required>
                                        <option value="">Pilih Departemen</option>
                                        @foreach ($departemen as $dd)
                                            <option value="{{ $dd->id }}" {{ $data->Departemen == $dd->id ? 'selected' : '' }}>{{ $dd->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unit" class="col-form-label">Unit</label>
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <select class="form-control kt-select2" id="unit" name="Unit">
                                        @if($data->Unit)
                                            <option value="{{ $data->Unit }}" selected>{{ $data->Unit }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tanggal" class="col-form-label">Tanggal</label>
                            <div class="form-group row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <input type="date" class="form-control" name="Tanggal" id="tanggal"
                                        value="{{ $data->Tanggal ? $data->Tanggal : date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>

                <!-- Table Aset -->
                <div class="row col-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-form-label">Daftar Aset yang Akan Dihapus</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table-aset">
                                    <thead>
                                        <tr>
                                            <th width="50%">Nama Item</th>
                                            <th width="40%">Keterangan</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-aset">

                                            @forelse($data->getDetail as $key => $detail)
                                                <tr>
                                                    <td>
                                                        <select class="form-control kt-select2" name="AssetId[]" required>
                                                            <option value="">Pilih Item</option>
                                                            @foreach ($item as $it)
                                                                <option value="{{ $it->id }}" {{ $detail->AssetId == $it->id ? 'selected' : '' }}>
                                                                    {{ $it->kode_item }} - {{ $it->nama }} - {{ $it->no_inventaris }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="Keterangan[]" placeholder="Masukkan keterangan alasan penghapusan" rows="2" required>{{ $detail->Keterangan }}</textarea>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-row" {{ $loop->first && count($data->getDetail) == 1 ? 'disabled' : '' }}>
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                            <tr>
                                                <td>
                                                    <select class="form-control kt-select2" name="AssetId[]" id="kt_select2_1"
                                                        required>
                                                        <option value="">Pilih Item</option>
                                                        @foreach ($item as $it)
                                                            <option value="{{ $it->id }}">{{ $it->kode_item }} -
                                                                {{ $it->nama }} - {{ $it->no_inventaris }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="form-control" name="Keterangan[]"
                                                        placeholder="Masukkan keterangan alasan penghapusan" rows="2"
                                                        required></textarea>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforelse
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
                                        placeholder="Masukkan catatan tambahan jika diperlukan" rows="3">{{ $data->Catatan ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary btn-block" type="submit" form="form-penghapusan">
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
        var addRow = function () {
            $('#add-row').on('click', function () {
                var newRow =
                    `
                    <tr>
                        <td>
                            <select class="form-control kt-select2" name="AssetId[]" required>
                                <option value="">Pilih Item</option>
                                @foreach ($item as $it)
                                    <option value="{{ $it->id }}">{{ $it->kode_item }} - {{ $it->nama }} - {{ $it->no_inventaris }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control" name="Keterangan[]" placeholder="Masukkan keterangan alasan penghapusan" rows="2" required></textarea>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;

                $('#tbody-aset').append(newRow);
                $('#tbody-aset').find('.kt-select2').select2();
                updateRemoveButtons();
            });
        }

        // Function untuk menghapus baris
        var removeRow = function () {
            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
                updateRemoveButtons();
            });
        }

        // Function untuk update status tombol remove
        var updateRemoveButtons = function () {
            var rowCount = $('#tbody-aset tr').length;
            if (rowCount === 1) {
                $('.remove-row').prop('disabled', true);
            } else {
                $('.remove-row').prop('disabled', false);
            }
        }
        var select_unit = function () {
            $('#unit').select2({
                placeholder: "Select Data",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('inventaris.get-unit-his') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item,
                                    id: item
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        var select_departemen = function () {
            $('#departemen').select2({
                placeholder: "Select departemen",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("master.get-departemen") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.nama
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        $(document).ready(function () {
            $('#kt_select2_1').select2();
        });
        $(document).ready(function () {
            $('.kt-select2').select2();
        });
        jQuery(document).ready(function () {

            $('.progress').hide();
            select_unit();
            addRow();
            removeRow();
            updateRemoveButtons();
            select_unit();
            select_departemen();
        });
    </script>
@endpush
