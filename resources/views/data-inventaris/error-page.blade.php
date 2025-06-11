@extends('layouts.app')

@section('content')
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v3"
            style="background-image: url({{asset('assets/media/error/bg3.jpg')}});">
            <div class="kt-error_container">
                <span class="kt-error_number">
                    <h1>410</h1>
                </span>
                <p class="kt-error_title kt-font-light">
                    Data Tidak Ditemukan
                </p>
                <p class="kt-error_subtitle">
                    Maaf, data yang Anda cari sudah tidak tersedia.
                </p>
                <p class="kt-error_description">
                    Data yang Anda akses telah dihapus dari sistem,<br>
                    atau mungkin sudah tidak aktif lagi.
                </p>
            </div>
        </div>
    </div>
@endsection