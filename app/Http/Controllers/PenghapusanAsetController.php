<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\Flipbook;
use App\Models\MasterDepartemenModel;
use App\Models\PenghapusanAset;
use App\Models\PenghapusanAsetDetail;
use Illuminate\Http\Request;
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

            $data = PenghapusanAset::with('getDepartemen', 'getDiajukan', 'getRs')->orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('pa.edit', $row->id) . '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="fa fa-edit"></i></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon btn-delete" data-id="' . $row->id . '" title="Hapus"><i class="fa fa-trash"></i></button>';
                    $btn = $btnEdit . ' ' . $btnDelete;
                    return $btn;
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
        ]);
        // Simpan detail penghapusan aset
        // Ambil id penghapusan aset yang baru saja dibuat
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
        // Format: DEL25MMNNNN, contoh: DEL25060123
        $tahun = date('y'); // hanya 2 digit tahun, misal 2025 -> 25
        $bulan = date('m'); // 2 digit bulan
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return \Illuminate\Http\Response
     */
    public function show(PenghapusanAset $penghapusanAset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = PenghapusanAset::with('getDepartemen', 'getDiajukan', 'getRs')->where('id', $id)->first();
        $departemen = MasterDepartemenModel::where('KodeRS', auth()->user()->kodeRS)->get();
        $item = DataInventaris::orderBy('nama', 'ASC')->get();
        return view('penghapusan-aset.edit', compact('data', 'departemen', 'item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PenghapusanAset $penghapusanAset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PenghapusanAset  $penghapusanAset
     * @return \Illuminate\Http\Response
     */
    public function destroy(PenghapusanAset $penghapusanAset)
    {
        //
    }
}
