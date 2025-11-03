<?php
namespace App\Http\Controllers;

use App\Http\Controllers\MasalahController;
use App\Models\DataInventaris;
use App\Models\MasterDepartemenModel;
use App\Models\MasterMerk;
use App\Models\MasterRs;
use App\Models\MasterUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\DataTables;

class DataInventarisController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:inventaris-create', ['only' => ['index','show']]);
        //  $this->middleware('permission:inventaris-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->role == 'admin' || auth()->user()->role == 'DKH') {
                $data = DataInventaris::latest();
            } else {
                $data = DataInventaris::where('nama_rs', auth()->user()->kodeRS)->latest();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if (auth()->user()->role == 'DKH') {
                        $btn = '-';
                    } else {
                        $print = '<center><a href="' . route('inventaris.label', $row->id) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Print Barcode" class="btn btn-outline-primary btn-icon btn-md" ><i class="fas fa-qrcode"></i></button></a></center>';
                        $history = '<center><a href="' . route('masalah.history', $row->kode_item) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Lihat Riwayat" class="btn btn-outline-warning btn-icon btn-md" ><i class="fas fa-bookmark"></i></button></a></center>';
                        $edit = '<center><a href="' . route('inventaris.edit', $row->id) . '" target="_blank"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-user-cog"></i></button></a></center>';
                        $delete = '';
                        if (Auth::check() && Auth::user()->role === 'admin') {
                            $delete = '<center><button onclick="delete_data(event, ' . $row->id . ')" class="btn btn-outline-danger btn-icon" title="Hapus"><i class="fa fa-trash"></i></button></center>';
                        }

                        $btn = $print . ' ' . $edit . ' ' . $history . ' ' . $delete;
                    }

                    return $btn;
                })
                ->addColumn('tahun_beli', function ($row) {
                    if (!$row->tanggal_beli) {
                        $tahun_beli = '-';
                    } else {
                        $tahun_beli = Carbon::parse($row->tanggal_beli)->format('Y');
                    }
                    return $tahun_beli;
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
                        case 'Q':
                            $realname = 'Awalbros Hangtuah';
                            break;
                        case 'W':
                            $realname = 'Awalbros Batu Aji';
                            break;
                        default:
                            $realname = 'Nama RS Kosong';
                            break;
                    }

                    $print = $realname;
                    return $print;
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('filter_pengguna') && $request->get('filter_pengguna') !== '') {
                        $instance->where('pengguna', $request->get('filter_pengguna'));
                    }
                    if ($request->get('filter_rs') && $request->get('filter_rs') !== '') {
                        $instance->where('nama_rs', $request->get('filter_rs'));
                    }
                    if ($request->get('filter_departemen') && $request->get('filter_departemen') !== '') {
                        $instance->where('departemen', $request->get('filter_departemen'));
                    }
                    if ($request->get('filter_unit') && $request->get('filter_unit') !== '') {
                        $instance->where('unit', $request->get('filter_unit'));
                    }
                    if ($request->get('filter_pembelian') && $request->get('filter_pembelian') !== '') {
                        $instance->whereYear('tanggal_beli', $request->get('filter_pembelian'));
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w
                                ->orWhere('nama', 'LIKE', "%$search%")
                                ->orWhere('no_inventaris', 'LIKE', "%$search%")
                                ->orWhere('no_sn', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action', 'tahun_beli'])
                ->make(true);
        }
        $rs = MasterRs::all();
        $dept = MasterDepartemenModel::where('KodeRS', auth()->user()->kodeRS)->get();
        return view('data-inventaris.index', compact('rs', 'dept'));
    }

    public function create()
    {
        if (auth()->user()->role == 'DKH') {
            return redirect()->back();
        }
        // $dataItem = DB::connection("mysql2")->table('departemen')->get();
        // dd($dataItem);
        return view('data-inventaris.create');
    }

    public function KsoAna()
    {
        return view('data-inventaris.kso-ana');
    }

    public function CreateBc()
    {
        return view('data-inventaris.create-tanpa-ro');
    }

    public function label($id)
    {
        $query = DataInventaris::find($id);
        // $routes = route('masalah.history', $query->kode_item);
        $routes = route('masalah.history', $query->kode_item);
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('L')->generate($routes));
        $pdf = Pdf::loadView('data-inventaris.label', compact('qrcode', 'query'))->setPaper([0, 0, 161.57, 70.0], 'portrait');
        $pdfmya = $pdf->stream('Label.pdf');
        return $pdf->stream('Label.pdf');
    }

    public function getItem(Request $request)
    {
        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
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
                } elseif ($kodeRS === 'Q') {  // hangtuah
                    $selectdb = 'mysql13';
                } elseif ($kodeRS === 'W') {  // hangtuah
                    $selectdb = 'mysql14';
                }
            }
        }
        $item = [];
        $kategori = $request->kategori;
        $dataItem = DB::connection($selectdb)->table('masteritem')->where('KategoriitemID', $kategori);
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem
                ->where('Nama', 'LIKE', "%$search%")
                ->limit(30)
                ->get(['ItemID', 'Nama']);
            $item = $dataItem->pluck('Nama', 'ItemID');
        } else {
            $item = $dataItem->limit(30)->get(['ItemID', 'Nama'])->pluck('Nama', 'ItemID');
        }
        return response()->json($item);
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
                ->get(['Nama']);
            $item = $dataItem->pluck('Nama');
        } else {
            $item = $dataItem->limit(10)->get(['Nama'])->pluck('Nama');
        }
        return response()->json($item);
    }

    public function getDepartemenHis(Request $request)
    {
        if (auth()->user()->role == 'admin') {
            $kodeRS = $request->rs;
        } else {
            $kodeRS = auth()->user()->kodeRS;
        }
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
        $item = [];
        $dataItem = DB::connection($selectdb);
        $dataItem = $dataItem->table('departemen')->where('NA', 'N');
        // dd($dataItem);
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
        $departemen = $request->departemen;
        // dd($departemen);
        $dataItem = MasterUnit::where('idDepartemen', $departemen);
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem
                ->where('namaUnit', 'LIKE', "%$search%")
                ->limit(5)
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
            } elseif ($kodeRS === 'Q') {  // hangtuah
                $selectdb = 'mysql13';
            } elseif ($kodeRS === 'W') {  // batuaji
                $selectdb = 'mysql14';
            }
        }
        $query = DB::connection($selectdb)
            ->table('ro2')
            ->where(function ($row) use ($request) {
                if ($request->cariNomorRo) {
                    $row->where('ROID', 'LIKE', "%$request->cariNomorRo%");
                }
            })
            ->join('masteritem', 'ro2.ItemID', '=', 'masteritem.ItemID')
            ->select('ro2.*', 'masteritem.Nama as NamaItem', 'masteritem.GroupItemID', 'masteritem.ItemID')
            ->orderBy('TanggalBuat', 'desc')
            ->take(100)
            ->get();
        $view = view('data-inventaris.data-item', compact('query'))->render();
        return response()->json(['data' => $query, 'view' => $view], 200);
    }

    public function getMerk(Request $request)
    {
        $merk = [];
        $dataMerk = MasterMerk::select('nama');
        if ($request->has('q')) {
            $search = $request->q;
            $merk = $dataMerk
                ->where('nama', 'LIKE', "%$search%")
                ->where('nama_rs', auth()->user()->kodeRS)
                ->get();
        } else {
            $merk = $dataMerk->limit(10)->get();
        }
        return response()->json($merk);
    }

    public function storeNoro(request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'real_name' => 'required|string|max:255',
            'no_sn' => 'nullable|string|max:255',
            'tanggal_beli' => 'nullable|date',
            'departemen' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'userPengguna' => 'required|in:Medis,Non Medis',
            'klasifikasi' => 'nullable|in:None,High Risk,Medium Risk,Low to Medium Risk,Low Risk',
            'gambar' => 'required|image|max:3048',  //
            'isKalibrasi' => 'nullable|in:0,1',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->userPengguna == 'Medis') {
            $JenisItem = 'MED';
        } else {
            $JenisItem = 'UMUM';
        }
        $TahunBeli = Carbon::parse($request->tanggal_beli)->year;
        $Departemen = $request->departemen;
        $unit = $request->unit;
        $autonumber = DataInventaris::latest()->first()->id + 1;
        $NoInv = $JenisItem . '-' . $autonumber;

        $latestId = null;
        if (DataInventaris::count() > 0) {
            $latestId = DataInventaris::latest()->first()->id + 1;
        }
        $kode_item = 'Item-' . str_pad($latestId, 8, '0', STR_PAD_LEFT);
        $kategori = $request->asd;

        $data = $request->nama;
        $data = explode(',', $data);
        $nama = $data[1];
        $assetid = $data[0];

        $this->validate($request, [
            'gambar' => 'required|file|mimes:jpeg,jpg,png|max:5000',
            'manualbook' => 'file|mimes:pdf|max:5000',
            'departemen' => 'required',
            'unit' => 'required',
        ]);
        // Kompres gambar sebelum disimpan agar ukuran file tidak terlalu besar
        if ($request->hasFile('gambar')) {
            $gambarFile = $request->file('gambar');
            $namaFile = $gambarFile->hashName();
            $lokasiSimpan = storage_path('app/public/gambar/' . $namaFile);

            // Kompres gambar menggunakan Intervention Image
            $image = Image::make($gambarFile->getRealPath());

            // Kompres ke kualitas 70 (bisa diubah sesuai kebutuhan)
            $image->encode('jpg', 70)->save($lokasiSimpan);

            $gambar = $namaFile;
        } else {
            $gambar = null;
        }

        if ($request->hasFile('manualbook')) {
            $manualbook = $request->file('manualbook');
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());
            $manualbook = $manualbook->hashName();
        } else {
            $manualbook = null;
        }

        DataInventaris::create([
            'ROID' => $request->ROID,
            'RO2ID' => $request->RO2ID,
            'harga' => null,
            'nama' => $nama,
            'merk' => $request->merk,
            'real_name' => $request->real_name,
            'kode_item' => $kode_item,
            'assetID' => $assetid,
            'no_inventaris' => $NoInv,
            'no_sn' => $request->no_sn,
            'tanggal_beli' => $request->tanggal_beli,
            'keterangan' => $request->keterangan,
            'departemen' => $request->departemen,
            'unit' => $request->unit,
            'pengguna' => $request->userPengguna,
            'gambar' => $gambar,
            'tgl_kalibrasi' => $request->tgl_kalibrasi,
            'tgl_expire' => $request->tgl_expire,
            'manualbook' => $manualbook,
            'klasifikasi' => $request->klasifikasi,
            'nama_rs' => auth()->user()->kodeRS,
            'isKalibrasi' => $request->isKalibrasi,
            'UserCreate' => auth()->user()->name ?? null,
            'UserId' => auth()->user()->id ?? null,
            'UpdateName' => null,
            'UpdateById' => null,
        ]);

        $username = auth()->user()->name;

        return redirect()->route('inventaris.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function store(request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'real_name' => 'required|string|max:255',
            'no_sn' => 'nullable|string|max:255',
            'tanggal_beli' => 'nullable|date',
            'departemen' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'userPengguna' => 'required|in:Medis,Non Medis',
            'klasifikasi' => 'nullable|in:None,High Risk,Medium Risk,Low to Medium Risk,Low Risk',
            'gambar' => 'required|image|max:3048',  //
            'isKalibrasi' => 'nullable|in:0,1',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $Harga = str_replace('Rp. ', '', $request->Harga);
        $Harga = str_replace('.', '', $Harga);

        if ($request->userPengguna == 'Medis') {
            $JenisItem = 'MED';
        } else {
            $JenisItem = 'UMUM';
        }
        $TahunBeli = Carbon::parse($request->tanggal_beli)->year;
        $Departemen = $request->departemen;
        $unit = $request->unit;
        $autonumber = DataInventaris::latest()->first()->id + 1;
        $NoInv = $JenisItem . '-' . $autonumber;

        $latestId = null;
        if (DataInventaris::count() > 0) {
            $latestId = DataInventaris::latest()->first()->id + 1;
        }
        $kode_item = 'Item-' . str_pad($latestId, 8, '0', STR_PAD_LEFT);
        $kategori = $request->asd;
        if ($request->hasFile('gambar') && $request->hasFile('manualbook')) {
            $this->validate($request, [
                'gambar' => 'required|file|mimes:jpeg,jpg,png|max:5000',
                'manualbook' => 'required|file|mimes:pdf|max:5000',
                'departemen' => 'required',
                'unit' => 'required',
            ]);
            $gambar = $request->file('gambar');
            // Kompres gambar sebelum disimpan
            $img = Image::make($gambar->getRealPath());
            $img->encode('jpg', 70);
            $namaFile = $gambar->hashName();
            $img->save(storage_path('app/public/gambar/' . $namaFile));
            // $dokumen = $request->file('dokumen');
            // $dokumen->storeAs('public/dokumen', $dokumen->hashName());
            $manualbook = $request->file('manualbook');
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());

            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'harga' => $Harga,
                'nama' => $request->nama,
                'merk' => $request->merk,
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
                'manualbook' => $manualbook->hashName(),
                'klasifikasi' => $request->klasifikasi,
                'nama_rs' => auth()->user()->kodeRS,
                'isKalibrasi' => $request->isKalibrasi,
                'UserCreate' => auth()->user()->name ?? null,
                'UserId' => auth()->user()->id ?? null,
                'UpdateName' => null,
                'UpdateById' => null,
            ]);
        } elseif ($request->hasFile('gambar')) {
            $this->validate($request, [
                'gambar' => 'required|file|mimes:jpeg,jpg,png|max:5000',
                'departemen' => 'required',
                'unit' => 'required',
            ]);
            $gambar = $request->file('gambar');
            // Kompres gambar sebelum disimpan
            $img = Image::make($gambar->getRealPath());
            $img->encode('jpg', 70);
            $namaFile = $gambar->hashName();
            $img->save(storage_path('app/public/gambar/' . $namaFile));
            // $dokumen = $request->file('dokumen');
            // $dokumen->storeAs('public/dokumen', $dokumen->hashName());

            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'harga' => $Harga,
                'nama' => $request->nama,
                'merk' => $request->merk,
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
                'nama_rs' => auth()->user()->kodeRS,
                'isKalibrasi' => $request->isKalibrasi,
                'klasifikasi' => $request->klasifikasi,
                'UserCreate' => auth()->user()->name ?? null,
                'UserId' => auth()->user()->id ?? null,
                'UpdateName' => null,
                'UpdateById' => null,
            ]);
            // }elseif ($request->hasFile('dokumen')) {
            //     $this->validate($request, [
            //         'dokumen' => 'required|file|mimes:pdf|max:4096',
            //     ]);
            //     $dokumen = $request->file('dokumen');
            //     $dokumen->storeAs('public/dokumen', $dokumen->hashName());

            //     DataInventaris::create([
            //         'ROID' => $request->ROID,
            //         'RO2ID' => $request->RO2ID,
            // 'harga' => $Harga,
            //         'nama' => $request->nama,
            //         'real_name' => $request->real_name,
            //         'kode_item' => $kode_item,
            //         'assetID' => $request->ItemID,
            //         'no_inventaris' => $NoInv,
            //         'no_sn' => $request->no_sn,
            //         'tanggal_beli' => $request->tanggal_beli,
            //         'keterangan' => $request->keterangan,
            //         'departemen' => $request->departemen,
            //         'unit' => $request->unit,
            //         'pengguna' => $request->userPengguna,
            //         // 'gambar' => $gambar->hashName(),
            //         'tgl_kalibrasi' => $request->tgl_kalibrasi,
            //         'tgl_expire' => $request->tgl_expire,
            //         'dokumen' => $dokumen->hashName(),
            //         'nama_rs' => auth()->user()->kodeRS,
            //     ]);
            // }
        } elseif ($request->hasFile('manualboook')) {
            $this->validate($request, [
                'manualbook' => 'required|file|mimes:pdf|max:5000',
                'departemen' => 'required',
                'unit' => 'required',
            ]);
            $manualbook = $request->file('manualbook');
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());

            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'harga' => $Harga,
                'nama' => $request->nama,
                'merk' => $request->merk,
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
                'klasifikasi' => $request->klasifikasi,
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_expire' => $request->tgl_expire,
                'manualbook' => $manualbook->hashName(),
                'nama_rs' => auth()->user()->kodeRS,
                'isKalibrasi' => $request->isKalibrasi,
                'UserCreate' => auth()->user()->name ?? null,
                'UserId' => auth()->user()->id ?? null,
                'UpdateName' => null,
                'UpdateById' => null,
            ]);
        } else {
            DataInventaris::create([
                'ROID' => $request->ROID,
                'RO2ID' => $request->RO2ID,
                'harga' => $Harga,
                'nama' => $request->nama,
                'merk' => $request->merk,
                'real_name' => $request->real_name,
                'kode_item' => $kode_item,
                'assetID' => $request->ItemID,
                'no_inventaris' => $NoInv,
                'no_sn' => $request->no_sn,
                'tanggal_beli' => $request->tanggal_beli,
                'keterangan' => $request->keterangan,
                'departemen' => $request->departemen,
                'unit' => $request->unit,
                'klasifikasi' => $request->klasifikasi,
                'pengguna' => $request->userPengguna,
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_expire' => $request->tgl_expire,
                'nama_rs' => auth()->user()->kodeRS,
                'isKalibrasi' => $request->isKalibrasi,
                'UserCreate' => auth()->user()->name ?? null,
                'UserId' => auth()->user()->id ?? null,
                'UpdateName' => null,
                'UpdateById' => null,
            ]);
        }

        return redirect()->route('inventaris.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $datainv = DataInventaris::find($id);
        $dept = MasterDepartemenModel::where('kodeRS', auth()->user()->kodeRS)->get();
        $unit = MasterUnit::where('nama_rs', auth()->user()->kodeRS)->get();
        return view('data-inventaris.edit', compact('datainv', 'dept', 'unit'));
    }

    public function updateKsoAna(Request $request)
    {
        if ($request->hasFile('dokumen_kso')) {
            $manualbook = $request->file('dokumen_kso');
            // $namaManualbookLama = DataInventaris::where('assetID')->first()->manualbook;
            // if ($namaManualbookLama) {
            //     Storage::delete('public/manualbook/' . $namaManualbookLama);
            // }
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());
        }

        $assetID = $request->kode_item;
        $manualbookName = isset($manualbook) ? $manualbook->hashName() : null;
        $updatedCount = DataInventaris::where('nama', 'like', '%' . $assetID . '%')
            ->where('nama_rs', 'K')
            ->update([
                'manualbook' => $manualbookName,
                'UpdateName' => 'KSO ANA'
            ]);

        return redirect()->back()->with('success', "{$updatedCount} Manualbook berhasil diupdate");
    }

    public function update(Request $request, $id)
    {
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaGambarLama = DataInventaris::find($id)->gambar;
            if ($namaGambarLama) {
                Storage::delete('public/gambar/' . $namaGambarLama);
            }

            // Kompres gambar menggunakan Intervention Image
            $namaFile = $gambar->hashName();
            $lokasiSimpan = storage_path('app/public/gambar/' . $namaFile);

            // Pastikan library Intervention Image sudah diimport di atas
            $image = Image::make($gambar->getRealPath());
            $image->encode('jpg', 70)->save($lokasiSimpan);

            $data['gambar'] = $namaFile;
        }

        if ($request->hasFile('dokumen')) {
            $dokumen = $request->file('dokumen');
            $namaDokumenLama = DataInventaris::find($id)->dokumen;
            if ($namaDokumenLama) {
                Storage::delete('public/dokumen/' . $namaDokumenLama);
            }
            $dokumen->storeAs('public/dokumen', $dokumen->hashName());
            $data['dokumen'] = $dokumen->hashName();
        }
        if ($request->hasFile('manualbook')) {
            $manualbook = $request->file('manualbook');
            $namaManualbookLama = DataInventaris::find($id)->manualbook;
            if ($namaManualbookLama) {
                Storage::delete('public/manualbook/' . $namaManualbookLama);
            }
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());
            $data['manualbook'] = $manualbook->hashName();
        }
        $data['nama'] = $request->nama;
        $data['real_name'] = $request->real_name;
        $data['no_inventaris'] = $request->no_inventaris;
        $data['no_sn'] = $request->no_sn;
        $data['tanggal_beli'] = $request->tanggal_beli;
        $data['departemen'] = $request->departemen;
        $data['unit'] = $request->unit;
        $data['pengguna'] = $request->userPengguna;
        $data['keterangan'] = $request->keterangan;
        $data['klasifikasi'] = $request->klasifikasi;
        $data['UpdateName'] = auth()->user()->name ?? null;
        $data['UpdateById'] = auth()->user()->id ?? null;
        $query = DataInventaris::find($id);
        $query->update($data);

        return redirect()->route('inventaris.index')->with('success', 'Data berhasil di ubah');
    }

    public function getMasterItem(Request $request)
    {
        $dataItem = DB::connection('mysql')->table('data_inventaris')->where('nama_rs', auth()->user()->kodeRS);
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem = $dataItem->where('nama', 'LIKE', "%$search%")->limit(10)->get();
        } else {
            $dataItem = $dataItem->limit(10)->get();
        }
        return response()->json($dataItem);
    }

    public function destroy($id)
    {
        $data = DataInventaris::find($id);
        $data->delete();
        return response()->json(['msg' => 'Deleted successfully']);
    }
}
