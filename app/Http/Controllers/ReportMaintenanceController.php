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
        $rs = MasterRs::all();
        return view('laporan.maintenance.laporanpm', compact('rs'));
    }
    public function excel_pm(Request $request)
    {
        $rs = $request->filter_rs;
        $tgl_mulai = $request->input('tgl_mulai') . ' 00:00:00';
        $tgl_akhir = $request->input('tgl_akhir') . ' 23:59:59';

        $nama_file = 'laporan Preventif Maintenance ' . $request->input('tgl_mulai') . ' - Hingga - ' . $request->input('tgl_akhir') . '.xlsx';
        return Excel::download(new PmExport($tgl_mulai, $tgl_akhir), $nama_file);
    }
}
