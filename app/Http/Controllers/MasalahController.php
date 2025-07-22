<?php

namespace App\Http\Controllers;

use App\Exports\MasalahExport;
use App\Http\Requests\MasalahRequest;
use App\Models\DataInventaris;
use App\Models\FormulirPembersihan;
use App\Models\KalibrasiModel;
use App\Models\Maintanance;
use App\Models\MasalahModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use DB;

class MasalahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MasalahModel::orderBy('id', 'desc')->where('nama_rs', auth()->user()->kodeRS)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $show = '';
                    $btnlihat = '';
                    $btnupdate = '';

                    $print = '<a href="' . route('masalah.history', $row->kode_item) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-print"></i></button></a>';
                    $btn = $show;
                    return $btn = $print;
                })
                ->addColumn('tanggal', function ($row) {
                    $show = $row->created_at;
                    return $show;
                })
                ->rawColumns(['action', 'tanggal'])
                ->make(true);
        }
        return view('masalah.index');
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
    public function store(MasalahRequest $request)
    {
        // dd($request->prioritas);
        $datanama = $request->nama_perangkat;
        $result = explode(',', $datanama);
        $kode_item = $result[0];
        $nama = $result[1];
        // dd($kode_item);
        MasalahModel::create([
            'kode_item' => $kode_item,
            'nama_perangkat' => $nama,
            'judul' => $request->judul,
            'kasus' => $request->kasus,
            'jumlah_masalah' => $request->jumlah_masalah,
            'jenis' => $request->jenis,
            'tindakan' => $request->tindakan,
            // 'waktu_pengerjaan' => $request->waktu_pengerjaan,
            'keterangan' => $request->keterangan,
            'prioritas' => $request->prioritas,
            'nama_rs' => auth()->user()->kodeRS,
        ]);

        return redirect()->route('masalah.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = MasalahModel::find($id);
        return view('masalah.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $masalah = DB::connection('mysql2')
            ->table('kasus')
            ->where('UserGroupID', 'elektromedis')
            ->orWhere('UserGroupID', 'MTNC')
            ->orderBy('Tanggal', 'Desc')
            ->find($id);
        return view('masalah.edit', compact('masalah'));
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
        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
            if ($kodeRS === 'K') {  // ayani
                $selectdb = 'mysql2';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'I') {  // panam
                $selectdb = 'mysql3';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'B') {  // batan
                $selectdb = 'mysql4';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'A') {  // sudirman
                $selectdb = 'mysql5';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'G') {  // ujung batu
                $selectdb = 'mysql6';
                if ($request->cariGroup == 'MTNC') {
                    $keyword = 'MAINTENANCE';
                } else {
                    $keyword = 'ALMED';
                }
            } elseif ($kodeRS === 'S') {  // bagan batu
                $selectdb = 'mysql7';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'R') {  // botania
                $selectdb = 'mysql8';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'D') {  // dUMAI
                $selectdb = 'mysql9';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'Q') {  // dUMAI
                $selectdb = 'mysql13';
                $keyword = $request->cariGroup;
            } elseif ($kodeRS === 'W') {  // dUMAI
                $selectdb = 'mysql14';
                $keyword = $request->cariGroup;
            }
        }
        $query = DB::connection($selectdb)
            ->table('kasus')
            ->where(function ($row) use ($request, $keyword) {
                if ($request->cariGroup) {
                    $row->where('UserGroupID', 'LIKE', "%$keyword%");
                }
            })
            ->where('statusID', 2)
            ->orderBy('TanggalBuat', 'desc')
            ->take(10)
            ->get();
        $view = view('masalah.data-asset', compact('query'))->render();
        return response()->json(['data' => $query, 'view' => $view], 200);
    }

    public function getAlat(Request $request)
    {
        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
            if ($kodeRS === 'K') {  // ayani
                $selectdb = 'mysql2';
            } elseif ($kodeRS === 'I') {  // panam
                $selectdb = 'mysql3';
            } elseif ($kodeRS === 'B') {  // batan
                $selectdb = 'mysql4';
            } elseif ($kodeRS === 'A') {  // sudirman
                $selectdb = 'mysql5';
            } elseif ($kodeRS === 'G') {  // ujung batu
                $selectdb = 'mysql6';
            } elseif ($kodeRS === 'S') {  // bagan batu
                $selectdb = 'mysql7';
            } elseif ($kodeRS === 'B') {  // botania
                $selectdb = 'mysql8';
            } elseif ($kodeRS === 'Q') {  // botania
                $selectdb = 'mysql13';
            } elseif ($kodeRS === 'W') {  // botania
                $selectdb = 'mysql14';
            }
        }
        $query = DB::connection($selectdb)
            ->table('masteritem')
            ->where(function ($row) use ($request) {
                if ($request->cariGroupItem) {
                    $row->where('GroupItemID', 'LIKE', "%$request->cariGroupItem%");
                }
                if ($request->cariAlat) {
                    $row->where('Nama', 'LIKE', "%$request->cariAlat%");
                }
            })
            ->take(10)
            ->get();
        $view = view('masalah.data-alat', compact('query'))->render();
        return response()->json(['data' => $query, 'view' => $view], 200);
    }

    public function getItem(Request $request)
    {
        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
            if ($kodeRS === 'K') {  // ayani
                $selectdb = 'mysql2';
            } elseif ($kodeRS === 'I') {  // panam
                $selectdb = 'mysql3';
            } elseif ($kodeRS === 'B') {  // batan
                $selectdb = 'mysql4';
            } elseif ($kodeRS === 'A') {  // sudirman
                $selectdb = 'mysql5';
            } elseif ($kodeRS === 'G') {  // ujung batu
                $selectdb = 'mysql6';
            } elseif ($kodeRS === 'S') {  // bagan batu
                $selectdb = 'mysql7';
            } elseif ($kodeRS === 'B') {  // botania
                $selectdb = 'mysql8';
            } elseif ($kodeRS === 'Q') {  // botania
                $selectdb = 'mysql13';
            } elseif ($kodeRS === 'W') {  // botania
                $selectdb = 'mysql14';
            }

        }
        $item = [];
        $dataItem = DB::connection('mysql')->table('data_inventaris');
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem
                ->where('nama', 'LIKE', "%$search%")
                ->orWhere('kode_item', 'LIKE', "%$search%")
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->get(['kode_item', 'nama']);
            $item = $dataItem->pluck('nama', 'kode_item');
        } else {
            $item = $dataItem->limit(15)->orderBy('id', 'DESC')->get(['kode_item', 'nama'])->pluck('nama', 'kode_item');
        }
        return response()->json($item);
    }

    public function excel_masalah(Request $request)
    {
        $tgl_mulai = $request->input('tgl_mulai') . ' 00:00:00';
        $tgl_akhir = $request->input('tgl_akhir') . ' 23:59:59';
        $nama_file = 'laporan kasus' . $tgl_mulai . 'Hingga' . $tgl_akhir . '.xlsx';
        return Excel::download(new MasalahExport($tgl_mulai, $tgl_akhir), $nama_file);
    }

    public function history($kode_item)
    {
        if (!request()->secure()) {
            return redirect()->secure(request()->getRequestUri());
        }
        $data_alat = DataInventaris::join('master_rs', 'data_inventaris.nama_rs', '=', 'master_rs.kodeRS')
            ->where('kode_item', $kode_item)
            ->select('data_inventaris.*', 'master_rs.nama as rumahsakit')
            ->first();

        if (empty($data_alat)) {
            return view('data-inventaris.error-page');
        }

        $detail_masalah = MasalahModel::where('kode_item', $kode_item)->orderby('created_at', 'desc')->get();
        $kalibrasi = KalibrasiModel::where('assetID', $kode_item)->orderby('created_at', 'desc')->get();
        $data_kalibrasi = KalibrasiModel::where('assetID', $kode_item)->orderby('created_at', 'desc')->first();
        $data_mtnc = Maintanance::with('getUser')->where('assetID', $kode_item)->orderby('created_at', 'desc')->get();
        $bulanakhir = Maintanance::where('assetID', $kode_item)->orderby('created_at', 'desc')->latest();
        $pembersihan = FormulirPembersihan::where('kode_item', $kode_item)->whereMonth('created_at', now()->month)->orderBy('created_at', 'desc')->get();
        // dd($pembersihan);
        return view('masalah.history', compact('data_alat', 'detail_masalah', 'data_kalibrasi', 'data_mtnc', 'bulanakhir', 'kalibrasi', 'pembersihan'));
    }
}
