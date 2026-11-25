<?php

namespace App\Http\Controllers;

use App\Models\MasterVendorKso;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterVendorKsoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterVendorKso::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('master-vendor-kso.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon"><i class="fa fa-cogs"></i></button></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')"><i class="fa fa-times"></i></button>';
                    return $btnEdit . '&nbsp;' . $btnDelete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-vendor-kso.index');
    }

    public function create()
    {
        return view('master-vendor-kso.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        MasterVendorKso::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('master-vendor-kso.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = MasterVendorKso::findOrFail($id);
        return view('master-vendor-kso.update', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $vendor = MasterVendorKso::findOrFail($id);
        $vendor->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('master-vendor-kso.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $vendor = MasterVendorKso::findOrFail($id);
        $vendor->delete();

        return response()->json(['status' => 'success']);
    }
}
