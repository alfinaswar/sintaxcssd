<?php
namespace App\Http\Controllers;

use App\Exports\PerluKalibrasiExport;
use App\Helpers\R2Client;
use App\Models\DataInventaris;
use App\Models\KalibrasiModel;
use App\Models\MasterMerk;
use App\Models\MasterRs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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

    public function PerluDikalibrasi(Request $request)
    {
        // 1. Cek Role Admin (Sesuaikan dengan role actual Anda, misal: 'admin' atau 'superadmin')
        $isAdmin = in_array(auth()->user()->role, ['admin', 'DKH']);

        // 2. Dropdown RS (Admin = Semua, User = Hanya RS-nya)
        $listRS = MasterRS::select('kodeRS', 'nama')
            ->when(!$isAdmin, function ($q) {
                $q->where('kodeRS', auth()->user()->kodeRS);
            })
            ->pluck('nama', 'kodeRS');


        // 3. Base Query dengan Optimasi Kolom (Hanya ambil yang diperlukan)
        $query = DataInventaris::query()->select(
            'data_inventaris.id',
            'data_inventaris.kode_item',
            'data_inventaris.nama',
            'data_inventaris.unit',
            'data_inventaris.departemen',
            'data_inventaris.nama_rs'
        );

        // Filter Default User
        if (!$isAdmin) {
            $query->where('nama_rs', auth()->user()->kodeRS);
        }

        // 4. Filter Form
        if ($request->filled('rs'))
            $query->where('nama_rs', $request->rs);
        if ($request->filled('unit'))
            $query->where('unit', 'like', '%' . $request->unit . '%');
        if ($request->filled('nama'))
            $query->where('nama', 'like', '%' . $request->nama . '%');

        // 5. Logika Eskalasi (Sangat Cepat berkat latestOfMany)
        if ($request->filled('eskalasi')) {
            $targetDate = Carbon::now()->addMonths((int) $request->eskalasi)->format('Y-m-d');
            $query->where(function ($q) use ($targetDate) {
                $q->whereDoesntHave('kalibrasiTerbaru')
                    ->orWhereHas('kalibrasiTerbaru', function ($sub) use ($targetDate) {
                        $sub->where('exp_date', '<=', $targetDate);
                    });
            });
        } else {
            $today = Carbon::now()->format('Y-m-d');
            $query->where(function ($q) use ($today) {
                $q->whereDoesntHave('kalibrasiTerbaru')
                    ->orWhereHas('kalibrasiTerbaru', function ($sub) use ($today) {
                        $sub->where('exp_date', '<=', $today);
                    });
            });
        }

        // 6. Handle AJAX DataTables
        if ($request->ajax()) {
            $data = $query->with([
                'kalibrasiTerbaru' => function ($q) {
                    // OPTIMASI: Hanya ambil kolom ini dari tabel kalibrasi untuk menghemat memori,
                    //           dengan prefix table agar tidak ambiguous pada join
                    $q->select('kalibrasi.id', 'kalibrasi.assetID', 'kalibrasi.tgl_kalibrasi', 'kalibrasi.exp_date');
                }
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kalibrasi_info', function ($row) {
                    if ($row->kalibrasiTerbaru) {
                        $tgl = Carbon::parse($row->kalibrasiTerbaru->tgl_kalibrasi)->format('d M Y');
                        $exp = Carbon::parse($row->kalibrasiTerbaru->exp_date)->format('d M Y');
                        return "<small class='text-muted'>Terakhir: {$tgl}<br><b>Exp: {$exp}</b></small>";
                    }
                    return '<span class="text-muted font-italic">Belum Pernah</span>';
                })
                ->addColumn('status', function ($row) {
                    if (!$row->kalibrasiTerbaru || !$row->kalibrasiTerbaru->exp_date) {
                        return '<span class="badge badge-secondary"><i class="fa fa-minus-circle"></i> Belum Dikalibrasi</span>';
                    }

                    $expDate = Carbon::parse($row->kalibrasiTerbaru->exp_date);
                    $today = Carbon::now();
                    $daysLeft = $today->diffInDays($expDate, false);

                    if ($daysLeft < 0) {
                        $daysAgo = abs($daysLeft);
                        $lastCal = Carbon::parse($row->kalibrasiTerbaru->tgl_kalibrasi)->format('d M Y');
                        return "<span class='badge badge-danger' title='Tanggal Kalibrasi Terakhir: {$lastCal}'><i class='fa fa-exclamation-triangle'></i> Expired ({$daysAgo} hari yang lalu)</span>";
                    } else {
                        return "<span class='badge badge-warning text-dark' title='Exp: " . $expDate->format('d M Y') . "'><i class='fa fa-clock'></i> Sisa {$daysLeft} hari menuju expire</span>";
                    }
                })
                ->rawColumns(['kalibrasi_info', 'status'])
                ->make(true);
        }

        return view('kalibrasi.perlu-dikalibrasi', compact('listRS', 'isAdmin'));
    }

    // --- METHOD BARU UNTUK EXPORT EXCEL ---
    public function exportExcelPerluDikalibrasi(Request $request)
    {
        $isAdmin = in_array(auth()->user()->role, ['admin', 'DKH']);

        // Siapkan filter query seperti pada tampilan
        $query = DataInventaris::query()
            ->select(
                'data_inventaris.kode_item',
                'data_inventaris.nama',
                'data_inventaris.unit',
                'data_inventaris.departemen',
                'data_inventaris.nama_rs'
            );

        if (!$isAdmin)
            $query->where('nama_rs', auth()->user()->kodeRS);
        if ($request->filled('rs'))
            $query->where('nama_rs', $request->rs);
        if ($request->filled('unit'))
            $query->where('unit', 'like', '%' . $request->unit . '%');
        if ($request->filled('nama'))
            $query->where('nama', 'like', '%' . $request->nama . '%');

        if ($request->filled('eskalasi')) {
            $targetDate = Carbon::now()->addMonths((int) $request->eskalasi)->format('Y-m-d');
            $query->where(function ($q) use ($targetDate) {
                $q->whereDoesntHave('kalibrasiTerbaru')
                    ->orWhereHas('kalibrasiTerbaru', function ($sub) use ($targetDate) {
                        $sub->where('exp_date', '<=', $targetDate);
                    });
            });
        } else {
            $today = Carbon::now()->format('Y-m-d');
            $query->where(function ($q) use ($today) {
                $q->whereDoesntHave('kalibrasiTerbaru')
                    ->orWhereHas('kalibrasiTerbaru', function ($sub) use ($today) {
                        $sub->where('exp_date', '<=', $today);
                    });
            });
        }

        // Fix: Select assetID with table prefix to avoid ambiguity
        $data = $query->with([
            'kalibrasiTerbaru' => function ($q) {
                $q->select('kalibrasi.assetID', 'kalibrasi.tgl_kalibrasi', 'kalibrasi.exp_date');
            }
        ])->get();

        // Mapping data mentah dari database ke format array yang siap di-export
        $exportData = $data->map(function ($item) {
            $tglKalibrasi = $item->kalibrasiTerbaru ? $item->kalibrasiTerbaru->tgl_kalibrasi : null;
            $expDate = $item->kalibrasiTerbaru ? $item->kalibrasiTerbaru->exp_date : null;

            $status = 'Belum Dikalibrasi';
            if ($expDate) {
                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($expDate), false);
                $status = $daysLeft < 0 ? "Expired (" . abs($daysLeft) . " hari yang lalu)" : "Sisa {$daysLeft} hari menuju expire";
            }

            return [
                'kode_item' => $item->kode_item,
                'nama' => $item->nama,
                'nama_rs' => $item->nama_rs, // <-- Biarkan kode RS (misal: 'K', 'A') dikirim, nanti di-mapping di Class Export
                'unit' => $item->unit,
                'departemen' => $item->departemen,
                'tgl_kalibrasi' => $tglKalibrasi,
                'exp_date' => $expDate,
                'status' => $status,
            ];
        });

        // Panggil Class Export
        return Excel::download(new PerluKalibrasiExport($exportData->toArray()), 'Laporan_Perlu_Kalibrasi_' . now()->format('Ymd_His') . '.xlsx');
    }
}
