<?php

namespace App\Http\Controllers;

use App\Models\InventarisKso;
use App\Models\MasterAlat;
use App\Models\MasterRs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;

class InventarisKsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = InventarisKso::with('getNamaAlat', 'getMerk', 'getRS', 'getDepartemen');
            if (!auth()->user() || (strtolower(auth()->user()->role) !== 'admin')) {
                $data->where('NamaRS', auth()->user()->kodeRS);
            }


            // Filtering
            if ($request->filter_pengguna)
                $data->where('Pengguna', $request->filter_pengguna);
            if ($request->filter_rs)
                $data->where('NamaRS', $request->filter_rs);
            if ($request->filter_departemen)
                $data->where('Departemen', $request->filter_departemen);
            if ($request->filter_unit)
                $data->where('Unit', $request->filter_unit);
            if ($request->filter_tahun_kerjasama)
                $data->whereYear('TanggalKerjasama', $request->filter_tahun_kerjasama);

            if ($request->filter_pencarian) {
                $search = $request->filter_pencarian;
                $data->where(function ($q) use ($search) {
                    $q->where('Nama', 'like', "%{$search}%")
                        ->orWhere('KodeBarang', 'like', "%{$search}%")
                        ->orWhere('NoSn', 'like', "%{$search}%")
                        ->orWhere('Merk', 'like', "%{$search}%");
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('inventariskso.edit', $row->id) . '" class="btn btn-warning btn-sm btn-icon" title="Edit"><i class="la la-edit"></i></a> ';
                    $btn .= '<button onclick="delete_data(event, ' . $row->id . ')" class="btn btn-danger btn-sm btn-icon" title="Hapus"><i class="la la-trash"></i></button> ';

                    // Rapikan tampilan dropdown
                    $btn .= '
                    <div class="btn-group ml-1">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle px-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Lainnya" style="min-width:90px;">
                            <i class="la la-cogs"></i> Aksi
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="' . route('inventariskso.edit', $row->id) . '"><i class="fa fa-wrench mr-2"></i>Kalibrasi</a>
                            <a class="dropdown-item" href="' . route('inventariskso.edit', $row->id) . '"><i class="fa fa-barcode mr-2"></i>Cetak Barcode</a>
                        </div>
                    </div>
                    ';

                    return $btn;
                })


                ->editColumn('Nama', function ($row) {
                    return $row->getNamaAlat && $row->getNamaAlat->Nama
                        ? $row->getNamaAlat->Nama
                        : $row->Nama;
                })
                ->editColumn('Merk', function ($row) {
                    return $row->getMerk && $row->getMerk->nama
                        ? $row->getMerk->nama
                        : $row->Merk;
                })
                ->editColumn('NamaRS', function ($row) {
                    return $row->getRS && $row->getRS->nama
                        ? $row->getRS->nama
                        : $row->NamaRS;
                })
                ->editColumn('Departemen', function ($row) {
                    return $row->getDepartemen && $row->getDepartemen->nama
                        ? $row->getDepartemen->nama
                        : $row->Departemen;
                })



                ->rawColumns(['action'])
                ->make(true);
        }
        $rs = MasterRs::get(); // Sesuaikan dengan model RS kamu
        return view('data-inventaris.kso.index', compact('rs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rs = MasterRs::get();
        return view('data-inventaris.kso.create', compact('rs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Nama' => 'required',
            'Merk' => 'required',
            'Tipe' => 'nullable|string|max:255',
            'NoSn' => 'nullable|string|max:255',
            'Vendor' => 'required',
            'TanggalKerjasama' => 'required|date',
            'AkhirKerjasama' => [
                'nullable',
                'date',
                'after_or_equal:TanggalKerjasama'
            ],

            'Departemen' => 'required',
            'Unit' => 'required|string|max:255',
            'Pengguna' => 'required|in:Medis,Non Medis',
            'Klasifikasi' => 'nullable|in:None,High Risk,Medium Risk,Low to Medium Risk,Low Risk',
            'Keterangan' => 'nullable|string|max:1000',
            'Dokumen' => 'nullable|file|mimes:pdf,doc,docx,xlsx,csv,jpg,jpeg,png|max:5048',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = null;
        $dokumenPath = null;

        if ($request->hasFile('Gambar')) {
            $gambarFile = $request->file('Gambar');
            $gambarOriginalName = pathinfo($gambarFile->getClientOriginalName(), PATHINFO_FILENAME);
            $gambarExtension = $gambarFile->getClientOriginalExtension();
            $gambarTimestamp = time();
            $gambarFileName = $gambarOriginalName . '_' . $gambarTimestamp . '.' . $gambarExtension;
            $gambarFile->storeAs('inventariskso/gambar', $gambarFileName, 'public');
            $gambarPath = $gambarFileName;
        }

        if ($request->hasFile('Dokumen')) {
            $dokumenFile = $request->file('Dokumen');
            $dokumenOriginalName = pathinfo($dokumenFile->getClientOriginalName(), PATHINFO_FILENAME);
            $dokumenExtension = $dokumenFile->getClientOriginalExtension();
            $dokumenTimestamp = time();
            $dokumenFileName = $dokumenOriginalName . '_' . $dokumenTimestamp . '.' . $dokumenExtension;
            $dokumenFile->storeAs('inventariskso/dokumen', $dokumenFileName, 'public');
            $dokumenPath = $dokumenFileName;
        }


        $inventarisKso = new InventarisKso();
        $inventarisKso->Nama = $request->Nama;
        $inventarisKso->Merk = $request->Merk;
        $inventarisKso->Tipe = $request->Tipe;
        $inventarisKso->NoSn = $request->NoSn;
        $inventarisKso->Vendor = $request->Vendor;
        $inventarisKso->TanggalKerjasama = $request->TanggalKerjasama;
        $inventarisKso->AkhirKerjasama = $request->AkhirKerjasama;
        $inventarisKso->Departemen = $request->Departemen;
        $inventarisKso->Unit = $request->Unit;
        $inventarisKso->Pengguna = $request->Pengguna;
        $inventarisKso->Klasifikasi = $request->Klasifikasi;
        $inventarisKso->TglKalibrasi = $request->TglKalibrasi;
        $inventarisKso->Keterangan = $request->Keterangan;
        $inventarisKso->NamaRS = auth()->user()->kodeRS;
        $inventarisKso->Gambar = $gambarPath;
        $inventarisKso->Dokumen = $dokumenPath;
        $inventarisKso->save();

        return redirect()->route('inventaris.index-kso')->with('success', 'Inventaris KSO berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventarisKso  $inventarisKso
     * @return \Illuminate\Http\Response
     */
    public function show(InventarisKso $inventarisKso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventarisKso  $inventarisKso
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventarisKso = InventarisKso::find($id);
        return view('data-inventaris.kso.edit', compact('inventarisKso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventarisKso  $inventarisKso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Cari dulu datanya baru update
        $inventarisKso = InventarisKso::findOrFail($id);

        $validated = $request->validate([
            'Nama' => 'required',
            'Merk' => 'required',
            'Tipe' => 'nullable|string|max:255',
            'NoSn' => 'nullable|string|max:255',
            'Vendor' => 'required',
            'TanggalKerjasama' => 'required|date',
            'AkhirKerjasama' => [
                'nullable',
                'date',
                'after_or_equal:TanggalKerjasama'
            ],
            'Departemen' => 'required',
            'Unit' => 'required|string|max:255',
            'Pengguna' => 'required|in:Medis,Non Medis',
            'Klasifikasi' => 'nullable|in:None,High Risk,Medium Risk,Low to Medium Risk,Low Risk',
            'Keterangan' => 'nullable|string|max:1000',
            'TglKalibrasi' => 'nullable|date',
            'Dokumen' => 'nullable|file|mimes:pdf,doc,docx,xlsx,csv,jpg,jpeg,png|max:2048',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle Gambar
        $gambarPath = $inventarisKso->Gambar;
        if ($request->hasFile('Gambar')) {
            $gambarFile = $request->file('Gambar');
            $gambarOriginalName = pathinfo($gambarFile->getClientOriginalName(), PATHINFO_FILENAME);
            $gambarExtension = $gambarFile->getClientOriginalExtension();
            $gambarTimestamp = time();
            $gambarFileName = $gambarOriginalName . '_' . $gambarTimestamp . '.' . $gambarExtension;
            $gambarFile->storeAs('inventariskso/gambar', $gambarFileName, 'public');
            $gambarPath = $gambarFileName;
        }

        // Handle Dokumen
        $dokumenPath = $inventarisKso->Dokumen;
        if ($request->hasFile('Dokumen')) {
            $dokumenFile = $request->file('Dokumen');
            $dokumenOriginalName = pathinfo($dokumenFile->getClientOriginalName(), PATHINFO_FILENAME);
            $dokumenExtension = $dokumenFile->getClientOriginalExtension();
            $dokumenTimestamp = time();
            $dokumenFileName = $dokumenOriginalName . '_' . $dokumenTimestamp . '.' . $dokumenExtension;
            $dokumenFile->storeAs('inventariskso/dokumen', $dokumenFileName, 'public');
            $dokumenPath = $dokumenFileName;
        }

        $inventarisKso->Nama = $request->Nama;
        $inventarisKso->Merk = $request->Merk;
        $inventarisKso->Tipe = $request->Tipe;
        $inventarisKso->NoSn = $request->NoSn;
        $inventarisKso->Vendor = $request->Vendor;
        $inventarisKso->TanggalKerjasama = $request->TanggalKerjasama;
        $inventarisKso->AkhirKerjasama = $request->AkhirKerjasama;
        $inventarisKso->Departemen = $request->Departemen;
        $inventarisKso->Unit = $request->Unit;
        $inventarisKso->Pengguna = $request->Pengguna;
        $inventarisKso->Klasifikasi = $request->Klasifikasi;
        $inventarisKso->TglKalibrasi = $request->TglKalibrasi;
        $inventarisKso->Keterangan = $request->Keterangan;
        $inventarisKso->NamaRS = auth()->user()->kodeRS;
        $inventarisKso->Gambar = $gambarPath;
        $inventarisKso->Dokumen = $dokumenPath;
        $inventarisKso->save();

        return redirect()->route('inventaris.index-kso')->with('success', 'Inventaris KSO berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventarisKso  $inventarisKso
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventarisKso $inventarisKso)
    {
        // Hapus file gambar jika ada
        if ($inventarisKso->Gambar) {
            $gambarPath = storage_path('app/public/inventariskso/gambar/' . $inventarisKso->Gambar);
            if (file_exists($gambarPath)) {
                @unlink($gambarPath);
            }
        }

        // Hapus file dokumen jika ada
        if ($inventarisKso->Dokumen) {
            $dokumenPath = storage_path('app/public/inventariskso/dokumen/' . $inventarisKso->Dokumen);
            if (file_exists($dokumenPath)) {
                @unlink($dokumenPath);
            }
        }

        $inventarisKso->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Inventaris KSO berhasil dihapus.'
        ]);
    }
    public function getMasterAlat(Request $request)
    {
        $search = $request->get('q');
        $data = MasterAlat::where('Nama', 'like', "%{$search}%")
            ->select('id', 'Nama')
            ->limit(20)
            ->get();
        return response()->json($data);
    }
}
