@extends('layouts.app')
@push('title')
Masalah
@endpush
@push('sub-title')
Detail Masalah
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Detail Masalah
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right">
        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Kode Masalah</label>
                        <div class="col-9">
                            <input class="form-control" type="text" readonly value="{{ $data->kode_masalah }}"
                                id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Nama Perangkat</label>
                        <div class="col-9">
                            <input class="form-control" type="text" readonly value="{{ $data->nama_perangkat }}"
                                id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Jenis Masalah</label>
                        <div class="col-9">
                            <input class="form-control" type="text" readonly value="{{ $data->jenis }}"
                                id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Tindakan</label>
                        <div class="col-9">
                            <input class="form-control" type="text" readonly value="{{ $data->tindakan }}"
                                id="example-text-input">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Jumlah Masalah</label>
                        <div class="col-9">
                            <input class="form-control" type="text" readonly value="{{ $data->jumlah_masalah }}"
                                id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Lama Pengerjaan</label>
                        <div class="col-9">
                            <input class="form-control" type="text" readonly value="{{ $data->waktu_pengerjaan }}"
                                id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Keterangan</label>
                        <div class="col-9">
                            <textarea class="form-control" readonly id="keterangan" name="keterangan"
                                rows="3">{{ $data->keterangan }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions kt-align-center">
                <a href="{{ route('masalah.index') }}"><button type="button" class="btn btn-danger">Back</button></a>
            </div>
        </div>
    </form>
</div>
@endsection
@push('js')
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
    jQuery(document).ready(function() {
            $('.progress').hide()
        });
</script>
@endpush