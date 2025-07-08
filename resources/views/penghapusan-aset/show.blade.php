@extends('layouts.app')

@push('title')
    Detail Pengajuan Penghapusan Aset
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-trash"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Detail Pengajuan Penghapusan Aset
                </h3>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!-- Informasi Umum -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Departemen</label>
                        <div class="border p-2 rounded bg-light">{{ $data->getDepartemen->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Unit</label>
                        <div class="border p-2 rounded bg-light">{{ $data->Unit ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal</label>
                        <div class="border p-2 rounded bg-light">
                            {{ \Carbon\Carbon::parse($data->Tanggal)->format('d-m-Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Aset -->
            <div class="form-group">
                <label class="font-weight-bold">Daftar Aset yang Akan Dihapus</label>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 60%;">Nama Item</th>
                                <th style="width: 40%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data->getDetail as $detail)
                                <tr>
                                    <td>
                                        {{ $detail->getItem->kode_item ?? '' }}
                                        {{ $detail->getItem->nama ?? '' }}
                                        {{ $detail->getItem->no_inventaris ?? '' }}
                                    </td>
                                    <td>{{ $detail->Keterangan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Tidak ada data aset</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div class="form-group">
                <label class="font-weight-bold">Catatan Tambahan</label>
                <div class="border p-3 rounded bg-light">
                    {{ $data->Catatan ?? '-' }}
                </div>
            </div>

            <!-- Tanda Tangan -->
            <div class="row text-center mt-5">
                <div class="col-md-4">
                    <p class="mb-4" style="font-weight: bold;">Diajukan Oleh,</p>
                    @if(!empty($data->getDiajukan->ttd))
                        <div style="height: 150px;">
                            <img src="{{ asset('storage/tandatangan/' . $data->getDiajukan->ttd) }}" alt="Tanda Tangan"
                                style="max-height: 150px;">
                        </div>
                    @else
                        <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                    @endif
                    <hr style="width: 80%;">
                    <p class="mt-2 font-weight-bold">( {{$data->getDiajukan->name}} )</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-4" style="font-weight: bold;">Mengetahui Meneger Penunjang dan Pelayanan Medis,</p>
                    @if($data->AccManager === 'Y')
                        @if(!empty($data->getDiajukan->ttd))
                            <div style="height: 150px;">
                                <img src="{{ asset('storage/tandatangan/' . $data->getDiajukan->ttd) }}" alt="Tanda Tangan"
                                    style="max-height: 150px;">
                            </div>
                        @else
                            <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                        @endif
                    @elseif($data->AccManager === 'N')
                        <div style="height: 80px;"
                            class="text-danger d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                            <div>Dit{{$data->getManager->name}} </div>
                        </div>
                    @else
                        <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                    @endif
                    <hr style="width: 80%;">
                    <p class="mt-2 font-weight-bold">( {{$data->getManager->name ?? '-'}} )</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-4" style="font-weight: bold;">Mengetahui SMI,</p>
                    @if($data->AccSmi === 'Y')
                        @if(!empty($data->getSmi->ttd))
                            <div style="height: 150px;">
                                <img src="{{ asset('storage/tandatangan/' . $data->getSmi->ttd) }}" alt="Tanda Tangan"
                                    style="max-height: 150px;">
                            </div>
                        @else
                            <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                        @endif
                    @elseif($data->AccSmi === 'N')
                        <div style="height: 80px;"
                            class="text-danger d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                            <div>Ditolak {{$data->getSmi->name}} </div>
                        </div>
                    @else
                        <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                    @endif
                    <hr style="width: 80%;">
                    <p class="mt-2 font-weight-bold">( {{$data->getSmi->name ?? '-'}} )</p>
                </div>
            </div>
            @can('approval-as-manager')
                @if($data->Status == 'pengajuan')
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-success btn-lg mr-3" onclick="konfirmasiSetujui({{ $data->id }})"
                                @if(!empty($data->Sign1)) disabled @endif>
                                <i class="fa fa-check"></i> Setujui Pengajuan
                            </button>

                            <button type="button" class="btn btn-danger btn-lg" onclick="konfirmasiTolak({{ $data->id }})"
                                @if(!empty($data->Sign1)) disabled @endif>
                                <i class="fa fa-times"></i> Tolak Pengajuan
                            </button>
                        </div>
                    </div>
                @endif
            @endcan
            @can('approval-as-smi')
                @if($data->Status == 'pengajuan')
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-success btn-lg mr-3"
                                onclick="konfirmasiSetujuiSmi({{ $data->id }})" @if(!empty($data->Sign2)) disabled @endif>
                                <i class="fa fa-check"></i> Setujui Pengajuan
                            </button>
                            <button type="button" class="btn btn-danger btn-lg" onclick="konfirmasiTolakSmi({{ $data->id }})"
                                @if(!empty($data->Sign2)) disabled @endif>
                                <i class="fa fa-times"></i> Tolak Pengajuan
                            </button>
                        </div>
                    </div>
                @endif
            @endcan
            @if($data->Status == 'disetujui')
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{route('pa.cetak', $data->id)}}" class="btn btn-info w-100">Cetak Pengajuan</a>
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection
@push('after-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function konfirmasiSetujui(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pengajuan penghapusan aset ini akan disetujui.",
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pa.setujui', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            Swal.fire('Berhasil!', 'Pengajuan telah disetujui.').then(() => {
                                window.location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menyetujui pengajuan.');
                        }
                    });
                }
            });
        }

        function konfirmasiTolak(id) {
            Swal.fire({
                title: 'Tolak Pengajuan?',
                text: "Anda akan menolak pengajuan penghapusan aset ini.",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pa.tolak', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            Swal.fire('Berhasil!', 'Pengajuan telah ditolak.').then(() => {
                                window.location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menolak pengajuan.');
                        }
                    });
                }
            });
        }


        function setujuiPengajuan(id) {
            window.location.href = `/penghapusan-aset/setujui/${id}`;
        }

        function tolakPengajuan(id) {
            window.location.href = `/penghapusan-aset/tolak/${id}`;
        }
    </script>
    <script>
        function konfirmasiSetujuiSmi(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pengajuan penghapusan aset ini akan disetujui.",
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pa.setujuiSmi', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            Swal.fire('Berhasil!', 'Pengajuan telah disetujui.').then(() => {
                                window.location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menyetujui pengajuan.');
                        }
                    });
                }
            });
        }

        function konfirmasiTolakSmi(id) {
            Swal.fire({
                title: 'Tolak Pengajuan?',
                text: "Anda akan menolak pengajuan penghapusan aset ini.",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pa.tolakSmi', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            Swal.fire('Berhasil!', 'Pengajuan telah ditolak.', 'success').then(() => {
                                window.location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menolak pengajuan.', 'error');
                        }
                    });
                }
            });
        }


        function setujuiPengajuanSmi(id) {
            window.location.href = `/penghapusan-aset/setujui/${id}`;
        }

        function tolakPengajuanSmi(id) {
            window.location.href = `/penghapusan-aset/tolak/${id}`;
        }
    </script>
@endpush
