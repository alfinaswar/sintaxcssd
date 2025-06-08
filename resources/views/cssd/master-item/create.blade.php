@extends('layouts.app')
@push('title')
    Master CSSD
@endpush
@push('sub-title')
    Tambah CSSD
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Master CSSD
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="simpanFrom" action="{{ route('master.cssd-master-item.store') }}"
            method="POST" accept-charset="utf-8" enctype="multipart/form-data">
            @csrf
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
                            <label for="serial_number" class="col-2 col-form-label">* Serial Number</label>
                            <div class="col-8">
                                <input class="form-control" name="SerialNumber" value="{{ old('SerialNumber') }}"
                                    placeholder="Serial Number" type="text" id="serial_number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-2 col-form-label">* Nama</label>
                            <div class="col-8">
                                <input class="form-control" name="Nama" value="{{ old('Nama') }}" placeholder="Nama"
                                    type="text" id="nama">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merk" class="col-2 col-form-label">* Merk</label>
                            <div class="col-8">
                                <select class="form-control select2" name="Merk" id="merk">
                                    <option value="">Pilih Merk</option>
                                    @foreach ($merks as $merkItem)
                                        <option value="{{ $merkItem->id }}"
                                            {{ old('Merk') == $merkItem->id ? 'selected' : '' }}>
                                            {{ $merkItem->Merk }}
                                        </option>
                                    @endforeach
                                    <option value="lainnya" {{ old('Merk') == 'lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="merk_baru_group" style="display: none;">
                            <label for="merk_baru" class="col-2 col-form-label">* Merk Baru</label>
                            <div class="col-8">
                                <input class="form-control" name="merk_baru" value="{{ old('merk_baru') }}"
                                    placeholder="Nama Merk Baru" type="text" id="merk_baru">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merk" class="col-2 col-form-label">* Tipe</label>
                            <div class="col-8">
                                <select class="form-control select2" name="Tipe" id="Tipe">
                                    <option value="">Pilih Tipe</option>
                                    @foreach ($tipe as $t)
                                        <option value="{{ $merkItem->id }}" {{ old('Merk') == $t->id ? 'selected' : '' }}>
                                            {{ $t->Tipe }}
                                        </option>
                                    @endforeach
                                    <option value="lainnya" {{ old('Tipe') == 'lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="tipe_baru_group" style="display: none;">
                            <label for="tipe_baru" class="col-2 col-form-label">* Tipe Baru</label>
                            <div class="col-8">
                                <input class="form-control" name="tipe_baru" value="{{ old('tipe_baru') }}"
                                    placeholder="Nama Tipe Baru" type="text" id="tipe_baru">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="qty" class="col-2 col-form-label">* Qty</label>
                            <div class="col-8">
                                <input class="form-control" name="Qty" value="{{ old('Qty') }}" placeholder="Qty"
                                    type="text" id="qty">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tahun_perolehan" class="col-2 col-form-label">* Tahun Perolehan</label>
                            <div class="col-8">
                                <select class="form-control select2" name="TahunPerolehan" id="tahun_perolehan" required>
                                    <option value="">Pilih Tahun</option>
                                    @for ($year = 2010; $year <= date('Y'); $year++)
                                        <option value="{{ $year }}"
                                            {{ old('TahunPerolehan') == $year ? 'selected' : '' }}>{{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="kondisi_barang" class="col-2 col-form-label">* Kondisi Barang</label>
                            <div class="col-8">
                                <select class="form-control" name="KondisiBarang" id="kondisi_barang" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="B" {{ old('KondisiBarang') == 'B' ? 'selected' : '' }}>B (Baik)
                                    </option>
                                    <option value="KB" {{ old('KondisiBarang') == 'KB' ? 'selected' : '' }}>KB (Kurang
                                        Baik)
                                    </option>
                                    <option value="R" {{ old('KondisiBarang') == 'R' ? 'selected' : '' }}>R (Rusak)
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gambar" class="col-2 col-form-label">* Gambar</label>
                            <div class="col-8">
                                <input class="form-control" name="Gambar" value="{{ old('Gambar') }}"
                                    placeholder="Gambar" type="file" id="gambar">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="satuan" class="col-2 col-form-label">* Satuan</label>
                            <div class="col-8">
                                <input class="form-control" name="Satuan" value="{{ old('Satuan') }}"
                                    placeholder="Satuan" type="text" id="satuan">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="harga" class="col-2 col-form-label">* Harga</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RP</span>
                                    </div>
                                    <input class="form-control" name="Harga" value="{{ old('Harga') }}"
                                        placeholder="Harga" type="text" id="harga" onkeyup="formatNumber(this)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                    <a href="{{ route('master.cssd-master-item.index') }}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
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

        jQuery(document).ready(function() {
            $('.progress').hide();

            // Initialize Select2
            $('.select2').select2({
                placeholder: "Pilih Merk",
                allowClear: true
            });

            // Show/hide merk baru input based on selection
            $('#merk').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'lainnya') {
                    $('#merk_baru_group').show();
                    $('#merk_baru').attr('required', true);
                } else {
                    $('#merk_baru_group').hide();
                    $('#merk_baru').attr('required', false);
                    $('#merk_baru').val('');
                }
            });
            $('#Tipe').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'lainnya') {
                    $('#tipe_baru_group').show();
                    $('#tipe_baru').attr('required', true);
                } else {
                    $('#tipe_baru_group').hide();
                    $('#tipe_baru').attr('required', false);
                    $('#tipe_baru').val('');
                }
            });
            // Check if 'lainnya' was selected on page load (for old input)
            if ($('#merk').val() === 'lainnya') {
                $('#merk_baru_group').show();
                $('#merk_baru').attr('required', true);
            }
            if ($('#Tipe').val() === 'lainnya') {
                $('#tipe_baru_group').show();
                $('#tipe_baru').attr('required', true);
            }
        });
    </script>
@endpush
