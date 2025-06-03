<?php
namespace App\Http\Controllers;

use App\Exports\PembelianExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\MasalahModel;



class GudangController extends Controller
{
    function __construct()
    {

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->check()) {
                $kodeRS = auth()->user()->kodeRS;
                if ($kodeRS === 'K') {       //ayani
                    $selectdb = 'mysql2';
                } elseif ($kodeRS === 'I') { //panam
                    $selectdb = 'mysql3';
                } elseif ($kodeRS === 'B') { //batan
                    $selectdb = 'mysql4';
                } elseif ($kodeRS === 'A') { //sudirman
                    $selectdb = 'mysql5';
                } elseif ($kodeRS === 'G') { //ujung batu
                    $selectdb = 'mysql6';
                } elseif ($kodeRS === 'S') { //bagan batu
                    $selectdb = 'mysql7';
                } elseif ($kodeRS === 'B') { //botania
                    $selectdb = 'mysql8';
                }
            }
            // $data = DB::connection("mysql2")
            //     ->table('po2')
            //     ->whereYear('TanggalBuat', date('Y') - 1) //data setahun terakhir
            //     ->orderBy('PO2ID', 'desc')
            //     ->get();
            $data = DB::connection("mysql2")
                ->table('po2')
                ->join('masteritem', 'masteritem.ItemID', '=', 'po2.ItemID')
                ->limit(100)
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $print = '<a href="' . route('pembelian.export', $row->PO2ID) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Brand skin" class="btn btn-outline-primary btn-icon" ><i class="fa fa-print"></i></button></a>';

                    // $btn = $show . ' ' . $print;
                    return $print;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('gudang.index');
    }

    public function create()
    {
        return view('data-inventaris.create');
    }
    public function export()
    {
        return Excel::download(new PembelianExport, 'Laporan Pembelian.xlsx');
    }
    public function store(request $request)
    {

    }

    public function getItem(Request $request)
    {
        if (auth()->check()) {
            $kodeRS = auth()->user()->kodeRS;
            if ($kodeRS === 'K') {       //ayani
                $selectdb = 'mysql2';
            } elseif ($kodeRS === 'I') { //panam
                $selectdb = 'mysql3';
            } elseif ($kodeRS === 'B') { //batan
                $selectdb = 'mysql4';
            } elseif ($kodeRS === 'A') { //sudirman
                $selectdb = 'mysql5';
            } elseif ($kodeRS === 'G') { //ujung batu
                $selectdb = 'mysql6';
            } elseif ($kodeRS === 'S') { //bagan batu
                $selectdb = 'mysql7';
            } elseif ($kodeRS === 'B') { //botania
                $selectdb = 'mysql8';
            }
        }
        $item = [];
        $dataItem = DB::connection($selectdb)->table('masteritem')->where('KategoriItemID', '8');
        if ($request->has('q')) {
            $search = $request->q;
            $dataItem->where('Nama', 'LIKE', "%$search%")->limit(5)
                ->get(['itemID', 'Nama']);
            $item = $dataItem->pluck('Nama', 'itemID');
        } else {
            $item = $dataItem->limit(5)->get(['itemID', 'Nama'])->pluck('Nama', 'itemID');
        }
        return response()->json($item);
    }
    public function excel_pembelian(Request $request)
    {
        $tgl_mulai = $request->input('tgl_mulai') . ' 00:00:00';
        $tgl_akhir = $request->input('tgl_akhir') . ' 23:59:59';
        $nama_file = 'laporan Pembelian' . $tgl_mulai . ' - Hingga - ' . $tgl_akhir . '.xlsx';
        return Excel::download(new PembelianExport($tgl_mulai, $tgl_akhir), $nama_file);
    }
}
