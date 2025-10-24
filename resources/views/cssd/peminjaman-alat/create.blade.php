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
                                        @foreach ($NamaAlat ?? [] as $alat)
                                            <option value="{{ $alat->id }}" data-merk="{{ $alat->getNama->Merk ?? '' }}"
                                                data-tipe="{{ $alat->getNama->Tipe ?? '' }}">
                                                {{ $alat->getNama->Nama ?? '' }} - {{$alat->getNama->Kode}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="Merk[]" readonly placeholder="Merk">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="Tipe[]" readonly placeholder="Tipe">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="Jumlah[]" min="1" value="1" required
                                        placeholder="Jumlah" readonly>
                                </td>
                                <td>
                                    <select class="form-control" name="KondisiAlat[]" required>
                                        <option value="">Pilih Kondisi</option>
                                        <option value="B">Baik</option>
                                        <option value="KB">Kurang Baik</option>
                                        <option value="R">Rusak</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="Keterangan[]" placeholder="Keterangan">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-square btn-remove-row"
                                        title="Hapus Baris"
                                        style="border-radius:0; padding:0.375rem 0.75rem; min-width:36px; min-height:36px;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success btn-sm" id="add-row-alat"><i class="fa fa-plus"></i> Tambah
                        Alat</button>



                </div>
                <div class="form-group row">
                    <label for="Keterangan" class="col-form-label">Keterangan</label>
                    <div class="col-12">
                        <textarea class="form-control {{ $errors->has('Keterangan') ? 'is-invalid' : '' }}"
                            name="Keterangan" id="Keterangan" rows="2"
                            placeholder="Masukkan keterangan tambahan">{{ old('Keterangan') }}</textarea>
                        @if ($errors->has('Keterangan'))
                            <div class="invalid-feedback">
                                {{ $errors->first('Keterangan') }}
                            </div>
                        @endif
                    </div>
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
@push('after-js')
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
    </script>

    <script>
        function initSelect2() {
            $('.select2').select2({
                width: '100%',
                dropdownParent: $('#alat-detail-table').parent()
            });
        }

        function setAlatChangeHandler() {
            $('#alat-detail-table').on('change', '.select2', function () {
                var selected = $(this).find('option:selected');
                var merk = selected.data('merk') || '';
                var tipe = selected.data('tipe') || '';
                var $row = $(this).closest('tr');
                $row.find('input[name="Merk[]"]').val(merk);
                $row.find('input[name="Tipe[]"]').val(tipe);
            });
        }

        // Tambah baris baru
        function addRowAlat() {
            var $table = $('#alat-detail-table tbody');
            var rowCount = $table.find('tr').length;
            var alatOptions = `
                                                                                                                                                                    <option value="">Pilih Alat</option>
                                                                                                                                                                    @foreach ($NamaAlat ?? [] as $alat)
                                                                                                                                                                        <option value="{{ $alat->id }}" data-merk="{{ $alat->getNama->Merk ?? '' }}" data-tipe="{{ $alat->getNama->Tipe ?? '' }}">
                                                                                                                                                                            {{ $alat->getNama->Nama ?? '' }} - {{$alat->getNama->Kode}}
                                                                                                                                                                        </option>
                                                                                                                                                                    @endforeach
                                                                                                                                                                `;
            var newRow = `
                                                                                                                                                                    <tr>
                                                                                                                                                                        <td>
                                                                                                                                                                            <select class="form-control select2" name="alat[${rowCount}][IdAlat]" required>
                                                                                                                                                                                ${alatOptions}
                                                                                                                                                                            </select>
                                                                                                                                                                        </td>
                                                                                                                                                                        <td>
                                                                                                                                                                            <input type="text" class="form-control" name="Merk[]" readonly placeholder="Merk">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td>
                                                                                                                                                                            <input type="text" class="form-control" name="Tipe[]" readonly placeholder="Tipe">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td>
                                                                                                                                                                            <input type="number" class="form-control" name="Jumlah[]" min="1" value="1" required placeholder="Jumlah" readonly>
                                                                                                                                                                        </td>
                                                                                                                                                                        <td>
                                                                                                                                                                            <select class="form-control" name="KondisiAlat[]" required>
                                                                                                                                                                                <option value="">Pilih Kondisi</option>
                                                                                                                                                                                <option value="B">Baik</option>
                                                                                                                                                                                <option value="KB">Kurang Baik</option>
                                                                                                                                                                                <option value="R">Rusak</option>
                                                                                                                                                                            </select>
                                                                                                                                                                        </td>
                                                                                                                                                                        <td>
                                                                                                                                                                            <input type="text" class="form-control" name="Keterangan[]" placeholder="Keterangan">
                                                                                                                                                                        </td>
                                                                                                                                                                        <td class="text-center">
                                                                                                                                                                            <button type="button" class="btn btn-danger btn-sm btn-remove-row" title="Hapus Baris"><i class="fa fa-trash"></i></button>
                                                                                                                                                                        </td>
                                                                                                                                                                    </tr>
                                                                                                                                                                `;
            $table.append(newRow);
            initSelect2();
        }

        // Hapus baris
        function removeRowAlat() {
            $('#alat-detail-table').on('click', '.btn-remove-row', function () {
                var $row = $(this).closest('tr');
                if ($('#alat-detail-table tbody tr').length > 1) {
                    $row.remove();
                }
            });
        }

        $(document).ready(function () {
            initSelect2();
            setAlatChangeHandler();
            removeRowAlat();

            $('#add-row-alat').on('click', function () {
                addRowAlat();
            });
            $('.Ruangan').select2({
                placeholder: "Pilih Ruangan",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('pinjam.get-unit-his') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (text, id) {
                                return { id: id, text: text };
                            })
                        };
                    },
                    cache: true
                }
            });
            $('#RuanganPeminjam').on('select2:select', function (e) {
                $('#KodeRuanganPeminjam').val(e.params.data.id);
            });

            $('#RuanganPenerima').on('select2:select', function (e) {
                $('#KodeRuanganPenerima').val(e.params.data.id);
            });
        });

    </script>
@endpush
