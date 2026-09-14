<?php
namespace App\Http\Controllers;

use App\Helpers\R2Client;
use App\Models\DataInventaris;
use App\Models\KalibrasiModel;
use App\Models\MasterRs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use DB;

class KalibrasiController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($data);
        if ($request->ajax()) {
            if (auth()->user()->role == 'admin') {
                $data = KalibrasiModel::latest();
            } else {
                $data = KalibrasiModel::where('kodeRS', auth()->user()->kodeRS)->latest();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $show = '<a href="' . route('kalibrasi.destroy', $row->id) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-trash"></i></button></a>';
                    $btnlihat = '';
                    $btnupdate = '';

                    // $print = '<a href="' . route('kalibrasi.store', $row->kode_item) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-print"></i></button></a>';
                    $btn = $show;
                    return $btn = $show;
                })
                ->addColumn('dokumen', function ($row) {
    // 1. Cek apakah ada file dokumen (Mencegah error/link rusak)
    if (empty($row->dokumen)) {
        return '<span class="text-muted font-italic">Tidak ada dokumen</span>';
    }

    // 2. Generate URL dari Cloudflare R2
    $r2 = new R2Client();
    $fileUrl = $r2->getUrl('dokumen/' . $row->dokumen);

    // 3. Buat HTML Button (Dibersihkan dan ditambahkan icon agar lebih rapi)
    $show = '<a href="' . $fileUrl . '" target="_blank">
                <button type="button" data-toggle="kt-tooltip" data-placement="top" title="Lihat Dokumen" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-file-pdf-o mr-1"></i> Lihat Dokumen
                </button>
            </a>';

    return $show;
})
                ->filter(function ($instance) use ($request) {
                    if ($request->get('filter_pemilik') == 'Dokter' || $request->get('filter_pemilik') == 'Rumah Sakit' || $request->get('filter_pemilik') == 'Vendor') {
                        $instance->where('kepemilikan', $request->get('filter_pemilik'));
                    }
                    if ($request->get('filter_rs') && $request->get('filter_rs') !== '') {
                        $instance->where('kodeRS', $request->get('filter_rs'));
                    }
                    if ($request->get('filter_tanggal') && $request->get('filter_tanggal') !== '') {
                        $instance->where('tgl_kalibrasi', $request->get('filter_tanggal'));
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('nama', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action', 'dokumen'])
                ->make(true);
        }
        $rs = MasterRs::all();
        return view('kalibrasi.index', compact('rs'));
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
        // 1. Validasi
        $this->validate($request, [
            'dokumen' => 'required|mimes:jpeg,bmp,png,gif,svg,pdf,doc,docx|max:5000',
        ]);

        $dokumen = $request->file('dokumen');
        $filename = $dokumen->hashName();

        // 2. Upload ke Cloudflare R2 dengan error handling
        try {
            $r2 = new R2Client();
            $r2->upload($dokumen->getRealPath(), 'dokumen/' . $filename, $dokumen->getMimeType());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupload dokumen ke R2: ' . $e->getMessage())
                ->withInput();
        }

        // 3. Simpan ke Database
        KalibrasiModel::create([
            'nama' => $request->nama,
            'assetID' => $request->idalat,
            'kodeRS' => auth()->user()->kodeRS,
            'kepemilikan' => $request->kepemilikan,
            'tgl_kalibrasi' => $request->tgl_kalibrasi,
            'exp_date' => $request->exp_date,
            'keterangan' => $request->keterangan,
            'dokumen' => $filename,
        ]);

        return redirect()->route('kalibrasi.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function getInv(Request $request)
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
            } elseif ($kodeRS === 'R') {  // botania
                $selectdb = 'mysql8';
            } elseif ($kodeRS === 'D') {  // dUMAI
                $selectdb = 'mysql9';
            } elseif ($kodeRS === 'Q') {  // dUMAI
                $selectdb = 'mysql13';
            } elseif ($kodeRS === 'W') {  // dUMAI
                $selectdb = 'mysql14';
            }
        }

        // $query = DataInventaris::where($request->filtercari,'LIKE','%'.$request->keyword.'%')
        $query = DataInventaris::where($request->filtercari, 'LIKE', '%' . $request->keyword . '%')
            ->where('nama_rs', auth()->user()->kodeRS)
            ->take(50)
            ->get();
        // dd($query);
        $view = view('kalibrasi.data-item', compact('query'))->render();
        return response()->json(['data' => $query, 'view' => $view], 200);
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
        try {
            $item = KalibrasiModel::findOrFail($id);
            $r2 = new R2Client();
            if (!empty($item->dokumen)) {
                $r2->delete('dokumen/' . $item->dokumen);
            }
            $item->delete();
            return redirect()->route('kalibrasi.index')->with('success', 'Data dan file berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->route('kalibrasi.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function getItem(Request $request)
    {
        if (auth()->user()->role == 'admin') {
            $kodeRS = $request->rs;
        } else {
            $kodeRS = auth()->user()->kodeRS;
        }
        $dataItem = DB::connection('mysql')->table('data_inventaris')->where('nama_rs', $kodeRS);
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem
                ->where('nama', 'LIKE', "%$search%")
                ->limit(10)
                ->get(['kode_item', 'nama']);
            $item = $dataItem->pluck('nama', 'kode_item');
        } else {
            $item = $dataItem->limit(10)->get(['kode_item', 'nama'])->pluck('nama', 'kode_item');
        }
        return response()->json($item);
    }
}
