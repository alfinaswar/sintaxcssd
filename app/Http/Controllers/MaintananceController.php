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

            $query = Maintanance::latest()->with('getInventaris');


            if ($user->role !== "admin") {
                $query->where('nama_rs', $user->kodeRS);
            }

            return DataTables::of($query)
                // ✅ TAMBAHKAN INI: Handle global search ke relasi
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->search['value'])) {
                        $keyword = $request->search['value'];
                        $query->where(function ($q) use ($keyword) {
                            $q->where('kode_item', 'like', "%{$keyword}%")
                                ->orWhere('keterangan', 'like', "%{$keyword}%")
                                ->orWhere('status', 'like', "%{$keyword}%")
                                ->orWhereHas('getInventaris', function ($sub) use ($keyword) {
                                    $sub->where(function ($ss) use ($keyword) {
                                        $ss->where('nama', 'like', "%{$keyword}%")
                                            ->orWhere('no_inventaris', $keyword)
                                            ->orWhere('no_sn', 'like', "%{$keyword}%");
                                    });
                                });

                        });
                    }
                }, true)



                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="javascript:void(0)" onclick="openEditModal(' . $row->id . ')" class="btn btn-outline-warning btn-icon" data-toggle="kt-tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></a> ';

                    $btnDelete = '<form action="' . route('maintanance.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Hapus Data" class="btn btn-outline-danger btn-icon"><i class="fa fa-trash"></i></button>'
                        . '</form>';

                    return $btnEdit . $btnDelete;
                })
                ->addColumn('bulan', function ($row) {
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
                // ✅ HAPUS filterColumn untuk kode_item (sudah handle di filter global)
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? "Sudah Maintenance" : "Belum Maintenance";
                })
                ->rawColumns(['action'])
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
        if (request()->ajax()) {
            $data = Maintanance::with('getInventaris')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
        return redirect()->route('maintanance.index');
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
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'status' => 'required|in:1,2',
            'keterangan' => 'nullable|string|max:500'
        ]);

        $maintanance = Maintanance::findOrFail($id);
        if (auth()->user()->role !== 'admin' && $maintanance->kodeRS !== auth()->user()->kodeRS) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $maintanance->update([
            'bulan' => $request->bulan,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data berhasil diupdate']);
        }

        return redirect()->route('maintanance.index')->with('success', 'Data berhasil diupdate');
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
