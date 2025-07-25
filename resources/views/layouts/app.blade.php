<!DOCTYPE html>

<html lang="en">
<!-- begin::Head -->

<head>
    <!--begin::Base Path (base relative path for assets of this page) -->
    <!--end::Base Path -->
    <meta charset="utf-8" />

    <title>Sinta App | @stack('title')</title>
    <meta name="description" content="Updates and statistics">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <!--end::Fonts -->

    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ asset('') }}assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
        type="text/css" />
    <!--end::Page Vendors Styles -->


    <!--begin:: Global Mandatory Vendors -->
    <link href="{{ asset('') }}assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"
        type="text/css" />
    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <link href="{{ asset('') }}assets/vendors/general/tether/dist/css/tether.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/select2/dist/css/select2.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/quill/dist/quill.snow.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/@yaireo/tagify/dist/tagify.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/summernote/dist/summernote.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/dual-listbox/dist/dual-listbox.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet"
        type="text/css" />
    <link
        href="{{ asset('') }}assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet"
        type="text/css" />
    <!--end:: Global Optional Vendors -->
    @stack('css')
    <!--begin::Global Theme Styles(used by all pages) -->

    <link href="{{ asset('') }}assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->

    <link href="{{ asset('') }}assets/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/demo1/skins/brand/dark.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/demo1/skins/aside/dark.css" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('') }}assets/media/logo/icon.png" />
</head>
<!-- end::Head -->

<!-- begin::Body -->

