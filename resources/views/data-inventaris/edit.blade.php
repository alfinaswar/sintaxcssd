@extends('layouts.app')
@push('title')
    Inventaris
@endpush
@push('sub-title')
    Update Data Inventaris
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Update Data Inventaris
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" action="{{ route('inventaris.update', $datainv->id) }}" method="POST"
            accept-charset="utf-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="kt-portlet__body">
                @if ($errors->any())
                    <div class="alert alert-warning fade show" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama" class="col-3 col-form-label">* Nama Alat</label>
                            <div class="col-9">
                                <input class="form-control" name="nama" value="{{ $datainv->nama }}" placeholder="Nama"
                                    type="text" id="nama" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="real_name" class="col-3 col-form-label">* Nama Barcode</label>
                            <div class="col-9">
                                <input class="form-control" name="real_name" value="{{ $datainv->real_name }}"
                                    placeholder="Nama di barcode" type="text" id="real_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-3">* Merk</label>
                            <div class="col-9">
                                <select class="form-control kt-select2" id="merk" name="merk">
                                    <option value="{{ $datainv->merk }}" selected>{{ $datainv->merk }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_inventaris" class="col-3 col-form-label">* Nomor Inventaris</label>
                            <div class="col-9">
                                <input class="form-control" name="no_inventaris" value="{{ $datainv->no_inventaris }}"
                                    placeholder="Nomor Inventaris Medis" type="text" id="no_inventaris">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_sn" class="col-3 col-form-label">* Nomor SN</label>
                            <div class="col-6">
                                <input class="form-control" name="no_sn" value="{{ $datainv->no_sn }}"
                                    placeholder="Nomor SN" type="text" id="no_sn">
                            </div>
                            <div class="col-3 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input" id="checkbox_sn" {{ empty($datainv->no_sn) ? 'checked' : '' }}>
                                <label class="form-check-label ml-2" for="checkbox_sn">Tidak Ada</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tanggal_beli" class="col-3 col-form-label">* Tanggal Beli</label>
                            <div class="col-9">
                                <input class="form-control" name="tanggal_beli"
                                    value="{{ date('Y-m-d', strtotime($datainv->tanggal_beli)) }}"
                                    placeholder="Tanggal beli" type="date" id="tanggal_beli">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="departemen" class="col-3 col-form-label">* Departemen</label>
                            <div class="col-9">
                                <select name="departemen" class="custom-select form-control" id="departemen">
                                    <option value="">--Pilih Departemen--</option>
                                    @foreach ($dept as $item)
                                        <option value="{{ $item->nama }}"
                                            {{ $datainv->departemen == $item->nama ? 'selected' : '' }}>
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-3">* Unit</label>
                            <div class="col-9">
                                <select class="form-control kt-select2" id="unit" name="unit">
                                    <option value="{{ $datainv->unit }}" selected>{{ $datainv->unit }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-3">* Jenis / Pengguna</label>
                            <div class="col-9">
                                <select class="form-control kt-select2" id="userPengguna" name="userPengguna">
                                    <option value="">--Pilih Jenis--</option>
                                    <option value="Medis" {{ $datainv->pengguna == 'Medis' ? 'selected' : '' }}>Medis</option>
                                    <option value="Non Medis" {{ $datainv->pengguna == 'Non Medis' ? 'selected' : '' }}>Non Medis</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="KategoriRisiko">
                            <label for="klasifikasi" class="col-3 col-form-label">* Kategori</label>
                            <div class="col-9">
                                <select name="klasifikasi" class="form-control" id="klasifikasi">
                                    <option value="">--Pilih Satu--</option>
                                    <option value="None" {{ $datainv->klasifikasi == 'None' ? 'selected' : '' }}>None</option>
                                    <option value="High Risk" {{ $datainv->klasifikasi == 'High Risk' ? 'selected' : '' }}>High Risk</option>
                                    <option value="Medium Risk" {{ $datainv->klasifikasi == 'Medium Risk' ? 'selected' : '' }}>Medium Risk</option>
                                    <option value="Low to Medium Risk" {{ $datainv->klasifikasi == 'Low to Medium Risk' ? 'selected' : '' }}>Low to Medium Risk</option>
                                    <option value="Low Risk" {{ $datainv->klasifikasi == 'Low Risk' ? 'selected' : '' }}>Low Risk</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="keterangan" class="col-3 col-form-label">Keterangan</label>
                            <div class="col-9">
                                <textarea name="keterangan" class="form-control" id="keterangan" rows="3">{{ $datainv->keterangan }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="isKalibrasi" class="col-3 col-form-label">Kalibrasi</label>
                            <div class="col-9">
                                <select class="form-control" id="isKalibrasi" name="isKalibrasi">
                                    <option value="">--Pilih Status Kalibrasi--</option>
                                    <option value="1" {{ (old('isKalibrasi', $datainv->isKalibrasi) == '1') ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ (old('isKalibrasi', $datainv->isKalibrasi) == '0') ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Bagian upload gambar dan SPO alat, drag & drop, preview, col-6 --}}
                <div class="form-group row align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label class="col-form-label">* Upload SPO Alat</label>
                        <div id="drop-area-manualbook" class="drop-area mb-2"
                            style="border: 2px dashed #ccc; border-radius: 6px; padding: 20px; text-align: center; cursor: pointer;">
                            <span id="drop-text-manualbook">Drag & Drop file PDF di sini atau klik untuk memilih</span>
                            <input type="file" name="manualbook" id="manualbook"
                                class="form-control-file d-none" accept="application/pdf">
                            <div id="manualbook-preview" class="mt-2">
                                @if($datainv->manualbook)
                                    <a href="{{ asset('storage/'.$datainv->manualbook) }}" target="_blank" class="btn btn-sm btn-primary">Lihat File Saat Ini</a>
                                    <small class="form-text text-muted">File saat ini: {{ $datainv->manualbook }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label class="col-form-label">* Gambar</label>
                        <div id="drop-area-gambar" class="drop-area mb-2"
                            style="border: 2px dashed #ccc; border-radius: 6px; padding: 20px; text-align: center; cursor: pointer;">
                            <span id="drop-text-gambar">Drag & Drop gambar di sini atau klik untuk memilih</span>
                            <input type="file" class="form-control-file d-none"
                                name="gambar" id="gambar" accept="image/*" />
                            <div id="gambar-preview" class="mt-2">
                                @if($datainv->gambar)
                                    <img src="{{ asset('storage/gambar/'.$datainv->gambar) }}" alt="Preview Gambar" class="img-thumbnail preview-img" style="max-width: 120px;">
                                    <small class="form-text text-muted">File saat ini: {{ $datainv->gambar }}</small>
                                @endif
                            </div>
                        </div>
                        <input type="text" hidden class="form-control"
                            value="{{ old('nama_rs', auth()->check() ? auth()->user()->kodeRS : '') }}"
                            name="nama_rs" id="nama_rs" placeholder="" />
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

                    .preview-pdf {
                        margin-top: 10px;
                        color: #007bff;
                        font-weight: bold;
                    }
                </style>
                <script>
                    // Gambar
                    const dropAreaGambar = document.getElementById('drop-area-gambar');
                    const inputGambar = document.getElementById('gambar');
                    const gambarPreview = document.getElementById('gambar-preview');
                    const dropTextGambar = document.getElementById('drop-text-gambar');

                    dropAreaGambar.addEventListener('click', () => inputGambar.click());
                    dropAreaGambar.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        dropAreaGambar.classList.add('dragover');
                    });
                    dropAreaGambar.addEventListener('dragleave', () => {
                        dropAreaGambar.classList.remove('dragover');
                    });
                    dropAreaGambar.addEventListener('drop', (e) => {
                        e.preventDefault();
                        dropAreaGambar.classList.remove('dragover');
                        if (e.dataTransfer.files.length) {
                            inputGambar.files = e.dataTransfer.files;
                            previewGambar();
                        }
                    });
                    inputGambar.addEventListener('change', previewGambar);

                    function previewGambar() {
                        gambarPreview.innerHTML = '';
                        if (inputGambar.files && inputGambar.files[0]) {
                            const file = inputGambar.files[0];
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                gambarPreview.innerHTML = `<img src="${e.target.result}" class="preview-img" alt="Preview Gambar">`;
                            }
                            reader.readAsDataURL(file);
                            dropTextGambar.style.display = 'none';
                        } else {
                            dropTextGambar.style.display = '';
                        }
                    }

                    // Manualbook (PDF)
                    const dropAreaManualbook = document.getElementById('drop-area-manualbook');
                    const inputManualbook = document.getElementById('manualbook');
                    const manualbookPreview = document.getElementById('manualbook-preview');
                    const dropTextManualbook = document.getElementById('drop-text-manualbook');

                    dropAreaManualbook.addEventListener('click', () => inputManualbook.click());
                    dropAreaManualbook.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        dropAreaManualbook.classList.add('dragover');
                    });
                    dropAreaManualbook.addEventListener('dragleave', () => {
                        dropAreaManualbook.classList.remove('dragover');
                    });
                    dropAreaManualbook.addEventListener('drop', (e) => {
                        e.preventDefault();
                        dropAreaManualbook.classList.remove('dragover');
                        if (e.dataTransfer.files.length) {
                            inputManualbook.files = e.dataTransfer.files;
                            previewManualbook();
                        }
                    });
                    inputManualbook.addEventListener('change', previewManualbook);

                    function previewManualbook() {
                        manualbookPreview.innerHTML = '';
                        if (inputManualbook.files && inputManualbook.files[0]) {
                            const file = inputManualbook.files[0];
                            if (file.type === "application/pdf") {
                                manualbookPreview.innerHTML = `<span class="preview-pdf"><i class="fa fa-file-pdf"></i> ${file.name}</span>`;
                                dropTextManualbook.style.display = 'none';
                            } else {
                                manualbookPreview.innerHTML = `<span class="text-danger">File harus berupa PDF</span>`;
                                dropTextManualbook.style.display = '';
                            }
                        } else {
                            dropTextManualbook.style.display = '';
                        }
                    }
                </script>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="{{ route('inventaris.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>

    </div>
@endsection
@push('js')
    <script>
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
        }

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
                                    id: item.nama,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        jQuery(document).ready(function() {
            $('.progress').hide()
            merk()
            select_unit()

            // Checkbox SN
            $('#checkbox_sn').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#no_sn').val('').prop('readonly', true);
                } else {
                    $('#no_sn').prop('readonly', false);
                }
            });
            if ($('#checkbox_sn').is(':checked')) {
                $('#no_sn').val('').prop('readonly', true);
            }
        });
    </script>
@endpush
