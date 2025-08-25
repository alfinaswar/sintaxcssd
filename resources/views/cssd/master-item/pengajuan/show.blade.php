@extends('layouts.app')

@push('title')
    Detail Persetujuan Pengajuan Item Baru
@endpush

@section('content')
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon2-plus"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Detail Persetujuan Pengajuan Item Baru
                </h3>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!-- Informasi Umum -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Pengajuan</label>
                        <div class="border p-2 rounded bg-light">
                            {{ \Carbon\Carbon::parse($data->Tanggal)->format('d-m-Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Item Baru -->
            <div class="form-group">
                <label class="font-weight-bold">Daftar Item yang Diajukan</label>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 30%;">Nama Item</th>
                                <th style="width: 20%;">Merk</th>
                                <th style="width: 20%;">RS</th>
                                <th style="width: 30%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data->getDetail as $detail)
                                <tr>
                                    <td>{{ $detail->NamaItem ?? '-' }}</td>
                                    <td>{{ $detail->getMerk->Merk ?? '-' }}</td>
                                    <td>{{ $data->getRs->nama ?? '-' }}</td>
                                    <td>{{ $detail->Keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada data item</td>
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
                    {{ $data->Keterangan ?? '-' }}
                </div>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Revisi</label>
                <textarea class="tinymce" id="Revisi" name="Revisi">{{ $data->Revisi ?? '' }}</textarea>
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
                    <p class="mt-2 font-weight-bold">( {{$data->getDiajukan->name ?? '-'}} )</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-4" style="font-weight: bold;">Disetujui Oleh,</p>
                    @if($data->Status === 'Y')
                        @if(!empty($data->getManager->ttd))
                            <div style="height: 150px;">
                                <img src="{{ asset('storage/tandatangan/' . $data->getManager->ttd) }}" alt="Tanda Tangan"
                                    style="max-height: 150px;">
                            </div>
                        @else
                            <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                        @endif
                    @elseif($data->Status === 'N')
                        <div style="height: 80px;"
                            class="text-danger d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                            <div>Ditolak {{$data->getManager->name ?? '-'}}</div>
                        </div>
                    @else
                        <div style="height: 80px;" class="text-muted">Belum ada persetujuan</div>
                    @endif
                    <hr style="width: 80%;">
                    <p class="mt-2 font-weight-bold">( {{$data->getManager->name ?? '-'}} )</p>
                </div>
            </div>


            @if(($data->Status == null || $data->Status == 'N') && auth()->user() && auth()->user()->role == 'admin')
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-success btn-lg " onclick="konfirmasiSetujui({{ $data->id }})"
                            @if(!empty($data->ApproveBy)) disabled @endif>
                            <i class="fa fa-check"></i> Setujui Pengajuan
                        </button>
                        <button type="button" class="btn btn-danger btn-lg" onclick="konfirmasiTolak({{ $data->id }})"
                            @if(!empty($data->ApproveBy)) disabled @endif>
                            <i class="fa fa-times"></i> Tolak Pengajuan
                        </button>
                    </div>
                </div>
            @endif


            @if($data->Status == 'Y')
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{route('pengajuan-nama-item-cssd.cetak', $data->id)}}" class="btn btn-info w-100">Cetak
                            Pengajuan</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('after-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.9.1/tinymce.min.js"
        integrity="sha512-wL4f713UTdXFhzoGj57R7cKAwWMp48nzQ4Z/OLy7r8Hrqsa53x3y9Wl1N27hNktcmmHUABHuIX5xQazAl0VMRg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        tinymce.init({
            selector: '.tinymce',
            height: 400,
            plugins: 'lists link table code image media',
            toolbar: 'undo redo | bold italic | fontsizeselect fontselect | numlist bullist | outdent indent | link | code | alignleft aligncenter alignright | image media',
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function konfirmasiSetujui(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pengajuan item baru ini akan disetujui.",
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pengajuan-nama-item-cssd.setujui', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            Revisi: tinymce.get('Revisi').getContent()
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
                text: "Anda akan menolak pengajuan item baru ini.",
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('pengajuan-nama-item-cssd.tolak', ':id') }}".replace(':id', id),
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            Revisi: tinymce.get('Revisi').getContent()
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
    </script>
@endpush
