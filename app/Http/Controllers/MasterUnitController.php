<?php

namespace App\Http\Controllers;

use App\Models\MasterUnit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterUnit::where('nama_rs', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '';
                    $btnlihat = '';
                    $btnupdate = '';
                    $btnEdite = '<a href="' . route('master-unit.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-unit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master-unit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd(auth()->user()->kodeRS);
        $data['idDepartemen'] = $request->departemen;
        $data['namaUnit'] = $request->namaUnit;
        $data['nama_rs'] = auth()->user()->kodeRS;
        $query = MasterUnit::create($data);
        return redirect()->route('master-unit.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterUnit  $masterUnit
     * @return \Illuminate\Http\Response
     */
    public function show(MasterUnit $masterUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterUnit  $masterUnit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = MasterUnit::find($id);
        return view('master-unit.update', compact('data'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterUnit  $masterUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $query = MasterUnit::find($id);
        $data = $request->all();
        $query->update($data);
        return redirect()->route('master-unit.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterUnit  $masterUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = MasterUnit::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
