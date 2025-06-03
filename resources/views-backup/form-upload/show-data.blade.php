<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Nomor Dokumen</th>
                <td>{{ $query->dokumen }}</td>
                <th>Prioritas</th>
                <td>{{ $query->priority }}</td>
            </tr>
            <tr>
                <th>Nama Dokumen</th>
                <td>{{ $query->namaDokumen }}</td>
                <th>Requester</th>
                <td>{{ $query->requester }}</td>
            </tr>
            <tr>
                <th>Jenis File</th>
                <td>{{ $query->jenisFile }}</td>
                <th>Status</th>
                <td>
                    @php
                    $status='';
                    if ($query->verifikasi == 2 && $query->tanggalSelesai) {
                    $status = '<span class="badge badge-primary">Done</span>';
                    } elseif ($query->verifikasi == 1 && $query->tanggalProses) {
                    $status = '<span class="badge badge-danger">Cancel</span>';
                    } elseif ($query->verifikasi == 0 && $query->tanggalProses) {
                    $status = '<span class="badge badge-warning">Proses</span>';
                    } elseif ($query->tanggalProses == null) {
                    $status = '<span class="badge badge-success">Open</span>';
                    }
                    @endphp
                    {!! $status !!} </td>
            </tr>
            <tr>
                <th>Tanggal Upload</th>
                <td>{{ date('d M Y', strtotime($query->created_at)) }}</td>
                <th>Tanggal Selesai</th>
                <td>{{ date('d M Y', strtotime($query->tanggalSelesai)) }}</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td colspan="3">{{ $query->keterangan }}</td>
            </tr>
            <tr>
                <th>Komentar</th>
                <td colspan="3">{{ $query->comments }}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="kt-divider">
    <span></span>
    <span>Dokumen</span>
    <span></span>
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width='5%'>No</th>
                <th width='50%'>Nama Dokumen</th>
                <th width='20%'>Requester</th>
                <th width='10%'>Status</th>
                <th width="10%" style="text-align: center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($file as $key=>$files )
            <tr>
                <th scope="row">{{ $key+1 }}</th>
                <td>{{ $files->name }}</td>
                <td>
                    {{ $files->requester }}
                </td>
                <td>
                    @php
                    $status='';
                    if ($files->status == 0) {
                    $status = '<span class="badge badge-success">Open</span>';
                    } elseif ($files->status == 1) {
                    $status = '<span class="badge badge-primary">Upload</span>';
                    }
                    @endphp
                    {!! $status !!} </td>
                </td>
                <td style="text-align: center">
                    <a href="{{ route('download-file', $files->id) }}"><button type=" button"
                            class="btn btn-info btn-elevate btn-icon btn-sm btn-circle"><i
                                class="la la-cloud-download"></i></button></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>