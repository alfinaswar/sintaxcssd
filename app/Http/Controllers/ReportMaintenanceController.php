<?php
namespace App\Http\Controllers;

use App\Exports\MaintenanceExport;
use App\Exports\PmExport;
use App\Models\MasterRs;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class ReportMaintenanceController extends Controller
{
    function __construct()
    {

    }

    public function index(Request $request)
    {
        $rs = MasterRs::all();
        return view('laporan.maintenance.index', compact('rs'));
    }

    public function create()
    {

    }
    public function export()
    {
        return Excel::download(new MaintenanceExport, 'Laporan Maintenance.xlsx');
    }
    public function excel_maintenance(Request $request)
    {
        if ($request->rs == 'none') {
            $rs = 'none';
        } else {
            $rs = $request->filter_rs;
        }
        $tgl_mulai = $request->input('tgl_mulai') . ' 00:00:00';
        $tgl_akhir = $request->input('tgl_akhir') . ' 23:59:59';
        $nama_file = 'laporan Mutasi Item Ke ruangan ' . $request->input('tgl_mulai') . ' - Hingga - ' . $request->input('tgl_akhir') . '.xlsx';
        return Excel::download(new MaintenanceExport($tgl_mulai, $tgl_akhir, $rs), $nama_file);
    }
    public function pm(Request $request)
    {
        $rs = MasterRs::get();
        return view('laporan.maintenance.laporanpm', compact('rs'));
    }
    public function excel_pm(Request $request)
    {
        $bulan_mulai = $request->input('bulan_mulai');
        $bulan_akhir = $request->input('bulan_akhir');
        $tahun = $request->input('tahun');
        $jenis = $request->input('jenis_alat');
        $kategori_risk = $request->input('kategori_risk');
        $filter_rs = $request->input('filter_rs'); // Hanya untuk admin
        if (!$tahun) {
            return back()->with('error', 'Tahun wajib dipilih!');
        }
        $nama_file = 'Laporan-Preventif-' .
            ($bulan_mulai ? $bulan_mulai . '-' : '') .
            ($bulan_akhir ? $bulan_akhir . '-' : '') .
            $tahun .
            ($jenis ? '-' . $jenis : '') .
            '.xlsx';

        return Excel::download(
            new PmExport($bulan_mulai, $bulan_akhir, $tahun, $jenis, $kategori_risk, $filter_rs),
            $nama_file
        );
    }
}
