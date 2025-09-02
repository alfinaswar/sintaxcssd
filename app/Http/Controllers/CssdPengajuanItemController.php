<?php

namespace App\Http\Controllers;

use App\Models\cssdMasterItem;
use App\Models\cssdMasterSupplier;
use App\Models\cssdMerk;
use App\Models\cssdPengajuanItem;
use App\Models\cssdPengajuanItemDetail;
use App\Models\MasterItemGroup;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CssdPengajuanItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (auth()->user() && auth()->user()->role == 'admin') {
                $data = cssdPengajuanItem::with('getDiajukan', 'getRs')->orderBy('id', 'desc')->get();
            } else {
                $data = cssdPengajuanItem::with('getDiajukan', 'getRs')
                    ->where('KodeRs', auth()->user()->kodeRS)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->Status == 'Y') {
                        $btnDelete = '';
                        $btnEdit = '';
                    } else {
                        $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                        $btnEdit = '<a href="' . route('pengajuan-nama-item-cssd.edit', $row->id) . '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="fa fa-edit"></i></a>';
                    }
                    $btn = $btnEdit . ' ' . $btnDelete;
                    return $btn;
                })
                ->editColumn('Kode', function ($row) {
                    if (auth()->user() && auth()->user()->role == 'admin') {
                        return '<a href="' . route('pengajuan-nama-item-cssd.show', $row->id) . '" class="text-primary">' . e($row->Kode) . '</a>';
                    } else {
                        return e($row->Kode);
                    }
                })
                ->editColumn('Status', function ($row) {
                    if ($row->Status == 'Y') {
                        return '<span class="badge badge-success">Disetujui</span>';
                    } elseif ($row->Status == 'N') {
                        return '<span class="badge badge-danger">Ditolak</span>';
                    } else {
                        return '<span class="badge badge-secondary">Proses Pengajuan</span>';
                    }
                })

                ->rawColumns(['action', 'Status', 'Kode'])
                ->make(true);
        }

        return view('cssd.master-item.pengajuan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $masterMerek = cssdMerk::orderBy('Merk', 'ASC')->get();
        $masterSupplier = cssdMasterSupplier::orderBy('Nama', 'ASC')->get();
        return view('cssd.master-item.pengajuan.create', compact('masterMerek', 'masterSupplier'));
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

        $data['Keterangan'] = $request->Catatan ?? null;
        $data['KodeRs'] = auth()->user()->kodeRS ?? null;
        $data['idUser'] = auth()->user()->id ?? null;
        $data['Kode'] = $this->generatenomor();

        cssdPengajuanItem::create($data);

        // Ambil id pengajuan terbaru
        $idpengajuan = cssdPengajuanItem::latest()->first()->id;
        foreach ($request->Nama as $key => $value) {
            cssdPengajuanItemDetail::create([
                'IdPengajuan' => $idpengajuan,
                'NamaItem' => $value,
                'Merk' => $request->Merk[$key],
                'Supplier' => $request->Supplier[$key] ?? null,
            ]);
        }
        return redirect()->route('pengajuan-nama-item-cssd.index')->with('success', 'Data berhasil ditambahkan');
    }
    private function generatenomor()
    {
        // Format: AB/tahun/bulan/nourut, reset tiap bulan
        $prefix = 'AB';
        $tahun = date('Y');
        $bulan = date('m');

        // Ambil nomor urut terakhir untuk bulan dan tahun ini
        $last = cssdPengajuanItem::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('id', 'desc')
            ->first();

        if ($last && isset($last->Kode)) {
            $parts = explode('/', $last->Kode);
            $nourut = isset($parts[3]) ? intval($parts[3]) + 1 : 1;
        } else {
            $nourut = 1;
        }

        $nourut_str = str_pad($nourut, 4, '0', STR_PAD_LEFT);

        return "{$prefix}/{$tahun}/{$bulan}/{$nourut_str}";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = cssdPengajuanItem::with('getDiajukan', 'getRs', 'getDetail', 'getDetail.getMerk')->find($id);
        return view('cssd.master-item.pengajuan.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $masterMerek = cssdMerk::orderBy('Merk', 'ASC')->get();
        $data = cssdPengajuanItem::with('getDiajukan', 'getRs', 'getDetail')->find($id);
        $masterSupplier = cssdMasterSupplier::orderBy('Nama', 'ASC')->get();
        return view('cssd.master-item.pengajuan.edit', compact('data', 'masterMerek', 'masterSupplier'));
    }
    public function AccPengajuan(Request $request, $id)
    {
        // dd($request->all());
        $pengajuan = cssdPengajuanItem::with('getDetail')->find($id);

        $pengajuan->update([
            'ApproveBy' => auth()->user()->id,
            'ApproveAt' => now(),
            'Revisi' => $request->Revisi ?? null,
            'Status' => 'Y',
        ]);
        foreach ($pengajuan->getDetail as $key => $value) {
            MasterItemGroup::create([
                'Nama' => $value->NamaItem,
                'Merk' => $value->Merk,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan telah disetujui dan item berhasil ditambahkan'
        ], 200);
    }
    public function Print($id)
    {
        $data = cssdPengajuanItem::with('getDiajukan', 'getRs', 'getDetail', 'getDetail.getMerk')->find($id);

        $pdf = Pdf::loadView('cssd.master-item.pengajuan.cetak', compact('data'));
        return $pdf->stream('Pengajuan Item Baru' . $data->Kode . '.pdf');
    }
    public function TolakPengajuan(Request $request, $id)
    {
        $pengajuan = cssdPengajuanItem::find($id);
        $pengajuan->update([
            'ApproveBy' => auth()->user()->id,
            'ApproveAt' => now(),
            'Revisi' => $request->Revisi ?? null,
            'Status' => 'N',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan telah ditolak oleh Manager.'
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $pengajuan = cssdPengajuanItem::findOrFail($id);
        $pengajuan->Keterangan = $request->Catatan ?? null;
        $pengajuan->KodeRs = auth()->user()->kodeRS ?? null;
        $pengajuan->Tanggal = $request->Tanggal ?? $pengajuan->Tanggal;
        $pengajuan->save();

        cssdPengajuanItemDetail::where('IdPengajuan', $pengajuan->id)->delete();

        if ($request->Nama && is_array($request->Nama)) {
            foreach ($request->Nama as $key => $value) {
                cssdPengajuanItemDetail::create([
                    'IdPengajuan' => $pengajuan->id,
                    'NamaItem' => $value,
                    'Merk' => $request->Merk[$key] ?? null,
                    'Supplier' => $request->Supplier[$key] ?? null,
                ]);
            }
        }
        return redirect()->route('pengajuan-nama-item-cssd.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = cssdPengajuanItem::with('getDiajukan', 'getRs', 'getDetail')->find($id);
        if ($data) {
            foreach ($data->getDetail as $detail) {
                $detail->delete();
            }
            $data->delete();
        }
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
