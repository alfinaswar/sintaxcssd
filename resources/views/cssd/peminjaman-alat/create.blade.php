@extends('layouts.app')
@push('title')
    Peminjaman Alat CSSD
@endpush
@push('sub-title')
    Tambah Peminjaman Alat
@endpush

@section('content')
    <style>
        .readonly-select {
            pointer-events: none;
            background-color: #e9ecef;
            color: #495057;
        }

        .table-detail th,
        .table-detail td {
            vertical-align: middle !important;
        }
    </style>
    @push('js')
        <script>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error(`{{ $error }}`, "Gagal");
                @endforeach
            @endif
        </script>
    @endpush
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Peminjaman Alat CSSD
                </h3>
            </div>
        </div>
        <form class="kt-form kt-form--label-right" id="simpanForm" action="{{ route('pinjam.store') }}" method="POST"
            accept-charset="utf-8">
            @csrf
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="TanggalPinjam" class="col-4 col-form-label">* Tanggal Pinjam</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('TanggalPinjam') ? 'is-invalid' : '' }}"
                                    name="TanggalPinjam" value="{{ old('TanggalPinjam', date('Y-m-d')) }}" type="date"
                                    id="TanggalPinjam" required placeholder="Pilih tanggal pinjam">
                                @if ($errors->has('TanggalPinjam'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('TanggalPinjam') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="NamaPeminjam" class="col-4 col-form-label">* Nama Peminjam</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('NamaPeminjam') ? 'is-invalid' : '' }}"
                                    name="NamaPeminjam" value="{{ old('NamaPeminjam') }}" type="text" id="NamaPeminjam"
                                    required placeholder="Masukkan nama peminjam">
                                @if ($errors->has('NamaPeminjam'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('NamaPeminjam') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="RuanganPeminjam" class="col-4 col-form-label">* Ruangan Peminjam</label>
                            <div class="col-8">
                                <select class="form-control kt-select2 Ruangan" id="RuanganPeminjam" name="RuanganPeminjam">
                                </select>
                                @if ($errors->has('RuanganPeminjam'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('RuanganPeminjam') }}
                                    </div>
                                @endif
                            </div>
                            <input type="hidden" id="KodeRuanganPeminjam" name="KodeRuanganPeminjam">
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- <div class="form-group row">
                            <label for="TanggalKembali" class="col-4 col-form-label">* Tanggal Kembali</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('TanggalKembali') ? 'is-invalid' : '' }}"
                                    name="TanggalKembali" value="{{ old('TanggalKembali') }}" type="date"
                                    id="TanggalKembali" required placeholder="Pilih tanggal kembali">
                                @if ($errors->has('TanggalKembali'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('TanggalKembali') }}
                                </div>
                                @endif
                            </div>
                        </div> --}}
                        <div class="form-group row">
                            <label for="NamaPenerima" class="col-4 col-form-label">* Nama Penerima</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('NamaPenerima') ? 'is-invalid' : '' }}"
                                    name="NamaPenerima" value="{{ old('NamaPenerima') }}" type="text" id="NamaPenerima"
                                    required placeholder="Masukkan nama penerima">
                                @if ($errors->has('NamaPenerima'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('NamaPenerima') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="RuanganPenerima" class="col-4 col-form-label">* Ruangan Penerima</label>
                            <div class="col-8">
                                <select class="form-control kt-select2 Ruangan" id="RuanganPenerima" name="RuanganPenerima">
                                </select>
                                @if ($errors->has('RuanganPenerima'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('RuanganPenerima') }}
                                    </div>
                                @endif
                            </div>
                            <input type="hidden" id="KodeRuanganPenerima" name="KodeRuanganPenerima">
                        </div>
                    </div>
                </div>
                <hr>
                <h5>Detail Alat Dipinjam</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-detail" id="alat-detail-table">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 20%;">* Nama Alat</th>
                                <th style="width: 15%;">Merk</th>
                                <th style="width: 15%;">Tipe</th>
                                <th style="width: 10%;">* Jumlah</th>
                                <th style="width: 15%;">* Kondisi Alat</th>
                                <th style="width: 20%;">Keterangan</th>
                                <th style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control select2" name="alat[0][IdAlat]" required>
                                        <option value="">Pilih Alat</option>
                                        @foreach ($alatList ?? [] as $alat)
                                            <option value="{{ $alat->id }}" data-merk="{{ $alat->merk ?? '' }}"
                                                data-tipe="{{ $alat->tipe ?? '' }}">
                                                {{ $alat->nama ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="alat[0][Merk]" readonly
                                        placeholder="Merk">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="alat[0][Tipe]" readonly
                                        placeholder="Tipe">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="alat[0][Jumlah]" min="1" value="1"
                                        required placeholder="Jumlah">
                                </td>
                                <td>
                                    <select class="form-control" name="alat[0][KondisiAlat]" required>
                                        <option value="">Pilih Kondisi</option>
                                        <option value="B">Baik</option>
                                        <option value="KB">Kurang Baik</option>
                                        <option value="R">Rusak</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="alat[0][Keterangan]"
                                        placeholder="Keterangan">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-row"
                                        title="Hapus Baris"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success btn-sm" id="add-row-alat"><i class="fa fa-plus"></i> Tambah
                        Alat</button>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                    <a href="{{ route('pinjam.index') }}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
    <script>
        function simpan(e, id) {
            e.preventDefault();
            KTApp.block('.kt-portlet', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Please wait...'
            });
            $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#simpanForm").submit();
        }

        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Pilih Data",
                allowClear: true
            });

            // Dynamic add/remove row for alat detail
            let alatIndex = 1;
            $('#add-row-alat').on('click', function () {
                let row = $('#alat-detail-table tbody tr:first').clone();
                row.find('select, input').each(function () {
                    let name = $(this).attr('name');
                    if (name) {
                        name = name.replace(/\[\d+\]/, '[' + alatIndex + ']');
                        $(this).attr('name', name);
                    }
                    if ($(this).is('select')) {
                        $(this).val('').trigger('change');
                    } else {
                        $(this).val('');
                    }
                });
                row.find('input[type=number]').val(1);
                // Tambahkan placeholder pada input baru
                row.find('input[name$="[Merk]"]').attr('placeholder', 'Merk');
                row.find('input[name$="[Tipe]"]').attr('placeholder', 'Tipe');
                row.find('input[name$="[Jumlah]"]').attr('placeholder', 'Jumlah');
                row.find('input[name$="[Keterangan]"]').attr('placeholder', 'Keterangan');
                $('#alat-detail-table tbody').append(row);
                alatIndex++;
            });

            // Remove row
            $(document).on('click', '.btn-remove-row', function () {
                if ($('#alat-detail-table tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    // clear values if only one row left
                    $(this).closest('tr').find('select, input').val('');
                    $(this).closest('tr').find('input[type=number]').val(1);
                }
            });

            // Auto fill Merk & Tipe when alat selected
            $(document).on('change', 'select[name^="alat"][name$="[IdAlat]"]', function () {
                let $row = $(this).closest('tr');
                let merk = $(this).find('option:selected').data('merk') || '';
                let tipe = $(this).find('option:selected').data('tipe') || '';
                $row.find('input[name$="[Merk]"]').val(merk);
                $row.find('input[name$="[Tipe]"]').val(tipe);
            });
            var select_unit = function () {
                $('.Ruangan').select2({
                    placeholder: "Select Data",
                    minimumInputLength: 1,
                    ajax: {
                        url: '{{ route('pinjam.get-unit-his') }}',
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (text, id) {
                                    return {
                                        text: text,
                                        id: id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }
            select_unit()
            // Untuk Ruangan Penerima
            $('#RuanganPenerima').on('select2:select', function (e) {
                let data = e.params.data;
                $('#KodeRuanganPenerima').val(data.id);
            });

            // Untuk Ruangan Peminjam
            $('#RuanganPeminjam').on('select2:select', function (e) {
                let data = e.params.data;
                $('#KodeRuanganPeminjam').val(data.id);
            });
        });
    </script>
@endpush
