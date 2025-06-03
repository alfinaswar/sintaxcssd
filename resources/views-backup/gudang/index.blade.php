@extends('layouts.app')
@push('title')
    Masalah
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Laporan Gudang
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">

                    </div>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <form method="GET" action="{{ route('pembelian.excel_pembelian') }}" id="cetak-laporan">

                <div class="row col-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="UserGroupID" class="col-form-label">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama" class="col-form-label">Sampai</label>
                            <input type="date" name="tgl_akhir" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <br><br>
                            <input type="submit" name="cetak" id="cetak" class="btn btn-info btn-md" value="Cetak Laporan">
                        </div>
                    </div>
                </div>

            </form>
            <div class="modal fade " id="cari-perangkat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">

            </div>
            <!-- Modal Cari Alat -->
            <!-- modal laporan -->
 
        </div>
    </div>
   
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
                ajax: "{{ route('pembelian.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'TanggalBuat',
                        name: 'TanggalBuat'
                    },
                    
                    {
                        data: 'ItemID',
                        name: 'ItemID'
                    },
                    {
                        data: 'Nama',
                        name: 'Nama'
                    },
                    {
                        data: 'Harga',
                        name: 'Harga'
                    },
                    {
                        data: 'Jumlah',
                        name: 'Jumlah'
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
        var cariData = function(e, attr) {
            e.preventDefault();
            var cariGroup = $('#cariGroup').val();
            $.ajax({
                type: "GET",
                url: "{{ route('get-asset') }}",
                data: {
                    cariGroup: cariGroup

                },
                dataType: "json",
                beforeSend: function() {
                    KTApp.block('.modal-body', {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'Please wait...'
                    });
                    $('.progress').show()
                    $('#dataAsset').empty();
                },
                success: function(res) {
                    $('#dataAsset').append(res.view);
                },
                complete: function() {
                    KTApp.unblock('.modal-body');
                    $('.progress').hide()
                }
            });
        }
        var cariDataItem = function(e, attr) {
            e.preventDefault();
            var cariGroupItem = $('#cariGroupItem').val();
            var cariAlat = $('#cariAlat').val();
            $.ajax({
                type: "GET",
                url: "{{ route('get-alat') }}",
                data: {
                    cariGroupItem: cariGroupItem,
                    cariAlat: cariAlat

                },
                dataType: "json",
                beforeSend: function() {
                    KTApp.block('.modal-body', {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'Please wait...'
                    });
                    $('.progress').show()
                    $('#dataAlat').empty();
                },
                success: function(res) {
                    $('#dataAlat').append(res.view);
                },
                complete: function() {
                    KTApp.unblock('.modal-body');
                    $('.progress').hide()
                }
            });
        }
        var data = function(attr) {
            $('#cariGroup').val('');
            $('#cariIp').val('');
            $('#cariDepartemen').val('');
            $('#dataAsset').empty();
            var id = $(attr).find('#dataId').text();
            var judul = $(attr).find('#dataJudul').text();
            var Kasus = $(attr).find('#dataKasus').text();
            var Tanggal = $(attr).find('#dataTanggal').text();
            var DitugaskanKe = $(attr).find('#dataDitugaskanKe').text();
            var StatusID = $(attr).find('#dataStatusID').text();
            var UserGroupID = $(attr).find('#dataUserGroupID').text();

            $('#judul').val(judul);
            $('#Kasus').val(Kasus);
            $('#Tanggal').val(Tanggal);
            $('#DitugaskanKe').val(DitugaskanKe);
            $('#UserGroupID').val(UserGroupID);
            $('#StatusID').val(StatusID);
            $('#cari-perangkat').modal('toggle');
        }
        var dataAlat = function(attr) {
            $('#cariGroupItem').val('');
            $('#cariNama').val('');
            $('#dataAsset').empty();
            var id = $(attr).find('#dataId').text();
            var nama = $(attr).find('#dataNama').text();
            var group = $(attr).find('#dataGroupItem').text();

            $('#id').val(id);
            $('#namaalat').val(nama);
            $('#jenis').val(group);
            $('#cari-alat').modal('toggle');
        }

        var cetak = function(e, id) {
            e.preventDefault();
            KTApp.block('.kt-portlet', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Please wait...'
            });
            $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#form-masalah").submit();
        }
        var select_item = function() {
            $('#nama_perangkat').select2({
                placeholder: "--Select Alat--",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('masalah.get-item') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item, key) {
                                return {
                                    text: item,
                                    id: item
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
            $("#form-masalah").submit();
        }
        //tes tes tes
        $(document).ready(function() {
            $('#jenis').change(function() {
                var jenisPerangkat = $(this).val();
                if (jenisPerangkat == 'Umum') {
                    $('select[name="prioritas"]').empty();
                    $('select[name="prioritas"]').append(
                        '<option value="--Select Klasifikasi--">--Select Risiko--</option>');
                    $('select[name="prioritas"]').append('<option value="Urgent">Urgent</option>');
                    $('select[name="prioritas"]').append('<option value="Biasa">Biasa</option>');
                } else {
                    $('select[name="prioritas"]').empty();
                    $('select[name="prioritas"]').append(
                    '<option value="">--Select Klasifikasi--</option>');
                    $('select[name="prioritas"]').append('<option value="High Risk">High Risk</option>');
                    $('select[name="prioritas"]').append(
                    '<option value="Medium Risk">Medium Risk</option>');
                    $('select[name="prioritas"]').append('<option value="Low Risk">Low Risk</option>');
                }
            });
        });
        //asdasd
        jQuery(document).ready(function() {
            dataTable()
            time()
            select_item()
            $('.progress').hide()
        });
    </script>
@endpush
