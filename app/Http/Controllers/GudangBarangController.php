<?php

namespace App\Http\Controllers;

use App\Models\GudangBarang;
use App\Models\MasterDepartemenModel;
use App\Models\MasterGudang;
use App\Models\MasterRs;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GudangBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->role == 'admin' || auth()->user()->role == 'DKH') {
                $data = GudangBarang::latest();
            } else {
                $data = GudangBarang::where('nama_rs', auth()->user()->kodeRS)->latest();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // Hanya aksi riwayat, tanpa aksi label, edit, dan hapus
                    $btn = '<center><a href="' . route('masalah.history', $row->kode_item) . '" target="_blank"><button type="button" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Lihat Riwayat" class="btn btn-outline-warning btn-icon btn-md" ><i class="fas fa-bookmark"></i></button></a></center>';
                    return $btn;
                })

                ->addColumn('tahun_beli', function ($row) {
                    if (!$row->tanggal_beli) {
                        $tahun_beli = '-';
                    } else {
                        $tahun_beli = Carbon::parse($row->tanggal_beli)->format('Y');
                    }
                    return $tahun_beli;
                })
                ->addColumn('nama_rs', function ($row) {
                    switch ($row->nama_rs) {
                        case 'K':
                            $realname = 'Awalbros Ayani';
                            break;
                        case 'I':
                            $realname = 'Awalbros Panam';
                            break;
                        case 'B':
                            $realname = 'Awalbros Batam';
                            break;
                        case 'A':
                            $realname = 'Awalbros Sudirman';
                            break;
                        case 'G':
                            $realname = 'Awalbros Ujung Batu';
                            break;
                        case 'S':
                            $realname = 'Awalbros Bagan Batu';
                            break;
                        case 'R':
                            $realname = 'Awalbros Botania';
                            break;
                        case 'D':
                            $realname = 'Awalbros Dumai';
                            break;
                        case 'Q':
                            $realname = 'Awalbros Hangtuah';
                            break;
                        case 'W':
                            $realname = 'Awalbros Batu Aji';
                            break;
                        default:
                            $realname = 'Nama RS Kosong';
                            break;
                    }

                    $print = $realname;
                    return $print;
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('filter_pengguna') && $request->get('filter_pengguna') !== '') {
                        $instance->where('pengguna', $request->get('filter_pengguna'));
                    }
                    if ($request->get('filter_rs') && $request->get('filter_rs') !== '') {
                        $instance->where('nama_rs', $request->get('filter_rs'));
                    }
                    if ($request->get('filter_departemen') && $request->get('filter_departemen') !== '') {
                        $instance->where('departemen', $request->get('filter_departemen'));
                    }
                    if ($request->get('filter_unit') && $request->get('filter_unit') !== '') {
                        $instance->where('unit', $request->get('filter_unit'));
                    }
                    if ($request->get('filter_pembelian') && $request->get('filter_pembelian') !== '') {
                        $instance->whereYear('tanggal_beli', $request->get('filter_pembelian'));
                    }
                    // Tambahkan filter gudang
                    if ($request->get('filter_gudang') && $request->get('filter_gudang') !== '') {
                        $instance->where('id_gudang', $request->get('filter_gudang'));
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w
                                ->orWhere('nama', 'LIKE', "%$search%")
                                ->orWhere('no_inventaris', 'LIKE', "%$search%")
                                ->orWhere('no_sn', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action', 'tahun_beli'])
                ->make(true);
        }
        $rs = MasterRs::all();
        $dept = MasterDepartemenModel::where('KodeRS', auth()->user()->kodeRS)->get();
        $gudang = MasterGudang::get();
        return view('gudang-barang.index', compact('rs', 'dept','gudang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GudangBarang  $gudangBarang
     * @return \Illuminate\Http\Response
     */
    public function show(GudangBarang $gudangBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GudangBarang  $gudangBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(GudangBarang $gudangBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GudangBarang  $gudangBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GudangBarang $gudangBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GudangBarang  $gudangBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(GudangBarang $gudangBarang)
    {
        //
    }
}
