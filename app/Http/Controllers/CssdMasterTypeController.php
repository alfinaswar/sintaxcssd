<?php

namespace App\Http\Controllers;

use App\Models\cssdMasterType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CssdMasterTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = cssdMasterType::where('KodeRs', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('cssd-master-tipe.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cssd.master-tipe.index');
    }


    public function create()
    {
        return view('cssd.master-tipe.create');
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $data['KodeRs'] = auth()->user()->kodeRS;
        $query = cssdMasterType::create($data);
        return redirect()->route('cssd-master-tipe.index')->with('success', 'Data berhasil ditambahkan');
    }



    public function edit($id)
    {
        $tipe = cssdMasterType::find($id);
        return view('cssd.master-tipe.edit', compact('tipe'));
    }


    public function update(Request $request, $id)
    {
        $query = cssdMasterType::find($id);
        $data = $request->all();
        $query->update($data);
        return redirect()->route('cssd-master-tipe.index')->with('success', 'Data berhasil diubah');
    }


    public function destroy($id)
    {
        $data = cssdMasterType::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
