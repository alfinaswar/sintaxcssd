@extends('layouts.app')
@push('title')
    Master CSSD
@endpush
@push('sub-title')
    Tambah CSSD
@endpush

@section('content')
    <style>
        .readonly-select {
            pointer-events: none;
            /* Tidak bisa diklik atau dipilih */
            background-color: #e9ecef;
            /* Warna abu seperti input readonly */
            color: #495057;
            /* Warna teks */
        }
    </style>
    <style>
        #drop-area.dragover {
            border-color: #28a745;
            background: #e9f7ef;
        }

        #preview img {
            max-width: 100%;
            max-height: 120px;
            border-radius: 6px;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
                    Master CSSD
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="simpanFrom"
            action="{{ route('master-cssd.cssd-master-item.store') }}" method="POST" accept-charset="utf-8"
            enctype="multipart/form-data">
            @csrf
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama" class="col-2 col-form-label">Kode Grafir</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('KodeGrafir') ? 'is-invalid' : '' }}"
                                    name="KodeGrafir" value="{{ old('KodeGrafir') }}" placeholder="Kode Grafir" type="text"
                                    id="serial_number">
                                <small class="form-text text-danger">Hanya diisi jika sudah terlanjur tergrafir.</small>
                                @if ($errors->has('Nama'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Nama') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-2 col-form-label">* Nama</label>
                            <div class="col-8">
                                <select class="form-control select2 {{ $errors->has('Nama') ? 'is-invalid' : '' }}"
                                    name="Nama" id="Nama">
                                    <option value="">Pilih Nama</option>
                                    @foreach ($masteritem as $NamaItem)
                                        <option value="{{ $NamaItem->id ?? '' }}" {{ old('Nama') == ($NamaItem->id ?? '') ? 'selected' : '' }}
                                            data-merk="{{ $NamaItem->getMerkGroup->Merk ?? '' }}">
                                            {{ $NamaItem->Nama ?? '' }} - {{ $NamaItem->Kode ?? '' }} -
                                            {{ $NamaItem->getMerkGroup->Merk ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('Nama'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Nama') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label for="serial_number" class="col-2 col-form-label">* Serial Number</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('SerialNumber') ? 'is-invalid' : '' }}"
                                    name="SerialNumber" value="{{ old('SerialNumber') }}" placeholder="Serial Number"
                                    type="text" id="serial_number">
                                @if ($errors->has('SerialNumber'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('SerialNumber') }}
                                </div>
                                @endif
                            </div>
                        </div> --}}

                        <div class="form-group row">
                            <label for="merk" class="col-2 col-form-label">* Merk</label>
                            <div class="col-8">
                                <select class="form-control" name="Merk" id="merk"
                                    style="pointer-events: none; background-color: #e9ecef; color: #495057;">
                                    <option value="">Pilih Merk</option>
                                    @foreach ($merks as $merkItem)
                                        <option value="{{ $merkItem->id }}" {{ old('Merk') == $merkItem->id ? 'selected' : '' }}>
                                            {{ $merkItem->Merk }}
                                        </option>
                                    @endforeach
                                    <option value="MerkBaru" {{ old('Merk') == 'MerkBaru' ? 'selected' : '' }}>Merk Baru
                                    </option>
                                </select>
                                @if ($errors->has('Merk'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Merk') }}
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="merk" class="col-2 col-form-label">* Tipe</label>
                            <div class="col-8">
                                <select class="form-control select2 {{ $errors->has('Tipe') ? 'is-invalid' : '' }}"
                                    name="Tipe" id="Tipe">
                                    <option value="">Pilih Tipe</option>
                                    @foreach ($tipe as $t)
                                        <option value="{{ $t->id }}" {{ old('Tipe') == $t->id ? 'selected' : '' }}>
                                            {{ $t->Tipe }}
                                        </option>
                                    @endforeach
                                    <option value="TipeBaru" {{ old('Tipe') == 'TipeBaru' ? 'selected' : '' }}>Tipe Baru
                                    </option>
                                </select>
                                @if ($errors->has('Tipe'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Tipe') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row" id="tipe_baru_group" style="display: none;">
                            <label for="tipe_baru" class="col-2 col-form-label">* Tipe Baru</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('tipe_baru') ? 'is-invalid' : '' }}"
                                    name="tipe_baru" value="{{ old('tipe_baru') }}" placeholder="Nama Tipe Baru" type="text"
                                    id="tipe_baru">
                                @if ($errors->has('tipe_baru'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('tipe_baru') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qty" class="col-2 col-form-label">* Qty</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('Qty') ? 'is-invalid' : '' }}" name="Qty"
                                    value="{{ old('Qty', '1') }}" placeholder="Qty" type="number" id="qty">
                                @if ($errors->has('Qty'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Qty') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group row">
                            <label for="tahun_perolehan" class="col-2 col-form-label">* Tahun Perolehan</label>
                            <div class="col-8">
                                <select
                                    class="form-control select2 {{ $errors->has('TahunPerolehan') ? 'is-invalid' : '' }}"
                                    name="TahunPerolehan" id="tahun_perolehan" required>
                                    <option value="">Pilih Tahun</option>
                                    @for ($year = 2025; $year >= 2010; $year--)
                                        <option value="{{ $year }}" {{ old('TahunPerolehan') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @if ($errors->has('TahunPerolehan'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('TahunPerolehan') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="kondisi_barang" class="col-2 col-form-label">* Kondisi Barang</label>
                            <div class="col-8">
                                <select class="form-control {{ $errors->has('KondisiBarang') ? 'is-invalid' : '' }}"
                                    name="KondisiBarang" id="kondisi_barang" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="B" {{ old('KondisiBarang') == 'B' ? 'selected' : '' }}>B (Baik)
                                    </option>
                                    <option value="KB" {{ old('KondisiBarang') == 'KB' ? 'selected' : '' }}>KB (Kurang
                                        Baik)
                                    </option>
                                    <option value="R" {{ old('KondisiBarang') == 'R' ? 'selected' : '' }}>R (Rusak)
                                    </option>
                                </select>
                                @if ($errors->has('KondisiBarang'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('KondisiBarang') }}
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="merk" class="col-2 col-form-label">* Satuan</label>
                            <div class="col-8">
                                <select class="form-control select2 {{ $errors->has('Satuan') ? 'is-invalid' : '' }}"
                                    name="Satuan" id="Satuan">
                                    <option value="">Pilih Satuan</option>
                                    @foreach ($Satuan as $t)
                                        <option value="{{ $t->id }}" {{ old('Satuan') == $t->id ? 'selected' : '' }}>
                                            {{ $t->Satuan }}
                                        </option>
                                    @endforeach
                                    <option value="SatuanBaru" {{ old('Satuan') == 'SatuanBaru' ? 'selected' : '' }}>
                                        Satuan
                                        Baru
                                    </option>
                                </select>
                                @if ($errors->has('Satuan'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Satuan') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row" id="satuan_baru_group" style="display: none;">
                            <label for="satuan_baru" class="col-2 col-form-label">* Satuan Baru</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('satuan_baru') ? 'is-invalid' : '' }}"
                                    name="satuan_baru" value="{{ old('satuan_baru') }}" placeholder="Nama Satuan Baru"
                                    type="text" id="satuan_baru">
                                @if ($errors->has('satuan_baru'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('satuan_baru') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merk" class="col-2 col-form-label"> Supplier</label>
                            <div class="col-8">
                                <select class="form-control select2 {{ $errors->has('Supplier') ? 'is-invalid' : '' }}"
                                    name="Supplier" id="Supplier">
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($Supplier as $t)
                                        <option value="{{ $t->id }}" {{ old('Supplier') == $t->id ? 'selected' : '' }}>
                                            {{ $t->Nama }}
                                        </option>
                                    @endforeach
                                    <option value="SupplierBaru" {{ old('Supplier') == 'SupplierBaru' ? 'selected' : '' }}> +
                                        Tambah Supplier
                                    </option>
                                </select>
                                @if ($errors->has('Supplier'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Supplier') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Keterangan" class="col-2 col-form-label">Keterangan</label>
                            <div class="col-8">
                                <textarea name="Keterangan" class="form-control"></textarea>
                                @if ($errors->has('Keterangan'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Keterangan') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label for="harga" class="col-2 col-form-label">* Harga</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RP</span>
                                    </div>
                                    <input class="form-control {{ $errors->has('Harga') ? 'is-invalid' : '' }}" name="Harga"
                                        value="{{ old('Harga') }}" placeholder="Harga" type="text" id="harga"
                                        onkeyup="formatNumber(this)">
                                    @if ($errors->has('Harga'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Harga') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="gambar" class="col-2 col-form-label">* Gambar</label>
                            <div class="col-8">
                                <div id="drop-area"
                                    style="border: 2px dashed #007bff; border-radius: 8px; padding: 30px; text-align: center; background: #f8f9fa; cursor: pointer; transition: border-color 0.3s;">
                                    <i class="fa fa-cloud-upload fa-2x" style="color:#007bff; margin-bottom:10px;"></i>
                                    <p style="margin: 0 0 10px 0;">Seret dan lepas gambar di sini atau klik untuk memilih
                                        file</p>
                                    <input class="form-upload {{ $errors->has('Gambar') ? 'is-invalid' : '' }}"
                                        name="Gambar" type="file" id="gambar" style="display: none;" accept="image/*"
                                        onchange="previewImage(this)">
                                    <div id="preview" style="margin-top: 10px;"></div>
                                </div>
                                @if ($errors->has('Gambar'))
                                    <div class="invalid-feedback" style="display:block;">
                                        {{ $errors->first('Gambar') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                    <a href="{{ route('master-cssd.cssd-master-item.index') }}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var dropArea = document.getElementById('drop-area');
            var fileInput = document.getElementById('gambar');

            dropArea.addEventListener('click', function () {
                fileInput.click();
            });

            dropArea.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.add('dragover');
            });

            dropArea.addEventListener('dragleave', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.remove('dragover');
            });

            dropArea.addEventListener('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.remove('dragover');
                if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    previewImage(fileInput);
                }
            });
        });

        function previewImage(input) {
            var preview = document.getElementById('preview');
            preview.innerHTML = '';
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview Gambar">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function previewImage(input) {
            var preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result +
                        '" style="max-width: 100%; max-height: 100px;">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function formatNumber(input) {
            var num = input.value.replace(/\./g, '');
            if (!isNaN(num)) {
                num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g, '$1.');
                num = num.split('').reverse().join('').replace(/^[\.]/, '');
                input.value = num;
            } else {
                alert('Hanya boleh angka');
                input.value = input.value.replace(/[^\d\.]*/g, '');
            }
        }
    </script>
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
            $("#simpanFrom").submit();
        }

        jQuery(document).ready(function () {
            $('.progress').hide();

            // Initialize Select2
            $('.select2').select2({
                placeholder: "Pilih Data",
                allowClear: true
            });


            $('#Tipe').on('change', function () {
                var selectedValue = $(this).val();
                if (selectedValue === 'TipeBaru') {
                    $('#tipe_baru_group').show();
                    $('#tipe_baru').attr('required', true);
                } else {
                    $('#tipe_baru_group').hide();
                    $('#tipe_baru').attr('required', false);
                    $('#tipe_baru').val('');
                }
            });
            $('#Satuan').on('change', function () {
                var selectedValue = $(this).val();
                if (selectedValue === 'SatuanBaru') {
                    $('#satuan_baru_group').show();
                    $('#satuan_baru').attr('required', true);
                } else {
                    $('#satuan_baru_group').hide();
                    $('#satuan_baru').attr('required', false);
                    $('#satuan_baru').val('');
                }
            });
            // Ketika Nama dipilih, otomatis set Merk sesuai data-merk pada option Nama
            $('#Nama').on('change', function () {
                var merk = $('#Nama option:selected').data('merk');
                if (merk) {
                    var found = false;
                    $('#merk option').each(function () {
                        if ($(this).text().trim() === merk) {
                            $(this).prop('selected', true);
                            found = true;
                            return false;
                        }
                    });
                    if (!found) {
                        $('#merk').val('');
                    }
                    $('#merk').trigger('change');
                }
            });


            if ($('#Tipe').val() === 'TipeBaru') {
                $('#tipe_baru_group').show();
                $('#tipe_baru').attr('required', true);
            }
            if ($('#Satuan').val() === 'SatuanBaru') {
                $('#Satuan_baru_group').show();
                $('#Satuan_baru').attr('required', true);
            }
        });
    </script>
@endpush
