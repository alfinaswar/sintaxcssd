<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\GudangBarang;
use App\Models\MasterDepartemenModel;
use App\Models\MasterGudang;
use App\Models\PenghapusanAset;
use App\Models\PenghapusanAsetDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PenghapusanAsetController extends Controller
{
    /**
     * Status yang masih boleh dihapus (belum masuk proses persetujuan).
     *
     * @var array
     */
    private const STATUS_BOLEH_DIHAPUS = ['draft', 'pengajuan', 'smi'];

    /**
     * Daftar halaman.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = PenghapusanAset::with('getDepartemen', 'getDiajukan', 'getRs')
                ->latest('id');

            if (strtolower(auth()->user()->role) !== 'admin') {
                $query->where('KodeRS', auth()->user()->kodeRS);
            }

            if ($request->filled('rs')) {
                $query->where('KodeRS', $request->rs);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnShow = '<a href="' . route('pa.show', $row->id) . '" class="btn btn-outline-info btn-icon" title="Show"><i class="fa fa-eye"></i></a>';
                    $btnEdit = '<a href="' . route('pa.edit', $row->id) . '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="fa fa-edit"></i></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" title="Hapus Data"><i class="fa fa-trash"></i></button>';

                    $btnLink = '';
                    $url = $row->urlApprovalAktif();
                    if ($url) {
                        $label = PenghapusanAset::konfigurasiTahap($row->tahapAktif())['label'];
                        $btnLink = '<a href="' . $url . '" target="_blank" class="btn btn-outline-success btn-icon" title="Link tanda tangan ' . e($label) . '"><i class="fa fa-link"></i></a>';
                    }

                    return $btnShow . ' ' . $btnEdit . ' ' . $btnDelete . ' ' . $btnLink;
                })
                ->editColumn('Status', function ($row) {
                    return '<span class="badge ' . $row->badgeStatus() . '">' . e($row->labelStatus()) . '</span>';
                })
                ->editColumn('Departemen', function ($row) {
                    return $row->getDepartemen->nama ?? '-';
                })
                ->addColumn('Tahap', function ($row) {
                    $tahap = $row->tahapAktif();

                    if ($tahap) {
                        return PenghapusanAset::nomorTahap($tahap) . ' / 6 - ' . e(PenghapusanAset::konfigurasiTahap($tahap)['label']);
                    }

                    return $row->Status === 'disetujui' ? 'Selesai' : '-';
                })
                ->addColumn('DiajukanOleh', function ($row) {
                    return $row->getDiajukan->name ?? '-';
                })
                ->rawColumns(['action', 'Status'])
                ->make(true);
        }

        return view('penghapusan-aset.index');
    }

    /**
     * Form pengajuan baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gudang = MasterGudang::get();
        $departemen = MasterDepartemenModel::where('KodeRS', auth()->user()->kodeRS)->get();
        $item = DataInventaris::orderBy('nama', 'ASC')->get();

        return view('penghapusan-aset.create', compact('departemen', 'item', 'gudang'));
    }

    /**
     * Simpan pengajuan baru. Langsung masuk antrean tahap 1 (SMI).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $penghapusan = PenghapusanAset::create([
            'NomorPengajuan' => $this->GenerateNumber(),
            'NamaGudang' => $data['Gudang'] ?? null,
            'Departemen' => $data['Departemen'] ?? null,
            'Unit' => $data['Unit'] ?? null,
            'Tanggal' => $data['Tanggal'] ?? null,
            'Status' => 'smi',
            'VersiAlur' => PenghapusanAset::VERSI_6_TAHAP,
            'DiajukanOleh' => auth()->user()->id,
            'KodeRS' => auth()->user()->kodeRS,
            'Catatan' => $data['Catatan'] ?? null,
        ]);

        foreach ((array) ($data['AssetId'] ?? []) as $key => $detail) {
            $cariasset = DataInventaris::where('kode_item', $detail)->first();

            PenghapusanAsetDetail::create([
                'idPenghapusan' => $penghapusan->id,
                'AssetId' => $detail,
                'NoInventaris' => $cariasset->no_inventaris ?? 'null',
                'SerialNumber' => $cariasset->no_sn ?? 'null',
                'Metode' => $request->MetodePenghapusan[$key] ?? ($cariasset->MetodePenghapusan ?? 'null'),
                'Qty' => $detail['Qty'] ?? 1,
                'Keterangan' => $request->Keterangan[$key] ?? null,
            ]);
        }

        return redirect()->route('pa.index')->with('success', 'Pengajuan penghapusan aset berhasil disimpan.');
    }

    /**
     * Nomor pengajuan otomatis.
     *
     * @return string
     */
    private function GenerateNumber()
    {
        $prefix = 'DEL' . date('y') . date('m');

        $last = PenghapusanAset::orderBy('id', 'desc')->first();
        $nextId = $last ? $last->id + 1 : 1;

        return $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    /* ---------------------------------------------------------------------
     | Tanda tangan 6 tahap lewat link (tanpa login, tanpa token):
     | Sign1 SMI -> Sign2 Karu/Kanit -> Sign3 Kadiv Penunjang Medis
     | -> Sign4 Kadiv Pelayanan Medis -> Sign5 Kadiv Keuangan -> Sign6 Direktur
     |
     | Link memakai id pengajuan saja. Kalau Sign sebelumnya sudah terisi,
     | otomatis tahap berikutnya yang menunggu tanda tangan.
     | ------------------------------------------------------------------ */

    /**
     * Ambil data pengajuan beserta relasi untuk halaman tanda tangan.
     *
     * @param  int  $id
     * @return \App\Models\PenghapusanAset
     */
    private function cariUntukApproval($id)
    {
        return PenghapusanAset::with('getGudang', 'getDepartemen', 'getDiajukan', 'getRs', 'getDetail', 'getDetail.getItem')
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * Halaman tanda tangan publik (link dibagikan, tanpa login).
     *
     * @param  int  $id
     * @param  string  $tahap
     * @return \Illuminate\Http\Response
     */
    public function approvalPublik($id, $tahap)
    {
        $data = $this->cariUntukApproval($id);

        $konfigurasi = PenghapusanAset::konfigurasiTahap($tahap);

        abort_unless($konfigurasi, 404, 'Tahap tanda tangan tidak dikenal.');

        return view('penghapusan-aset.approval', [
            'data' => $data,
            'tahap' => $tahap,
            'konfigurasi' => $konfigurasi,
            'nomorTahap' => PenghapusanAset::nomorTahap($tahap),
            // Form hanya muncul kalau ini memang tahap yang sedang menunggu
            'bolehProses' => $data->menungguTahap($tahap),
            'sudahDitandatangani' => $data->accTahap($tahap) === 'Y',
            'ditolak' => $data->accTahap($tahap) === 'N',
            'tahapAktif' => $data->tahapAktif(),
            'urlTahapAktif' => $data->urlApprovalAktif(),
        ]);
    }

    /**
     * Halaman tanda tangan dari dalam aplikasi (harus login).
     * Selalu mengarah ke tahap yang sedang menunggu.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approval($id)
    {
        $data = $this->cariUntukApproval($id);

        $tahap = $data->tahapAktif();

        if (! $tahap) {
            return redirect()->route('pa.show', $id)
                ->with('error', 'Pengajuan ini tidak sedang menunggu tanda tangan.');
        }

        return redirect($data->urlApproval($tahap));
    }

    /**
     * Endpoint lama: arahkan ke tahap Karu pada alur baru.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approvalKaru($id)
    {
        $data = $this->cariUntukApproval($id);

        return redirect($data->urlApproval('karu'));
    }

    /**
     * Endpoint lama: simpan tanda tangan Karu (diteruskan ke alur baru).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approvalKaruSubmit(Request $request, $id)
    {
        $request->validate([
            'action_type' => 'required|in:approve,reject',
            'nama_penandatangan' => 'required|string|max:255',
            'signature' => 'required|string',
        ]);

        return $this->prosesApproval($request, $id, 'karu');
    }

    /**
     * Proses tanda tangan / penolakan untuk tahap apa pun.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $tahap
     * @return \Illuminate\Http\JsonResponse
     */
    public function prosesApproval(Request $request, $id, $tahap)
    {
        $konfigurasi = PenghapusanAset::konfigurasiTahap($tahap);

        if (! $konfigurasi) {
            return response()->json([
                'success' => false,
                'message' => 'Tahap tanda tangan tidak dikenal.',
            ], 422);
        }

        $data = PenghapusanAset::find($id);

        if (! $data) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'action_type' => 'required|in:approve,reject',
            'nama_penandatangan' => 'required|string|max:255',
            'signature' => 'required|string',
            'catatan' => 'nullable|string|max:1000',
        ]);

        if (! $data->isAlurBerurutan()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan ini memakai alur lama dan tidak dapat diproses di alur berurutan.',
            ], 422);
        }

        // Satu-satunya penentu boleh/tidak: urutan tahap
        if (! $data->menungguTahap($tahap)) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan ini tidak sedang menunggu ' . $konfigurasi['label']
                    . '. Tahap saat ini: ' . $data->labelStatus() . '.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $signaturePath = $this->simpanTandaTangan($request->signature, $tahap, $data->id);

            $kolomAcc = PenghapusanAset::kolomAccTahap($tahap);
            $kolomAccPada = PenghapusanAset::kolomAccPadaTahap($tahap);

            if (! $kolomAcc || ! $kolomAccPada) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi kolom tanda tangan untuk tahap ini tidak ditemukan.',
                ], 500);
            }

            // Path ttd ke kolom Sign tahap ini, nama approver ke Acc*Oleh
            $data->{$konfigurasi['sign']} = $signaturePath;
            $data->{$konfigurasi['acc_oleh']} = $request->nama_penandatangan;
            $data->{$kolomAcc} = $request->action_type === 'approve' ? 'Y' : 'N';
            $data->{$kolomAccPada} = now();

            if ($request->action_type === 'approve') {
                $data->Status = PenghapusanAset::statusSetelahTahap($tahap);

                // Tahap terakhir (Direktur): barang pindah ke gudang
                if ($data->Status === 'disetujui') {
                    $this->pindahkanAsetKeGudang($data);
                }

                $message = 'Pengajuan ditandatangani oleh ' . $konfigurasi['label'] . '.';
            } else {
                $data->Status = 'ditolak';
                $data->CatatanApproval = $request->catatan
                    ? '[' . $konfigurasi['label'] . '] ' . $request->catatan
                    : '[' . $konfigurasi['label'] . '] Ditolak oleh ' . $request->nama_penandatangan;
                $message = 'Pengajuan ditolak oleh ' . $konfigurasi['label'] . '.';
            }

            $data->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => $this->linkSetelahApproval($data),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses tanda tangan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Link yang dibuka setelah approver menandatangani tahapnya.
     * Diarahkan ke tahap berikutnya supaya link Sign selanjutnya bisa disalin.
     *
     * @param  \App\Models\PenghapusanAset  $data
     * @return string
     */
    private function linkSetelahApproval(PenghapusanAset $data)
    {
        if (auth()->check()) {
            return route('pa.show', $data->id);
        }

        $tahapBerikut = $data->tahapAktif();

        return $data->urlApproval($tahapBerikut ?: 'direktur');
    }

    /**
     * Simpan tanda tangan base64 ke storage publik.
     *
     * @param  string  $signatureData
     * @param  string  $tahap
     * @param  int  $id
     * @return string path relatif terhadap disk public
     */
    private function simpanTandaTangan($signatureData, $tahap, $id)
    {
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $signatureData);
        $image = str_replace(' ', '+', $image);

        $path = 'penghapusan-aset/signatures/ttd_' . $tahap . '_' . $id . '_' . time() . '.png';

        Storage::disk('public')->put($path, base64_decode($image));

        return $path;
    }

    /**
     * Pindahkan aset ke GudangBarang setelah Direktur (tahap terakhir) menandatangani.
     *
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return void
     */
    private function pindahkanAsetKeGudang(PenghapusanAset $penghapusanAset)
    {
        foreach ($penghapusanAset->getDetail as $value) {
            $cek = DataInventaris::with('DataMaintenance', 'getFormPembersihan')
                ->where('kode_item', $value->AssetId)
                ->first();

            if (! $cek) {
                continue;
            }

            GudangBarang::create([
                'kode_item'      => $cek->kode_item ?? null,
                'RO2ID'          => $cek->RO2ID ?? null,
                'harga'          => $cek->harga ?? null,
                'ROID'           => $cek->ROID ?? null,
                'assetID'        => $cek->assetID ?? null,
                'nama'           => $cek->nama ?? null,
                'merk'           => $cek->merk ?? null,
                'real_name'      => $cek->real_name ?? null,
                'no_inventaris'  => $cek->no_inventaris ?? null,
                'no_sn'          => $cek->no_sn ?? null,
                'tanggal_beli'   => $cek->tanggal_beli ?? null,
                'nama_rs'        => $cek->nama_rs ?? null,
                'departemen'     => $cek->departemen ?? null,
                'unit'           => $cek->unit ?? null,
                'pengguna'       => $cek->pengguna ?? null,
                'gambar'         => $cek->gambar ?? null,
                'tgl_kalibrasi'  => $cek->tgl_kalibrasi ?? null,
                'tgl_expire'     => $cek->tgl_expire ?? null,
                'dokumen'        => $cek->dokumen ?? null,
                'keterangan'     => $cek->keterangan ?? null,
                'isKalibrasi'    => $cek->isKalibrasi ?? null,
                'manualbook'     => $cek->manualbook ?? null,
                'klasifikasi'    => $cek->klasifikasi ?? null,
                'UserCreate'     => $cek->UserCreate ?? null,
                'UserId'         => $cek->UserId ?? null,
                'UpdateName'     => $cek->UpdateName ?? null,
                'id_gudang'      => $penghapusanAset->NamaGudang ?? null,
                'qty'            => $value->Qty ?? 1,
                'id_penghapusan' => $penghapusanAset->id,
            ]);

            $cek->delete();
        }
    }

    /**
     * Endpoint tanda tangan per tahap (legacy).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $tahap
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveTahap(Request $request, $id, $tahap)
    {
        $request->merge(['action_type' => 'approve']);

        return $this->prosesApproval($request, $id, $tahap);
    }

    /**
     * Endpoint penolakan per tahap (legacy).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $tahap
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectTahap(Request $request, $id, $tahap)
    {
        $request->merge(['action_type' => 'reject']);

        return $this->prosesApproval($request, $id, $tahap);
    }

    /* ---------------------------------------------------------------------
     | Alias endpoint lama (dipertahankan agar tautan lama tidak 500)
     | ------------------------------------------------------------------ */

    /**
     * Alias lama: tanda tangan tahap SMI.
     */
    public function AccPengajuanSmi(Request $request, $id)
    {
        return $this->approveTahap($request, $id, 'smi');
    }

    /**
     * Alias lama: tanda tangan tahap yang sedang menunggu.
     */
    public function AccPengajuan(Request $request, $id)
    {
        $data = PenghapusanAset::find($id);

        return $this->approveTahap($request, $id, $data ? $data->tahapAktif() : null);
    }

    /**
     * Alias lama: tolak tahap yang sedang menunggu.
     */
    public function TolakPengajuan(Request $request, $id)
    {
        $data = PenghapusanAset::find($id);

        return $this->rejectTahap($request, $id, $data ? $data->tahapAktif() : null);
    }

    /**
     * Alias lama: tolak tahap SMI.
     */
    public function TolakPengajuanSmi(Request $request, $id)
    {
        return $this->rejectTahap($request, $id, 'smi');
    }

    /**
     * Detail pengajuan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->cariUntukApproval($id);

        return view('penghapusan-aset.show', compact('data'));
    }

    /**
     * Cetak pengajuan (PDF).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Print($id)
    {
        $data = $this->cariUntukApproval($id);

        $pdf = Pdf::loadView('penghapusan-aset.print-pengajuan', compact('data'));

        return $pdf->stream('pengajuan_penghapusan_aset_' . $data->NomorPengajuan . '.pdf');
    }

    /**
     * Form edit pengajuan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gudang = MasterGudang::get();
        $data = PenghapusanAset::with('getDetail')->findOrFail($id);

        return view('penghapusan-aset.edit', compact('data', 'gudang'));
    }

    /**
     * Simpan perubahan pengajuan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $penghapusanAset = PenghapusanAset::findOrFail($id);

        $penghapusanAset->update([
            'Departemen' => $data['Departemen'] ?? null,
            'Unit' => $data['Unit'] ?? null,
            'Tanggal' => $data['Tanggal'] ?? null,
            'Catatan' => $data['Catatan'] ?? null,
        ]);

        PenghapusanAsetDetail::where('idPenghapusan', $id)->delete();

        foreach ((array) ($data['AssetId'] ?? []) as $key => $detail) {
            $cariasset = DataInventaris::where('kode_item', $detail)->first();

            PenghapusanAsetDetail::create([
                'idPenghapusan' => $id,
                'AssetId' => $detail,
                'NoInventaris' => $cariasset->no_inventaris ?? 'null',
                'SerialNumber' => $cariasset->no_sn ?? 'null',
                'Metode' => $request->MetodePenghapusan[$key] ?? null,
                'Qty' => $detail['Qty'] ?? 1,
                'Keterangan' => $request->Keterangan[$key] ?? null,
            ]);
        }

        return redirect()->route('pa.index')->with('success', 'Data penghapusan aset berhasil diperbarui.');
    }

    /**
     * Hapus pengajuan (hanya yang belum masuk proses persetujuan).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = PenghapusanAset::with('getDetail')->findOrFail($id);

            if (! in_array($data->Status, self::STATUS_BOLEH_DIHAPUS, true)) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Data tidak dapat dihapus karena sudah masuk proses persetujuan.',
                ], 422);
            }

            DB::beginTransaction();
            $data->getDetail()->delete();
            $data->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Data berhasil dihapus',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Data tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'msg' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
