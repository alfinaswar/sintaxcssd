<?php

namespace App\Http\Controllers;

use App\Models\MasterRs;
use App\Models\Maintanance;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\DataInventaris;
use App\Models\MasalahModel;
use App\Models\KalibrasiModel;

class MaintananceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->role == "admin") {
                $data = Maintanance::
                    join('data_inventaris', 'data_inventaris.kode_item', '=', 'maintanance.kode_item')
                    ->join('master_rs', 'master_rs.kodeRS', '=', 'maintanance.nama_rs')
                    ->select('data_inventaris.nama as nama', 'maintanance.bulan as bulan', 'maintanance.status as status', 'maintanance.keterangan as keterangan', 'master_rs.nama as namars')
                    ->get();
            } else {
                $data = Maintanance::
                    join('data_inventaris', 'data_inventaris.kode_item', '=', 'maintanance.kode_item')
                    ->join('master_rs', 'master_rs.kodeRS', '=', 'maintanance.nama_rs')
                    ->select('data_inventaris.nama as nama', 'maintanance.bulan as bulan', 'maintanance.status as status', 'maintanance.keterangan as keterangan', 'master_rs.nama as namars')
                    ->where('kodeRS', auth()->user()->kodeRS)
                    ->get();

            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $show = '<a href="' . route('maintanance.destroy', $row->id) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-trash"></i></button></a>';
                    $btnlihat = '';
                    $btnupdate = '';

                    // $print = '<a href="' . route('kalibrasi.store', $row->kode_item) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-print"></i></button></a>';
                    $btn = $show;
                    return $btn = $show;
                })
                ->addColumn('bulan', function ($row) {
                    $show = Carbon::create()->month($row->bulan)->format('F');
                    return $show;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status = "1")
{
    $status = "Sudah Maintenance";
} else {
                        $status = "Belum Maintenance";
}
              return $status;
                })
                ->rawColumns(['action', 'bulan','status'])
                ->make(true);
        }
        $rs = MasterRs::all();
        return view('maintanance.index', compact('rs'));
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
        //pisahkan koma
        $latest = Maintanance::latest()->first()->id ?? 0 + 1;
        $kd_mtnc = 'MTNC' . $latest . '';
        $datanama = $request->nama;
        $kodeRS = auth()->user()->kodeRS;
        $result = explode(",", $datanama);
        $assetid = $result[0];
        $nama = $result[1];

        Maintanance::create([
            'kode_maintanance' => $kd_mtnc,
            'kode_item' => $assetid,
            'assetID' => $assetid,
            'bulan' => $request->bulan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'id_user' => auth()->user()->id,
            'nama_rs' => auth()->user()->kodeRS,
        ]);
        return redirect()->route('maintanance.index')->with('success', 'Data berhasil ditambahkan');
    }
    public function AddPm(Request $request)
    {
        //pisahkan koma
        $latest = Maintanance::latest()->first()->id ?? 0 + 1;
        $kd_mtnc = 'MTNC' . $latest . '';
        $datanama = $request->nama;
        $kodeRS = auth()->user()->kodeRS;

        Maintanance::create([
            'kode_maintanance' => $kd_mtnc,
            'kode_item' => $datanama,
            'assetID' => $datanama,
            'bulan' => $request->bulan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'id_user' => auth()->user()->id,
            'nama_rs' => auth()->user()->kodeRS,
        ]);
return redirect()->back()->with('success', 'Data berhasil ditambahkan');
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

    }
}
