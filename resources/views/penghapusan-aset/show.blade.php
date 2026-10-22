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
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Gudang</label>
                        <div class="border p-2 rounded bg-light">{{ $data->getGudang->Nama ?? '-' }}</div>
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
                                <th style="width: 5%;">No</th>
                                <th style="width: 25%;">Nama Item</th>
                                <th style="width: 15%;">SN</th>
                                <th style="width: 20%;">Metode</th>
                                <th style="width: 35%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data->getDetail as $i => $detail)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        {{ $detail->getItem->kode_item ?? '' }}
                                        {{ $detail->getItem->nama ?? '' }}
                                        {{ $detail->getItem->no_inventaris ?? '' }}
                                    </td>
                                    <td>
                                        {{ $detail->getItem->no_sn ?? '-' }}
                                    </td>
                                    <td>{{ $detail->Metode ?? '-' }}</td>
                                    <td>{{ $detail->Keterangan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data aset</td>
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

            @if ($data->CatatanApproval)
                <div class="form-group">
                    <label class="font-weight-bold">Catatan Approval</label>
                    <div class="border p-3 rounded bg-light text-danger">
                        {{ $data->CatatanApproval }}
                    </div>
                </div>
            @endif

            @if ($data->isAlurBerurutan())
                {{-- Alur 6 tingkat berurutan:
                     Sign1 SMI -> Sign2 Karu/Kanit -> Sign3 Kadiv Penunjang Medis
                     -> Sign4 Kadiv Pelayanan Medis -> Sign5 Kadiv Keuangan -> Sign6 Direktur --}}
                @php
                    $tahapAktif = $data->tahapAktif();
                    $urlAktif = $data->urlApprovalAktif();
                @endphp

                <div class="form-group mt-4">
                    <label class="font-weight-bold">Progress Tanda Tangan</label>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 10%;">Kolom</th>
                                    <th>Tahap</th>
                                    <th>Status</th>
                                    <th>Penanda Tangan</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\PenghapusanAset::TAHAP as $key => $config)
                                    @php
                                        $acc = $data->accTahap($key);
                                        $accPada = $data->accPadaTahap($key);
                                        $namaTahap = $data->namaTahap($key);
                                    @endphp
                                    <tr @if ($key === $tahapAktif) class="table-warning" @endif>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><code>{{ $config['sign'] }}</code></td>
                                        <td>{{ $config['label'] }}</td>
                                        <td>
                                            @if ($acc === 'Y')
                                                <span class="badge badge-success">Ditandatangani</span>
                                            @elseif ($acc === 'N')
                                                <span class="badge badge-danger">Ditolak</span>
                                            @elseif ($key === $tahapAktif)
                                                <span class="badge badge-warning">Menunggu</span>
                                            @else
                                                <span class="badge badge-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $namaTahap ?: '-' }}</td>
                                        <td>{{ $accPada ? \Carbon\Carbon::parse($accPada)->format('d-m-Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($urlAktif)
                    {{-- Link untuk tahap yang sedang menunggu. Setelah Sign1 ditandatangani,
                         link ini otomatis menjadi link untuk Sign2, dan seterusnya. --}}
                    <div class="form-group">
                        <label class="font-weight-bold">
                            Link Tanda Tangan
                            {{ \App\Models\PenghapusanAset::TAHAP[$tahapAktif]['sign'] }}
                            ({{ \App\Models\PenghapusanAset::konfigurasiTahap($tahapAktif)['label'] }})
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="linkApproval" readonly
                                value="{{ $urlAktif }}">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="salinLinkApproval()">
                                    <i class="fa fa-copy"></i> Salin
                                </button>
                                <a href="{{ $urlAktif }}" target="_blank" class="btn btn-outline-success">
                                    <i class="fa fa-external-link-alt"></i> Buka
                                </a>
                            </div>
                        </div>
                        <small class="text-muted">Bagikan link ini ke penanda tangan. Tidak perlu login.</small>
                    </div>
                @endif

                <div class="row text-center mt-5">
                    @foreach (\App\Models\PenghapusanAset::TAHAP as $key => $config)
                        @php
                            $acc = $data->accTahap($key);
                            $sign = $data->signTahap($key);
                            $penandatangan = $data->namaTahap($key);
                            $accPada = $data->accPadaTahap($key);
                        @endphp
                        <div class="col-md-4 mb-4">
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ $config['sign'] }}</p>
                            <p class="mb-4" style="font-weight: bold;">{{ $config['jabatan'] }},</p>
                            @if ($acc === 'Y')
                                @if ($sign)
                                    <div style="height: 150px;">
                                        <img src="{{ asset('storage/' . $sign) }}"
                                            alt="Tanda Tangan {{ $config['label'] }}" style="max-height: 150px;">
                                    </div>
                                @else
                                    <div style="height: 150px;">
                                        <img src="{{ asset('assets/img/approved.png') }}"
                                            alt="Tanda Tangan {{ $config['label'] }}" style="max-height: 150px;">
                                    </div>
                                @endif
                            @elseif ($acc === 'N')
                                <div style="height: 150px;">
                                    <img src="{{ asset('assets/img/rejected.png') }}"
                                        alt="Tanda Tangan {{ $config['label'] }}" style="max-height: 150px;">
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center text-muted"
                                    style="height: 150px;">Belum ditandatangani</div>
                            @endif
                            <hr style="width: 80%;">
                            <p class="mt-2 mb-1 font-weight-bold">( {{ $penandatangan ?: '-' }} )</p>
                            @if ($accPada)
                                <small class="text-muted">
                                    {{ $acc === 'N' ? 'Ditolak' : 'Ditandatangani' }}
                                    {{ \Carbon\Carbon::parse($accPada)->format('d-m-Y H:i') }}
                                </small>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Alur lama: data historis (Sign1 = Manager, Sign2 = SMI, Sign3 = ttd Karu) --}}
                <div class="row text-center mt-5">
                    <div class="col-md-4">
                        <p class="mb-4" style="font-weight: bold;">Mengetahui Karu/Kanit,</p>
                        @if (!is_null($data->AccKaruPada))
                            @if (!empty($data->Sign3))
                                <div style="height: 150px;">
                                    <img src="{{ asset('storage/' . $data->Sign3) }}" alt="Tanda Tangan"
                                        style="max-height: 150px;">
                                </div>
                            @else
                                <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                            @endif
                        @endif
                        <hr style="width: 80%;">
                        <p class="mt-2 font-weight-bold">( {{ $data->NamaKaru ?? '-' }} )</p>
                    </div>

                <div class="col-md-4">
                    <p class="mb-4" style="font-weight: bold;">Mengetahui SMI,</p>
                    @if ($data->AccSmi === 'Y')
                        <div style="height: 150px;">
                            <img src="{{ asset('assets/img/approved.png') }}" alt="Tanda Tangan SMI"
                                style="max-height: 150px;">
                        </div>
                    @elseif($data->AccSmi === 'N')
                        <div style="height: 150px;">
                            <img src="{{ asset('assets/img/rejected.png') }}" alt="Tanda Tangan SMI"
                                style="max-height: 150px;">
                        </div>
                    @else
                        <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                    @endif
                    <hr style="width: 80%;">
                    <p class="mt-2 font-weight-bold">( {{ $data->getSmi->name ?? '-' }} )</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-4" style="font-weight: bold;">Mengetahui Meneger Penunjang dan Pelayanan Medis,</p>
                    @if ($data->AccManager === 'Y')
                        <div style="height: 150px;">
                            <img src="{{ asset('assets/img/approved.png') }}" alt="Tanda Tangan Manager"
                                style="max-height: 150px;">
                        </div>
                    @elseif($data->AccManager === 'N')
                        <div style="height: 150px;">
                            <img src="{{ asset('assets/img/rejected.png') }}" alt="Tanda Tangan Manager"
                                style="max-height: 150px;">
                        </div>
                    @else
                        <div style="height: 80px;" class="text-muted">Tidak ada gambar tanda tangan</div>
                    @endif

                    <hr style="width: 80%;">
                    <p class="mt-2 font-weight-bold">( {{ $data->getManager->name ?? '-' }} )</p>
                </div>

                </div>
            @endif

            @if ($data->Status == 'disetujui')
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="{{ route('pa.cetak', $data->id) }}" class="btn btn-info w-100">Cetak Pengajuan</a>
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection
@push('after-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function salinLinkApproval() {
            var input = document.getElementById('linkApproval');

            if (!input) {
                return;
            }

            input.select();
            input.setSelectionRange(0, 99999);

            function berhasil() {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersalin',
                    text: 'Link tanda tangan sudah disalin.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }

            if (navigator.clipboard) {
                navigator.clipboard.writeText(input.value).then(berhasil);
            } else {
                document.execCommand('copy');
                berhasil();
            }
        }
    </script>
@endpush
