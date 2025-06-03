@extends('layouts.header')
<body
    class="kt-app__aside--left kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <div class="kt-portlet ">
            <div class="kt-portlet__body">
                <div class="kt-widget kt-widget--user-profile-3">
            <div class="kt-widget__top">
               <div class="kt-widget__media kt-hidden-">
                            <?php if ($data_alat->gambar == null) {
                                $gambar = 'image_not_found.png';
                            } else {
                                $gambar = $data_alat->gambar;
                            }
                            ?>
                            <img src="{{ asset('public/storage/gambar/'.$gambar) }}" />
                        </div>
                <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                    JM
                </div>
                <div class="kt-widget__content">
                    <div class="kt-widget__head">
                        <a href="#" class="kt-widget__username kt-hidden">
                            Jason Muller
                            <i class="flaticon2-correct"></i>
                        </a>

                        <a href="#" class="kt-widget__title">{{$data_alat->nama}}</a>
                    </div>
                    <div class="kt-widget__info">
                        <div class="kt-widget__desc">

                                    {{ $data_alat->no_inventaris }}
                                    <br>{{ $data_alat->no_sn }}
                                    <br> {{ $data_alat->pengguna }}

                        </div>


                        <div class="kt-widget__stats d-flex align-items-left flex-fill">
                            <div class="kt-widget__item">
                                <span class="kt-widget__date">
                                   Tanggal Kalibrasi
                                </span>
                                <div class="kt-widget__label">

                                    <span class="btn btn-label-brand btn-sm btn-bold btn-upper"><?php if (isset($data_alat->tgl_kalibrasi)) {
    echo date('d/m/Y', strtotime($data_alat->tgl_kalibrasi));
} else {
    echo 'Tidak Dikalibrasi';
} ?></span>
                                </div>
                            </div>

                            <div class="kt-widget__item">
                                <span class="kt-widget__date">
                                    Expire
                                </span>
                                <div class="kt-widget__label">
                                    <span class="btn btn-label-danger btn-sm btn-bold btn-upper">@if (isset($data_alat->tgl_expire))
                                    {{date('d/m/Y', strtotime($data_alat->tgl_expire))}}
                                    @else
                                        Tidak Dikalibrasi
                                    @endif</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
         <div class="kt-widget__bottom">
                <div class="kt-widget__item">
                    <div class="kt-widget__icon">
                        <i class="flaticon2-hospital"></i>
                    </div>
                    <div class="kt-widget__details">
                        <span class="kt-widget__title">Awalbros</span>
                        <span class="kt-widget__title">{{$data_alat->rumahsakit}}</span>
                    </div>
                </div>

                <div class="kt-widget__item">
                    <div class="kt-widget__icon">
                        <i class="fa flaticon-app"></i>
                    </div>
                    <div class="kt-widget__details">
                        <span class="kt-widget__title">Departemen</span>
                        <span class="kt-widget__value">{{$data_alat->departemen}}</span>
                    </div>
                </div>

                <div class="kt-widget__item">
                    <div class="kt-widget__icon">
                        <i class="flaticon-calendar-1"></i>
                    </div>
                    <div class="kt-widget__details">
                         <span class="kt-widget__title">Beli</span>
                        <span class="kt-widget__value"><?= date('d/m/Y', strtotime($data_alat->tanggal_beli)); ?></span>
                    </div>
                </div>

                <div class="kt-widget__item">
                    <div class="kt-widget__icon">
                        <i class="flaticon-file-2"></i>
                    </div>
                    <div class="kt-widget__details">
                        <span class="kt-widget__title">@if (isset($data_alat->tgl_kalibrasi))
                                    {!!'<a href="' . Storage::url('public/dokumen/') . $data_alat->dokumen . '" target="_blank">Lihat Dokumen</a>'!!}
                                    @else
                                        Tidak Dikalibrasi
                                    @endif</span>
                        {{-- <span class="kt-widget__value">{!!$file!!}</span> --}}
                    </div>
                </div>

                {{-- <div class="kt-widget__item">
                    <div class="kt-widget__icon">
                        <i class="flaticon-chat-1"></i>
                    </div>
                    <div class="kt-widget__details">
                        <span class="kt-widget__title">Maintenance</span>
                        <a class="kt-widget__value kt-font-brand"></a>
                    </div>
                </div> --}}


            </div>
                </div>
        <!--end:: Portlet-->
            </div>
        </div>
        <div class="row">

            <div class="col-xl-6">
                <!--begin:: Widgets/Tasks -->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Riwayat Masalah
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                @if (count($detail_masalah) > 0)
    @foreach ($detail_masalah as $masalah_detail)
                                    <div class="kt-widget2">

                                        <div class="kt-widget__label">
                                            <span class="btn btn-label-brand btn-sm btn-bold btn-upper"
                                                style="font-size: 12px;">{{ $masalah_detail->created_at }}</span>
                                        </div>
                                        <div class="kt-widget2__item kt-widget2__item--primary">
                                            <div class="kt-widget2__checkbox">

                                            </div>
                                            <div class="kt-widget2__info">
                                                <span class="kt-widget2__title" style="font-size: 12px;">
                                                    {{ $masalah_detail->kasus }}
                                                </span>
                                                <span class="kt-widget2__username">
                                                    {{ $masalah_detail->prioritas }}
                                                </span>
                                                <span class="kt-widget2__username">
                                                    {{ $masalah_detail->tindakan }}
                                                </span>
                                            </div>
                                            <div class="kt-widget2__actions">
                                                <span class="btn btn-clean btn-sm btn-icon btn-icon-md"
                                                    data-toggle="dropdown">
                                                    <i class="flaticon-more-1"></i>
                                                </span>

                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                               @else
    <div class="alert alert-warning" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text"><strong>Whoops!, Tidak ada data yang ditemukan</strong></div>
                        </div>
