<?php

namespace App\Http\Controllers;

use App\Models\MasterAlatKso;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterAlatKsoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterAlatKso::where('nama_rs', auth()->user()->kodeRS)
                ->orderBy('id', 'desc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('nama', function ($row) {
                    return '<a href="javascript:;" onclick="showDetail(' . $row->id . ')" class="kt-link kt-font-bold">' . e($row->nama) . '</a>';
                })
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('master-alat-kso.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon"><i class="fa fa-cogs"></i></button></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')"><i class="fa fa-times"></i></button>';
                    return $btnEdit . '&nbsp;' . $btnDelete;
                })
                ->rawColumns(['nama', 'action'])
                ->make(true);
        }

        return view('master-alat-kso.index');
    }

    public function create()
    {
        return view('master-alat-kso.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        MasterAlatKso::create([
            'nama' => $request->nama,
            'nama_rs' => auth()->user()->kodeRS,
        ]);

        return redirect()->route('master-alat-kso.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
        $alat = MasterAlatKso::with(['merks', 'tipes.merk'])->findOrFail($id);

        return response()->json([
            'alat' => $alat,
            'merks' => $alat->merks,
            'tipes' => $alat->tipes,
        ]);
    }

    public function edit($id)
    {
        $data = MasterAlatKso::findOrFail($id);
        return view('master-alat-kso.update', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $alat = MasterAlatKso::findOrFail($id);
        $alat->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('master-alat-kso.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $alat = MasterAlatKso::findOrFail($id);
        $alat->delete();

        return response()->json(['status' => 'success']);
    }
}
