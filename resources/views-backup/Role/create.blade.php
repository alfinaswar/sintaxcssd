@extends('layouts.app')
@push('title')
Role
@endpush
@push('sub-title')
Tambah Role Baru
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Role Bagian
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right" action="{{ route('Role.store') }}" method="POST" accept-charset="utf-8">
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
                        <label for="noDokumen" class="col-2 col-form-label">Namaw Bagian</label>
                        <div class="col-10">
                            <input class="form-control" name="name" type="text" value="{{ old("name") }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Pilih Permission</label>
                       
                        <div class="kt-checkbox-list">
                             @foreach ($permission as $permissions)
                            <label class="kt-checkbox kt-checkbox--brand">
                                <input type="checkbox" value="{{ $permissions->name }}" value="{{ old("permission") }}"
                                    name="permission[]">{{ $permissions->name }}
                                <span></span>
                                 
                            </label>
                             @endforeach
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-info">Submit</button>
                <a href="{{ route('Role.index') }}">
                    <button type="button" class="btn btn-danger">Cancel</button>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection