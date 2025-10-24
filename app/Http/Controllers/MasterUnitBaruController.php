<?php

namespace App\Http\Controllers;

use App\Models\MasterRs;
use App\Models\MasterUnitBaru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MasterUnitBaruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterUnitBaru::with('getRs')->where('KodeRs', auth()->user()->kodeRS)
                ->orderBy('id', 'desc')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('KodeRs', function ($row) {
                    return optional($row->getRs)->nama ?? $row->KodeRs;
                })
                ->make(true);
        }
        $rs = MasterRs::get();
        return view('cssd.master-unit.index', compact('rs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $kodeRS = $request->kode_rs ?? auth()->user()->kodeRS;

        $dbMap = [
            'K' => 'mysql2',    // ayani
            'I' => 'mysql3',    // panam
            'B' => 'mysql4',    // batan
            'A' => 'mysql5',    // sudirman
            'G' => 'mysql6',    // ujung batu
            'S' => 'mysql7',    // bagan batu
            'R' => 'mysql8',    // botania
            'D' => 'mysql9',    // dumai
            'Q' => 'mysql13',   // hangtuah
            'W' => 'mysql14',   // hangtuah
        ];

        $selectdb = $dbMap[$kodeRS] ?? null;
        if (!$selectdb) {
            return response()->json(['success' => false, 'message' => 'Kode RS tidak valid'], 400);
        }

        try {
            $dataItem = DB::connection($selectdb)
                ->table('departemen')
                ->where('NA', 'N')
                ->get();

            foreach ($dataItem as $item) {
                MasterUnitBaru::updateOrCreate(
                    [
                        'IdUnit' => $item->DepartemenID,
                        'KodeRs' => $kodeRS,
                    ],
                    [
                        'KodeUnit' => $item->KodeDepartemen ?? null,
                        'Nama' => $item->Nama ?? null,
                        'UserCreate' => auth()->user()->name ?? null,
                        'UserUpdate' => null,
                        'UserDelete' => null,
                    ]
                );
            }

            return response()->json(['success' => true, 'message' => 'Sinkronisasi berhasil!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterUnitBaru  $masterUnitBaru
     * @return \Illuminate\Http\Response
     */
    public function show(MasterUnitBaru $masterUnitBaru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterUnitBaru  $masterUnitBaru
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterUnitBaru $masterUnitBaru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterUnitBaru  $masterUnitBaru
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterUnitBaru $masterUnitBaru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterUnitBaru  $masterUnitBaru
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterUnitBaru $masterUnitBaru)
    {
        //
    }
}
