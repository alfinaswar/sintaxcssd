<div class="modal fade" id="modal-verifikasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="updateIzin" method="POST" action="{{ route('update-status') }}" accept-charset="utf-8"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="text" class="form-control" hidden name="idDokIzin" id="idDokIzin">
                    <div class="form-group">
                        <label>Proses</label>
                        <input data-switch="true" name="verifikasi" data-inverse="true" value="0" data-size="small"
                            type="checkbox" checked="checked">
                        <label>Di Tolak</label>
                        <input data-switch="true" name="verifikasi" data-inverse="true" value="1" data-size="small"
                            type="checkbox" checked="checked">
                        <label>Selesai</label>
                        <input data-switch="true" name="verifikasi" data-inverse="true" value="2" data-size="small"
                            type="checkbox" checked="checked">
                    </div>
                    {{-- <div class="form-group kt-margin-t-20">
                        <label class="col-form-label col-lg-2 col-sm-12">Verifikasi</label>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <input data-switch="true" type="checkbox" name="verifikasi" value="1"
                                    data-inverse="true" name="verifikasi" data-on-text="Di Terima" checked="true"
                                    data-handle-width="70" data-off-text="Di Tolak" data-on-color="primary"
                                    data-off-color="warning">
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="example-text-input" class="col-2 col-form-label">Keterangan</label>
                        <div class="col-10">
                            <textarea class="form-control" name="keterangan" id="exampleTextarea" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-2 col-form-label">File Browser</label>
                        <div></div>
                        <div class="custom-file col-10">
                            <input type="file" name="files[]" multiple class="custom-file-input" id="customFile">
                            <span class="custom-file-label" for="customFile">Choose file</span>
                        </div>
                    </div>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" onclick="updateStatus(event,this)" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('after-js')
<script>
    function updateStatus(e,id){
        e.preventDefault();
        KTApp.block('.modal-body', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Please wait...'
            });
            $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
        $("#updateIzin").submit();
//         var form = document.querySelector('form');
//         var data=new FormData(form)
//         jQuery.each($('input[name^="files"]')[0].files, function(i, file) {
//     data.append(i, file);
// });
// const data=$('#updateIzin').serialize()
// console.log(data);
        // const idDok=$('#idDokIzin').val();
        // var url="{{ route('index.update','id') }}"
        // url=url.replace('id',idDok)
        // console.log(url);
        // $.ajax({
        //     type: "POST",
        //     url: url,
        //     data: data,
        //     cache: false,
        //     dataType: "json",
        //     beforeSend: function() {
                  
        //         },
        //     success: function (res) {
        //         toastr.success('Data berhasil di update')
        //     },
        //         error: function(xhr, ajaxOptions, thrownError) {
        //             toastr.warning(xhr.responseJSON.massage, xhr.status)
        //         },
        //         complete: function() {
        //             dataTable()
        //             $('#modal-verifikasi').modal('toggle');
        //         }
        // });
    }
    
</script>
@endpush