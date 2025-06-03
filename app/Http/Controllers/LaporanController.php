<?php


namespace App\Http\Controllers;
use App\Exports\DataInventarisExport;
use App\Exports\ItemRuanganExport;
use App\Models\DataInventaris;
use App\Models\MasterMerk;
use App\Models\MasterRs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            $data = MasterRs::get();
        } else {
            $data = MasterRs::where('kodeRS', auth()->user()->kodeRS)->get();
        }
        $merk = MasterMerk::where('nama_rs', auth()->user()->kodeRS)->get();
        $alat = DataInventaris::select('nama')->distinct()->get();
        return view('laporan.item-ruangan.index', compact('data', 'merk', 'alat'));
    }

    public function create()
    {

    }
    public function export()
    {
        return Excel::download(new MutasiExport, 'Laporan Mutasi Item Ke Ruangan.xlsx');
    }
    public function excel_mutasi(Request $request)
    {
        $tgl_mulai = $request->input('tgl_mulai') . ' 00:00:00';
        $tgl_akhir = $request->input('tgl_akhir') . ' 23:59:59';
        $nama_file = 'laporan Mutasi Item Ke ruangan ' . $tgl_mulai . ' - Hingga - ' . $tgl_akhir . '.xlsx';
        return Excel::download(new MutasiExport($tgl_mulai, $tgl_akhir), $nama_file);
    }
    public function excel_item(Request $request)
    {
        $unit = $request->unit;
        $namaunit = str_replace('/', ' atau ', $request->unit);
        $jenis = $request->jenis;
        $alat = $request->nama;
        $merk = $request->merk;
        $rs = $request->rs;
        if ($jenis == 'Medis') {
            $header = 'Medis';
        } else {
            $header = 'Umum';
        }
        if ($request->format == 'excel') {
            $nama_file = 'laporan Item ruangan ' . $namaunit . '.xlsx';
            return Excel::download(new ItemRuanganExport($unit, $jenis, $alat, $merk, $rs), $nama_file);
        } else if ($request->format == 'pdf') {
            $query = DataInventaris::
                when($unit, function ($query) use ($unit) {
                    return $query->where('unit', $unit);
                })
                ->when($alat, function ($query) use ($alat) {
                    return $query->where('nama', $alat);
                })
                ->when($jenis, function ($query) use ($jenis) {
                    return $query->where('pengguna', $jenis);
                })
                ->when($merk, function ($query) use ($merk) {
                    return $query->where('merk', $merk);
                })
                ->when($rs, function ($query) use ($rs) {
                    return $query->where('nama_rs', $rs);
                })
                ->orderBy('unit', 'asc')
                ->get();
            // dd($query);

            $pdf = Pdf::loadView('laporan.item_pdf', compact('query', 'unit', 'header'));
            return $pdf->stream('Laporan Item Ruangan ' . $unit . '.pdf');
        }
    }
    public function DataInventaris(Request $request)
    {
        // dd($request->all());
        $filter_unit = $request->filter_unit;
        $filter_jenis = $request->filter_jenis; //medis non medis
        $filter_departemen = $request->filter_departemen;
        $namaunit = str_replace('/', ' atau ', $request->filter_unit);
        $filter_pembelian = $request->filter_pembelian;
        $filter_rs = $request->filter_rs;
        $filter_pencarian = $request->filter_pencarian;
        // dd($merk);
        $nama_file = 'laporan Item ruangan ' . $namaunit . '.xlsx';
        return Excel::download(new DataInventarisExport($filter_unit, $filter_jenis, $filter_departemen, $filter_pembelian, $filter_pencarian, $filter_rs), $nama_file);
    }
}
