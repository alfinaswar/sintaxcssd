<?php

namespace App\Http\Controllers;

use App\Models\cssdPengajuanItem;
use App\Models\cssdPengajuanItemDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CssdPengajuanItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = cssdPengajuanItem::with('getDepartemen', 'getDiajukan', 'getRs')->orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnShow = '<a href="' . route('pengajuan-nama-item-cssd.show', $row->id) . '" class="btn btn-outline-info btn-icon" title="Show"><i class="fa fa-eye"></i></a>';
                    $btnEdit = '<a href="' . route('pengajuan-nama-item-cssd.edit', $row->id) . '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="fa fa-edit"></i></a>';
                    $btnDelete = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnShow . ' ' . $btnEdit . ' ' . $btnDelete;
                    return $btn;
                })
                ->editColumn('Status', function ($row) {
                    if ($row->Status == 'Y') {
                        return '<span class="badge badge-success">Disetujui</span>';
                    } elseif ($row->Status == 'N') {
                        return '<span class="badge badge-danger">Ditolak</span>';
                    } else {
                        return '<span class="badge badge-secondary">Proses Pengajuan</span>';
                    }
                })

                ->rawColumns(['action', 'Status'])
                ->make(true);
        }

        return view('cssd.master-item.pengajuan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cssd.master-item.pengajuan.create');
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
        $data['Keterangan'] = $request->Catatan ?? null;
        cssdPengajuanItem::create($data);

        // Ambil id pengajuan terbaru
        $idpengajuan = cssdPengajuanItem::latest()->first()->id;
        foreach ($request->Nama as $key => $value) {
            cssdPengajuanItemDetail::create([
                'IdPengajuan' => $idpengajuan,
                'NamaItem' => $value,
                'Merk' => $request->Merek[$key],
                'KodeRs' => auth()->user()->KodeRs ?? null,
                'Keterangan' => $request->Keterangan[$key] ?? null,
            ]);
        }
        return redirect()->route('pengajuan-nama-item-cssd.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function show(cssdPengajuanItem $cssdPengajuanItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function edit(cssdPengajuanItem $cssdPengajuanItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cssdPengajuanItem $cssdPengajuanItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cssdPengajuanItem  $cssdPengajuanItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(cssdPengajuanItem $cssdPengajuanItem)
    {
        //
    }
}
