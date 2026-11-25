@extends('layouts.app')
@push('title')
Edit Master Tipe (KSO)
@endpush
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Edit Master Tipe (KSO)
            </h3>
        </div>
    </div>

    <form class="kt-form" action="{{ route('master-tipe-kso.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="kt-portlet__body">
            <div class="form-group">
                <label>Alat (KSO) <span class="text-danger">*</span></label>
                <select name="master_alat_kso_id" id="master_alat_kso_id" class="form-control" required>
                    <option value="">-- Pilih Alat (KSO) --</option>
                    @foreach ($alats as $alat)
                        <option value="{{ $alat->id }}" {{ old('master_alat_kso_id', $data->master_alat_kso_id) == $alat->id ? 'selected' : '' }}>
                            {{ $alat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Merk (KSO)</label>
                <select name="master_merk_kso_id" id="master_merk_kso_id" class="form-control">
                    <option value="">-- Pilih Merk (KSO) (Opsional) --</option>
                    @foreach ($merks as $merk)
                        <option value="{{ $merk->id }}" {{ old('master_merk_kso_id', $data->master_merk_kso_id) == $merk->id ? 'selected' : '' }}>
                            {{ $merk->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nama Tipe (KSO) <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama tipe KSO" value="{{ old('nama', $data->nama) }}" required>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('master-tipe-kso.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
@push('js')
<script>
    $('#master_alat_kso_id').on('change', function() {
        var alatId = $(this).val();
        var merkSelect = $('#master_merk_kso_id');
        merkSelect.html('<option value="">-- Pilih Merk (KSO) (Opsional) --</option>');
        if (alatId) {
            var url = "{{ route('master-merk-kso.by-alat', ':id') }}".replace(':id', alatId);
            $.get(url, function(data) {
                data.forEach(function(item) {
                    merkSelect.append('<option value="' + item.id + '">' + $('<div>').text(item.nama).html() + '</option>');
                });
            });
        }
    });
</script>
@endpush
