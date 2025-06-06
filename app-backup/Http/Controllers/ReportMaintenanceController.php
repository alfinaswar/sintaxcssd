<?php
namespace App\Http\Controllers;

use App\Exports\MaintenanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class ReportMaintenanceController extends Controller
{
    function __construct()
    {

    }

    public function index(Request $request)
    {
        return view('laporan.maintenance.index');
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
        $tgl_mulai = $request->input('tgl_mulai') . ' 00:00:00';
        $tgl_akhir = $request->input('tgl_akhir') . ' 23:59:59';
        $nama_file = 'laporan Mutasi Item Ke ruangan ' . $request->input('tgl_mulai') . ' - Hingga - ' . $request->input('tgl_akhir') . '.xlsx';
        return Excel::download(new MaintenanceExport($tgl_mulai, $tgl_akhir), $nama_file);
    }
}
