<?php
namespace App\Http\Controllers;
use App\Models\DataInventaris;
use App\Models\MasterRs;
use App\Models\MasterDepartemenModel;
use App\Models\MasterUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MasalahController;
use Carbon\Carbon;
use Illuminate\Contracts\Support\ValidatedData;

class DataInventarisController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:inventaris-create', ['only' => ['index','show']]);
        //  $this->middleware('permission:inventaris-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            if (auth()->user()->role == "admin") {
                $data = DataInventaris::latest();
            } else {
                $data = DataInventaris::where('nama_rs', auth()->user()->kodeRS)->latest();

            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $print = '<center><a href="' . route('inventaris.label', $row->id) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Print Barcode" class="btn btn-outline-primary btn-icon btn-md" ><i class="fas fa-qrcode"></i></button></a></center>';
                    $history = '<center><a href="' . route('masalah.history', $row->kode_item) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Lihat Riwayat" class="btn btn-outline-warning btn-icon btn-md" ><i class="fas fa-bookmark"></i></button></a></center>';
                    $edit = '<center><a href="' . route('inventaris.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-user-cog"></i></button></a></center>';
                    $btn = $print .' '. $edit .''. $history;
                    return $btn;
                })
                ->addColumn('nama_rs', function ($row) {

                    switch ($row->nama_rs) {
                        case 'K':
                            $realname = 'Awalbros Ayani';
                            break;
                        case 'I':
                            $realname = 'Awalbros Panam';
                            break;
                        case 'B':
                            $realname = 'Awalbros Batam';
                            break;
                        case 'A':
                            $realname = 'Awalbros Sudirman';
                            break;
                        case 'G':
                            $realname = 'Awalbros Ujung Batu';
                            break;
                        case 'S':
                            $realname = 'Awalbros Bagan Batu';
                            break;
                        case 'R':
                            $realname = 'Awalbros Botania';
                            break;
                        case 'D':
                            $realname = 'Awalbros Dumai';
                            break;
                        default:
                            $realname = 'Nama RS Kosong';
                            break;
                    }

                    $print = $realname;
                    return $print;
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('filter_pengguna') == 'umum' || $request->get('filter_pengguna') == 'Medis') {
                        $instance->where('pengguna', $request->get('filter_pengguna'));
                    }
    if ($request->get('filter_rs') && $request->get('filter_rs') !== '') {
                        $instance->where('nama_rs', $request->get('filter_rs'));
    }
    if ($request->get('filter_departemen') && $request->get('filter_departemen') !== '') {
                        $instance->where('departemen', $request->get('filter_departemen'));
    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('nama', 'LIKE', "%$search%")
                                ->orWhere('no_inventaris', 'LIKE', "%$search%")
                                ->orWhere('no_sn', 'LIKE', "%$search%");
                        });
                    }
})
                ->rawColumns(['action'])
                ->make(true);
        }
        $rs = MasterRs::all();
        $dept = MasterDepartemenModel::where('KodeRS',auth()->user()->kodeRS)->get();
        return view('data-inventaris.index', compact('rs','dept'));
    }

    public function create()
    {
        return view('data-inventaris.create');
    }

    public function label($id)
    {
        $query = DataInventaris::find($id);
        // $routes = route('masalah.history', $query->kode_item);
        $routes = "inventarisreg.awalbros-hospital.com/asset-inventaris/history/$query->kode_item";
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('L')->generate($routes));
        $pdf = Pdf::loadView('data-inventaris.label', compact('qrcode', 'query'))->setPaper([0, 0, 161.57, 80.37], 'portrait');
        $pdfmya = $pdf->stream('Label.pdf');
        return $pdf->stream('Label.pdf');
    }

    public function getItem(Request $request)
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
                default:
                    $selectdb = 'Unknown';
                    break;
            }
        }
        $item = [];
        $kategori = $request->kategori;
        $dataItem = DB::connection($selectdb)->table('masteritem')->where('KategoriitemID', $kategori);
        if ($request->has('q')) {
            $search =  $request->q;
            $dataItem->where('Nama', 'LIKE', "%$search%")->limit(5)
                ->get(['ItemID', 'Nama']);
            $item = $dataItem->pluck('Nama', 'ItemID');
        } else {
            $item = $dataItem->limit(5)->get(['ItemID', 'Nama'])->pluck('Nama', 'ItemID');
        }
        return response()->json($item);
    }
    public function getUnit(Request $request)
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
                default:
                    $selectdb = 'Unknown';
                    break;
            }
        }
        $item = [];
        $departemen = $request->departemen;
        // dd($departemen);
        $dataItem = MasterUnit::where('idDepartemen', $departemen);
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem->where('namaUnit', 'LIKE', "%$search%")->limit(5)
                ->get(['id', 'namaUnit']);
            $item = $dataItem->pluck('namaUnit', 'id');
        } else {
            $item = $dataItem->limit(5)->get(['id', 'namaUnit'])->pluck('namaUnit', 'id');
        }
        return response()->json($item);
    }
    public function getRoItem(Request $request)
    {
        // dd($request->cariNomorRo);
        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
            if ($kodeRS === 'K') {       //ayani
                $selectdb = 'mysql2';
            } elseif ($kodeRS === 'I') { //panam
                $selectdb = 'mysql3';
            } elseif ($kodeRS === 'B') { //batan
                $selectdb = 'mysql4';
            } elseif ($kodeRS === 'A') { //sudirman
                $selectdb = 'mysql5';
            } elseif ($kodeRS === 'G') { //ujung batu
                $selectdb = 'mysql6';
            } elseif ($kodeRS === 'S') { //bagan batu
                $selectdb = 'mysql7';
            } elseif ($kodeRS === 'B') { //botania
                $selectdb = 'mysql8';
            }
        }
        $query = DB::connection($selectdb)
            ->table('ro2')
            ->where(function ($row) use ($request) {
                if ($request->cariNomorRo) {
                    $row->where('ROID', 'LIKE', "$request->cariNomorRo");
                }
            })
            ->join('masteritem','ro2.ItemID','=','masteritem.ItemID')
            ->select('ro2.*','masteritem.Nama as NamaItem', 'masteritem.GroupItemID', 'masteritem.ItemID')
            ->orderBy('TanggalBuat', 'desc')
            ->take(15)->get();
        $view = view('data-inventaris.data-item', compact('query'))->render();
        return response()->json(['data' => $query, 'view' => $view], 200);
    }

    public function store(request $request)
    {
// dd($request->ItemID);
        $CekItem = DataInventaris::where('ROID', $request->ROID)
            ->where('assetID', $request->ItemID)
            ->first();

        if ($CekItem) {
            return redirect()->back()->with('warning', 'Inventaris dengan ROID dan Kode Item tersebut sudah ada.');
        }

        if($request->userPengguna == "Medis"){
            $JenisItem = "MED";
        }else{
            $JenisItem = "UMUM";
        }
        $TahunBeli = Carbon::parse($request->tanggal_beli)->year;
        $Departemen = MasterDepartemenModel::where('id', $request->departemen)->pluck('nama')->first();
        $unit = $request->unit;
        $autonumber = DataInventaris::latest()->first()->id + 1;
        $NoInv = $JenisItem."/".$TahunBeli."/".$Departemen."/".$unit."/".$autonumber;



        $latestId = null;
        if (DataInventaris::count() > 0) {
            $latestId = DataInventaris::latest()->first()->id + 1;
        }
       $kode_item = 'Item-' . str_pad($latestId, 8, '0', STR_PAD_LEFT);
       $kategori = $request->asd;
       if ($request->hasFile('dokumen') && $request->hasFile('gambar') && $request->hasFile('manualbook') ) {
            $this->validate($request, [
                'dokumen' => 'required|file|mimes:pdf|max:4096',
                'gambar' => 'required|file|mimes:jpeg,jpg,png|max:4096',
                'manualbook' => 'required|file|mimes:pdf|max:4096',
            ]);
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/gambar', $gambar->hashName());
            $dokumen = $request->file('dokumen');
            $dokumen->storeAs('public/dokumen', $dokumen->hashName());
            $manualbook = $request->file('manualbook');
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());

            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'nama' => $request->nama,
                'real_name' => $request->real_name,
                'kode_item' => $kode_item,
                'assetID' => $request->ItemID,
                'no_inventaris' => $NoInv,
                'no_sn' => $request->no_sn,
                'tanggal_beli' => $request->tanggal_beli,
                'keterangan' => $request->keterangan,
                'departemen' => $request->departemen,
                'unit' => $request->unit,
                'pengguna' => $request->userPengguna,
                'gambar' => $gambar->hashName(),
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_expire' => $request->tgl_expire,
                'dokumen' => $dokumen->hashName(),
                'manualbook' => $manualbook->hashName(),
                'nama_rs' => auth()->user()->kodeRS,
            ]);
        }

       elseif ($request->hasFile('gambar')) {
            $this->validate($request, [
                'gambar' => 'required|file|mimes:jpeg,jpg,png|max:4096',
            ]);
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/gambar', $gambar->hashName());
            // $dokumen = $request->file('dokumen');
            // $dokumen->storeAs('public/dokumen', $dokumen->hashName());

            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'nama' => $request->nama,
                'real_name' => $request->real_name,
                'kode_item' => $kode_item,
                'assetID' => $request->ItemID,
                'no_inventaris' => $NoInv,
                'no_sn' => $request->no_sn,
                'tanggal_beli' => $request->tanggal_beli,
                'keterangan' => $request->keterangan,
                'departemen' => $request->departemen,
                'unit' => $request->unit,
                'pengguna' => $request->userPengguna,
                'gambar' => $gambar->hashName(),
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_expire' => $request->tgl_expire,
                // 'dokumen' => $dokumen->hashName(),
                'nama_rs' => auth()->user()->kodeRS,
            ]);
        }elseif ($request->hasFile('dokumen')) {
            $this->validate($request, [
                'dokumen' => 'required|file|mimes:pdf|max:4096',
            ]);
            $dokumen = $request->file('dokumen');
            $dokumen->storeAs('public/dokumen', $dokumen->hashName());

            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'nama' => $request->nama,
                'real_name' => $request->real_name,
                'kode_item' => $kode_item,
                'assetID' => $request->ItemID,
                'no_inventaris' => $NoInv,
                'no_sn' => $request->no_sn,
                'tanggal_beli' => $request->tanggal_beli,
                'keterangan' => $request->keterangan,
                'departemen' => $request->departemen,
                'unit' => $request->unit,
                'pengguna' => $request->userPengguna,
                // 'gambar' => $gambar->hashName(),
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_expire' => $request->tgl_expire,
                'dokumen' => $dokumen->hashName(),
                'nama_rs' => auth()->user()->kodeRS,
            ]);
        } elseif ($request->hasFile('manualboook')) {
            $this->validate($request, [
                'manualbook' => 'required|file|mimes:pdf|max:4096',
            ]);
            $manualbook = $request->file('manualbook');
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());

            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'nama' => $request->nama,
                'real_name' => $request->real_name,
                'kode_item' => $kode_item,
                'assetID' => $request->ItemID,
                'no_inventaris' => $NoInv,
                'no_sn' => $request->no_sn,
                'tanggal_beli' => $request->tanggal_beli,
                'keterangan' => $request->keterangan,
                'departemen' => $request->departemen,
                'unit' => $request->unit,
                'pengguna' => $request->userPengguna,
                // 'gambar' => $gambar->hashName(),
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_expire' => $request->tgl_expire,
                'manualbook' => $manualbook->hashName(),
                'nama_rs' => auth()->user()->kodeRS,
            ]);
        }

        return redirect()->route('inventaris.index')->with('success', 'Data berhasil ditambahkan');
    }
    public function edit($id)
    {
        $datainv = DataInventaris::find($id);
        $dept = MasterDepartemenModel::where('kodeRS',auth()->user()->kodeRS)->get();
        return view('data-inventaris.edit', compact('datainv','dept'));
    }
    public function update(Request $request, $id)
    {
    if ($request->hasFile('gambar')) {

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/gambar', $gambar->hashName());
        Storage::delete('public/gambar/' . $request->gambar);
        $data['nama'] = $request->nama;
        $data['real_name'] = $request->real_name;
        $data['no_inventaris'] = $request->no_inventaris;
        $data['no_sn'] = $request->no_sn;
        $data['tanggal_beli'] = $request->tanggal_beli;
        $data['departemen'] = $request->departemen;
        $data['pengguna'] = $request->userPengguna;
        $data['gambar'] = $gambar->hashName();
        $query = DataInventaris::find($id);
        $query->update($data);
    }else if ($request->hasFile('dokumen')) {
        $dokumen = $request->file('dokumen');
        $dokumen->storeAs('public/dokumen', $dokumen->hashName());
        Storage::delete('public/dokumen/' . $request->dokumen);
        $data['nama'] = $request->nama;
        $data['real_name'] = $request->real_name;
        $data['no_inventaris'] = $request->no_inventaris;
        $data['no_sn'] = $request->no_sn;
        $data['tanggal_beli'] = $request->tanggal_beli;
        $data['departemen'] = $request->departemen;
        $data['pengguna'] = $request->userPengguna;
        $data['dokumen'] = $dokumen->hashName();
        $query = DataInventaris::find($id);
        $query->update($data);
    }
    else{
            $data['nama'] = $request->nama;
            $data['real_name'] = $request->real_name;
            $data['no_inventaris'] = $request->no_inventaris;
            $data['no_sn'] = $request->no_sn;
            $data['tanggal_beli'] = $request->tanggal_beli;
            $data['departemen'] = $request->departemen;
            $data['pengguna'] = $request->userPengguna;
            $query = DataInventaris::find($id);
            $query->update($data);
    }
        return redirect()->route('inventaris.index')->with('success', 'Data berhasil di ubah');
    }
}
