@extends('layouts.app')
@push('title')
Asset Inventaris
@endpush
@push('sub-title')
Tambah Asset Inventaris
@endpush
@section('content')
		<div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Tambah Asset Inventaris
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
                <div class="tab-content">
                    <div class="tab-pane active" id="kt_portlet_base_demo_3_3_tab_content" role="tabpanel">
    <form class="kt-form kt-form--label-right" id="simpanForm" action="{{ route('inventaris.store') }}" method="POST"
        accept-charset="utf-8" enctype="multipart/form-data">
        @csrf
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
@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif
            <div class="row">
                <div class="col-md-6">
                      {{-- <div class="form-group row">
                        <label for="kategori" class="col-3 col-form-label">* Kategori</label>
                        <div class=" col-lg-9 col-md-4 col-sm-12">
                            <select class="form-control " id="kategori" name="kategori">
                                <option value="">--Select Kategori--</option>
                                <option value="8">Inventaris Medis</option>
                                 <option value="5">Inventaris Umum</option>
                            </select>

                        </div>
                    </div> --}}

                    {{-- <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">* Nama Alat</label>
                        <div class=" col-lg-9 col-md-4 col-sm-12">
                            <select class="form-control kt-select2" id="nama" name="nama">
                            </select>

                        </div>
                    </div> --}}
                     <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">* Nama Alat</label>
                        <div class=" col-lg-9 col-md-4 col-sm-12">
                        <input type="text" name="nama" class="form-control" id="NamaAlat" placeholder="Nama Alat" readonly>
                        <input type="hidden" name="ItemID" class="form-control" id="ItemID" placeholder="">
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">* Nomor RO</label>
                        <div class=" col-lg-9 col-md-4 col-sm-12">
                        <input type="text" name="ROID" class="form-control" id="ROID" placeholder="Nomor RO" readonly>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="nama" class="col-3 col-form-label">* Nomor RO2ID</label>
                        <div class=" col-lg-9 col-md-4 col-sm-12">
                        <input type="text" name="RO2ID" class="form-control" id="RO2ID" placeholder="Nomor RO2ID" readonly>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="real_name" class="col-3 col-form-label">* Nama Barcode</label>
                        <div class="col-9">
                            <input class="form-control" name="real_name" value="{{ old('real_name') }}"
                                placeholder="Nama di barcode" type="text" id="real_name">
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="no_inventaris" class="col-3 col-form-label">* Nomor Inventaris</label>
                        <div class="col-9">
                            <input class="form-control" name="no_inventaris" value="{{ old('no_inventaris') }}"
                                placeholder="Nomor Inventaris Medis" type="text" id="no_inventaris" required>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="departemen" class="col-3 col-form-label">* Nomor SN</label>
                        <div class=" col-lg-9">
                            <input class="form-control" name="no_sn" value="{{ old('no_sn') }}" placeholder="Nomor SN"
                                type="text" id="no_sn" required>
                        </div>
                    </div>
                      <div class="form-group row">
                        <label for="tanggal_beli" class="col-3 col-form-label">* Tanggal Beli</label>
                        <div class=" col-lg-9">
                            <input class="form-control" name="tanggal_beli" value="{{ old('tanggal_beli') }}" placeholder="Tanggal beli"
                                type="date" value="" id="tanggal_beli">
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">* Departemen</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
<select class="form-control kt-select2" id="departemen" name="departemen">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">* Unit</label>
                        <div class=" col-lg-9 col-md-9 col-sm-12">
