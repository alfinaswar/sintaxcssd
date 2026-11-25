<?php

namespace App\Http\Controllers;

use App\Models\MasterAlatKso;
use App\Models\MasterMerkKso;
use App\Models\MasterTipeKso;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterTipeKsoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterTipeKso::with(['alat', 'merk'])
                ->where('nama_rs', auth()->user()->kodeRS)
                ->orderBy('id', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('alat_nama', function ($row) {
                    return $row->alat ? e($row->alat->nama) : '-';
                })
                ->addColumn('merk_nama', function ($row) {
                    return $row->merk ? e($row->merk->nama) : '-';
                })
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('master-tipe-kso.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon"><i class="fa fa-cogs"></i></button></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')"><i class="fa fa-times"></i></button>';
                    return $btnEdit . '&nbsp;' . $btnDelete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-tipe-kso.index');
    }

    public function create()
    {
        $alats = MasterAlatKso::where('nama_rs', auth()->user()->kodeRS)->orderBy('nama')->get();
        $merks = MasterMerkKso::where('nama_rs', auth()->user()->kodeRS)->orderBy('nama')->get();
        return view('master-tipe-kso.create', compact('alats', 'merks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_alat_kso_id' => 'required|exists:master_alat_kso,id',
            'master_merk_kso_id' => 'nullable|exists:master_merk_kso,id',
            'nama' => 'required|string|max:255',
        ]);

        MasterTipeKso::create([
            'master_alat_kso_id' => $request->master_alat_kso_id,
            'master_merk_kso_id' => $request->master_merk_kso_id,
            'nama' => $request->nama,
            'nama_rs' => auth()->user()->kodeRS,
        ]);

        return redirect()->route('master-tipe-kso.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = MasterTipeKso::findOrFail($id);
        $alats = MasterAlatKso::where('nama_rs', auth()->user()->kodeRS)->orderBy('nama')->get();
        $merks = MasterMerkKso::where('master_alat_kso_id', $data->master_alat_kso_id)->orderBy('nama')->get();
        return view('master-tipe-kso.update', compact('data', 'alats', 'merks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'master_alat_kso_id' => 'required|exists:master_alat_kso,id',
            'master_merk_kso_id' => 'nullable|exists:master_merk_kso,id',
            'nama' => 'required|string|max:255',
        ]);

        $tipe = MasterTipeKso::findOrFail($id);
        $tipe->update([
            'master_alat_kso_id' => $request->master_alat_kso_id,
            'master_merk_kso_id' => $request->master_merk_kso_id,
            'nama' => $request->nama,
        ]);

        return redirect()->route('master-tipe-kso.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $tipe = MasterTipeKso::findOrFail($id);
        $tipe->delete();

        return response()->json(['status' => 'success']);
    }
}
