<!DOCTYPE html>
@if(!request()->secure())
    <script>
        // Handle berbagai kemungkinan URL format
        if (location.protocol !== 'https:') {
            var currentUrl = window.location.href;
            var httpsUrl;

            if (currentUrl.startsWith('http://')) {
                // Jika ada http://, ganti ke https://
                httpsUrl = currentUrl.replace('http://', 'https://');
            } else if (currentUrl.startsWith('//')) {
                // Jika format //domain.com, tambah https:
                httpsUrl = 'https:' + currentUrl;
            } else if (!currentUrl.startsWith('http')) {
                // Jika cuma domain tanpa protocol, tambah https://
                httpsUrl = 'https://' + currentUrl;
            }

            if (httpsUrl) {
                window.location.href = httpsUrl;
            }
        }
    </script>
@endif
<html lang="en">
<!-- begin::Head -->

<head><!--begin::Base Path (base relative path for assets of this page) -->
    <base href="../../../../"><!--end::Base Path -->
    <meta charset="utf-8" />

    <title>Aset | Informasi Alat</title>
    <meta name="description" content="View project page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <!--end::Fonts -->



    <!--begin:: Global Mandatory Vendors -->
    <link href="{{ asset('') }}assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet"
        type="text/css" />
    <!--end:: Global Mandatory Vendors -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!--begin:: Global Optional Vendors -->
    <link href="{{ asset('') }}assets/vendors/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/quill/dist/quill.snow.css" rel="stylesheet" type="text/css" />
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
    <link href="{{ asset('') }}assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet"
        type="text/css" />
    <!--end:: Global Optional Vendors -->

    <!--begin::Global Theme Styles(used by all pages) -->

    <link href="{{ asset('') }}assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->

    <link href="{{ asset('') }}assets/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/demo1/skins/brand/dark.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/css/demo1/skins/aside/dark.css" rel="stylesheet" type="text/css" />
    <!--end::Layout Skins -->

    <link rel="shortcut icon" href="{{ asset('') }}assets/media/logos/favicon.ico" />
</head>
