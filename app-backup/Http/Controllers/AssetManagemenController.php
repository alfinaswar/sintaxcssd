<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetManagemenRequest;
use App\Models\AssetManagemenModel;
use App\Models\MasterDepartemenModel;
use App\Models\MasterIPModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\DataTables;

class AssetManagemenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AssetManagemenModel::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '';
                    $btnlihat = '';
                    $btnupdate = '';
                    $btnEdite = '<a href="' . route('asset-managemen.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btnupdate = '<a href="' . route("label", $row->id) . '" target="_blank"><button type="button" class="btn btn-outline-brand btn-icon"  ><i class="fa fa-print"></i></button> </a>';
                    $btn = $btnEdite . '&nbsp;' . $btnupdate . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->addColumn('status', function ($query) {
                    // $status = '';
                    if ($query->status == 1) {
                        return $status = '<span class="badge badge-primary">Done</span>';
                    } else {
                        return $status = '<span class="badge badge-warning">Proses</span>';
                    }
                    // $status2 = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                    // return $status;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('asset-managemen.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('asset-managemen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetManagemenRequest $request)
    {
        $ip = MasterIPModel::find($request->ipID);
        $status['status'] = '1';
        $ip->update($status);

        $data = $request->all();
        $data['noIP'] = $ip->nama;
        $query = AssetManagemenModel::create($data);

        return redirect()->route('asset-managemen.index')->with('success', 'Data berhasil ditambahkan');
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
        $data = AssetManagemenModel::find($id);
        return view('asset-managemen.update', compact('data'));
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
        $query = AssetManagemenModel::find($id);
        if ($query->ipID != $request->ipID) {
            $IP = MasterIPModel::find($query->ipID);
            $IP->update(['status' => '0']);
        }
        $ip = MasterIPModel::find($request->ipID);
        $status['status'] = '1';
        $ip->update($status);

        $data = $request->all();
        $data['noIP'] = $ip->nama;
        $request->status ? $data['status'] = $request->status : $data['status'] = null;
        $query->update($data);
        return redirect()->route('asset-managemen.index')->with('success', 'Data berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = AssetManagemenModel::find($id);
        $IP = MasterIPModel::find($query->ipID);
        $IP->update(['status' => '0']);
        $query->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
    public function getIP(Request $request)
    {
        $IP = [];
        $dataIP = MasterIPModel::select('id', 'nama')->where('status', '0');
        if ($request->has('q')) {
            $search = $request->q;
            $IP = $dataIP->where('ip', 'LIKE', "$search%")
                ->get();
        } else {
            $IP = $dataIP->limit(5)->get();
        }
        return response()->json($IP);
    }
    public function getDepartemen(Request $request)
    {
        $departemen = [];
        $dataDepartemen = MasterDepartemenModel::select('id', 'nama');
        if ($request->has('q')) {
            $search = $request->q;
            $departemen = $dataDepartemen->where('nama', 'LIKE', "%$search%")
                ->get();
        } else {
            $departemen = $dataDepartemen->limit(5)->get();
        }
        return response()->json($departemen);
    }
    public function label($id)
    {
        $query = AssetManagemenModel::find($id);
        // $qrcode = QrCode::size(400)->generate();
        $qrcode = base64_encode(QrCode::format('svg')->size(85)->errorCorrection('H')->generate($query->nama));
        // dd($qrcode);
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')
        ];

        $pdf = Pdf::loadView('asset-managemen.label', compact('qrcode', 'query'))->setPaper([0, 0, 113.38582677, 184.2519685], 'landscape');

        return $pdf->stream('itsolutionstuff.pdf');
    }
}
