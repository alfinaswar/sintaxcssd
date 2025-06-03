@extends('layouts.app')
@push('title')
Asset Inventaris
@endpush
@push('sub-title')
Update Asset Inventaris
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Asset Inventaris
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right" id="simpanFrom" action="{{ route('asset-managemen.update',$data->id) }}"
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
                <div class="col-md-6">

                    <div class="form-group row">
                        <label for="nama" class="col-2 col-form-label">Nama Perangkat</label>
                        <div class="col-10">
                            <input class="form-control" name="nama" value="{{ $data->nama }}"
                                placeholder="Nama Perangkat" type="text" value="" id="nama">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="userPengguna" class="col-2 col-form-label">User Pengguna</label>
                        <div class="col-10">
                            <input class="form-control" name="userPengguna" value="{{ $data->userPengguna }}"
                                placeholder="User Pengguna" type="text" value="" id="userPengguna">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="departemen" class="col-2 col-form-label">Departemen</label>
                        <div class=" col-lg-10 col-md-10 col-sm-12">
                            <select class="form-control kt-select2" id="departemen" name="departemen">
                                <option value="{{ $data->departemen }}" selected>{{ $data->departemen }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="keterangan" class="col-2 col-form-label">Keterangan</label>
                        <div class="col-10">
                            <textarea class="form-control" id="keterangan" name="keterangan"
                                rows="3">{{ $data->keterangan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Jenis Perangkat</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
                            <select class="form-control kt-select2" id="jenis" name="jenis">
                                <option value="{{ $data->jenis }}" selected>{{ $data->jenis }}</option>
                                <option value=" PRINTER">PRINTER</option>
                                <option value="WIFI">Wifi</option>
                                <option value="PC">PC</option>
                                <option value="LAPTOP">LAPTOP</option>
                                <option value="All in One">All in One</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">IP Address</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
                            <select class="form-control kt-select2" id="ip-address" name="ipID">
                                <option value="{{ $data->ipID }}" selected>{{ $data->noIP }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Mac Adrress</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <input type='text' class="form-control" value="{{$data->mac }}" name="mac" id="mac"
                                type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">OS</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <input type='text' class="form-control" value="{{ $data->os }}" name="os" id="os"
                                type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Password</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <input type='text' name="password" id="password" value="{{ $data->password }}"
                                class="form-control" type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Pengecekan</label>
                        <div class="kt-checkbox-list col-lg-9 col-md-9 col-sm-12 kt-mt-10">
                            <label class="kt-checkbox kt-checkbox--success">
                                <input type="checkbox" {{ $data->status!=null ?'checked':'' }} value="1" name="status">
                                Selesai
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                <a href="{{ route('asset-managemen.index') }}">
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
        var select_ip = function() {
            $('#ip-address').select2({
                placeholder: "Select IP Address",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("get-ip") }}',
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
        var select_departemen = function() {
            $('#departemen').select2({
                placeholder: "Select departemen",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("get-departemen") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.nama
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        var validasi= function(){
            $("#mac").inputmask({
            "mask": "**:**:**:**:**:**"
        });  
        }
        jQuery(document).ready(function() {
            select_ip();
            select_departemen();
            validasi()
            $('.progress').hide();
        });
</script>
@endpush