<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\Flipbook;
use App\Models\MasterMerk;
use App\Models\MasterRs;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FlipbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Flipbook::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnshow = '<a href="' . route('flipbook.show', $row->id) . '" target="_blank"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btn = $btnshow;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('flipbook.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            $data = MasterRs::get();
        } else {
            $data = MasterRs::where('kodeRS', auth()->user()->kodeRS)->get();
        }
        $merk = MasterMerk::where('nama_rs', auth()->user()->kodeRS)->get();
        $alat = DataInventaris::select('nama')->distinct()->get();
        return view('flipbook.create', compact('data', 'merk', 'alat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
    $unit = $request->unit;
    $namaunit = str_replace('/', ' atau ', $unit);
    $jenis = $request->jenis;
    $alat = $request->nama;
    $merk = $request->merk;
    $rs = $request->rs;
    $tahun = $request->TahunPembelian;

    $header = $jenis == 'Medis' ? 'Medis' : 'Umum';

    $flipbook = Flipbook::create([
        'Nama' => auth()->user()->name ?? 'Admin',
        'NamaItem' => $alat ?? null,
        'Jenis' => $jenis,
        'RumahSakit' => $rs ?? null,
        'Departemen' => $unit ?? null,
        'NamaFile' => $request->NamaFile,
        'TanggalPembelian' => $request->TahunPembelian,
    ]);

    $query = DataInventaris::
        when($unit, fn($q) => $q->where('unit', $unit))
        ->when($alat, fn($q) => $q->where('nama', $alat))
        ->when($jenis, fn($q) => $q->where('pengguna', $jenis))
        ->when($merk, fn($q) => $q->where('merk', $merk))
        ->when($rs, fn($q) => $q->where('nama_rs', $rs))
        ->when($tahun, fn($q) => $q->whereYear('tanggal_beli', $rs))
        ->orderBy('unit', 'asc')
        ->get();
    $pdf = Pdf::loadView('flipbook.item_pdf', compact('query', 'unit', 'header'));

    $timestamp = Carbon::now()->format('Ymd_His');
    $filename = $request->NamaFile . '.pdf';
    $path = 'flipbooks/' . $filename;
    Storage::disk('public')->put($path, $pdf->output());

   return redirect()->route('flipbook.index')->with('success','Flipbook berhasil Dibauat');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Flipbook  $flipbook
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nama = Flipbook::find($id);
        return view('flipbook.show', compact('nama'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Flipbook  $flipbook
     * @return \Illuminate\Http\Response
     */
    public function edit(Flipbook $flipbook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Flipbook  $flipbook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flipbook $flipbook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Flipbook  $flipbook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flipbook $flipbook)
    {
        //
    }
}
