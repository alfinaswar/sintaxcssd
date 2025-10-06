<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\FormulirPembersihan;
use App\Models\MasterMerk;
use App\Models\MasterRs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

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
        if ($jenis == 'Medis') {
            $header = 'Medis';
        } else {
            $header = 'Umum';
        }
        if ($request->format == 'excel') {
            $nama_file = 'laporan Monitoring Alat ' . $namaunit . '.xlsx';
            return Excel::download(new ItemRuanganExport($unit, $jenis, $alat, $merk, $rs), $nama_file);
        } else if ($request->format == 'pdf') {
            $query = DataInventaris::with('getLaporanMonitoring')->
                when($unit, function ($query) use ($unit) {
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

            dd($query);
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
