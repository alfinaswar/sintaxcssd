<?php

namespace App\Http\Controllers;

use App\Models\MasterMerkKso;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterMerkKsoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterMerkKso::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('master-merk-kso.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon"><i class="fa fa-cogs"></i></button></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')"><i class="fa fa-times"></i></button>';
                    return $btnEdit . '&nbsp;' . $btnDelete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-merk-kso.index');
    }

    public function create()
    {
        return view('master-merk-kso.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:255',
        ]);

        MasterMerkKso::create([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('master-merk-kso.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = MasterMerkKso::findOrFail($id);
        return view('master-merk-kso.update', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:255',
        ]);

        $merk = MasterMerkKso::findOrFail($id);
        $merk->update([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('master-merk-kso.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $merk = MasterMerkKso::findOrFail($id);
        $merk->delete();

        return response()->json(['status' => 'success']);
    }

    public function byAlat($alatId)
    {
        $merks = MasterMerkKso::where('master_alat_kso_id', $alatId)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($merks);
    }
}
