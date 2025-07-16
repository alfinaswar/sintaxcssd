<?php

namespace App\Http\Controllers;

use App\Models\MasterNamaSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MasterNamaSetController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterNamaSet::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('master-cssd.master-set-item.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon"><i class="fa fa-cogs"></i></button></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')"><i class="fa fa-times"></i></button>';
                    $btn = $btnEdit . '&nbsp;' . $btnDelete;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cssd.master-nama-set.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cssd.master-nama-set.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_set' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        MasterNamaSet::create([
            'Nama' => $request->nama_set,
        ]);

        return redirect()->route('master-cssd.master-set-item.index')->with('success', 'Nama Set berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterNamaSet  $masterNamaSet
     * @return \Illuminate\Http\Response
     */
    public function show(MasterNamaSet $masterNamaSet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $namaSet = MasterNamaSet::findOrFail($id);
        return view('cssd.master-nama-set.edit', compact('namaSet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_set' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $namaSet = MasterNamaSet::findOrFail($id);
        $namaSet->update([
            'Nama' => $request->nama_set,
        ]);

        return redirect()->route('master-cssd.master-set-item.index')->with('success', 'Nama Set berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = MasterNamaSet::findOrFail($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil dihapus'], 200);
    }
}
