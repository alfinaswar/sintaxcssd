<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\Flipbook;
use App\Models\MasterDepartemenModel;
use App\Models\PenghapusanAset;
use App\Models\PenghapusanAsetDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                ->orderBy('id', 'desc');

            // Filter berdasarkan kode RS
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
                    return $btnShow . ' ' . $btnEdit . ' ' . $btnDelete;
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
        $departemen = MasterDepartemenModel::where('KodeRS', auth()->user()->kodeRS)->get();
        $item = DataInventaris::orderBy('nama', 'ASC')->get();
        return view('penghapusan-aset.create', compact('departemen', 'item'));
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
            'Departemen' => $data['Departemen'] ?? null,
            'Unit' => $data['Unit'] ?? null,
            'Tanggal' => $data['Tanggal'] ?? null,
            'Status' => 'pengajuan',
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
            PenghapusanAsetDetail::create([
                'idPenghapusan' => $idPenghapusan ? $idPenghapusan->id : null,
                'AssetId' => $detail,
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

        // Ambil nomor terakhir di bulan dan tahun ini
        $last = PenghapusanAset::whereRaw("LEFT(NomorPengajuan, 6) = ?", [$prefix])
            ->orderBy('NomorPengajuan', 'desc')
            ->first();

        if ($last && strlen($last->NomorPengajuan) >= 10) {
            $lastNumber = intval(substr($last->NomorPengajuan, 6, 4));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nomorBaru = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        return $nomorBaru;
    }


    public function show($id)
    {
        $data = PenghapusanAset::with('getDepartemen', 'getDiajukan', 'getManager', 'getSmi', 'getRs', 'getDetail', 'getDetail.getItem')->where('id', $id)->first();
        return view('penghapusan-aset.show', compact('data'));
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
        $data = PenghapusanAset::with('getDetail')->findOrFail($id);
        return view('penghapusan-aset.edit', compact('data'));
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
        $penghapusanAset = PenghapusanAset::findOrFail($id);
        $penghapusanAset->update([
            'Departemen' => $data['Departemen'] ?? null,
            'Unit' => $data['Unit'] ?? null,
            'Tanggal' => $data['Tanggal'] ?? null,
            'Catatan' => $data['Catatan'] ?? null,

        ]);

        PenghapusanAsetDetail::where('idPenghapusan', $id)->delete();

        foreach ($data['AssetId'] as $key => $detail) {
            PenghapusanAsetDetail::create([
                'idPenghapusan' => $id,
                'AssetId' => $detail,
                'Qty' => $detail['Qty'] ?? 1,
                'Keterangan' => $request->Keterangan[$key] ?? null,
            ]);
        }

        return redirect()->route('pa.index')->with('success', 'Data penghapusan aset berhasil diperbarui.');
    }
    public function AccPengajuan(Request $request, $id)
    {
        $penghapusanAset = PenghapusanAset::find($id);
        $penghapusanAset->update([
            'Sign1' => auth()->user()->id,
            'AccManager' => 'Y',
            'AccManagerPada' => now(),
            'Status' => 'disetujui',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan telah disetujui oleh Manager.'
        ], 200);
    }
    public function AccPengajuanSmi(Request $request, $id)
    {
        $penghapusanAset = PenghapusanAset::with('getDetail')->find($id);
        $penghapusanAset->update([
            'Sign1' => auth()->user()->id,
            'AccSmi' => 'Y',
            'AccSmiPada' => now(),
            'Status' => 'proses',
        ]);

        foreach ($penghapusanAset->getDetail as $key => $value) {
            $cek = DataInventaris::with('DataMaintenance', 'getFormPembersihan')->find($value->AssetId);
            $cek->DataMaintenance->delete();
            $cek->getFormPembersihan->delete();
            $cek->delete();
        }
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
