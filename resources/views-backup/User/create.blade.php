@extends('layouts.app')
@push('title')
User
@endpush
@push('sub-title')
Tambah User
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Tambah User
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right" action="{{ route('User.store') }}" method="POST" accept-charset="utf-8">
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
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="noDokumen" class="col-2 col-form-label">Nama</label>
                        <div class="col-10">
                            <input class="form-control" name="name" value="{{ old('name') }}" type="text" placeholder="Nama User" value=""
                                id="noDokumen">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prioritas" class="col-2 col-form-label">role</label>
                        <div class="col-10">
                            <select name="role" class="custom-select form-control" id="prioritas">
                               
                                @foreach ($role as $roles )
                                <option value="{{ $roles->name }}" {{ old('role') == $roles->name ? "selected" :""}}>
                                    {{ $roles->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                       <div class="form-group row">
                        <label for="prioritas" class="col-2 col-form-label">Rumah Sakit</label>
                        <div class="col-10">
                            <select name="kodeRS" class="custom-select form-control" id="prioritas">
                                <option selected>--Pilih RS--</option>
                                @foreach ($rs as $item )
                                <option value="{{ $item->kodeRS }}" {{ old('kodeRs') == $item->kodeRS ? "selected" :""}}>
                                    {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Username</label>
                        <div class="col-10">
                            <input class="form-control" name="username" value="{{ old('username') }}" type="text" placeholder="Username"
                                value="" id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Password</label>
                        <div class="col-10">
                            <input class="form-control" name="password" value="{{ old('password') }}" type="text" placeholder="Password "
                                value="" id="example-text-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-info">Submit</button>
                <a href="{{ route('User.index') }}">
                    <button type="button" class="btn btn-danger">Cancel</button>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection