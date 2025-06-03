<?php
namespace App\Http\Controllers;

use app\Exports\MutasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class MutasiController extends Controller
{
    function __construct()
    {

    }

    public function index(Request $request)
    {
        return view('mutasi.index');
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
}