<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">


    <!-- begin:: Page -->
    <!-- begin:: Header Mobile -->

    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="{{ route('masalah.index') }}">
                <img alt="Logo" width="60%" src="{{ asset('') }}assets/media/logo/Logo Putih.png" />
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left"
                id="kt_aside_mobile_toggler"><span></span></button>

            <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>

            <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                    class="flaticon-more"></i></button>
        </div>
    </div>

    <!-- end:: Header Mobile -->

    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            <!-- begin:: Aside -->
            <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>

            <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop"
                id="kt_aside">
                <!-- begin:: Aside -->
                <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
                    <div class="kt-aside__brand-logo">
                        <a href="{{ route('inventaris.index') }}">
                            <img alt="Logo" width="100%" src="{{ asset('') }}assets/media/logo/Logo.png" />
                        </a>
                    </div>

                    <div class="kt-aside__brand-tools">
                        <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                            <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero"
                                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                                        <path
                                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                                    </g>
                                </svg></span>
                            <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero" />
                                        <path
                                            d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                            id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                            transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                    </g>
                                </svg></span>
                        </button>
                        <!--
   <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
   -->
                    </div>
                </div>

                <!-- end:: Aside -->
                <!-- begin:: Aside Menu -->
                <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
                    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1"
                        data-ktmenu-dropdown-timeout="500">
                        <ul class="kt-menu__nav ">
                            <li class="kt-menu__item" aria-haspopup="true"><a href="{{ route('home') }}"
                                    class="kt-menu__link "><span class="kt-menu__link-icon"><i
                                            class="fa fa-home"></i></span><span
                                        class="kt-menu__link-text">Beranda</span></a></li>
                            @can('inventaris')
                                <li class="kt-menu__item @if (request()->is('inventaris'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('inventaris/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true"><a href="{{ route('inventaris.index') }}"
                                        class="kt-menu__link "><span class="kt-menu__link-icon"><i
                                                class="fa fa-boxes"></i></span><span
                                            class="kt-menu__link-text">Inventaris</span></a></li>
                            @endcan
                            @can('cssd-master-item')
                                <li class="kt-menu__item @if (request()->is('master-cssd'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('master-cssd/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true">
                                    <a href="{{ route('master-cssd.cssd-master-item.index') }}" class="kt-menu__link ">
                                        <span class="kt-menu__link-icon">
                                            {{-- Icon alat kesehatan, gunakan icon medical kit --}}
                                            <i class="fa fa-medkit"></i>
                                        </span>
                                        <span class="kt-menu__link-text">Master Item CSSD</span>
                                    </a>
                                </li>
                            @endcan
                            @can('cssd-item-set')
                                <li class="kt-menu__item @if (request()->is('cssd-master-item'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('cssd-item-set/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true">
                                    <a href="{{ route('cssd-item-set.index') }}" class="kt-menu__link ">
                                        <span class="kt-menu__link-icon">
                                            {{-- Icon set alat kesehatan, misal icon puzzle untuk "set" --}}
                                            <i class="fa fa-puzzle-piece"></i>
                                        </span>
                                        <span class="kt-menu__link-text">Item Set</span>
                                    </a>
                                </li>
                            @endcan
                            @can('flipbook')
                                <li class="kt-menu__item @if (request()->is('flipbook'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('flipbook/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true">
                                    <a href="{{ route('flipbook.index') }}" class="kt-menu__link ">
                                        <span class="kt-menu__link-icon">
                                            <i class="fa fa-book-open"></i>
                                        </span>
                                        <span class="kt-menu__link-text">Daftar Flipbook</span>
                                    </a>
                                </li>
                            @endcan
                            @can('')
                                <li class="kt-menu__item @if (request()->is('inventaris'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('inventaris/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true"><a href="{{ route('inventaris.index') }}"
                                        class="kt-menu__link "><span class="kt-menu__link-icon"><i
                                                class="fa fa-boxes"></i></span><span
                                            class="kt-menu__link-text">Inventaris</span></a></li>
                            @endcan

                            @can('kalibrasi')
                                <li class="kt-menu__item @if (request()->is('kalibrasi'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('kalibrasi/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true"><a href="{{ route('kalibrasi.index') }}"
                                        class="kt-menu__link "><span class="kt-menu__link-icon"><i
                                                class="fa fa-balance-scale"></i></span><span
                                            class="kt-menu__link-text">Kalibrasi</span></a></li>
                            @endcan
                            @can('masalah')
                                <li class="kt-menu__item @if (request()->is('masalah'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('masalah/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true"><a href="{{ route('masalah.index') }}"
                                        class="kt-menu__link "><span class="kt-menu__link-icon"><i
                                                class="fa fa-wrench"></i></span><span class="kt-menu__link-text">Riwayat
                                            Perbaikan</span></a></li>
                            @endcan

                            @can('maintenance')

                                <li class="kt-menu__item @if (request()->is('maintanance'))
                                    {{ 'kt-menu__item--active' }}
                                @elseif (request()->is('maintanance/*'))
                                    {{ 'kt-menu__item--active' }}
                                @endif" aria-haspopup="true"><a href="{{ route('maintanance.index') }}"
                                        class="kt-menu__link "><span class="kt-menu__link-icon"><i
                                                class="fa fa-tools"></i></span><span
                                            class="kt-menu__link-text">Maintanance</span></a></li>
                            @endcan

@can('penghapusan-aset')
    <li class="kt-menu__item @if (request()->is('penghapusan-aset'))
        {{ 'kt-menu__item--active' }}
    @elseif (request()->is('penghapusan-aset/*'))
        {{ 'kt-menu__item--active' }}
    @endif" aria-haspopup="true">
        <a href="{{ route('pa.index') }}" class="kt-menu__link ">
            <span class="kt-menu__link-icon">
                <i class="fa fa-trash"></i>
            </span>
            <span class="kt-menu__link-text">Penghapusan Aset</span>
        </a>
    </li>
