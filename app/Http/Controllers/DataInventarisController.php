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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Helpers\R2Client;
use App\Models\KalibrasiModel;

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
                $data = DataInventaris::with('getKalibrasi')->latest();
            } else {
                $data = DataInventaris::with('getKalibrasi')->where('nama_rs', auth()->user()->kodeRS)->latest();
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
                ->addColumn('status_kalibrasi', function ($row) {
                    // Ambil data kalibrasi terbaru berdasarkan tanggal
                    $latestKal = $row->getKalibrasi ? $row->getKalibrasi->sortByDesc('tgl_kalibrasi')->first() : null;

                    // Only text color, no background.
                    $baseStyle = 'display:inline-block;padding:4px 8px;border-radius:4px;font-weight:bold;';
                    $iconStyle = 'margin-right:4px;';

                    if (!$latestKal || !$latestKal->exp_date) {
                        $labelStyle = $baseStyle . 'color:#6c757d;'; // abu-abu
                        return '<span style="'.$labelStyle.'"><i class="fa fa-minus-circle" style="'.$iconStyle.'"></i> Belum / Tidak Dikalibrasi</span>';
                    }

                    $expDate = Carbon::parse($latestKal->exp_date);
                    $daysLeft = Carbon::now()->diffInDays($expDate, false); // false = allow negative

                    if ($daysLeft < 0) {
                        // Sudah kadaluarsa - Merah tua
                        $labelStyle = $baseStyle . 'color:#a71d2a;';
                        return '<span style="'.$labelStyle.'" title="Kadaluarsa: '.$expDate->format('d M Y').'"><i class="fa fa-exclamation-triangle" style="'.$iconStyle.'"></i> Expired (' . abs($daysLeft) . ' hari)</span>';
                    } elseif ($daysLeft <= 7) {
                        // <= 1 minggu - Merah
                        $labelStyle = $baseStyle . 'color:#c82333;';
                        return '<span style="'.$labelStyle.'" title="Kadaluarsa: '.$expDate->format('d M Y').'"><i class="fa fa-bell" style="'.$iconStyle.'"></i> ' . $daysLeft . ' hari lagi (Minggu terakhir!)</span>';
                    } elseif ($daysLeft <= 30) {
                        // <= 1 bulan - Oranye
                        $labelStyle = $baseStyle . 'color:#a38320;';
                        return '<span style="'.$labelStyle.'" title="Kadaluarsa: '.$expDate->format('d M Y').'"><i class="fa fa-clock" style="'.$iconStyle.'"></i> ' . $daysLeft . ' hari lagi (&lt; 1 Bulan)</span>';
                    } elseif ($daysLeft <= 60) {
                        // <= 2 bulan - Biru muda
                        $labelStyle = $baseStyle . 'color:#0c5460;';
                        return '<span style="'.$labelStyle.'" title="Kadaluarsa: '.$expDate->format('d M Y').'"><i class="fa fa-info-circle" style="'.$iconStyle.'"></i> ' . $daysLeft . ' hari lagi (&lt; 2 Bulan)</span>';
                    } elseif ($daysLeft <= 90) {
                        // <= 3 bulan - Biru
                        $labelStyle = $baseStyle . 'color:#004085;';
                        return '<span style="'.$labelStyle.'" title="Kadaluarsa: '.$expDate->format('d M Y').'"><i class="fa fa-flag" style="'.$iconStyle.'"></i> ' . $daysLeft . ' hari lagi (&lt; 3 Bulan)</span>';
                    } else {
                        // Aman, > 3 bulan - Hijau
                        $labelStyle = $baseStyle . 'color:#155724;';
                        return '<span style="'.$labelStyle.'" title="Kadaluarsa: '.$expDate->format('d M Y').'"><i class="fa fa-check-circle" style="'.$iconStyle.'"></i> Aman (' . $daysLeft . ' hari)</span>';
                    }
                })
                ->addColumn('tahun_beli', function ($row) {
                    if (!$row->tanggal_beli) {
                        $tahun_beli = '-';
                    } else {
                        $tahun_beli = Carbon::parse($row->tanggal_beli)->format('Y');
                    }
                    return $tahun_beli;
                })
                ->addColumn('kode_item', function($row) {
                    $route = route('inventaris.show', $row->id);
                    return '<a href="' . $route . '" target="_blank" style="text-decoration: underline;">' . e($row->kode_item) . '</a>';
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
                    $filterUnit = $request->get('filter_unit');

                    if ($filterUnit && $filterUnit !== '') {
                        // Cek apakah ada karakter garis miring (/) menggunakan strpos
                        if (strpos($filterUnit, '/') !== false) {
                            $instance->where('unit', 'LIKE', '%' . $filterUnit . '%');
                        } else {
                            // dd($filterUnit);
                            // Kalau tidak ada, gunakan where biasa (exact match)
                            $instance->where('unit', $filterUnit);
                        }
                    }
                    if ($request->get('filter_unit') && $request->get('filter_unit') !== '') {
                        $instance->where('unit', 'like', '%' . $request->get('filter_unit') . '%');
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
                 ->rawColumns(['action', 'tahun_beli', 'kode_item', 'status_kalibrasi'])
                ->make(true);
        }
        $rs = MasterRs::all();
        $dept = MasterDepartemenModel::where('KodeRS', auth()->user()->kodeRS)->get();
        return view('data-inventaris.index', compact('rs', 'dept'));
    }
    public function indexKso(Request $request)
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
        return view('data-inventaris.kso.index', compact('rs', 'dept'));
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
                } elseif ($kodeRS === 'C') {  // hangtuah
                    $selectdb = 'mysql15';
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
                case 'C':
                    $selectdb = 'mysql15';
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
            case 'C':
                $selectdb = 'mysql15';
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
                case 'C':
                    $selectdb = 'mysql15';
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
            } elseif ($kodeRS === 'C') {  // batuaji
                $selectdb = 'mysql15';
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
        $dataMerk = MasterMerk::select('id', 'nama', 'nama_rs');
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

    public function storeNoro(Request $request)
    {
        // 1. Validasi Terpusat
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
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:5000',
            'manualbook' => 'nullable|file|mimes:pdf|max:5000',
            'isKalibrasi' => 'nullable|in:0,1',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Proses Data Dasar
        $JenisItem = $request->userPengguna == 'Medis' ? 'MED' : 'UMUM';

        $latestRecord = DataInventaris::latest('id')->first();
        $latestId = $latestRecord ? $latestRecord->id + 1 : 1;

        $NoInv = $JenisItem . '-' . $latestId;
        $kode_item = 'Item-' . str_pad($latestId, 8, '0', STR_PAD_LEFT);

        // Parsing nama dan assetID
        $dataNama = explode(',', $request->nama);
        $nama = $dataNama[1] ?? $request->nama;
        $assetid = $dataNama[0] ?? null;

        // 3. Siapkan Array Data
        $data = [
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
            'klasifikasi' => $request->klasifikasi,
            'tgl_kalibrasi' => $request->tgl_kalibrasi,
            'tgl_expire' => $request->tgl_expire,
            'nama_rs' => auth()->user()->kodeRS,
            'isKalibrasi' => $request->isKalibrasi,
            'UserCreate' => auth()->user()->name ?? null,
            'UserId' => auth()->user()->id ?? null,
            'UpdateName' => null,
            'UpdateById' => null,
        ];

        // 4. Inisialisasi R2 Client
        $r2 = new R2Client();

        // 5. Handle Upload GAMBAR (PATH TETAP: 'gambar/')
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = $file->hashName();

            $image = Image::make($file->getRealPath());
            $image->orientate();
            $image->encode('jpg', 70);

            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath);

            $r2->upload($tempPath, 'gambar/' . $filename, 'image/jpeg');
            File::delete($tempPath);

            $data['gambar'] = $filename;
        }

        // 6. Handle Upload MANUALBOOK (PATH TETAP: 'manualbook/')
        if ($request->hasFile('manualbook')) {
            $file = $request->file('manualbook');
            $filename = $file->hashName();

            $r2->upload($file->getRealPath(), 'manualbook/' . $filename, $file->getMimeType());

            $data['manualbook'] = $filename;
        }

        // 7. Simpan ke Database (HANYA 1 KALI)
        DataInventaris::create($data);

        return redirect()->route('inventaris.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function store(Request $request)
    {
        // 1. Validasi Terpusat (Menggabungkan semua validasi agar tidak berulang)
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
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:5000',
            'manualbook' => 'nullable|file|mimes:pdf|max:5000',
            'isKalibrasi' => 'nullable|in:0,1',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Proses Data Dasar (Logika tetap sama persis dengan kode asli Anda)
        $Harga = str_replace(['Rp. ', '.'], '', $request->Harga);
        $JenisItem = $request->userPengguna == 'Medis' ? 'MED' : 'UMUM';

        $latestRecord = DataInventaris::latest('id')->first();
        $latestId = $latestRecord ? $latestRecord->id + 1 : 1;

        $NoInv = $JenisItem . '-' . $latestId;
        $kode_item = 'Item-' . str_pad($latestId, 8, '0', STR_PAD_LEFT);

        // 3. Siapkan Array Data untuk Database (Hanya field teks/angka dulu)
        $data = [
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
            'klasifikasi' => $request->klasifikasi,
            'tgl_kalibrasi' => $request->tgl_kalibrasi,
            'tgl_expire' => $request->tgl_expire,
            'nama_rs' => auth()->user()->kodeRS,
            'isKalibrasi' => $request->isKalibrasi,
            'UserCreate' => auth()->user()->name ?? null,
            'UserId' => auth()->user()->id ?? null,
            'UpdateName' => null,
            'UpdateById' => null,
        ];

        // 4. Inisialisasi R2 Client
        $r2 = new R2Client();

        // 5. Handle Upload GAMBAR (dengan kompresi, PATH TETAP: 'gambar/')
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = $file->hashName();

            $image = Image::make($file->getRealPath());
            $image->orientate();
            $image->encode('jpg', 70);

            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath);

            // Upload ke R2 dengan path folder 'gambar/' (Sesuai permintaan)
            $r2->upload($tempPath, 'gambar/' . $filename, 'image/jpeg');

            File::delete($tempPath); // Hapus file temp
            $data['gambar'] = $filename; // Simpan nama file saja ke array $data
        }

        // 6. Handle Upload MANUALBOOK (PATH TETAP: 'manualbook/')
        if ($request->hasFile('manualbook')) {
            $file = $request->file('manualbook');
            $filename = $file->hashName();

            // Upload ke R2 dengan path folder 'manualbook/' (Sesuai permintaan)
            $r2->upload($file->getRealPath(), 'manualbook/' . $filename, $file->getMimeType());

            $data['manualbook'] = $filename; // Simpan nama file saja ke array $data
        }

        // 7. Simpan ke Database (HANYA 1 KALI, menggantikan 4 blok if/else yang panjang)
        DataInventaris::create($data);

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
            $namaManualbookLama = DataInventaris::where('assetID')->first()->manualbook;
            if ($namaManualbookLama) {
                Storage::delete('public/manualbook/' . $namaManualbookLama);
            }
            $manualbook->storeAs('public/manualbook', $manualbook->hashName());
        }

        $assetID = $request->kode_item;
        // dd($assetID);
        $manualbookName = isset($manualbook) ? $manualbook->hashName() : null;
        $updatedCount = DataInventaris::where('nama', 'like', '%' . $assetID . '%')
            ->where('nama_rs', 'K')
            ->update([
                'manualbook' => $manualbookName,
                'UpdateName' => 'KSO ANA'
            ]);
        // dd($updatedCount);
        return redirect()->back()->with('success', "{$updatedCount} Manualbook berhasil diupdate");
    }

    public function update(Request $request, $id)
    {
        // 1. Ambil data lama SEKALI saja (lebih efisien daripada memanggil find() berulang kali)
        $item = DataInventaris::findOrFail($id);

        // 2. Inisialisasi R2 Client dan Array Data
        $r2 = new R2Client();
        $data = [];

        // 3. Handle Upload GAMBAR (dengan kompresi & hapus file lama di R2)
        if ($request->hasFile('gambar')) {
            // Hapus file lama dari R2 jika ada
            if (!empty($item->gambar)) {
                $r2->delete('gambar/' . $item->gambar);
            }

            $file = $request->file('gambar');
            $filename = $file->hashName();

            // Kompres gambar
            $image = Image::make($file->getRealPath());
            $image->orientate(); // Memperbaiki rotasi gambar dari HP
            $image->encode('jpg', 70);

            // Simpan sementara di folder temp OS (agar tidak memenuhi storage Laravel)
            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath);

            // Upload ke R2 (Path folder TETAP: 'gambar/')
            $r2->upload($tempPath, 'gambar/' . $filename, 'image/jpeg');

            // Hapus file temp agar server tidak penuh
            File::delete($tempPath);

            $data['gambar'] = $filename;
        }

        // 4. Handle Upload DOKUMEN
        if ($request->hasFile('dokumen')) {
            // Hapus file lama dari R2 jika ada
            if (!empty($item->dokumen)) {
                $r2->delete('dokumen/' . $item->dokumen);
            }

            $file = $request->file('dokumen');
            $filename = $file->hashName();

            // Upload ke R2 (Path folder TETAP: 'dokumen/')
            $r2->upload($file->getRealPath(), 'dokumen/' . $filename, $file->getMimeType());

            $data['dokumen'] = $filename;
        }

        // 5. Handle Upload MANUALBOOK
        if ($request->hasFile('manualbook')) {
            if (!empty($item->manualbook)) {
                $r2->delete('manualbook/' . $item->manualbook);
            }
            $file = $request->file('manualbook');
            $filename = $file->hashName();
            $r2->upload($file->getRealPath(), 'manualbook/' . $filename, $file->getMimeType());

            $data['manualbook'] = $filename;
        }

        // 6. Update Field Teks Lainnya
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

        // 7. Simpan Perubahan ke Database
        $item->update($data);

        return redirect()->route('inventaris.index')->with('success', 'Data berhasil diubah');
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
        try {
            // 1. Ambil data. Jika tidak ditemukan, otomatis return 404 (aman dari error null)
            $item = DataInventaris::findOrFail($id);

            // 2. Inisialisasi R2 Client
            $r2 = new R2Client();

            // 3. Hapus file GAMBAR dari R2 jika ada
            if (!empty($item->gambar)) {
                $r2->delete('gambar/' . $item->gambar);
            }

            // 4. Hapus file DOKUMEN dari R2 jika ada
            if (!empty($item->dokumen)) {
                $r2->delete('dokumen/' . $item->dokumen);
            }

            // 5. Hapus file MANUALBOOK dari R2 jika ada
            if (!empty($item->manualbook)) {
                $r2->delete('manualbook/' . $item->manualbook);
            }

            // 6. Hapus data dari database
            $item->delete();

            // 7. Return response JSON (Sangat cocok untuk request AJAX/DataTables)
            return response()->json([
                'success' => true,
                'msg' => 'Data dan file terkait berhasil dihapus dari R2.'
            ]);

        } catch (\Exception $e) {
            // Handle error jika ada masalah (misal: record tidak ditemukan atau R2 error)
            return response()->json([
                'success' => false,
                'msg' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getItemPenghapusan(Request $request)
    {
        $search = $request->get('q', '');
        $term = strtolower($search);
        $unit = $request->get('unit', ''); // ✅ Ambil parameter unit dari request AJAX

        // Inisialisasi Query Builder
        $query = DataInventaris::query();

        // ✅ 1. Filter berdasarkan Unit
        if (!empty($unit)) {
            // PENTING: Sesuaikan 'unit' dengan nama kolom unit di tabel DataInventaris Anda
            // (misalnya: 'unit', 'nama_unit', 'kode_unit', atau 'id_unit')
            $query->where('unit', $unit);
        }

        // 2. Filter berdasarkan pencarian (jika ada keyword yang diketik)
        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('assetID', 'like', "%$term%")
                    ->orWhere('nama', 'like', "%$term%")
                    ->orWhere('kode_item', 'like', "%$term%")
                    ->orWhere('merk', 'like', "%$term%")
                    ->orWhere('real_name', 'like', "%$term%");
            });
        }

        // Eksekusi query dengan urutan dan limit
        $items = $query->where('nama_rs', auth()->user()->kodeRS)
            ->orderBy('assetID', 'asc')
            ->limit(30)
            ->get();


        // Format untuk Select2 (valuenya kode_item)
        $results = [];
        foreach ($items as $item) {
            $results[] = [
                'id' => $item->kode_item,
                'text' => $item->no_inventaris . ' - ' . $item->real_name . ' - ' . $item->merk
            ];
        }

        return response()->json($results);
    }
    public function show($id)
    {
        // Cari item DataInventaris berdasarkan id
        $item = DataInventaris::with([
            'DataMaintenance',
            'getKalibrasi',
            'getLaporanMonitoring' => function ($query) {
                // Ambil hanya 7 hari terakhir untuk formulir pembersihan
                $query->where('Tanggal', '>=', now()->subDays(7)->toDateString());
            }
        ])->find($id);
        return view('data-inventaris.show', compact('item')); // Sesuaikan path view Anda
    }
    public function storeKalibrasi(Request $request)
{
    $request->validate([
        'nama' => 'required|string',
        'idalat' => 'required',
        'kepemilikan' => 'required|string',
        'tgl_kalibrasi' => 'nullable|date',
        'exp_date' => 'nullable|date',
        'keterangan' => 'nullable|string',
        'dokumen' => 'required|mimes:jpeg,bmp,png,gif,svg,pdf,doc,docx|max:5000',
    ]);

    $dokumen = $request->file('dokumen');
    $filename = $dokumen->hashName();

    // 2. Upload ke Cloudflare R2
    try {
        $r2 = new R2Client();
        $r2->upload($dokumen->getRealPath(), 'dokumen/' . $filename, $dokumen->getMimeType());
    } catch (\Exception $e) {
        // Kembalikan JSON error agar ditangkap oleh blok 'error' di AJAX frontend
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupload dokumen ke R2: ' . $e->getMessage()
        ], 500);
    }

    // 3. Simpan ke Database
    try {
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

        // Kembalikan JSON success agar ditangkap oleh blok 'success' di AJAX frontend
        return response()->json([
            'success' => true,
            'message' => 'Data kalibrasi berhasil ditambahkan.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan data ke database: ' . $e->getMessage()
        ], 500);
    }
}
    public function getDepartemenPenghapusan(Request $request)
    {
        $search = $request->get('q', '');
        $term = strtolower($search);

        $departemens = MasterDepartemenModel::orderBy('nama', 'asc')
            ->limit(20)
            ->get();
        if (!empty($term)) {
            $departemens = $departemens->filter(function ($item) use ($term) {
                return str_contains(strtolower($item->nama ?? ''), $term);
            });
        }

        // Format untuk Select2
        $results = [];
        foreach ($departemens as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama
            ];
        }

        return response()->json($results);
    }
}
