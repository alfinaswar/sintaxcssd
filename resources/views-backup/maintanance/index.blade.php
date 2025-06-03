@extends('layouts.app')
@push('title')
    Preventif Maintenance
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                   Preventif Maintenance Alat
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">

                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <form id="form-maintanance" action="{{ route('maintanance.store') }}" method="POST" accept-charset="utf-8"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama" class="col-3 col-form-label">* Nama Alat</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control kt-select2" id="nama" name="nama">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label">* Bulan</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control kt-select2" id="bulan" name="bulan">
                                    <option value=" " selected>--Select Bulan--</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">Status</label>
                           <div class=" col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control kt-select2" id="status" name="status">
                                    <option value=" " selected>--Select Status--</option>
                                    <option value="1">Sudah Maintanance</option>
                                    <option value="2">Belum Maintanance</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">Keterangan</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="kt-align-right">
                            <button type="button" onclick="simpan(event,this)" class="btn btn-brand btn-hover-primari"> <i
                                    class="la la-save"></i>Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Bulan</th>
                        <th>Status`</th>
                        <th>Nama RS</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
@endsection
@push('css')
    <link href="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet"
        type="text/css" />
@endpush
@push('js')
    <script src="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <script>
        @if (Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "Berhasil");
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.warning(`{{ $error }}`, "Gagal");
            @endforeach
        @endif
    </script>
    <script>
        var dataTable = function() {
            var table = $('#kt_table_1');
            table.DataTable({
                responsive: true,
                serverSide: true,
                bDestroy: true,
                processing: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                ajax: {
                    url: "{{ route('maintanance.index') }}",
                    data: function(d) {
                        d.filter_tanggal = $('#filter_tanggal').val(),
                        d.filter_status = $('#filter_status').val(),
                            d.filter_rs = $('#filter_rs').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama    '
                    },
                    {
                        data: 'bulan',
                        name: 'bulan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'namars',
                        name: 'namars'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            })
        };

        var time = function() {
            $('#kt_timepicker_2').timepicker({
                minuteStep: 1,
                defaultTime: '',
                showSeconds: true,
                showMeridian: false,
                snapToStep: true
            });
        }

        var select_item = function() {
            $('#nama').select2({
                placeholder: "--Select Alat--",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('kalibrasi.get-item') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item, key) {
                                return {
                                    text: key + ' - ' + item,
                                    id: key + ',' + item
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        var simpan = function(e, id) {
            e.preventDefault();
            KTApp.block('.kt-portlet', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Please wait...'
            });
            $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#form-maintanance").submit();
        }
        //tes tes tes

   
        //asdasd
        jQuery(document).ready(function() {
            dataTable()
            time()
            select_item()
            $('.progress').hide()
        });
        $('#filter_tanggal,#filter_rs,#filter_status').change(function() {
            var table = $('#kt_table_1').DataTable();
            table.draw();
        });
    
  </script>
@endpush
