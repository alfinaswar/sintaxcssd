@extends('layouts.app')
@push('title')
Dashboard
@endpush
@push('sub-title')
Upload Dokumen
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Upload Dokumen
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right" id="simpanFrom" action="{{ route('index.store') }}" method="POST"
        accept-charset="utf-8" enctype="multipart/form-data">
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
                <input class="form-control" name="requester" hidden type="text" value="{{ auth()->user()->name }}">
                <input class="form-control" name="user_at" hidden type="text" value="{{ auth()->user()->username }}">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="noDokumen" class="col-2 col-form-label">Nomor Dokumen</label>
                        <div class="col-10">
                            <input class="form-control" name="dokumen" value="{{ old('dokumen') }}"
                                placeholder="No Dokumen/ No SK" type="text" value="" id="noDokumen">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Nama Dokumen</label>
                        <div class="col-10">
                            <input class="form-control" name="namaDokumen" value="{{ old('namaDokumen') }}" type="text"
                                value="" id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prioritas" class="col-2 col-form-label">Prioritis</label>
                        <div class="col-10">
                            <select name="priority" class="custom-select form-control" id="prioritas">
                                <option selected>Pilih Prioritas</option>
                                <option value="Normal" @if (old('priority')=="Normal" ) {{ 'selected' }} @endif>Normal
                                </option>
                                <option value="Hight" @if (old('priority')=="Hight" ) {{ 'selected' }} @endif>Hight
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">File Browser</label>
                        <div></div>
                        <div class="custom-file col-10">
                            <input type="file" name="files[]" multiple class="custom-file-input" id="customFile">
                            <span class="custom-file-label" for="customFile">Choose file</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Jenis File</label>
                        <div class="col-10">
                            <input class="form-control" value="{{ old('jenisFile') }}" placeholder="PKS/IZIN/dst"
                                name="jenisFile" type="text" value="" id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Keterangan</label>
                        <div class="col-10">
                            <textarea class="form-control" name="keterangan" id="exampleTextarea"
                                rows="5">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                <a href="{{ route('index.index') }}">
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
            $("#simpanFrom").submit();
        }
</script>
@endpush