@endif
                            </div>


                        </div>


                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <!--begin:: Widgets/Tasks -->
                <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Preventif Maintenance
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="kt_widget2_tab1_content">
                                @if (count($data_mtnc) > 0)
    @foreach ($data_mtnc as $data)
                                    <div class="kt-widget2">

                                        <div class="kt-widget__label">
                                            <span class="btn btn-label-brand btn-sm btn-bold btn-upper"
                                                style="font-size: 12px;">{{ date("F", mktime(0, 0, 0, $data->bulan, 10)) }}</span>
                                        </div>
                                        <div class="kt-widget2__item kt-widget2__item--primary">
                                            <div class="kt-widget2__checkbox">
<label class="kt-checkbox kt-checkbox--solid kt-checkbox--single">
							<input type="checkbox" checked readonly>
							<span></span>
							</label>
                                            </div>
                                            <div class="kt-widget2__info">
                                                <span class="kt-widget2__title" style="font-size: 12px;">

                                                </span>

                                                <span class="kt-widget2__username">
                                                    {{ $data->keterangan }}
                                                </span>
                                            </div>
                                            <div class="kt-widget2__actions">
                                                <span class="btn btn-clean btn-sm btn-icon btn-icon-md"
                                                    data-toggle="dropdown">
                                                    <i class="flaticon-more-1"></i>
                                                </span>

                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                               @else
    <div class="alert alert-warning" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text"><strong>Whoops!, Tidak ada data yang ditemukan</strong></div>
                        </div>
@endif
                            </div>


                        </div>


                    </div>
                </div>
            </div>
            <!--end:: Widgets/Tasks -->
        </div>
    </div>

    </div>
    <!-- end:: Content --> </div>

    <!-- begin:: Footer -->
    <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-footer__copyright">
                2023&nbsp;&copy;&nbsp;<a href="#" target="" class="kt-link">Digital Indonesia Hebat</a>
            </div>

        </div>
    </div>
    <!-- end:: Footer --> </div>
    </div>
    </div>

    <!-- end:: Page -->










    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>
    <!-- end::Scrolltop -->

    <!-- begin::Demo Panel -->



    <!--Begin:: Chat-->

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


    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ asset('') }}assets/js/demo1/pages/dashboard.js" type="text/javascript"></script>
    <!--end::Page Scripts -->
</body>
<!-- end::Body -->

</html>
