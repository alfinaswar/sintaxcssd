<?php

namespace App\Http\Controllers;

use App\Models\kso;
use App\Models\MasterDepartemenModel;
use App\Models\MasterRs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->role == 'admin') {
            $data = kso::with('getNamaBarang', 'getKalibrasi')->latest()->get();
        } else {
            $data = kso::with('getNamaBarang', 'getKalibrasi')->where('KodeRs', auth()->user()->kodeRS)->latest()->get();
        }
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btndelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btnupdate = '<a href="' . route('kso.edit', $row->id) . '" target="_blank"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-user-cog"></i></button></a></center>';
                    return $btnupdate . ' ' . $btndelete;
                })
                ->addColumn('TanggalKSO', function ($row) {
                    $TanggalKSO = $row->TanggalKerjaSama . '<hr>' . $row->DueDateKerjaSama;
                    return $TanggalKSO;
                })
                ->addColumn('DokumenKalibrasi', function ($row) {
                    $DokumenKalibrasi = '<a href="' . asset('storage/kso/' . $row->DokumenKalibrasi) . '" target="_blank">Lihat Dokumen Kalibrasi</a>';
                    return $DokumenKalibrasi;
                })
                ->addColumn('DokumenKso', function ($row) {
                    $DokumenKso = '<a href="' . asset('storage/kso/' . $row->DokumenKso) . '" target="_blank">Lihat Dokumen KSO</a>';
                    return $DokumenKso;
                })
                ->rawColumns(['action', 'TanggalKSO', 'DokumenKalibrasi', 'DokumenKso'])
                ->make(true);
        }
        $rs = MasterRs::all();
        $dept = MasterDepartemenModel::where('KodeRS', auth()->user()->kodeRS)->get();
        return view('kso.index', compact('rs', 'dept'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kso.create');
    }

    public function KsoPdf()
    {
        $rs = MasterRs::all();
        return view('kso.laporan', compact('rs'));
    }

    public function LaporanPDF(Request $request)
    {
        $data = kso::with('getNamaBarang', 'getKalibrasi')
            ->when($request->filled('filter_rs'), function ($query) use ($request) {
                $query->where('KodeRs', $request->filter_rs);
            })
            ->get();
        $pdf = Pdf::loadView('kso.FormatLaporanKso', compact('data'));
        return $pdf->download('laporan_kso.pdf');
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
        if ($request->hasFile('DokumenKalibrasi')) {
            $file = $request->file('DokumenKalibrasi');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $hasname = $filename . '.' . $extension;
            $file->storeAs('kso', $hasname, 'public');
            $data['DokumenKalibrasi'] = $hasname;
        } else {
            $data['DokumenKalibrasi'] = null;
        }
        if ($request->hasFile('DokumenKso')) {
            $file = $request->file('DokumenKso');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $hasname = $filename . '.' . $extension;
            $file->storeAs('kso', $hasname, 'public');
            $data['DokumenKso'] = $hasname;
        } else {
            $data['DokumenKalibrasi'] = null;
        }
        $data['KodeRs'] = auth()->user()->kodeRS;
        kso::create($data);
        return redirect()->route('kso.index')->with('success', 'Data Berhasil Ditembahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\kso  $kso
     * @return \Illuminate\Http\Response
     */
    public function show(kso $kso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\kso  $kso
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = kso::with('getNamaBarang', 'getKalibrasi')->find($id)->first();
        return view('kso.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\kso  $kso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = kso::findOrFail($id);  // Mencari data berdasarkan ID
        $data->fill($request->except(['DokumenKalibrasi', 'DokumenKso']));
        if ($request->hasFile('DokumenKalibrasi')) {
            $file = $request->file('DokumenKalibrasi');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $hasname = $filename . '.' . $extension;
            $file->storeAs('kso', $hasname, 'public');
            $data->DokumenKalibrasi = $hasname;
        } else {
            $data->DokumenKalibrasi = null;
        }

        // Proses upload DokumenKso jika ada
        if ($request->hasFile('DokumenKso')) {
            $file = $request->file('DokumenKso');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $hasname = $filename . '.' . $extension;
            $file->storeAs('kso', $hasname, 'public');
            $data->DokumenKso = $hasname;
        } else {
            $data->DokumenKso = null;
        }

        $data->save();
        return redirect()->route('kso.index')->with('success', 'Data Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\kso  $kso
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = kso::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