@endcan
                            @can('laporan')
                                <li class="kt-menu__item  kt-menu__item--submenu @if (request()->segment(1) == 'laporan')
                                    {{ 'kt-menu__item--open kt-menu__item--here' }}
                                @endif  " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a
                                        href="javascript:;" class="kt-menu__link kt-menu__toggle"><span
                                            class="kt-menu__link-icon"><i class="fa fa-file-alt"></i></span><span
                                            class="kt-menu__link-text">Laporan</span><i
                                            class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">

                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                                    class="kt-menu__link"><span class="kt-menu__link-text">Data
                                                        Master</span></span></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'pembelian')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a
                                                    href="{{ route('pembelian.index') }}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Laporan Pembelian</span></a></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'maintenance')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a
                                                    href="{{ route('laporan.maintenance.index') }}"
                                                    class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Laporan Maintenance</span></a></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'maintenance')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a
                                                    href="{{ route('laporan.maintenance.pm') }}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Laporan Preventif Maintenance</span></a>
                                            </li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'item-ruangan')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a
                                                    href="{{ route('laporan.item-ruangan.index') }}"
                                                    class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Laporan Inventaris Ruangan</span></a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                            @endcan

                            @can('data-master')
                                <li class="kt-menu__item  kt-menu__item--submenu @if (request()->segment(1) == 'master')
                                    {{ 'kt-menu__item--open kt-menu__item--here' }}
                                @endif  " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a
                                        href="javascript:;" class="kt-menu__link kt-menu__toggle"><span
                                            class="kt-menu__link-icon"><i class="fa fa-database"></i></span><span
                                            class="kt-menu__link-text">Data Master</span><i
                                            class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">

                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                                    class="kt-menu__link"><span class="kt-menu__link-text">Data
                                                        Master</span></span></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'master-departemen')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a
                                                    href="{{ route('master-departemen.index') }}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Master Departemen</span></a></li>
                                            {{-- <li class="kt-menu__item  @if (request()->segment(2) == 'master-unit')
                                                                        {{ 'kt-menu__item--active' }}
                                                                    @endif" aria-haspopup="true"><a
                                                    href="{{ route('master-unit.index') }}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Master Unit</span></a></li> --}}
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'master-merk')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a
                                                    href="{{ route('master-merk.index') }}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Master Merk</span></a></li>

                                        </ul>
                                    </div>
                                </li>
                            @endcan
@can('cssd-data-master')
    <li class="kt-menu__item  kt-menu__item--submenu @if (request()->segment(1) == 'master-cssd')
        {{ 'kt-menu__item--open kt-menu__item--here' }}
    @endif  " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a
            href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><i
                    class="fa fa-database"></i></span><span class="kt-menu__link-text">Data Master CSSD</span><i
                class="kt-menu__ver-arrow la la-angle-right"></i></a>
        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
            <ul class="kt-menu__subnav">

                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span
                            class="kt-menu__link-text">Data
                            Master CSSD</span></span></li>
                <li class="kt-menu__item  @if (request()->segment(2) == 'master-item-group')
                    {{ 'kt-menu__item--active' }}
                @endif" aria-haspopup="true"><a
                        href="{{ route('master-cssd.item-group.index') }}" class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text">Master Item Group</span></a></li>


                            <li class="kt-menu__item  @if (request()->segment(2) == 'master-nama-set')
                                {{ 'kt-menu__item--active' }}
                            @endif" aria-haspopup="true"><a href="{{ route('master-cssd.master-set-item.index') }}" class="kt-menu__link "><i
                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                        class="kt-menu__link-text">Master Nama Set</span></a></li>
                <li class="kt-menu__item  @if (request()->segment(2) == 'cssd-master-merk')
                    {{ 'kt-menu__item--active' }}
                @endif" aria-haspopup="true"><a href="{{ route('cssd-master-merk.index') }}"
                        class="kt-menu__link "><i
                            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                            class="kt-menu__link-text">Master Merk</span></a></li>
                            <li class="kt-menu__item  @if (request()->segment(2) == 'cssd-master-tipe')
                                {{ 'kt-menu__item--active' }}
                            @endif" aria-haspopup="true"><a href="{{ route('cssd-master-tipe.index') }}"
                                    class="kt-menu__link "><i
                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                        class="kt-menu__link-text">Master Tipe</span></a></li>

                                        <li class="kt-menu__item  @if (request()->segment(2) == 'cssd-master-satuan')
                                {{ 'kt-menu__item--active' }}
                            @endif" aria-haspopup="true"><a href="{{ route('cssd-master-satuan.index') }}"
                                    class="kt-menu__link "><i
                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                        class="kt-menu__link-text">Master Satuan</span></a></li>

            </ul>
        </div>
    </li>
@endcan

                            @can('manajemen-user')
                                <li class="kt-menu__item  kt-menu__item--submenu @if (request()->segment(1) == 'managemenUser')
                                    {{ 'kt-menu__item--open kt-menu__item--here' }}
                                @endif  " aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;"
                                        class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><i
                                                class="fa fa-users"></i></span><span class="kt-menu__link-text">Managemen
                                            User</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                                        <ul class="kt-menu__subnav">
                                            <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span
                                                    class="kt-menu__link"><span class="kt-menu__link-text">Managemen
                                                        User</span></span></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'User')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a href="{{ route('User.index') }}"
                                                    class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">User Account</span></a></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'Role')
                                                {{ 'kt-menu__item--active' }}
                                            @endif" aria-haspopup="true"><a href="{{ route('Role.index') }}"
                                                    class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Role</span></a></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'Permission')
                                            {{ 'kt-menu__item--active' }} @endif" aria-haspopup="true"><a
                                                    href="{{ route('Permission.index') }}" class="kt-menu__link "><i
                                                        class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
                                                        class="kt-menu__link-text">Permission</span></a></li>
                                            <li class="kt-menu__item  @if (request()->segment(2) == 'master-rs')
                                                {{ 'kt-menu__item--active' }} @endif"
        aria-haspopup="true"><a href="{{ route('master.master-rs.index') }}" class="kt-menu__link "><i
            class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span
            class="kt-menu__link-text">Master RS</span></a></li>
    </ul>
    </div>
    </li>
