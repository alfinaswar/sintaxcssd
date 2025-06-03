<div class="modal fade" id="modal-update-file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="updateFile" method="POST" action="{{ route('update-file') }}" accept-charset="utf-8"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="text" class="form-control" hidden name="idDokIzin" id="idIzin">
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
                <button type="button" onclick="updateFile(event,this)" class="btn btn-primary ">Simpan</button>
            </div>
        </div>
    </div>
</div>
@push('after-js')
<script>
    function updateFile(e, id) {
            e.preventDefault();
            KTApp.block('.modal-body', {
                overlayColor: '#000000',
                type: 'v2',
                state: 'success',
                message: 'Please wait...'
            });
            $('.progress').show()
            $(id).addClass('kt-spinner kt-spinner--md kt-spinner--danger disabled');
            $("#updateFile").submit();
        }
</script>
@endpush