<select class="form-control kt-select2" id="unit" name="unit">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">* Jenis / Pengguna</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <select class="form-control kt-select2" id="userPengguna" name="userPengguna">
                                <option value=" " selected>--Select Jenis--</option>
                                <option value="Medis">Medis</option>
                                <option value="Non Medis">Non Medis</option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12">* Gambar</label>
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <input type="file" class="custom-file" value="{{ old('gambar') }}" name="gambar" id="gambar" type="text" placeholder="" required/>
                            <input type="text" hidden class="form-control" value="{{ old('nama_rs', auth()->check() ? auth()->user()->kodeRS : '') }}" name="nama_rs" id="nama_rs" type="text" placeholder="" />
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="keterangan" class="col-3 col-form-label">Keterangan</label>
                        <div class="col-9">
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Keterangan"></textarea>
                        </div>
                    </div>

                </div>
            </div>
                    </div>
                    </div>
                </div>

                    </div>

                </div>
                		<div class="kt-portlet kt-portlet--tabs">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Tambah Data Kalibrasi
            </h3>
        </div>
                <div class="kt-portlet__head-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#" role="tab">
                                <i class="flaticon2-pie-chart-2" aria-hidden="true"></i>Kalibrasi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="kt_portlet_base_demo_3_3_tab_content" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
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
                                <input type="date" class="form-control" name="tgl_expire" id="tgl_expire"
                                    value="{{ old('tgl_expire') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama_perangkat" class="col-form-label col-lg-3 col-sm-12">* Uplpad Dokumen</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <input type="file" name="dokumen" id="upload-btn" class="form-control-file">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label for="nama_perangkat" class="col-form-label col-lg-3 col-sm-12">* Uplpad Manual Book</label>
                            <div class=" col-lg-9 col-md-9 col-sm-12">
                                <input type="file" name="manualbook" id="upload-btn" class="form-control-file">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="button" onclick="simpan(event,this)" class="btn btn-info">Submit</button>
                <a href="{{ route('inventaris.index') }}">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                </a>
            </div>
        </div>
    </form>
                    </div>
                </div>

                    </div>

                </div>
            </div>
        </div>

{{-- Modal Cari Asset --}}
    <div class="modal fade " id="cari-nama" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Data Inventaris</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row col-12">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="NomorRo" class="col-form-label">Nomor ROID</label>
                                   <input type="text" class="form-control" id="NomorRo" name="NomorRo" placeholder="Nomor ROID">
                                   </div>
                                   <button type="button" class="btn btn-md btn-primary" onclick="cariData(event,this)">Cari</button>


                            </div>

                        </div>

                        <div class="kt-section kt-mt-10">
                            <div class="kt-section__content">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nomor ROID</th>
                                            <th>Nomor RO2ID</th>
                                             <th>Item ID</th>
                                            <th>Item</th>
                                            <th>Jenis</th>
                                            <th>Tanggal</th>
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
@push('js')
<script>
    var cariData=function(e,attr){
            e.preventDefault();
            var cariNomorRo= $('#NomorRo').val();
            $.ajax({
                type: "GET",
                url: "{{ route('inventaris.getroitem') }}",
                data: {
                    cariNomorRo:cariNomorRo
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
            var ItemID=$(attr).find('#dataItemID').text();
            var NomorRo=$(attr).find('#dataNomorRo').text();
            var NomorRo2=$(attr).find('#dataNomorRo2').text();
            var Nama=$(attr).find('#dataNama').text();
            var GroupItemID=$(attr).find('#dataGroupItemID').text();
            var TanggalBuat=$(attr).find('#dataTanggal').text();

            $('#ItemID').val(ItemID);
            $('#RO2ID').val(NomorRo2);
            $('#ROID').val(NomorRo);
            $('#NamaAlat').val(Nama);
            $('#cari-nama').modal('toggle');
        }
    $('#kategori').on('change',function(){
                 let kategori = $('#kategori').val();
                 $('#nama').select2({
                    placeholder: 'Select Alat',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('inventaris.get-item') }}?kategori=" + kategori,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item,key) {
                                    return {
                                    text:item,
                                    id:key + ',' + item
                                    }
                                })
                            };
                        }
                    }
                });

            })
     $('#departemen').on('change',function(){
                 let departemen = $('#departemen').val();
                 $('#unit').select2({
                    placeholder: 'Select Unit',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('inventaris.get-unit') }}?departemen=" + departemen,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item,key) {
                                    return {
                                        text:item,
                                    id:item
                                    }
                                })
                            };
                        }
                    }
                });

            })
 function nama(){
    $(selector).val();
            $('#nama').select2({
                    placeholder: 'Select Alat',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('inventaris.get-item') }}?kategori=" + kategori,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama,
                                        id: item.id
                                    }
                                })
                            };
                        }
                    }
                });
            }
    function simpan(e, id) {
            e.preventDefault();
            KTApp.block('.kt-portlet', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Please wait...'
            });
            // $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#simpanForm").submit();
        }
        var select_departemen = function() {
            $('#departemen').select2({
                placeholder: "Select departemen",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("master.get-departemen") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }
        var select_unit = function() {
            $('#unit').select2({
                placeholder: "Select Unit",
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route("master.get-departemen") }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.nama
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        jQuery(document).ready(function() {
        select_departemen()
        cariData();
        data();
        });
</script>
@endpush
