@extends('layouts.app')
@push('title')
Master RS
@endpush
@push('sub-title')
Update rs
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Master RS
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right" id="simpanFrom"
        action="{{ route('master-rs.update',$data->id) }}" method="POST" accept-charset="utf-8"
        enctype="multipart/form-data">
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
                        <label for="nama" class="col-2 col-form-label">Nama rs</label>
                        <div class="col-10">
                            <input class="form-control" name="nama" value="{{ $data->nama }}"
                                placeholder="Nama Perangkat" type="text" value="" id="nama">
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="kodeRs" class="col-2 col-form-label">Kode RS</label>
                        <div class="col-10">
                            <?php if ($data->kodeRS == 'K') {
    $namars="RS Awal Bros Ayani";
} elseif ($data->kodeRS == 'I') {
    $namars="RS Awal Bros Panam";
} elseif ($data->kodeRS == 'B') {
    $namars="RS Awal Bros Batam";
} elseif ($data->kodeRS == 'A') {
    $namars="RS Awal Bros Sudirman";
} elseif ($data->kodeRS == 'G') {
    $namars="RS Awal Bros Ujung Batu";
} elseif ($data->kodeRS == 'S') {
    $namars="RS Awal Bros Bagan Batu";
} elseif ($data->kodeRS == 'B') {
    $namars="RS Awal Bros Bonatia";
} elseif ($data->kodeRS == 'D') {
    $namars="RS Awal Bros Dumai";
} ?>
                            <input class="form-control" name="kodeRs" value="{{ $namars }}"
                                placeholder="Nama Perangkat" type="text" value="" id="kodeRs" read>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                <a href="{{ route('master-rs.index') }}">
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
        jQuery(document).ready(function() {
            
            $('.progress').hide();
        });
</script>
@endpush