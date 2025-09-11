@extends('layouts.app')
@push('title')
    Manualbook
@endpush
@push('sub-title')
    Edit Manualbook
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Edit Manualbook
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="updateForm" action="{{ route('manualbook.update', $data->id) }}"
            method="POST" accept-charset="utf-8" enctype="multipart/form-data">
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
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="nama" class="col-3 col-form-label">* Nama</label>
                            <div class="col-9">
                                <input class="form-control" name="Nama" value="{{ old('Nama', $data->Nama) }}"
                                    placeholder="Nama Alat" type="text" id="nama">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dokumen" class="col-3 col-form-label">* Dokumen</label>
                            <div class="col-9">
                                <input class="form-control" name="Dokumen" type="file" id="dokumen">
                                @if($data->Dokumen)
                                    <small>
                                        <a href="{{ asset('storage/manual-book/' . $data->Dokumen) }}" target="_blank">Lihat
                                            Dokumen Lama</a>
                                    </small>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="keterangan" class="col-3 col-form-label">Keterangan</label>
                            <div class="col-9">
                                <textarea class="form-control" name="Keterangan" id="keterangan"
                                    placeholder="Keterangan">{{ old('Keterangan', $data->Keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="button" onclick="updateManualbook(event,this)" class="btn btn-info">Update</button>
                    <a href="{{ route('manualbook.index') }}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
    <script>
        function updateManualbook(e, id) {
            e.preventDefault();
            KTApp.block('.kt-portlet', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Mohon tunggu...'
            });
            $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#updateForm").submit();
        }
        jQuery(document).ready(function () {
            $('.progress').hide()
        });
    </script>
@endpush