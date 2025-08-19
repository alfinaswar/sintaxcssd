@extends('layouts.app')
@push('title')
    Master Supplier CSSD
@endpush
@push('sub-title')
    Edit Supplier
@endpush
@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Edit Master Supplier CSSD
                </h3>
            </div>
        </div>
        <!--begin::Form-->
        <form class="kt-form kt-form--label-right" id="simpanFrom"
            action="{{ route('cssd-master-supplier.update', $data->id) }}" method="POST" accept-charset="utf-8"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="Nama" class="col-3 col-form-label">* Nama Supplier</label>
                            <div class="col-9">
                                <input class="form-control" name="Nama" value="{{ old('Nama', $data->Nama) }}"
                                    placeholder="Nama Supplier" type="text" id="Nama">
                                @if ($errors->has('Nama'))
                                    <span class="text-danger">{{ $errors->first('Nama') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="button" onclick="simpan(event,this)" class="btn btn-info">Update</button>
                    <a href="{{ route('cssd-master-supplier.index') }}">
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
                message: 'Silakan tunggu...'
            });
            $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#simpanFrom").submit();
        }
        jQuery(document).ready(function () {
            $('.progress').hide()
        });
    </script>
@endpush