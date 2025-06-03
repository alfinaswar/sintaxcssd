<?php

namespace App\Http\Controllers;

use App\Models\MasterPengguna;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterPenggunaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterPengguna::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '';
                    $btnlihat = '';
                    $btnEdite = '<a href="' . route('master-departemen.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('master-pengguna.index');
    }

    public function create()
    {
        return view('master-pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $query = MasterPengguna::create($data);
        return redirect()->route('master.master-pengguna.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = MasterPengguna::find($id);
        return view('master-departemen.update', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $query = MasterPengguna::find($id);
        $data = $request->all();
        $query->update($data);
        return redirect()->route('master-departemen.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        $data = MasterPengguna::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
