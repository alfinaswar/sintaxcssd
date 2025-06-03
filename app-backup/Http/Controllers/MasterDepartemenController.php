<?php

namespace App\Http\Controllers;

use App\Models\MasterDepartemenModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MasterDepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterDepartemenModel::where('kodeRS', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '';
                    $btnlihat = '';
                    $btnupdate = '';
                    $btnEdite = '<a href="' . route('master-departemen.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('master-departemen.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master-departemen.create');
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
        $data['kodeRS'] = auth()->user()->kodeRS;

        $query = MasterDepartemenModel::create($data);
        return redirect()->route('master-departemen.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $data = MasterDepartemenModel::find($id);
        return view('master-departemen.update', compact('data'));
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
        $query = MasterDepartemenModel::find($id);
        $data = $request->all();
        $query->update($data);
        return redirect()->route('master-departemen.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = MasterDepartemenModel::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }

    public function getDepartemen(Request $request)
    {
        $departemen = [];
        $dataDepartemen = MasterDepartemenModel::select('id', 'nama');
        if ($request->has('q')) {
            $search = $request->q;
            $departemen = $dataDepartemen->where('nama', 'LIKE', "%$search%")->where('kodeRS', auth()->user()->kodeRS)
                ->get();
        } else {
            $departemen = $dataDepartemen->limit(5)->get();
        }
        return response()->json($departemen);
    }
}
