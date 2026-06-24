@extends('layouts.app')
@push('title')
    Inventaris KSO
@endpush
@push('sub-title')
    Edit Inventaris KSO
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--tabs">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Edit Inventaris KSO</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold"
                    role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#" role="tab">
                            <i class="flaticon2-heart-rate-monitor" aria-hidden="true"></i>Edit Data Alat KSO
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="tab-content">
                <div class="tab-pane active" id="kt_portlet_base_demo_3_3_tab_content" role="tabpanel">

                    <form class="kt-form kt-form--label-right" id="simpanForm"
                        action="{{ route('inventariskso.update', $inventarisKso->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="kt-portlet__body">

                            <div class="row">
                                <!-- KOLOM KIRI -->
                                <div class="col-md-6">


                                    <div class="form-group row">
                                        <label for="Nama" class="col-4 col-form-label">* Nama Alat</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <select class="form-control kt-select2 @error('Nama') is-invalid @enderror"
                                                id="Nama" name="Nama" required>
                                                @if ($inventarisKso->Nama)
                                                    <option value="{{ $inventarisKso->Nama }}" selected>
                                                        {{ $inventarisKso->getNamaAlat->Nama }}</option>
                                                @else
                                                    <option value="">--Pilih Alat--</option>
                                                @endif
                                            </select>
                                            @error('Nama')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-4 col-sm-12">* Merk</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <select class="form-control kt-select2 @error('Merk') is-invalid @enderror"
                                                id="merk" name="Merk">
                                                @if ($inventarisKso->Merk)
                                                    <option value="{{ $inventarisKso->Merk }}" selected>
                                                        {{ $inventarisKso->getMerk->nama }}</option>
                                                @endif
                                            </select>
                                            @error('Merk')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="Tipe" class="col-4 col-form-label">Tipe</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <input class="form-control @error('Tipe') is-invalid @enderror" name="Tipe"
                                                value="{{ old('Tipe', $inventarisKso->Tipe) }}" placeholder="Tipe"
                                                type="text">
                                            @error('Tipe')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="NoSn" class="col-4 col-form-label">Nomor SN</label>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <input class="form-control @error('NoSn') is-invalid @enderror" name="NoSn"
                                                value="{{ old('NoSn', $inventarisKso->NoSn) }}" placeholder="Nomor SN"
                                                type="text" id="NoSn">
                                            @error('NoSn')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <input type="checkbox" class="form-check-input" id="checkbox_sn"
                                                {{ old('NoSn', $inventarisKso->NoSn) == 'None' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="checkbox_sn">Tidak Ada</label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="Vendor" class="col-4 col-form-label">* Vendor KSO</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <select class="form-control kt-select2 @error('Vendor') is-invalid @enderror"
                                                id="Vendor" name="Vendor" required>
                                                @if ($inventarisKso->Vendor)
                                                    <option value="{{ $inventarisKso->Vendor }}" selected>
                                                        {{ $inventarisKso->Vendor }}</option>
                                                @else
                                                    <option value="">--Pilih Vendor / Mitra--</option>
                                                @endif
                                            </select>
                                            @error('Vendor')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="TanggalKerjasama" class="col-4 col-form-label">* Tgl Kerjasama</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <input class="form-control @error('TanggalKerjasama') is-invalid @enderror"
                                                name="TanggalKerjasama"
                                                value="{{ old('TanggalKerjasama', $inventarisKso->TanggalKerjasama) }}"
                                                type="date" required>
                                            @error('TanggalKerjasama')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="AkhirKerjasama" class="col-4 col-form-label">* Akhir Kerjasama</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <input class="form-control @error('AkhirKerjasama') is-invalid @enderror"
                                                name="AkhirKerjasama"
                                                value="{{ old('AkhirKerjasama', $inventarisKso->AkhirKerjasama) }}"
                                                type="date" required>
                                            @error('AkhirKerjasama')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- KOLOM KANAN -->
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-4 col-sm-12">* Departemen</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <select
                                                class="form-control kt-select2 @error('Departemen') is-invalid @enderror"
                                                id="departemen" name="Departemen" required>
                                                @if ($inventarisKso->Departemen)
                                                    <option value="{{ $inventarisKso->Departemen }}" selected>
                                                        {{ $inventarisKso->getDepartemen->nama }}</option>
                                                @else
                                                    <option value="">--Pilih Departemen--</option>
                                                @endif
                                            </select>
                                            @error('Departemen')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-4 col-sm-12">* Unit</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <select class="form-control kt-select2 @error('Unit') is-invalid @enderror"
                                                id="unit" name="Unit" required>
                                                @if ($inventarisKso->Unit)
                                                    <option value="{{ $inventarisKso->Unit }}" selected>
                                                        {{ $inventarisKso->Unit }}</option>
                                                @else
                                                    <option value="">--Pilih Unit--</option>
                                                @endif
                                            </select>
                                            @error('Unit')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-4 col-sm-12">* Jenis / Pengguna</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <select class="form-control @error('Pengguna') is-invalid @enderror"
                                                id="Pengguna" name="Pengguna" required>
                                                <option value="">--Pilih Jenis--</option>
                                                <option value="Medis"
                                                    {{ old('Pengguna', $inventarisKso->Pengguna) == 'Medis' ? 'selected' : '' }}>
                                                    Medis</option>
                                                <option value="Non Medis"
                                                    {{ old('Pengguna', $inventarisKso->Pengguna) == 'Non Medis' ? 'selected' : '' }}>
                                                    Non Medis
                                                </option>
                                            </select>
                                            @error('Pengguna')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row" id="risiko" style="display:none;">
                                        <label for="Klasifikasi" class="col-4 col-form-label">Klasifikasi Risiko</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <select name="Klasifikasi"
                                                class="form-control @error('Klasifikasi') is-invalid @enderror">
                                                <option value="">--Pilih Satu--</option>
                                                <option value="None"
                                                    {{ old('Klasifikasi', $inventarisKso->Klasifikasi) == 'None' ? 'selected' : '' }}>
                                                    None</option>
                                                <option value="High Risk"
                                                    {{ old('Klasifikasi', $inventarisKso->Klasifikasi) == 'High Risk' ? 'selected' : '' }}>
                                                    High Risk
                                                </option>
                                                <option value="Medium Risk"
                                                    {{ old('Klasifikasi', $inventarisKso->Klasifikasi) == 'Medium Risk' ? 'selected' : '' }}>
                                                    Medium Risk
                                                </option>
                                                <option value="Low to Medium Risk"
                                                    {{ old('Klasifikasi', $inventarisKso->Klasifikasi) == 'Low to Medium Risk' ? 'selected' : '' }}>
                                                    Low
                                                    to Medium Risk</option>
                                                <option value="Low Risk"
                                                    {{ old('Klasifikasi', $inventarisKso->Klasifikasi) == 'Low Risk' ? 'selected' : '' }}>
                                                    Low Risk
                                                </option>
                                            </select>
                                            @error('Klasifikasi')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row" id="tgl_kalibrasi_row" style="display: none;">
                                        <label for="TglKalibrasi" class="col-4 col-form-label">Tgl Kalibrasi</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <input class="form-control @error('TglKalibrasi') is-invalid @enderror"
                                                name="TglKalibrasi"
                                                value="{{ old('TglKalibrasi', $inventarisKso->TglKalibrasi) }}"
                                                type="date">
                                            @error('TglKalibrasi')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="Keterangan" class="col-4 col-form-label">Keterangan</label>
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <textarea class="form-control @error('Keterangan') is-invalid @enderror" id="Keterangan" name="Keterangan"
                                                rows="3" placeholder="Keterangan">{{ old('Keterangan', $inventarisKso->Keterangan) }}</textarea>
                                            @error('Keterangan')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BAGIAN UPLOAD FILE -->
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row align-items-center">
                                        <!-- Dokumen Kerja Sama -->
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                            <label class="col-form-label">Upload Dokumen Kerja Sama</label>
                                            <div id="drop-area-dokumen" class="drop-area mb-2"
                                                style="border: 2px dashed #ccc; border-radius: 6px; padding: 20px; text-align: center; cursor: pointer;">
                                                <span id="drop-text-dokumen">Drag & Drop file di sini atau klik untuk
                                                    memilih</span>
                                                <input type="file"
                                                    class="form-control-file d-none @error('Dokumen') is-invalid @enderror"
                                                    name="Dokumen" id="Dokumen" accept=".pdf,.doc,.docx" />
                                                <div id="dokumen-preview" class="mt-2">
                                                    @if ($inventarisKso->Dokumen)
                                                        <div class="preview-file">
                                                            <i class="fa fa-file"></i>
                                                            <span>
                                                                <a href="{{ asset('storage/inventariskso/dokumen/' . $inventarisKso->Dokumen) }}"
                                                                    target="_blank">{{ basename($inventarisKso->Dokumen) }}</a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @error('Dokumen')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <small class="form-text text-muted">Format: PDF, DOC, DOCX (Maks. 5MB)</small>
                                        </div>

                                        <!-- Upload Gambar -->
                                        <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                            <label class="col-form-label">Upload Gambar</label>
                                            <div id="drop-area-gambar" class="drop-area mb-2"
                                                style="border: 2px dashed #ccc; border-radius: 6px; padding: 20px; text-align: center; cursor: pointer;">
                                                <span id="drop-text-gambar">Drag & Drop gambar di sini atau klik untuk
                                                    memilih</span>
                                                <input type="file"
                                                    class="form-control-file d-none @error('Gambar') is-invalid @enderror"
                                                    name="Gambar" id="Gambar" accept="image/*" />
                                                <div id="gambar-preview" class="mt-2">
                                                    @if ($inventarisKso->Gambar)
                                                        <img src="{{ asset('storage/inventariskso/gambar/' . $inventarisKso->Gambar) }}"
                                                            class="preview-img" alt="Preview Gambar Lama">
                                                    @endif
                                                </div>
                                            </div>
                                            @error('Gambar')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <input type="hidden"
                                                value="{{ old('NamaRS', $inventarisKso->NamaRS ?? (auth()->check() ? auth()->user()->kodeRS : '')) }}"
                                                name="NamaRS" id="NamaRS" />
                                        </div>
                                    </div>

                                    <style>
                                        .drop-area {
                                            transition: border-color 0.2s;
                                        }

                                        .drop-area.dragover {
                                            border-color: #007bff;
                                            background: #f0f8ff;
                                        }

                                        .preview-img {
                                            max-width: 120px;
                                            max-height: 120px;
                                            margin-top: 10px;
                                            border-radius: 6px;
                                            border: 1px solid #ddd;
                                        }

                                        .preview-file {
                                            margin-top: 10px;
                                            color: #007bff;
                                            font-weight: bold;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            gap: 8px;
                                        }

                                        .preview-file i {
                                            font-size: 24px;
                                        }
                                    </style>
                                </div>
                            </div>

                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <button type="button" onclick="simpan(event,this)"
                                        class="btn btn-info">Update</button>
                                    <a href="{{ route('inventaris.index-kso') }}">
                                        <button type="button" class="btn btn-secondary">Cancel</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // 1. Select2 for Nama Alat
        var select_nama_alat = function(nama = '') {
            $('#Nama').select2({
                placeholder: "Ketik untuk mencari alat...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: '{{ route('inventariskso.get-master-alat') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.Nama,
                                    id: item.Nama
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            @if ($inventarisKso->Nama)
                $('#Nama').append(
                    `<option value="{{ $inventarisKso->Nama }}" selected>{{ $inventarisKso->Nama }}</option>`);
            @endif
            $('#Nama').val('{{ old('Nama', $inventarisKso->Nama) }}').trigger('change');
        };

        // 1a. Select2 for Vendor KSO
        var select_vendor_kso = function() {
            $('#Vendor').select2({
                placeholder: "Ketik untuk mencari vendor...",
                minimumInputLength: 1,
                allowClear: true,
                ajax: {
                    url: 'https://abproc.awalbros.com/api/master-vendor',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.Nama,
                                    id: item.Nama
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            @if ($inventarisKso->Vendor)
                $('#Vendor').append(
                    `<option value="{{ $inventarisKso->Vendor }}" selected>{{ $inventarisKso->Vendor }}</option>`);
            @endif
            $('#Vendor').val('{{ old('Vendor', $inventarisKso->Vendor) }}').trigger('change');
        };

        var merk = function() {
            $('#merk').select2({
                placeholder: "Select Merk",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('inventaris.get-merk') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            @if ($inventarisKso->Merk)
                $('#merk').append(
                    `<option value="{{ $inventarisKso->Merk }}" selected>{{ $inventarisKso->Merk }}</option>`);
            @endif
            $('#merk').val('{{ old('Merk', $inventarisKso->Merk) }}').trigger('change');
        }

        // 2. Select2 for Departemen
        var select_departemen = function() {
            $('#departemen').select2({
                placeholder: "Select departemen",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('master.get-departemen') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            @if ($inventarisKso->Departemen)
                $('#departemen').append(
                    `<option value="{{ $inventarisKso->Departemen }}" selected>{{ $inventarisKso->Departemen }}</option>`
                );
            @endif
            $('#departemen').val('{{ old('Departemen', $inventarisKso->Departemen) }}').trigger('change');
        }

        var select_unit = function() {
            $('#unit').select2({
                placeholder: "Select Data",
                minimumInputLength: 1,
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
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
            @if ($inventarisKso->Unit)
                $('#unit').append(
                    `<option value="{{ $inventarisKso->Unit }}" selected>{{ $inventarisKso->Unit }}</option>`);
            @endif
            $('#unit').val('{{ old('Unit', $inventarisKso->Unit) }}').trigger('change');
        }

        // 4. Checkbox SN (Tidak Ada)
        $('#checkbox_sn').on('change', function() {
            var input = document.getElementById('NoSn');
            if (this.checked) {
                input.value = 'None';
                input.readOnly = true;
            } else {
                input.value = '';
                input.readOnly = false;
            }
        });

        // 5. Toggle Tgl Kalibrasi berdasarkan dropdown Kalibrasi
        $('#IsKalibrasi').on('change', function() {
            if ($(this).val() == '1') {
                $('#tgl_kalibrasi_row').show();
            } else {
                $('#tgl_kalibrasi_row').hide();
            }
        });
        if ($('#IsKalibrasi').length && $('#IsKalibrasi').val() == '1') $('#tgl_kalibrasi_row').show();

        // 6. Show/Hide Klasifikasi Risiko berdasarkan Jenis Pengguna
        function toggleRisiko() {
            if ($('#Pengguna').val() == 'Medis') {
                $('#risiko').show();
            } else {
                $('#risiko').hide();
            }
        }
        $('#Pengguna').on('change', toggleRisiko);
        $(function() {
            toggleRisiko();
        }); // initial

        // 7. Drag & Drop Handlers untuk Upload Gambar
        function setupDropAreaGambar(areaId, inputId, previewId, textId) {
            const dropArea = document.getElementById(areaId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const dropText = document.getElementById(textId);

            dropArea.addEventListener('click', () => input.click());
            dropArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropArea.classList.add('dragover');
            });
            dropArea.addEventListener('dragleave', () => {
                dropArea.classList.remove('dragover');
            });
            dropArea.addEventListener('drop', (e) => {
                e.preventDefault();
                dropArea.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    previewFile();
                }
            });
            input.addEventListener('change', previewFile);

            function previewFile() {
                preview.innerHTML = '';
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" class="preview-img" alt="Preview">`;
                    };
                    reader.readAsDataURL(file);
                    dropText.style.display = 'none';
                } else {
                    dropText.style.display = '';
                }
            }
        }

        // 7b. Drag & Drop Handlers untuk Upload Dokumen
        function setupDropAreaDokumen(areaId, inputId, previewId, textId) {
            const dropArea = document.getElementById(areaId);
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const dropText = document.getElementById(textId);

            dropArea.addEventListener('click', () => input.click());
            dropArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropArea.classList.add('dragover');
            });
            dropArea.addEventListener('dragleave', () => {
                dropArea.classList.remove('dragover');
            });
            dropArea.addEventListener('drop', (e) => {
                e.preventDefault();
                dropArea.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    previewFile();
                }
            });
            input.addEventListener('change', previewFile);

            function previewFile() {
                preview.innerHTML = '';
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const allowedTypes = ['application/pdf', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ];

                    if (allowedTypes.includes(file.type)) {
                        let iconClass = 'fa fa-file';
                        if (file.type === 'application/pdf') iconClass = 'fa fa-file-pdf';
                        else if (file.type.includes('word')) iconClass = 'fa fa-file-word';

                        preview.innerHTML =
                            `<div class="preview-file"><i class="${iconClass}"></i> <span>${file.name}</span></div>`;
                        dropText.style.display = 'none';
                    } else {
                        preview.innerHTML = `<span class="text-danger">Format file tidak valid!</span>`;
                        input.value = '';
                        dropText.style.display = '';
                    }
                } else {
                    dropText.style.display = '';
                }
            }
        }

        // Inisialisasi Drop Area
        setupDropAreaGambar('drop-area-gambar', 'Gambar', 'gambar-preview', 'drop-text-gambar');
        setupDropAreaDokumen('drop-area-dokumen', 'Dokumen', 'dokumen-preview', 'drop-text-dokumen');

        // 8. Form Submit dengan Loading State
        function simpan(e, id) {
            e.preventDefault();
            KTApp.block('.kt-portlet', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Please wait...'
            });
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#simpanForm").submit();
        }

        $(document).ready(function() {
            select_vendor_kso();
            merk();
            select_nama_alat();
            select_departemen();
            select_unit();
        });
    </script>
@endpush