@endcan


</ul>
</div>
</div>
<!-- end:: Aside Menu -->
</div>
<!-- end:: Aside -->

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

    <!-- begin:: Header -->
    {{-- <div class="progress progress-sm">
                    <div class="progress-bar  progress-bar-striped progress-bar-animated" role="progressbar"
                        style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div> --}}
    <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

        <!-- begin:: Header Menu -->
        <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i
                class="la la-close"></i></button>
        <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">

        </div>
        <!-- end:: Header Menu -->
        <!-- begin:: Header Topbar -->
        <div class="kt-header__topbar">
            <div class="kt-header__topbar-item kt-header__topbar-item--user">
                <div class="kt-header__topbar-item kt-header__topbar-item--langs">
                    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                        <div class="kt-header__topbar-user">
                            <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
                            <span
                                class="kt-header__topbar-username kt-hidden-mobile">{{ auth()->user() ? auth()->user()->name : 'Asset' }}</span>

                            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                            <span
                                class="kt-badge kt-badge--username kt-badge--unified-info kt-badge--lg kt-badge--rounded kt-badge--bold">A</span>
                        </div>
                    </div>
                    <div
                        class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
                        <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                            <li class="kt-nav__item {{ request()->is('User/*') ? 'kt-menu__item--active' : '' }}">
                                <a href="{{ route('change-password') }}" class="kt-nav__link">
                                    <span class="kt-nav__link-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                            height="24px" viewBox="0 0 24 24" version="1.1"
                                            class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none"
                                                fill-rule="evenodd">
                                                <rect id="bound" x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M18.6225,9.75 L18.75,9.75 C19.9926407,9.75 21,10.7573593 21,12 C21,13.2426407 19.9926407,14.25 18.75,14.25 L18.6854912,14.249994 C18.4911876,14.250769 18.3158978,14.366855 18.2393549,14.5454486 C18.1556809,14.7351461 18.1942911,14.948087 18.3278301,15.0846699 L18.372535,15.129375 C18.7950334,15.5514036 19.03243,16.1240792 19.03243,16.72125 C19.03243,17.3184208 18.7950334,17.8910964 18.373125,18.312535 C17.9510964,18.7350334 17.3784208,18.97243 16.78125,18.97243 C16.1840792,18.97243 15.6114036,18.7350334 15.1896699,18.3128301 L15.1505513,18.2736469 C15.008087,18.1342911 14.7951461,18.0956809 14.6054486,18.1793549 C14.426855,18.2558978 14.310769,18.4311876 14.31,18.6225 L14.31,18.75 C14.31,19.9926407 13.3026407,21 12.06,21 C10.8173593,21 9.81,19.9926407 9.81,18.75 C9.80552409,18.4999185 9.67898539,18.3229986 9.44717599,18.2361469 C9.26485393,18.1556809 9.05191298,18.1942911 8.91533009,18.3278301 L8.870625,18.372535 C8.44859642,18.7950334 7.87592081,19.03243 7.27875,19.03243 C6.68157919,19.03243 6.10890358,18.7950334 5.68746499,18.373125 C5.26496665,17.9510964 5.02757002,17.3784208 5.02757002,16.78125 C5.02757002,16.1840792 5.26496665,15.6114036 5.68716991,15.1896699 L5.72635306,15.1505513 C5.86570889,15.008087 5.90431906,14.7951461 5.82064513,14.6054486 C5.74410223,14.426855 5.56881236,14.310769 5.3775,14.31 L5.25,14.31 C4.00735931,14.31 3,13.3026407 3,12.06 C3,10.8173593 4.00735931,9.81 5.25,9.81 C5.50008154,9.80552409 5.67700139,9.67898539 5.76385306,9.44717599 C5.84431906,9.26485393 5.80570889,9.05191298 5.67216991,8.91533009 L5.62746499,8.870625 C5.20496665,8.44859642 4.96757002,7.87592081 4.96757002,7.27875 C4.96757002,6.68157919 5.20496665,6.10890358 5.626875,5.68746499 C6.04890358,5.26496665 6.62157919,5.02757002 7.21875,5.02757002 C7.81592081,5.02757002 8.38859642,5.26496665 8.81033009,5.68716991 L8.84944872,5.72635306 C8.99191298,5.86570889 9.20485393,5.90431906 9.38717599,5.82385306 L9.49484664,5.80114977 C9.65041313,5.71688974 9.7492905,5.55401473 9.75,5.3775 L9.75,5.25 C9.75,4.00735931 10.7573593,3 12,3 C13.2426407,3 14.25,4.00735931 14.25,5.25 L14.249994,5.31450877 C14.250769,5.50881236 14.366855,5.68410223 14.552824,5.76385306 C14.7351461,5.84431906 14.948087,5.80570889 15.0846699,5.67216991 L15.129375,5.62746499 C15.5514036,5.20496665 16.1240792,4.96757002 16.72125,4.96757002 C17.3184208,4.96757002 17.8910964,5.20496665 18.312535,5.626875 C18.7350334,6.04890358 18.97243,6.62157919 18.97243,7.21875 C18.97243,7.81592081 18.7350334,8.38859642 18.3128301,8.81033009 L18.2736469,8.84944872 C18.1342911,8.99191298 18.0956809,9.20485393 18.1761469,9.38717599 L18.1988502,9.49484664 C18.2831103,9.65041313 18.4459853,9.7492905 18.6225,9.75 Z"
                                                    id="Combined-Shape" fill="#000000" fill-rule="nonzero"
                                                    opacity="0.3" />
                                                <path
                                                    d="M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z"
                                                    id="Path" fill="#000000" />
                                            </g>
                                        </svg></span>
                                    <span class="kt-nav__link-text">Change Password</span>
                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"
                                    class="kt-nav__link">
                                    <span class="kt-nav__link-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                            height="24px" viewBox="0 0 24 24" version="1.1"
                                            class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none"
                                                fill-rule="evenodd">
                                                <rect id="bound" x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M10.9,2 C11.4522847,2 11.9,2.44771525 11.9,3 C11.9,3.55228475 11.4522847,4 10.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,16 C20,15.4477153 20.4477153,15 21,15 C21.5522847,15 22,15.4477153 22,16 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L10.9,2 Z"
                                                    id="Path-57" fill="#000000" fill-rule="nonzero"
                                                    opacity="0.3" />
                                                <path
                                                    d="M24.0690576,13.8973499 C24.0690576,13.1346331 24.2324969,10.1246259 21.8580869,7.73659596 C20.2600137,6.12944276 17.8683518,5.85068794 15.0081639,5.72356847 L15.0081639,1.83791555 C15.0081639,1.42370199 14.6723775,1.08791555 14.2581639,1.08791555 C14.0718537,1.08791555 13.892213,1.15726043 13.7542266,1.28244533 L7.24606818,7.18681951 C6.93929045,7.46513642 6.9162184,7.93944934 7.1945353,8.24622707 C7.20914339,8.26232899 7.22444472,8.27778811 7.24039592,8.29256062 L13.7485543,14.3198102 C14.0524605,14.6012598 14.5269852,14.5830551 14.8084348,14.2791489 C14.9368329,14.140506 15.0081639,13.9585047 15.0081639,13.7695393 L15.0081639,9.90761477 C16.8241562,9.95755456 18.1177196,10.0730665 19.2929978,10.4469645 C20.9778605,10.9829796 22.2816185,12.4994368 23.2042718,14.996336 L23.2043032,14.9963244 C23.313119,15.2908036 23.5938372,15.4863432 23.9077781,15.4863432 L24.0735976,15.4863432 C24.0735976,15.0278051 24.0690576,14.3014082 24.0690576,13.8973499 Z"
                                                    id="Shape" fill="#000000" fill-rule="nonzero"
                                                    transform="translate(15.536799, 8.287129) scale(-1, 1) translate(-15.536799, -8.287129) " />
                                            </g>
                                        </svg></span>
                                    <form action="{{ route('logout') }}" id="logout-form" method="POST">
                                        @csrf
                                    </form>
                                    <span class="kt-nav__link-text">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

        <div class="kt-subheader  kt-grid__item" id="kt_subheader">
            <div class="kt-container  kt-container--fluid ">
                <div class="kt-subheader__main">
                    <h3 class="kt-subheader__title">@stack('title')</h3>
                    <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                    <span class="kt-subheader__desc">#@stack('sub-title')</span>
                </div>

            </div>
        </div>

        <!-- end:: Content Head -->
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <!--Begin::Dashboard 1-->
            @yield('content')
        </div>
    </div>
    <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-footer__copyright">
                2024&nbsp;&copy;&nbsp;<span class="kt-link">Digital Indonesia Hebat</span>
            </div>
        </div>
    </div>

