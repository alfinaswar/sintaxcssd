<?php

namespace App\Http\Controllers;

use App\Models\cssdMasterItem;
use App\Models\CssdPeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CssdPeminjamanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (auth()->user() && auth()->user()->role == 'admin') {
                $data = CssdPeminjamanBarang::with('getDiajukan', 'getRs')->orderBy('id', 'desc')->get();
            } else {
                $data = CssdPeminjamanBarang::with('getDiajukan', 'getRs')
                    ->where('KodeRS', auth()->user()->kodeRS)
                    ->orderBy('id', 'desc')
                    ->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->Status == 'Y') {
                        $btnDelete = '';
                        $btnEdit = '';
                    } else {
                        $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                        $btnEdit = '<a href="' . route('pinjam.edit', $row->id) . '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="fa fa-edit"></i></a>';
                    }
                    $btn = $btnEdit . ' ' . $btnDelete;
                    return $btn;
                })
                ->editColumn('Kode', function ($row) {
                    if (auth()->user() && auth()->user()->role == 'admin') {
                        return '<a href="' . route('pinjam.show', $row->id) . '" class="text-primary">' . e($row->Kode) . '</a>';
                    } else {
                        return e($row->Kode);
                    }
                })
                ->editColumn('Status', function ($row) {
                    if ($row->Status == 'Y') {
                        return '<span class="badge badge-success">Disetujui</span>';
                    } elseif ($row->Status == 'N') {
                        return '<span class="badge badge-danger">Ditolak</span>';
                    } else {
                        return '<span class="badge badge-secondary">Proses Pengajuan</span>';
                    }
                })

                ->rawColumns(['action', 'Status', 'Kode'])
                ->make(true);
        }

        return view('cssd.peminjaman-alat.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $NamaAlat = cssdMasterItem::with('getNama')->orderBy('Nama', 'ASC')->get();
        return view('cssd.peminjaman-alat.create', compact('NamaAlat'));
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
        dd($data);
    }
    public function getUnitHis(Request $request)
    {
        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
            switch ($kodeRS) {
                case 'K':
                    $selectdb = 'mysql2';
                    break;
                case 'I':
                    $selectdb = 'mysql3';
                    break;
                case 'B':
                    $selectdb = 'mysql4';
                    break;
                case 'A':
                    $selectdb = 'mysql5';
                    break;
                case 'G':
                    $selectdb = 'mysql6';
                    break;
                case 'S':
                    $selectdb = 'mysql7';
                    break;
                case 'R':
                    $selectdb = 'mysql8';
                    break;
                case 'D':
                    $selectdb = 'mysql9';
                    break;
                case 'Q':
                    $selectdb = 'mysql13';
                    break;
                case 'W':
                    $selectdb = 'mysql14';
                    break;
                default:
                    $selectdb = 'Unknown';
                    break;
            }
        }
        $item = [];
        $dataItem = DB::connection($selectdb)->table('departemen')->where('NA', 'N');
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem
                ->where('Nama', 'LIKE', "%$search%")
                ->limit(10)
                ->get(['Nama', 'DepartemenID']);
            $item = $dataItem->pluck('Nama', 'DepartemenID');
        } else {
            $item = $dataItem->limit(10)->get(['Nama', 'DepartemenID'])->pluck('Nama', 'DepartemenID');
        }
        return response()->json($item);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CssdPeminjamanBarang  $cssdPeminjamanBarang
     * @return \Illuminate\Http\Response
     */
    public function show(CssdPeminjamanBarang $cssdPeminjamanBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CssdPeminjamanBarang  $cssdPeminjamanBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(CssdPeminjamanBarang $cssdPeminjamanBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CssdPeminjamanBarang  $cssdPeminjamanBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CssdPeminjamanBarang $cssdPeminjamanBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CssdPeminjamanBarang  $cssdPeminjamanBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(CssdPeminjamanBarang $cssdPeminjamanBarang)
    {
        //
    }
}
