<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\Flipbook;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = PenghapusanAset::with('getDepartemen', 'getDiajukan', 'getRs')
                ->latest('id');

            if (strtolower(auth()->user()->role) !== 'admin') {
                $query->where('KodeRS', auth()->user()->getKeyName());
            }


            // Tambahan filter manual berdasarkan input 'rs'
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
                    $btnApprovalKaru = '<a href="' . route('pa.approval-karu', $row->id) . '" class="btn btn-outline-success btn-icon" title="Approval Karu"><i class="fa fa-check-square"></i></a>';
                    return $btnShow . ' ' . $btnEdit . ' ' . $btnDelete . ' ' . $btnApprovalKaru;
                })

                ->editColumn('Status', function ($row) {
                    if ($row->Status == 'pengajuan') {
                        return '<span class="badge badge-warning">Pengajuan</span>';
                    } elseif ($row->Status == 'disetujui') {
                        return '<span class="badge badge-success">Disetujui</span>';
                    } elseif ($row->Status == 'ditolak') {
                        return '<span class="badge badge-danger">Ditolak</span>';
                    } elseif ($row->Status == 'proses') {
                        return '<span class="badge badge-info">Proses</span>';
                    } else {
                        return '<span class="badge badge-secondary">' . ucfirst($row->Status) . '</span>';
                    }
                })
                ->editColumn('Departemen', function ($row) {
                    return $row->getDepartemen ? $row->getDepartemen->nama : '-';
                })
                ->addColumn('Sign1', function ($row) {
                    // Sesuaikan dengan field/relasi Sign1 yang ada di model kamu
                    return $row->Sign1 ?? '-';
                })
                ->rawColumns(['action', 'Status'])
                ->make(true);
        }

        return view('penghapusan-aset.index');
    }

    /**
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        PenghapusanAset::create([
            'NomorPengajuan' => $this->GenerateNumber(),
            'NamaGudang' => $data['Gudang'] ?? null,
            'Departemen' => $data['Departemen'] ?? null,
            'Unit' => $data['Unit'] ?? null,
            'Tanggal' => $data['Tanggal'] ?? null,
            'Status' => 'draft',
            'DiajukanOleh' => auth()->user()->id,
            'Sign1' => $data['Sign1'] ?? null,
            'Sign2' => $data['Sign2'] ?? null,
            'Sign3' => $data['Sign3'] ?? null,
            'Sign4' => $data['Sign4'] ?? null,
            'KodeRS' => Auth()->user()->kodeRS,
            'Catatan' => $data['Catatan'] ?? null,
        ]);
        $idPenghapusan = PenghapusanAset::where('DiajukanOleh', auth()->user()->id)->orderBy('id', 'desc')->first();
        foreach ($data['AssetId'] as $key => $detail) {
            $cariasset = DataInventaris::where('kode_item', $detail)->first();
            PenghapusanAsetDetail::create([
                'idPenghapusan' => $idPenghapusan ? $idPenghapusan->id : null,
                'AssetId' => $detail,
                'NoInventaris' => $cariasset->no_inventaris ?? 'null',
                'SerialNumber' => $cariasset->no_sn ?? 'null',
                'Metode' => $cariasset->MetodePenghapusan ?? 'null',
                'Qty' => $detail['Qty'] ?? 1,
                'Keterangan' => $request->Keterangan[$key] ?? null,
            ]);
        }

        return redirect()->route('pa.index')->with('success', 'Pengajuan penghapusan aset berhasil disimpan.');


    }
    private function GenerateNumber()
    {
        $tahun = date('y');
        $bulan = date('m');
        $prefix = 'DEL' . $tahun . $bulan;

        // Ambil id terakhir dari tabel penghapusan_asets
        $last = PenghapusanAset::orderBy('id', 'desc')->first();
        $nextId = $last ? $last->id + 1 : 1;

        $nomorBaru = $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        return $nomorBaru;
    }

    public function approvalKaruSubmit(Request $request, $id)
    {
        $request->validate([
            'action_type' => 'required|in:approve,reject',
            'nama_penandatangan' => 'required|string|max:255',
            'signature' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $data = PenghapusanAset::findOrFail($id);
            // Simpan tanda tangan ke storage
            $signatureData = $request->signature;
            $signatureName = 'signature_karu_' . $id . '_' . time() . '.png';
            $signaturePath = 'penghapusan-aset/signatures/' . $signatureName;

            // Decode base64 dan simpan ke storage
            $image = str_replace('data:image/png;base64,', '', $signatureData);
            $image = str_replace(' ', '+', $image);
            Storage::disk('public')->put($signaturePath, base64_decode($image));

            // Update status berdasarkan action
            if ($request->action_type === 'approve') {
                $data->NamaKaru = $request->nama_penandatangan;
                $data->AccKaru = now();
                $data->Sign3 = $signaturePath;
                $message = 'Pengajuan di acc oleh Karu.';
            } else {
                $data->status_karu = 'rejected';
                $data->rejected_karu_by = $request->nama_penandatangan;
                $data->rejected_karu_at = now();
                $data->signature_karu = $signaturePath;
                $message = 'Pengajuan ditolak oleh Karu.';
            }

            $data->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('pa.approval-karu', $id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses approval: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $data = PenghapusanAset::with('getGudang', 'getDepartemen', 'getDiajukan', 'getManager', 'getSmi', 'getRs', 'getDetail', 'getDetail.getItem')->where('id', $id)->first();
        // dd($data);
        return view('penghapusan-aset.show', compact('data'));
    }
    public function approvalKaru($id)
    {
        $data = PenghapusanAset::with('getGudang', 'getDepartemen', 'getDiajukan', 'getManager', 'getSmi', 'getRs', 'getDetail', 'getDetail.getItem')->where('id', $id)->first();
        return view('penghapusan-aset.approval-karu', compact('data'));
    }
    public function Print($id)
    {
        $data = PenghapusanAset::with(
            'getDepartemen',
            'getDiajukan',
            'getManager',
            'getSmi',
            'getRs',
            'getDetail',
            'getDetail.getItem'
        )->where('id', $id)->first();

        $pdf = Pdf::loadView('penghapusan-aset.print-pengajuan', compact('data'));
        return $pdf->stream('pengajuan_penghapusan_aset_' . $data->NomorPengajuan . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gudang = MasterGudang::get();
        $data = PenghapusanAset::with('getDetail')->findOrFail($id);
        return view('penghapusan-aset.edit', compact('data', 'gudang'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        // dd($data);
        $penghapusanAset = PenghapusanAset::findOrFail($id);
        $penghapusanAset->update([
            'Departemen' => $data['Departemen'] ?? null,
            'Unit' => $data['Unit'] ?? null,
            'Tanggal' => $data['Tanggal'] ?? null,
            'Catatan' => $data['Catatan'] ?? null,

        ]);

        PenghapusanAsetDetail::where('idPenghapusan', $id)->delete();

        foreach ($data['AssetId'] as $key => $detail) {
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
    public function AccPengajuan(Request $request, $id)
    {
        $penghapusanAset = PenghapusanAset::find($id);
        // dd($penghapusanAset);
        $penghapusanAset->update([
            'Sign1' => auth()->user()->id,
            'AccManager' => 'Y',
            'AccManagerPada' => now(),
            'Status' => 'disetujui',
        ]);
        foreach ($penghapusanAset->getDetail as $key => $value) {
            $cek = DataInventaris::with('DataMaintenance', 'getFormPembersihan')->where('kode_item', $value->AssetId)->first();

            // Data $cek:
            // id, kode_item, RO2ID, harga, ROID, assetID, nama, merk, real_name, no_inventaris, no_sn, tanggal_beli,
            // nama_rs, departemen, unit, pengguna, gambar, tgl_kalibrasi, tgl_expire, dokumen,
            // keterangan, isKalibrasi, manualbook, klasifikasi, UserCreate, UserId

            GudangBarang::create([
                'kode_item'      => $cek->kode_item ?? null,
                'RO2ID'          => $cek->RO2ID ?? null,
                'harga'          => $cek->harga ?? null,
                'ROID'           => $cek->ROID ?? null,
                'assetID'        => $cek->assetID ?? null,
                'nama'    => $cek->nama ?? null,
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
        if ($cek) {
            $cek->delete();
        }

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan telah disetujui oleh Manager dan inventaris telah dipindahkan ke GudangBarang.'
        ], 200);
    }
    public function AccPengajuanSmi(Request $request, $id)
    {
        $penghapusanAset = PenghapusanAset::with('getDetail')->find($id);
        // dd($penghapusanAset);
        $penghapusanAset->update([
            'Sign2' => auth()->user()->id,
            'AccSmi' => 'Y',
            'AccSmiPada' => now(),
            'Status' => 'proses',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan telah disetujui oleh SMI.'
        ], 200);
    }
    public function TolakPengajuan(Request $request, $id)
    {
        $penghapusanAset = PenghapusanAset::find($id);
        $penghapusanAset->update([
            'Sign1' => auth()->user()->id,
            'AccManager' => 'N',
            'AccManagerPada' => now(),
            'Status' => 'ditolak',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan telah ditolak oleh Manager.'
        ], 200);
    }
    public function TolakPengajuanSmi(Request $request, $id)
    {
        $penghapusanAset = PenghapusanAset::find($id);
        $penghapusanAset->update([
            'Sign2' => auth()->user()->id,
            'AccSmi' => 'N',
            'AccSmiPada' => now(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan telah ditolak oleh SMI.'
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = PenghapusanAset::with('getDetail')->findOrFail($id);
            if ($data->Status == "disetujui") {
                return response()->json([
                    'success' => false,
                    'msg' => 'Data tidak dapat dihapus karena sudah disetujui'
                ], 422);
            }
            DB::beginTransaction();
            $data->getDetail()->delete();
            $data->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Data berhasil dihapus'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Data tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

}
