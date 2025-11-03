@extends('layouts.app')
@push('title')
    Update KSO Alat
@endpush
@push('sub-title')
    Update KSO Alat (Multi Kode Alat)
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Update KSO Alat
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="formKsoUpdate" action="{{ route('kso.update-alat') }}" method="POST"
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
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="kode_item" class="col-3 col-form-label">* Kode Alat</label>
                            <div class="col-9">
                                <input class="form-control" name="kode_item" id="kode_item" type="text" required
                                    placeholder="Masukkan kode alat, pisahkan dengan koma. Contoh: IN-000112, IN-000113, IN-000114"
                                    value="{{ old('kode_item') }}">
                                <small class="form-text text-muted">
                                    Masukkan satu kode alat per baris. Semua kode alat yang diinput akan diupdate sekaligus.
                                </small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dokumen_kso" class="col-3 col-form-label">* Dokumen KSO</label>
                            <div class="col-9">
                                <input class="form-control" name="dokumen_kso" type="file" id="dokumen_kso" required>
                                <small class="form-text text-muted">
                                    Upload satu dokumen KSO (pdf/dokumen terkait).
                                </small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="keterangan" class="col-3 col-form-label">Keterangan</label>
                            <div class="col-9">
                                <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-info">Submit</button>
                    <a href="{{ url()->previous() }}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
