@extends('layouts.app')
@push('title')
User
@endpush
@push('sub-title')
Update User
@endpush
@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Upadate Data User
            </h3>
        </div>
    </div>
    <!--begin::Form-->
    <form class="kt-form kt-form--label-right" action="{{ route('User.update',$masalah->id) }}" method="POST"
        accept-charset="utf-8">
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
                        <label for="noDokumen" class="col-2 col-form-label">Tanggal</label>
                        <div class="col-10">
                            <input class="form-control" name="name" value="{{ $masalah->name }}" type="date"
                                id="noDokumen">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="prioritas" class="col-2 col-form-label">Bagian</label>
                        <div class="col-10">
                            <select name="bagian" class="custom-select form-control" id="prioritas">
                                <option value="{{ $masalah->getRoleNames()->first() }}" selected>
                                    {{ $masalah->getRoleNames()->first() }}</option>
                                @foreach ($role as$roles )
                                <option value="{{ $roles->name }}" {{ old('bagian') == $roles->name ? "selected" :""}}>
                                    {{ $roles->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Nama Rumah Sakit</label>
                        <div class="col-10">
                            <input class="form-control" name="rs" value="{{ $masalah->rs }}" type="text"
                                id="example-text-input">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Username</label>
                        <div class="col-10">
                            <input class="form-control" name="username" value="{{ $masalah->username }}" type="text"
                                id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Password</label>
                        <div class="col-10">
                            <input class="form-control" name="password"
                                placeholder="Kosongkan jika tidak mengganti password" type="text"
                                id="example-text-input">
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