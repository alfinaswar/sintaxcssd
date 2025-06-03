@extends('layouts.app')
@push('title')
Master Unit
@endpush
@push('sub-title')
Tambah Unit
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Master Unit
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right" id="simpanFrom" action="{{ route('master-unit.store') }}"
        method="POST" accept-charset="utf-8" enctype="multipart/form-data">
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
                        <label for="nama" class="col-3 col-form-label">* Nama Departemen</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
<select class="form-control kt-select2" id="departemen" name="departemen">
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">* Nama Unit</label>
                        <div class="col-9">
                            <input class="form-control" name="namaUnit" value="{{ old('namaUnit') }}"
                                placeholder="Nama Unit" type="text" id="namaUnit">
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                <a href="{{ route('master-unit.index') }}">
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

            var select_departemen = function() {
            $('#departemen').select2({
                placeholder: "Select departemen",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("master.get-departemen") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        jQuery(document).ready(function() {
            select_departemen()
            $('.progress').hide()
        });
</script>
@endpush
