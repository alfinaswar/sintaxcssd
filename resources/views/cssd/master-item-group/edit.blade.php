@extends('layouts.app')
@push('title')
    Master Group CSSD
@endpush
@push('sub-title')
    Edit Master Group CSSD
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Edit Master Group CSSD
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="formGroupCSSD"
            action="{{ route('master-cssd.item-group.update', $itemGroup->id) }}" method="POST" accept-charset="utf-8"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label for="nama" class="col-2 col-form-label">* Nama</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('Nama') ? 'is-invalid' : '' }}" name="Nama"
                                    value="{{ old('Nama', $itemGroup->Nama) }}" placeholder="Masukkan Nama Item Group"
                                    type="text" id="nama">
                                @if ($errors->has('Nama'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('Nama') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merk" class="col-2 col-form-label">* Merk</label>
                            <div class="col-8">
                                <select class="form-control select2 {{ $errors->has('Merk') ? 'is-invalid' : '' }}"
                                    name="Merk" id="merk">
                                    <option value="">Pilih Merk</option>
                                    @foreach ($Merk as $merkItem)
                                        <option value="{{ $merkItem->id }}" {{ old('Merk', $itemGroup->Merk) == $merkItem->id ? 'selected' : '' }}>
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
                        <div class="form-group row" id="merk_baru_group" style="display: none;">
                            <label for="merk_baru" class="col-2 col-form-label">* Merk Baru</label>
                            <div class="col-8">
                                <input class="form-control {{ $errors->has('merk_baru') ? 'is-invalid' : '' }}"
                                    name="merk_baru" value="{{ old('merk_baru') }}" placeholder="Nama Merk Baru" type="text"
                                    id="merk_baru">
                                @if ($errors->has('merk_baru'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('merk_baru') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="Foto" class="col-2 col-form-label">* Foto</label>
                            <div class="col-8">
                                <div id="drop-area"
                                    style="border: 2px dashed #6c757d; border-radius: 8px; padding: 30px; text-align: center; background: #f8f9fa; cursor: pointer;">
                                    <p style="margin-bottom: 10px; color: #6c757d;">
                                        <i class="fa fa-cloud-upload-alt" style="font-size: 2rem;"></i><br>
                                        <span>Seret dan lepas Foto di sini atau klik untuk memilih file</span>
                                    </p>
                                    <input class="form-upload {{ $errors->has('Foto') ? 'is-invalid' : '' }}" name="Foto"
                                        type="file" id="gambar" style="display: none;" accept="image/*">
                                    @if ($errors->has('Foto'))
                                        <div class="invalid-feedback" style="display:block;">
                                            {{ $errors->first('Foto') }}
                                        </div>
                                    @endif
                                    <div id="preview" style="margin-top: 10px;">
                                        @if ($itemGroup->Foto)
                                            <img src="{{ asset('storage/cssd_item_group/' . $itemGroup->Foto) }}"
                                                style="max-width: 100%; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        @endif
                                    </div>
                                </div>
                                @if ($itemGroup->Foto)
                                    <small class="form-text text-muted">Foto saat ini ditampilkan di atas. Upload file baru
                                        untuk mengganti.</small>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ route('master-cssd.item-group.index') }}">
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
            var input = document.getElementById('gambar');
            var preview = document.getElementById('preview');

            // Klik area untuk trigger file input
            dropArea.addEventListener('click', function () {
                input.click();
            });

            // Drag over
            dropArea.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.style.background = '#e2e6ea';
            });

            dropArea.addEventListener('dragleave', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.style.background = '#f8f9fa';
            });

            // Drop file
            dropArea.addEventListener('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.style.background = '#f8f9fa';
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    input.files = e.dataTransfer.files;
                    previewImage(input);
                }
            });

            // Preview saat file dipilih
            input.addEventListener('change', function () {
                previewImage(input);
            });

            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    // Jika tidak ada file baru, tampilkan foto lama jika ada
                    @if ($itemGroup->Foto)
                        preview.innerHTML = '<img src="{{ asset('storage/cssd_item_group/' . $itemGroup->Foto) }}" style="max-width: 100%; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
                    @else
                        preview.innerHTML = '';
                    @endif
                        }
            }
        });
    </script>
    <script>
        jQuery(document).ready(function () {
            $('.progress').hide();

            // Initialize Select2
            $('.select2').select2({
                placeholder: "Pilih Merk",
                allowClear: true
            });

            $('#merk').on('change', function () {
                var selectedValue = $(this).val();
                if (selectedValue === 'MerkBaru') {
                    $('#merk_baru_group').show();
                    $('#merk_baru').attr('required', true);
                } else {
                    $('#merk_baru_group').hide();
                    $('#merk_baru').attr('required', false);
                    $('#merk_baru').val('');
                }
            });

            // Cek jika 'MerkBaru' dipilih saat load (untuk old input)
            if ($('#merk').val() === 'MerkBaru') {
                $('#merk_baru_group').show();
                $('#merk_baru').attr('required', true);
            }
        });
        function previewImage(input) {
            var preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100%; max-height: 100px;">';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                // Jika tidak ada file baru, tampilkan foto lama jika ada
                @if ($itemGroup->Foto)
                    preview.innerHTML = '<img src="{{ asset('storage/cssd_item_group/' . $itemGroup->Foto) }}" style="max-width: 100%; max-height: 100px;">';
                @else
                    preview.innerHTML = '';
                @endif
                    }
        }
    </script>
@endpush