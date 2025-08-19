<?php

namespace App\Http\Controllers;

use App\Models\cssdMasterSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CssdMasterSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = cssdMasterSupplier::orderBy('Nama', 'Asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('cssd-master-supplier.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cssd.master-supplier.index');
    }


    public function create()
    {
        return view('cssd.master-supplier.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();
        $data['KodeRS'] = auth()->user()->kodeRS;
        $data['UserCreate'] = auth()->user()->name;

        $query = cssdMasterSupplier::create($data);
        return redirect()->route('cssd-master-supplier.index')->with('success', 'Data berhasil ditambahkan');
    }



    public function edit($id)
    {
        $data = cssdMasterSupplier::find($id);
        return view('cssd.master-supplier.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $query = cssdMasterSupplier::find($id);
        $data = $request->all();
        $data['UserUpdate'] = auth()->user()->name;
        $query->update($data);
        return redirect()->route('cssd-master-supplier.index')->with('success', 'Data berhasil diubah');
    }


    public function destroy($id)
    {
        $data = cssdMasterSupplier::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
