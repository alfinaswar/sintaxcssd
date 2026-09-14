<?php

namespace App\Http\Controllers;

use App\Exports\InventarisKsoExport;
use App\Models\InventarisKso;
use App\Models\MasterAlat;
use App\Models\MasterRs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Helpers\R2Client; // Pastikan import ini ada di bagian atas controller
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
            if ($request->filter_nama_barang)
                $data->whereYear('Nama', $request->filter_nama_barang);

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
        // 1. Validasi (Saya tambahkan TglKalibrasi karena ada di kode simpan Anda)
        $validated = $request->validate([
            'Nama' => 'required|string|max:255',
            'Merk' => 'required|string|max:255',
            'Tipe' => 'nullable|string|max:255',
            'NoSn' => 'nullable|string|max:255',
            'Vendor' => 'required|string|max:255',
            'TanggalKerjasama' => 'required|date',
            'AkhirKerjasama' => 'nullable|date|after_or_equal:TanggalKerjasama',
            'Departemen' => 'required|string|max:255',
            'Unit' => 'required|string|max:255',
            'Pengguna' => 'required|in:Medis,Non Medis',
            'Klasifikasi' => 'nullable|in:None,High Risk,Medium Risk,Low to Medium Risk,Low Risk',
            'TglKalibrasi' => 'nullable|date', // Ditambahkan agar valid
            'Keterangan' => 'nullable|string|max:1000',
            'Dokumen' => 'nullable|file|mimes:pdf,doc,docx,xlsx,csv,jpg,jpeg,png|max:5048',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Inisialisasi R2 Client
        $r2 = new R2Client();
        $gambarPath = null;
        $dokumenPath = null;

        // 3. Handle Upload GAMBAR (dengan kompresi)
        if ($request->hasFile('Gambar')) {
            $file = $request->file('Gambar');
            $filename = $file->hashName(); // Lebih aman untuk cloud storage

            // Kompres gambar
            $image = Image::make($file->getRealPath());
            $image->orientate(); // Memperbaiki rotasi gambar dari HP
            $image->encode('jpg', 70); // Kompresi ke kualitas 70%

            // Simpan sementara di folder temp OS
            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath);

            // Upload ke R2 (Path folder TETAP: 'inventariskso/gambar/')
            $r2->upload($tempPath, 'inventariskso/gambar/' . $filename, 'image/jpeg');

            // Hapus file temp agar server tidak penuh
            File::delete($tempPath);

            $gambarPath = $filename;
        }

        // 4. Handle Upload DOKUMEN
        if ($request->hasFile('Dokumen')) {
            $file = $request->file('Dokumen');
            $filename = $file->hashName();

            // Upload ke R2 (Path folder TETAP: 'inventariskso/dokumen/')
            $r2->upload($file->getRealPath(), 'inventariskso/dokumen/' . $filename, $file->getMimeType());

            $dokumenPath = $filename;
        }

        // 5. Simpan ke Database (Menggunakan create() agar lebih rapi dan aman)
        InventarisKso::create([
            'Nama' => $request->Nama,
            'Merk' => $request->Merk,
            'Tipe' => $request->Tipe,
            'NoSn' => $request->NoSn,
            'Vendor' => $request->Vendor,
            'TanggalKerjasama' => $request->TanggalKerjasama,
            'AkhirKerjasama' => $request->AkhirKerjasama,
            'Departemen' => $request->Departemen,
            'Unit' => $request->Unit,
            'Pengguna' => $request->Pengguna,
            'Klasifikasi' => $request->Klasifikasi,
            'TglKalibrasi' => $request->TglKalibrasi,
            'Keterangan' => $request->Keterangan,
            'NamaRS' => auth()->user()->kodeRS,
            'Gambar' => $gambarPath,   // Hanya menyimpan nama file
            'Dokumen' => $dokumenPath, // Hanya menyimpan nama file
        ]);

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
        // 1. Cari data lama (findOrFail mencegah error jika ID tidak ditemukan)
        $inventarisKso = InventarisKso::findOrFail($id);

        // 2. Validasi
        $validated = $request->validate([
            'Nama' => 'required|string|max:255',
            'Merk' => 'required|string|max:255',
            'Tipe' => 'nullable|string|max:255',
            'NoSn' => 'nullable|string|max:255',
            'Vendor' => 'required|string|max:255',
            'TanggalKerjasama' => 'required|date',
            'AkhirKerjasama' => 'nullable|date|after_or_equal:TanggalKerjasama',
            'Departemen' => 'required|string|max:255',
            'Unit' => 'required|string|max:255',
            'Pengguna' => 'required|in:Medis,Non Medis',
            'Klasifikasi' => 'nullable|in:None,High Risk,Medium Risk,Low to Medium Risk,Low Risk',
            'Keterangan' => 'nullable|string|max:1000',
            'TglKalibrasi' => 'nullable|date',
            'Dokumen' => 'nullable|file|mimes:pdf,doc,docx,xlsx,csv,jpg,jpeg,png|max:5048',
            'Gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 3. Inisialisasi R2 Client dan Array Data
        $r2 = new R2Client();
        $data = [];

        // 4. Handle Upload GAMBAR (dengan kompresi & hapus file lama)
        if ($request->hasFile('Gambar')) {
            // Hapus file lama dari R2 jika ada
            if (!empty($inventarisKso->Gambar)) {
                $r2->delete('inventariskso/gambar/' . $inventarisKso->Gambar);
            }

            $file = $request->file('Gambar');
            $filename = $file->hashName(); // Lebih aman untuk cloud storage

            // Kompres gambar
            $image = Image::make($file->getRealPath());
            $image->orientate(); // Memperbaiki rotasi gambar dari HP
            $image->encode('jpg', 70);

            // Simpan sementara di folder temp OS
            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath);

            // Upload ke R2 (Path folder TETAP: 'inventariskso/gambar/')
            $r2->upload($tempPath, 'inventariskso/gambar/' . $filename, 'image/jpeg');

            // Hapus file temp agar server tidak penuh
            File::delete($tempPath);

            $data['Gambar'] = $filename;
        }

        // 5. Handle Upload DOKUMEN (hapus file lama)
        if ($request->hasFile('Dokumen')) {
            // Hapus file lama dari R2 jika ada
            if (!empty($inventarisKso->Dokumen)) {
                $r2->delete('inventariskso/dokumen/' . $inventarisKso->Dokumen);
            }

            $file = $request->file('Dokumen');
            $filename = $file->hashName();

            // Upload ke R2 (Path folder TETAP: 'inventariskso/dokumen/')
            $r2->upload($file->getRealPath(), 'inventariskso/dokumen/' . $filename, $file->getMimeType());

            $data['Dokumen'] = $filename;
        }

        // 6. Update Field Teks Lainnya
        $data['Nama'] = $request->Nama;
        $data['Merk'] = $request->Merk;
        $data['Tipe'] = $request->Tipe;
        $data['NoSn'] = $request->NoSn;
        $data['Vendor'] = $request->Vendor;
        $data['TanggalKerjasama'] = $request->TanggalKerjasama;
        $data['AkhirKerjasama'] = $request->AkhirKerjasama;
        $data['Departemen'] = $request->Departemen;
        $data['Unit'] = $request->Unit;
        $data['Pengguna'] = $request->Pengguna;
        $data['Klasifikasi'] = $request->Klasifikasi;
        $data['TglKalibrasi'] = $request->TglKalibrasi;
        $data['Keterangan'] = $request->Keterangan;
        $data['NamaRS'] = auth()->user()->kodeRS;

        // 7. Simpan Perubahan ke Database (Lebih bersih daripada assign manual satu per satu)
        $inventarisKso->update($data);

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
        try {
            $r2 = new R2Client();
            if (!empty($inventarisKso->Gambar)) {
                $r2->delete('inventariskso/gambar/' . $inventarisKso->Gambar);
            }
            if (!empty($inventarisKso->Dokumen)) {
                $r2->delete('inventariskso/dokumen/' . $inventarisKso->Dokumen);
            }
            $inventarisKso->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data dan file Inventaris KSO berhasil dihapus dari R2.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
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

    public function laporanKso(Request $request)
    {
        $filename = 'Laporan-Inventaris-KSO-' . date('Ymd-His') . '.xlsx';
        return Excel::download(new InventarisKsoExport($request), $filename);
    }
}