</div>
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="fa fa-arrow-up"></i>
</div>
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>
<!-- end::Global Config -->

<!--begin:: Global Mandatory Vendors -->
<script src="{{ asset('') }}assets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="{{ asset('') }}assets/vendors/general/jquery-form/dist/jquery.form.min.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-datepicker.init.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-timepicker.init.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js"
    type="text/javascript"></script>
<script
    src="{{ asset('') }}assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-switch.init.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/select2/dist/js/select2.full.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/ion-rangeslider/js/ion.rangeSlider.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/typeahead.js/dist/typeahead.bundle.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/handlebars/dist/handlebars.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/nouislider/distribute/nouislider.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/owl.carousel/dist/owl.carousel.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/autosize/dist/autosize.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/clipboard/dist/clipboard.min.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/dropzone/dist/dropzone.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/dropzone.init.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/quill/dist/quill.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/@yaireo/tagify/dist/tagify.polyfills.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/@yaireo/tagify/dist/tagify.min.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/markdown/lib/markdown.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-markdown.init.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/bootstrap-notify/bootstrap-notify.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/bootstrap-notify.init.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/jquery-validation/dist/jquery.validate.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/jquery-validation/dist/additional-methods.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/jquery-validation.init.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/dual-listbox/dist/dual-listbox.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/raphael/raphael.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/morris.js/morris.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
<script
    src="{{ asset('') }}assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js"
    type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/custom/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
<script src="{{ asset('') }}assets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript">
</script>
<script src="{{ asset('') }}assets/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>
<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle(used by all pages) -->

<script src="{{ asset('') }}assets/js/demo1/scripts.bundle.js" type="text/javascript"></script>
<!--end::Global Theme Bundle -->
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
@stack('js')
@stack('after-js')
</body>
<!-- end::Body -->

</html>
