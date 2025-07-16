@extends('layouts.app')
@push('title')
    Master Nama Set CSSD
@endpush
@push('sub-title')
    Edit Master Nama Set CSSD
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Edit Master Nama Set CSSD
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="formNamaSetCSSD"
            action="{{ route('master-cssd.master-set-item.update', $namaSet->id) }}" method="POST" accept-charset="utf-8">
            @csrf
            @method('PUT')
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label for="nama_set" class="col-2 col-form-label">* Nama Set</label>
                            <div class="col-8">
                                <input class="form-control @error('nama_set') is-invalid @enderror" name="nama_set"
                                    value="{{ old('nama_set', $namaSet->Nama) }}" placeholder="Masukkan Nama Set"
                                    type="text" id="nama_set">
                                @error('nama_set')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ route('master-cssd.master-set-item.index') }}">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection