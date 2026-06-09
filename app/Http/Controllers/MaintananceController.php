<?php

namespace App\Http\Controllers;

use App\Exports\MaintananceExport;
use App\Models\MasterRs;
use App\Models\Maintanance;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\DataInventaris;
use App\Models\MasalahModel;
use App\Models\KalibrasiModel;
use Maatwebsite\Excel\Facades\Excel;

class MaintananceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            // 1. OPTIMASI UTAMA: Gunakan Query Builder, HAPUS ->get()
            $query = Maintanance::with('getInventaris');

            if ($user->role !== "admin") {
                $query->where('kodeRS', $user->kodeRS);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="javascript:void(0)" onclick="editMaintanance(' . $row->id . ')" class="btn btn-outline-warning btn-icon" data-toggle="kt-tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></a> ';


                    $btnDelete = '<form action="' . route('maintanance.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Hapus Data" class="btn btn-outline-danger btn-icon"><i class="fa fa-trash"></i></button>'
                        . '</form>';

                    return $btnEdit . $btnDelete;
                })
                ->addColumn('bulan', function ($row) {
                    // Tampilkan Bulan dan Tahun
                    $months = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember'
                    ];
                    $bulan = $months[$row->bulan] ?? '-';
                    $tahun = $row->created_at ? date('Y', strtotime($row->created_at)) : '-';

                    return $bulan . ' ' . $tahun;
                })

                ->editColumn('kode_item', function ($row) {
                    if ($row->getInventaris) {
                        return $row->getInventaris->no_inventaris . ' - ' . $row->getInventaris->nama;
                    }
                    return $row->kode_item ?? '-';
                })
                // 3. OPTIMASI SEARCH: Agar pencarian "Nama Barang" berfungsi di DataTables
                ->filterColumn('kode_item', function ($query, $keyword) {
                    $query->where('kode_item', 'like', "%{$keyword}%")
                        ->orWhereHas('getInventaris', function ($q) use ($keyword) {
                            $q->where('nama', 'like', "%{$keyword}%")
                                ->orWhere('no_inventaris', 'like', "%{$keyword}%");
                        });
                })
                ->addColumn('status', function ($row) {
                    // 4. BUG FIX: Gunakan '==' (perbandingan) bukan '=' (assignment)
                    return $row->status == 1 ? "Sudah Maintenance" : "Belum Maintenance";
                })
                ->rawColumns(['action']) // Hanya action yang berisi HTML mentah
                ->make(true);
        }

        $rs = MasterRs::all();
        return view('maintanance.index', compact('rs'));
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
    public function export(Request $request)
    {
        $request->validate([
            'filter_rs' => 'nullable|string',
            'bulan_awal' => 'required|integer|min:1|max:12',
            'bulan_akhir' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer'
        ]);

        $filterRs = auth()->user()->role == 'admin' ? $request->filter_rs : auth()->user()->kodeRS;

        if ($request->bulan_awal > $request->bulan_akhir) {
            return back()->with('error', 'Bulan awal tidak boleh lebih besar dari bulan akhir');
        }

        $filename = 'Laporan_Preventif_Maintenance_' . $request->tahun . '_' . date('YmdHis');

        return Excel::download(
            new MaintananceExport(
                $filterRs,
                $request->bulan_awal,
                $request->bulan_akhir,
                $request->tahun
            ),
            $filename . '.xlsx'
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //pisahkan koma
        $latest = Maintanance::latest()->first()->id ?? 0 + 1;
        $kd_mtnc = 'MTNC' . $latest . '';
        $datanama = $request->nama;
        $kodeRS = auth()->user()->kodeRS;
        $result = explode(",", $datanama);
        $assetid = $result[0];
        $nama = $result[1];

        Maintanance::create([
            'kode_maintanance' => $kd_mtnc,
            'kode_item' => $assetid,
            'assetID' => $assetid,
            'bulan' => $request->bulan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'id_user' => auth()->user()->id,
            'nama_rs' => auth()->user()->kodeRS,
        ]);
        return redirect()->route('maintanance.index')->with('success', 'Data berhasil ditambahkan');
    }
    public function AddPm(Request $request)
    {
        //pisahkan koma
        $latest = Maintanance::latest()->first()->id ?? 0 + 1;
        $kd_mtnc = 'MTNC' . $latest . '';
        $datanama = $request->nama;
        $kodeRS = auth()->user()->kodeRS;
        if ($request->hasFile('dokumentasi')) {
            $dokumentasi = $request->file('dokumentasi');
            $dokumentasi->storeAs('public/dokumentasi', $dokumentasi->hashName());
            $dokumentasi = $dokumentasi->hashName();
        } else {
            $dokumentasi = null;
        }
        Maintanance::create([
            'kode_maintanance' => $kd_mtnc,
            'kode_item' => $datanama,
            'assetID' => $datanama,
            'bulan' => $request->bulan,
            'status' => $request->status,
            'kategori' => $request->kategori,
            'dokumentasi' => $dokumentasi,
            'keterangan' => $request->keterangan,
            'id_user' => auth()->user()->id,
            'nama_rs' => auth()->user()->kodeRS,
        ]);
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
