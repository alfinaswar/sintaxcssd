@extends('layouts.app')
@push('title')
    Kalibrasi
@endpush
@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-line-chart"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Data Kalibrasi Alat
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#" role="tab">
                                <i class="flaticon2-heart-rate-monitor" aria-hidden="true"></i>Data Alat
                            </a>

                        </li>
                         <li class="nav-item">
                             <button data-toggle="modal" data-target="#cari-nama"
                        class="btn btn-info btn-elevate btn-icon-sm">
                        <i class="la la-search"></i>
                        Cari Data
                    </button>

                        </li>
                    </ul>
                </div>
        </div>

        <div class="kt-portlet__body">
            <form id="form-kalibrasi" action="{{ route('kalibrasi.store') }}" method="POST" accept-charset="utf-8"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama" class="col-3 col-form-label">* Nama Alat</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <input type="text" readonly name="nama" id="nama" class="form-control" placeholder="Nama Alat" placeholder="">
                                 <input type="hidden" name="idalat" id="idalat" class="form-control" placeholder="Nama Alat">
                                {{-- <select class="form-control kt-select2" id="nama" name="nama">
                                </select> --}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">* Kepemilikan</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control kt-select2" id="kepemilikan" name="kepemilikan">
                                    <option value=" " selected>--Select Kepemilikan--</option>
                                    <option value="Rumah Sakit">Rumah Sakit</option>
                                    <option value="Dokter">Dokter</option>
                                    <option value="Vendor">Vendor</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">Tanggal Kalibrasi</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <input type="date" class="form-control" name="tgl_kalibrasi" id="tgl_kalibrasi"
                                    value="{{ old('tgl_kalibrasi') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-3 col-sm-12">Expire Date</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <input type="date" class="form-control" name="exp_date" id="exp_date"
                                    value="{{ old('exp_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="nama_perangkat" class="col-form-label col-lg-3 col-sm-12">* Uplpad Dokumen</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <input type="file" name="dokumen" id="upload-btn">
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
            <div class="row">
                <div class="col-md-3">
                    <small>Filter By Rumah Sakit:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-hospital-alt"></i></span></div>
                            <select name="filter_rs" class="custom-select form-control" id="filter_rs">
                                <option value="" selected>--Semua RS--</option>
                                @foreach ($rs as $item)
                                    <option value="{{ $item->kodeRS }}" {{ old('nama') == $item->kodeRS ? 'selected' : '' }}>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <small>Filter By Kepemilikan:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-user"></i></span></div>
                            <select class="form-control " name="filter_pemilik" id="filter_pemilik">
                                <option value="">--Semua Kepemilikan--</option>
                                <option value="Dokter">Dokter</option>
                                <option value="Rumah Sakit">Rumah Sakit</option>
                                <option value="Vendor">Vendor</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <small>Filter By Tanggal Kalibrasi:</small>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="fa fa-calendar-alt"></i></span></div>
                            <input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                     <small>&nbsp;</small>

                </div>
            </div>
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Kapemilikan</th>
                        <th>Tgl Kalibrasi</th>
                        <th>Exp Date</th>
                        <th>Keterangan</th>
                        <th>Dokumen</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
        <div class="modal fade " id="cari-nama" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Data Inventaris</h5>
                        {{-- <span class="badge bg-danger text-white">Normal Kembali Pada Pukul 15.15 Wib</span> --}}
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                        
                    </div>
                    <div class="modal-body">
                        
                        <div class="row col-12">
                           
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="cariNama" class="col-form-label">Cari Berdasarkan</label>
                                   <select name="filtercari" id="filtercari" class="form-control">
                                    <option value="nama">Nama Alat</option>
                                    <option value="no_inventaris">Nomor Inventaris</option>
                                    <option value="no_sn">Serial Number</option>
                                   </select>
                                </div>
                                   
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cariNama" class="col-form-label">Ketik Kata Kunci</label>
                                   <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Ketikan Kata Kunci">
                                   </div>
                                   
                            </div>

                        <div class="col-md-3">
                            <br> <br>
                             <button type="button" class="btn btn-md btn-primary" onclick="cariData(event,this)">Cari</button>
                        </div>
 

                        </div>

                        <div class="kt-section kt-mt-10">
                            <div class="kt-section__content">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>No Inventaris</th>
                                            <th>Nama</th>
                                            <th>Departemen</th>
                                            <th>Unit</th>
                                            <th>Jenis</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataInventaris">
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
                    url: "{{ route('kalibrasi.index') }}",
                    data: function(d) {
                        d.filter_tanggal = $('#filter_tanggal').val(),
                            d.filter_rs = $('#filter_rs').val(),
                            d.filter_pemilik = $('#filter_pemilik').val(),
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
                        name: 'nama'
                    },
                    {
                        data: 'kepemilikan',
                        name: 'kepemilikan'
                    },
                    {
                        data: 'tgl_kalibrasi',
                        name: 'tgl_kalibrasi'
                    },
                    {
                        data: 'exp_date',
                        name: 'exp_date'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'dokumen',
                        name: 'dokumen'
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

        // var select_item = function() {
        //     $('#nama').select2({
        //         placeholder: "--Select Alat--",
        //         minimumInputLength: 1,
        //         ajax: {
        //             url: '{{ route('kalibrasi.get-item') }}',
        //             dataType: 'json',
        //             delay: 250,
        //             processResults: function(data) {
        //                 return {
        //                     results: $.map(data, function(item, key) {
        //                         return {
        //                             text: item,
        //                             id: key
        //                         }
        //                     })
        //                 };
        //             },
        //             cache: true
        //         }
        //     });
        // }
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
            $("#form-kalibrasi").submit();
        }
        //tes tes tes
    var cariData=function(e,attr){
            e.preventDefault();
            var filtercari= $('#filtercari').val();
            var keyword= $('#keyword').val();
            $.ajax({
                type: "GET",
                url: "{{ route('kalibrasi.getInv') }}",
                data: {
                    filtercari:filtercari,
                    keyword:keyword
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
                            $('#dataInventaris').empty();
                        },
                success: function (res) {
                    $('#dataInventaris').append(res.view);
                },complete: function() {
                            KTApp.unblock('.modal-body');
                            $('.progress').hide()
                        }
            });
        }

        var data=function(attr){
            var nama=$(attr).find('#datanama').text();
            var id=$(attr).find('#dataid').text();
            $('#nama').val(nama);
            $('#idalat').val(id);
            $('#cari-nama').modal('toggle');
        }

        //asdasd
        jQuery(document).ready(function() {
            $('.progress').hide()
            dataTable()
            time()
            // select_item()
            cariData();
        data();
        });
        $('#filter_tanggal,#filter_rs,#filter_pemilik').change(function() {
            var table = $('#kt_table_1').DataTable();
            table.draw();
        });

  </script>
@endpush
