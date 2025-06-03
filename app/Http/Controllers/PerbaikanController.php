<?php

namespace App\Http\Controllers;

use App\Models\AssetManagemenModel;
use App\Models\PerbaikanModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PerbaikanModel::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $show = '';
                    $print = '';
                    $btnupdate = '';

                    $show = '<a href="' . route('perbaikan.show', $row->id) . '"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $print = '<a href="' . route('label-perbaikan', $row->id) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-print"></i></button></a>';
                    $btn = $show . ' ' . $print;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('perbaikan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('perbaikan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $jumlah_masalah = PerbaikanModel::where('assetID', $request->assetID)->get()->count();
        $data = $request->all();
        $query = PerbaikanModel::create($data);

        return redirect()->route('perbaikan.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PerbaikanModel::find($id);
        return view('perbaikan.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getAsset(Request $request)
    {

        $query = AssetManagemenModel::where(function ($row) use ($request) {
            if ($request->cariNama) {
                $row->where('nama', 'LIKE', "%$request->cariNama%");
            }
            if ($request->cariNamaPemilik) {
                $row->where('userPengguna', 'LIKE', "%$request->cariNamaPemilik%");
            }
            if ($request->cariDepartemen) {
                $row->where('departemen', 'LIKE', "%$request->cariDepartemen%");
            }
        })->take(10)->get();
        $view = view('perbaikan.data-asset', compact('query'))->render();
        return response()->json(['data' => $query, 'view' => $view], 200);
    }
    public function label($id)
    {
        $query = PerbaikanModel::find($id);
        // $qrcode = QrCode::size(400)->generate();
        // dd($qrcode);


        $pdf = Pdf::loadView('perbaikan.label', compact('query'))->setPaper([0, 0, 113.38582677, 184.2519685], 'landscape');

        return $pdf->stream('itsolutionstuff.pdf');
    }
    public function status(Request $request, $id)
    {

        $query = PerbaikanModel::find($id);
        $data['status'] = '1';
        $query->update($data);

        return redirect()->route('perbaikan.index')->with('success', 'Data berhasil di update');
    }
}
