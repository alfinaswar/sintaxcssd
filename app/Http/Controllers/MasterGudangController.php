<?php

namespace App\Http\Controllers;

use App\Models\MasterGudang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterGudang::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<button type="button" class="btn btn-outline-success btn-icon btn-edit-gudang"
                   data-id="' . $row->id . '"
                   data-nama="' . $row->Nama . '"
                   data-usercreate="' . $row->UserCreate . '"
                   data-userupdate="' . $row->UserUpdate . '">
                   <i class="fa fa-cogs"></i>
                </button>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')"><i class="fa fa-times"></i></button>';
                    return $btnEdit . '&nbsp;' . $btnDelete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-gudang.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
        ]);

        $gudang = new MasterGudang();
        $gudang->Nama = $request->Nama;
        $gudang->UserCreate = $request->UserCreate ?? auth()->user()->name;
        $gudang->save();

        return response()->json([
            'success' => true,
            'msg' => 'Data gudang berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterGudang $masterGudang)
    {
        // Optional: implement detail page if needed
        return view('master-gudang.show', compact('masterGudang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gudang = MasterGudang::findOrFail($id);
        return response()->json($gudang);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama' => 'required|string|max:255',
        ]);

        $gudang = MasterGudang::findOrFail($id);
        $gudang->Nama = $request->Nama;
        $gudang->UserUpdate = $request->UserUpdate ?? auth()->user()->name;
        $gudang->save();

        return response()->json([
            'success' => true,
            'msg' => 'Data gudang berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = MasterGudang::findOrFail($id);
        $data->UserDelete = auth()->user() ? auth()->user()->name : null;
        $data->save();
        $data->delete();
        return response()->json(['msg' => 'Gudang berhasil dihapus'], 200);
    }
}
