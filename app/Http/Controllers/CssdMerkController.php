<?php

namespace App\Http\Controllers;

use App\Models\cssdMerk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CssdMerkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = cssdMerk::orderBy('Merk', 'ASC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('cssd-master-merk.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cssd.master-merk.index');
    }


    public function create()
    {
        return view('cssd.master-merk.create');
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $data['KodeRs'] = auth()->user()->kodeRS;
        $query = cssdMerk::create($data);
        return redirect()->route('cssd-master-merk.index')->with('success', 'Data berhasil ditambahkan');
    }



    public function edit($id)
    {
        $data = cssdMerk::find($id);
        return view('cssd.master-merk.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $query = cssdMerk::find($id);
        $data = $request->all();
        $query->update($data);
        return redirect()->route('cssd-master-merk.index')->with('success', 'Data berhasil diubah');
    }


    public function destroy($id)
    {
        $data = cssdMerk::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
