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
                                        value="{{ date('Y-m-d') }}" required min="{{ date('Y-m-d') }}">
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
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="Nama[]"
                                                    placeholder="Nama Item" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="Merek[]" placeholder="Merek"
                                                    required>
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
                                        placeholder="Masukkan catatan tambahan jika diperlukan" rows="3"></textarea>
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
                                    // Fungsi untuk menambah baris baru
                                    var addRow = function () {
            $('#add-row').on('click', function () {
                var newRow =
                    `
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" name="Nama[]" placeholder="Nama Item" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="Merek[]" placeholder="Merek" required>
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
            addRow();
            removeRow();
            updateRemoveButtons();
        });
    </script>
@endpush