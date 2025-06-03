<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\MasterDepartemenModel;
use App\Models\ReturnInventaris;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReturnInventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ReturnInventaris::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $show = '<a href="' . route('perbaikan.show', $row->id) . '"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $print = '<a href="' . route('label-perbaikan', $row->id) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-print"></i></button></a>';
                    $btn = $show . ' ' . $print;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pemusnahan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inventaris = DataInventaris::where('nama_rs', auth()->user()->KodeRS)->get();
        $departemen = MasterDepartemenModel::where('kodeRS', auth()->user()->KodeRS)->get();
        return view('pemusnahan.create', compact('inventaris', 'departemen'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReturnInventaris  $returnInventaris
     * @return \Illuminate\Http\Response
     */
    public function show(ReturnInventaris $returnInventaris)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReturnInventaris  $returnInventaris
     * @return \Illuminate\Http\Response
     */
    public function edit(ReturnInventaris $returnInventaris)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReturnInventaris  $returnInventaris
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReturnInventaris $returnInventaris)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReturnInventaris  $returnInventaris
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReturnInventaris $returnInventaris)
    {
        //
    }
}
