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
                            <label for="nama" class="col-3 col-form-label">* Nama Barcode</label>
                            <div class="col-9">
                                <input class="form-control" name="real_name" value="{{ $datainv->real_name }}"
                                    placeholder="Nama" type="text" id="real_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">* Merk</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control kt-select2" id="merk" name="merk">
                                    <option value="{{ $datainv->merk }}">{{ $datainv->merk }}</option>
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
                            <label for="departemen" class="col-3 col-form-label">* Nomor SN</label>
                            <div class=" col-lg-9">
                                <input class="form-control" name="no_sn" value="{{ $datainv->no_sn }}"
                                    placeholder="Nomor SN" type="text" id="no_sn">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tanggal_beli" class="col-3 col-form-label">* Tanggal Beli</label>
                            <div class=" col-lg-9">
                                <input class="form-control" name="tanggal_beli"
                                    value="{{ date('Y-m-d', strtotime($datainv->tanggal_beli)) }}"
                                    placeholder="Tanggal beli" type="date" id="tanggal_beli">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="departemen" class="col-3 col-form-label">Departemen</label>
                            <div class="col-9">

                                <select name="departemen" class="custom-select form-control" id="departemen">
                                    <option selected value="">--Pilih Departemen--</option>
                                    @foreach ($dept as $item)
                                        <option value="{{ $item->nama }}"
                                            {{ $datainv->departemen == $item->nama ? 'selected' : '' }}>
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">* Unit</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control kt-select2" id="unit" name="unit">
                                    <option value="{{ $datainv->unit }}" selected>
                                        {{ $datainv->unit }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">* Jenis / Pengguna</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control kt-select2" id="userPengguna" name="userPengguna">
                                    <option value=" " selected>--Select Jenis--</option>
                                    <option value="Medis" <?php echo $datainv->pengguna == 'Medis' ? 'selected' : ''; ?>>Medis</option>
                                    <option value="Non Medis" <?php echo $datainv->pengguna == 'Non Medis' ? 'selected' : ''; ?>>Non Medis</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="KategoriRisiko">
                            <label for="tanggal_beli" class="col-3 col-form-label">Kategori</label>
                            <div class=" col-lg-9">
                                <select name="klasifikasi" class="form-control">
                                    <option value="">--Pilih Satu--</option>
                                    <option value="None" <?php echo $datainv->klasifikasi == 'None' ? 'selected' : ''; ?>>None</option>
                                    <option value="High Risk" <?php echo $datainv->klasifikasi == 'High Risk' ? 'selected' : ''; ?>>High Risk</option>
                                    <option value="Medium Risk" <?php echo $datainv->klasifikasi == 'Medium Risk' ? 'selected' : ''; ?>>Medium Risk</option>
                                    <option value="Low to Medium Risk" <?php echo $datainv->klasifikasi == 'Low to Medium Risk' ? 'selected' : ''; ?>>Low to Medium Risk</option>
                                    <option value="Low Risk" <?php echo $datainv->klasifikasi == 'Low Risk' ? 'selected' : ''; ?>>Low Risk</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">Keterangan</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <textarea name="keterangan" class="form-control">{{ $datainv->keterangan }}</textarea>
                            </div>
                            <div class="form-group row mt-3 ml-5">
                                <label class="col-form-label col-lg-3 col-sm-12">* Gambar</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <input type="file" name="gambar" value="{{ $datainv->gambar }}"
                                        class="custom-file form-control">
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">* Dokumen</label>
                        <div class="col-lg- col-md-9 col-sm-12">
                          <div class="col-lg-12 col-md-12 col-sm-12">
                            <input type="file" name="dokumen" value="{{ $datainv->dokumen }}" class="form-control">
                        </div>
                        </div>
                    </div> --}}
                            <div class="form-group row ml-5">
                                <label class="col-form-label col-lg-3 col-sm-12">* SPO Alat</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <input type="file" name="manualbook" value="{{ $datainv->manualbook }}"
                                        class="custom-file form-control">
                                </div>
                            </div>


                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-info">Submit</button>
                                <a href="{{ route('inventaris.index') }}">
                                    <button type="button" class="btn btn-danger">Cancel</button>
                                </a>
                            </div>
                        </div>
        </form>
    </div>
    </div>
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
        });
    </script>
@endpush
