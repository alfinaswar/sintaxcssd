@extends('layouts.app')
@push('title')
Riwayat Perbaikan
@endpush
@section('content')
<div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__head kt-portlet__head--lg">
        <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Catatan Perbaikan
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
                <div class="kt-portlet__head-actions">
                    <button data-toggle="modal" data-target="#cari-perangkat"
                        class="btn btn-info btn-elevate btn-icon-sm">
                        <i class="la la-search"></i>
                        Cari Data
                    </button>
                    <button data-toggle="modal" data-target="#cetak-laporan"
                        class="btn btn-success btn-elevate btn-icon-sm">
                        <i class="la la-search"></i>
                        Cetak Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="kt-portlet__body">
        <form id="form-masalah" action="{{ route('masalah.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control" name="assetID" value="" placeholder="Nama Perangkat"
                        type="text" hidden id="id">
                    <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">* Judul</label>
                        <div class="col-9">
                            <input class="form-control" name="judul" value="{{ old('judul') }}"
                                placeholder="Judul" type="text" id="judul">
                        </div>
                    </div>

<div class="form-group row">
                        <label for="Kasus" class="col-3 col-form-label">* Kasus</label>
                        <div class="col-9">
                            {{-- <input class="form-control" name="kasus" value="{{ old('kasus') }}"
                                placeholder="Kasus" type="text"  id="Kasus"> --}}
                                <textarea class="form-control" name="kasus" placeholder="Kasus" type="text"  id="Kasus" >
                                   {{ old('kasus') }}
                                </textarea>
                        </div>
                    </div>
 <div class="form-group row">
                        <label for="nama_perangkat" class="col-3 col-form-label">* Nama Alat</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
                            <select class="form-control kt-select2" id="namaalat" name="nama_perangkat">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">* Tindakan</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
                            <select class="form-control kt-select2" id="tindakan" name="tindakan">
                                <option value=" " selected>--Select Tindakan--</option>
                                <option value="Ganti Komponen">Ganti Komponen</option>
                                <option value="Service Vendor">Service Vendor</option>
                                <option value="Service di tempat">Service di tempat</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">* Jenis Perangkat</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
                            {{-- <input class="form-control" name="jenis" value="{{ old('jenis') }}"
                                placeholder="Jenis Perangkat" readonly type="text" id="jenis" readonly> --}}
                            <select class="form-control kt-select2" id="jenis" name="jenis">
                                <option value=" " selected>--Select jenis perangkat--</option>
                                <option value="Medis">Medis</option>
                                <option value="Umum">Umum</option>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="col-md-6">


                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Jumlah Kerusakan</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                           <input type="text" class="form-control" name="qty" id="qty" value="{{ old('qty') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Klasifikasi</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                           <select name="prioritas" class="form-control">
                            <option >--Select Risk--</option>
                            <option value="High Risk">High Risk</option>
                            <option value="Medium Risk">Medium Risk</option>
                            <option value="Low Risk">Low Risk</option>
                           </select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="keterangan" class="col-3 col-form-label">Keterangan</label>
                        <div class="col-9">
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>



                    <div class="kt-align-right">
                        <button type="button" onclick="simpan(event,this)" class="btn btn-brand btn-hover-primari"> <i
                                class="la la-save"></i>Simpan</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="modal fade " id="cari-perangkat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Data Asset</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="UserGroupID" class="col-form-label">UserGroup</label>
                                        <select name="UserGroupID" value="{{ old('UserGroupID') }}"
                                        placeholder="Nama Group" onchange="cariData(event,this)" id="cariGroup" class="form-control">
                                        <option name="">--Select Group--</option>
                                        <option name="elektromedis">ElektroMedis</option>
                                        <option name="MTNC">MTNC</option>
                                        </select>
                                </div>
                            </div>

                        </div>

                        <div class="kt-section kt-mt-10">
                            <div class="kt-section__content">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Group</th>
                                            <th>Kasus</th>
                                            <th>Tanggal</th>
                                            <th>Ditugaskan Ke</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataAsset">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    <!-- modal laporan -->
            <div class="modal fade " id="cetak-laporan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
             <form method="GET" action="{{ route('masalah.excel_masalah') }}" id="cetak-laporan">
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cetak laporan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="nama" class="col-form-label">Aksi</label>
                                    <input type="submit" name="cetak" id="cetak" class="btn btn-info btn-md" value="Cetak">
                                </div>
                            </div>

                        </div>


                    </div>
                    </form>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Alat</th>
                    <th>Kasus</th>
                    <th>Actions</th>
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
<link href="{{ asset('') }}assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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
                ajax: "{{ route('masalah.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'nama_perangkat',
                        name: 'nama_perangkat'
                    },
                    {
                        data: 'kasus',
                        name: 'kasus'
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

        var time= function(){
            $('#kt_timepicker_2').timepicker({
            minuteStep: 1,
            defaultTime: '',
            showSeconds: true,
            showMeridian: false,
            snapToStep: true
        });
        }
        var cariData=function(e,attr){
            e.preventDefault();
            var cariGroup= $('#cariGroup').val();
            $.ajax({
                type: "GET",
                url: "{{ route('get-asset') }}",
                data: {
                    cariGroup:cariGroup

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
                success: function (res) {
                    $('#dataAsset').append(res.view);
                },complete: function() {
                            KTApp.unblock('.modal-body');
                            $('.progress').hide()
                        }
            });
        }
         var cariDataItem=function(e,attr){
            e.preventDefault();
            var cariGroupItem= $('#cariGroupItem').val();
            var cariAlat= $('#cariAlat').val();
            $.ajax({
                type: "GET",
                url: "{{ route('get-alat') }}",
                data: {
                    cariGroupItem:cariGroupItem,
                    cariAlat:cariAlat

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
                success: function (res) {
                    $('#dataAlat').append(res.view);
                },complete: function() {
                            KTApp.unblock('.modal-body');
                            $('.progress').hide()
                        }
            });
        }
        var data=function(attr){
           $('#cariGroup').val('');
           $('#cariIp').val('');
            $('#cariDepartemen').val('');
            $('#dataAsset').empty();
            var id=$(attr).find('#dataId').text();
            var judul=$(attr).find('#dataJudul').text();
            var Kasus=$(attr).find('#dataKasus').text();
            var Tanggal=$(attr).find('#dataTanggal').text();


            var UserGroupID=$(attr).find('#dataUserGroupID').text();
            $('#judul').val(judul);
            $('#Kasus').val(Kasus);
            $('#Tanggal').val(Tanggal);

            $('#UserGroupID').val(UserGroupID);
            $('#cari-perangkat').modal('toggle');
        }
 var dataAlat=function(attr){
           $('#cariGroupItem').val('');
           $('#cariNama').val('');
           $('#dataAsset').empty();
            var id=$(attr).find('#dataId').text();
            var nama=$(attr).find('#dataNama').text();
            var group=$(attr).find('#dataGroupItem').text();

            $('#id').val(id);
            $('#namaalat').val(nama);
            $('#jenis').val(group);
            $('#cari-alat').modal('toggle');
        }

        var cetak= function(e, id){
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
            $('#namaalat').select2({
                placeholder: "--Select Alat--",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("masalah.get-item") }}',
                    dataType: 'json',
                    delay: 250,
                     processResults: function(data) {
                        return {
                            results: $.map(data, function(item,key) {
                            return {
                                    text:key + ' ' + item,
                                    id:key + ',' + item
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
         var simpan= function(e, id){
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
            $(document).ready(function(){
        $('#jenis').change(function(){
            var jenisPerangkat = $(this).val();
            if(jenisPerangkat == 'Umum'){
                $('select[name="prioritas"]').empty();
                $('select[name="prioritas"]').append('<option value="--Select Klasifikasi--">--Select Risiko--</option>');
                $('select[name="prioritas"]').append('<option value="Urgent">Urgent</option>');
                $('select[name="prioritas"]').append('<option value="Biasa">Biasa</option>');
            } else {
                $('select[name="prioritas"]').empty();
                $('select[name="prioritas"]').append('<option value="">--Select Klasifikasi--</option>');
                $('select[name="prioritas"]').append('<option value="High Risk">High Risk</option>');
                $('select[name="prioritas"]').append('<option value="Medium Risk">Medium Risk</option>');
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
