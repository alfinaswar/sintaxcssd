<?php

namespace App\Http\Controllers;

use App\Exports\ItemRuanganExport;
use App\Exports\LaporanPembersihanAlat;
use App\Models\DataInventaris;
use App\Models\FormulirPembersihan;
use App\Models\MasterMerk;
use App\Models\MasterRs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class FormulirPembersihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            $data = MasterRs::get();
        } else {
            $data = MasterRs::where('kodeRS', auth()->user()->kodeRS)->get();
        }
        $merk = MasterMerk::where('nama_rs', auth()->user()->kodeRS)->get();
        $alat = DataInventaris::select('nama')->distinct()->get();
        return view('laporan.monitoring.index', compact('data', 'merk', 'alat'));
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            $query = FormulirPembersihan::select([
                'formulir_pembersihans.*',
                'data_inventaris.real_name as inventaris_real_name',
                'data_inventaris.no_inventaris as inventaris_no_inventaris'
            ])
                ->join('data_inventaris', 'formulir_pembersihans.kode_item', '=', 'data_inventaris.kode_item');

            if ($user->role !== "admin") {
                // INNER JOIN otomatis memfilter data yang tidak punya relasi inventaris
                // Jadi tidak perlu lagi memakai ->whereHas('getInventaris')
                $query->where('data_inventaris.nama_rs', $user->kodeRS);
            }

            // 2. Spesifikasikan nama tabel pada latest() agar tidak error 'ambiguous column'
            $query->latest('formulir_pembersihans.created_at');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->search['value'])) {
                        $keyword = $request->search['value'];
                        $query->where(function ($q) use ($keyword) {
                            // 3. Prefix nama tabel untuk menghindari error 'ambiguous column' saat search
                            $q->where('formulir_pembersihans.kode_item', 'like', "%{$keyword}%")
                                ->orWhere('formulir_pembersihans.Status', 'like', "%{$keyword}%")
                                ->orWhere('formulir_pembersihans.Keterangan', 'like', "%{$keyword}%");

                            // Opsional: Hapus komentar di bawah jika ingin kolom search bisa mencari nama alat
                            // ->orWhere('data_inventaris.real_name', 'like', "%{$keyword}%");
                        });
                    }
                }, true)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="javascript:void(0)" onclick="openEditModal(' . $row->id . ')" class="btn btn-outline-warning btn-icon" data-toggle="kt-tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></a> ';
                    $btnDelete = '<form action="' . route('formulir-pembersihan.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Hapus Data" class="btn btn-outline-danger btn-icon"><i class="fa fa-trash"></i></button>'
                        . '</form>';
                    return $btnEdit . $btnDelete;
                })
                ->addColumn('kode_item', function ($row) {
                    // 4. Ambil langsung dari kolom hasil join, tidak perlu akses relasi lagi
                    $nama = $row->inventaris_real_name ?? '-';
                    $no_inventaris = $row->inventaris_no_inventaris ?? '-';
                    return $nama . '-' . $no_inventaris;
                })
                ->addColumn('Tanggal', function ($row) {
                    return $row->Tanggal ? date('d-m-Y', strtotime($row->Tanggal)) : '-';
                })
                ->editColumn('Status', function ($row) {
                    return $row->Status == 1 ? "Sudah Dibersihkan" : "Belum Dibersihkan";
                })
                // Catatan: editColumn 'Before' dan 'After' dihapus karena sudah di-render di JS (lihat Bonus Fix)
                ->editColumn('Keterangan', function ($row) {
                    return $row->Keterangan ?: '-';
                })
                ->rawColumns(['action', 'kode_item'])
                ->make(true);
        }

        $rs = MasterRs::all();
        return view('pembersihan-alat.index', compact('rs'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Print(Request $request)
    {
        // dd($request->all());
        $unit = $request->unit;
        $namaunit = str_replace('/', ' atau ', $request->unit);
        $jenis = $request->jenis;
        $alat = $request->nama;
        $rs = $request->rs;

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        if ($jenis == 'Medis') {
            $header = 'Medis';
        } else {
            $header = 'Umum';
        }
        if ($request->format == 'excel') {
            $nama_file = 'laporan Monitoring Alat ' . $namaunit . '.xlsx';
            return Excel::download(new LaporanPembersihanAlat($unit, $jenis, $alat, $rs, $bulan, $tahun), $nama_file);


        } else if ($request->format == 'pdf') {


            $query = DataInventaris::with([
                'getLaporanMonitoring' => function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun);
                }
            ])
                ->when($unit, function ($query) use ($unit) {
                    return $query->where('unit', $unit);
                })
                ->when($alat, function ($query) use ($alat) {
                    return $query->where('kode_item', $alat);
                })
                ->when($jenis, function ($query) use ($jenis) {
                    return $query->where('pengguna', $jenis);
                })
                ->when($rs, function ($query) use ($rs) {
                    return $query->where('nama_rs', $rs);
                })
                ->orderBy('unit', 'asc')
                ->get();


            // dd($query);
            $pdf = Pdf::loadView('laporan.monitoring.cetak_pdf', compact('query', 'unit', 'header'));
            return $pdf->stream('Laporan Monitoring Alat ' . $unit . '.pdf');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['createdBy'] = auth()->user()->id ?? 1;

        // Kompres gambar sebelum disimpan agar ukuran file lebih kecil
        if ($request->hasFile('Before')) {
            $file = $request->file('Before');
            $filename = $file->getClientOriginalName() . '-' . microtime(true) . '.' . $file->getClientOriginalExtension();

            // Kompres gambar menggunakan Intervention Image
            $image = Image::make($file->getRealPath());
            $image->encode('jpg', 70); // Kompres ke kualitas 70
            $path = public_path('storage/gambar/Pembersihan/Before/' . $filename);
            $image->save($path);

            $data['Before'] = $filename;
        }

        if ($request->hasFile('After')) {
            $file = $request->file('After');
            $filename = $file->getClientOriginalName() . '-' . microtime(true) . '.' . $file->getClientOriginalExtension();

            // Kompres gambar menggunakan Intervention Image
            $image = Image::make($file->getRealPath());
            $image->encode('jpg', 70); // Kompres ke kualitas 70
            $path = public_path('storage/gambar/Pembersihan/After/' . $filename);
            $image->save($path);

            $data['After'] = $filename;
        }

        FormulirPembersihan::create($data);
        return redirect()->back()->with('success', 'Data Telah Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function show(FormulirPembersihan $formulirPembersihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function edit(FormulirPembersihan $formulirPembersihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormulirPembersihan $formulirPembersihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormulirPembersihan $formulirPembersihan)
    {
        //
    }
}
