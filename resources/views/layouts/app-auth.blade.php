{{-- <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{asset('')}}assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Legal') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html> --}}


<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!-- begin::Head -->

<head>
    <!--begin::Base Path (base relative path for assets of this page) -->
    <!--end::Base Path -->
    <meta charset="utf-8" />

    <title>Asset | Login</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <!--end::Fonts -->


    <!--begin::Page Custom Styles(used by this page) -->
    <link href="{{asset('')}}assets/css/demo1/pages/login/login-6.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles -->

    <!--begin:: Global Mandatory Vendors -->
    <link href="{{asset('')}}assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"
        type="text/css" />
    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->

    <link href="{{asset('')}}assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/vendors/general/dual-listbox/dist/dual-listbox.css" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('')}}assets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('')}}assets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('')}}assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet"
        type="text/css" />
    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->

    <link href="{{asset('')}}assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->

    <link href="{{asset('')}}assets/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/css/demo1/skins/brand/dark.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/css/demo1/skins/aside/dark.css" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->

    <link rel="shortcut icon" href="{{asset('')}}assets/media/logo/icon.ico" />
</head>
<!-- end::Head -->

<!-- begin::Body -->

<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">


    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
            <div
                class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">

                <div
                    class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
                    <div class="kt-login__wrapper">
                        <div class="kt-login__container">
                            <div class="kt-login__body">
                                <div class="kt-login__logo">
                                    <a href="{{ route('login') }}">
                                        <img width="30%" src="{{asset('')}}assets/media/logo/Aawalbros.jpg">
                                    </a>
                                </div>
                                @yield('content')
                            </div>
                        </div>
                        {{-- <div class="kt-login__account">
                            <span class="kt-login__account-msg">
                                Don't have an account yet ?
                            </span>&nbsp;&nbsp;
                            <a href="javascript:;" id="kt_login_signup" class="kt-login__account-link">Sign Up!</a>
                        </div> --}}
                    </div>
                </div>

                <div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content"
                    style="background-image: url({{asset('')}}assets/media/logo/bg-login3.png);">
                    <div class="kt-login__section">
                        <div class="kt-login__block">
                            {{-- <h3 class="kt-section__title">Selamat Datang</h3> --}}
                            <div class="kt-login__desc">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Page -->


    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
        var KTAppOptions = {"colors":{"state":{"brand":"#5d78ff","dark":"#282a3c","light":"#ffffff","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
    </script>
    <!-- end::Global Config -->

    <!--begin:: Global Mandatory Vendors -->
    <script src="{{asset('')}}assets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript">
    </script>
    <script src="{{asset('')}}assets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript">
    </script>
    <script src="{{asset('')}}assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js"
        type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
    <!--end:: Global Mandatory Vendors -->

    <!--begin:: Global Optional Vendors -->
    <script src="{{asset('')}}assets/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/dual-listbox/dist/dual-listbox.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/raphael/raphael.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/morris.js/morris.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
    <script
        src="{{asset('')}}assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js"
        type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript">
    </script>
    <script src="{{asset('')}}assets/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript">
    </script>
    <script src="{{asset('')}}assets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript">
    </script>
    <script src="{{asset('')}}assets/vendors/custom/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript">
    </script>
    <script src="{{asset('')}}assets/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript"></script>
    <script src="{{asset('')}}assets/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>
    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Bundle(used by all pages) -->

    <script src="{{asset('')}}assets/js/demo1/scripts.bundle.js" type="text/javascript"></script>
    <!--end::Global Theme Bundle -->

    <script>
        toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
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
     @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Berhasil");
        @endif
    </script>
    <!--begin::Page Scripts(used by this page) -->
    {{-- <script src="{{asset('')}}assets/js/demo1/pages/login/login-general.js" type="text/javascript"></script> --}}
    <!--end::Page Scripts -->
</body>
<!-- end::Body -->

</html>
