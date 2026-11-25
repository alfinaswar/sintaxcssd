@extends('layouts.app')
@push('title')
Edit Master Alat (KSO)
@endpush
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Edit Master Alat (KSO)
            </h3>
        </div>
    </div>

    <form class="kt-form" action="{{ route('master-alat-kso.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="kt-portlet__body">
            <div class="form-group">
                <label>Nama Alat (KSO) <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama alat KSO" value="{{ old('nama', $data->nama) }}" required>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('master-alat-kso.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
