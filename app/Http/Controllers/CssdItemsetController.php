<?php

namespace App\Http\Controllers;

use App\Models\cssdItemset;
use App\Models\cssdItemsetDetail;
use App\Models\cssdMasterItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CssdItemsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = cssdMasterItem::with('getMerk', 'getTipe', 'getNamaRS', 'getSatuan')->where('KodeRs', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
        // dd($data);
        if ($request->ajax()) {
            if (auth()->user()->hasRole('superadmin_cssd')) {
                $data = cssdItemset::orderBy('id', 'desc')->get();
            } else {
                $data = cssdItemset::where('KodeRs', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('cssd-item-set.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cssd.master-item-set.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = cssdMasterItem::where('KodeRs', auth()->user()->kodeRs)->get();
        return view('cssd.master-item-set.create', compact('items'));
    }
    public function getItemDetail(Request $request)
    {
        $item = cssdMasterItem::with('getMerk', 'getTipe')->where('id', $request->id)->where('KodeRs', auth()->user()->kodeRS)->first();
        // dd($item);
        return response()->json([
            'Merk' => $item->getMerk->Merk ?? '',
            'Tipe' => $item->getTipe->Tipe ?? ''
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        cssdItemset::create([
            'KodeRs' => auth()->user()->kodeRS,
            'Nama' => $request->NamaSet,
            'idUser' => auth()->user()->id,
        ]);
        $getlatestid = cssdItemset::where('idUser', auth()->user()->id)->latest()->first()->id;

        cssdItemsetDetail::create([
            'IdItemset' => $getlatestid,
            'ItemId' => $request->Item,
            'Qty' => $request->Qty,
            'KodeRs' => auth()->user()->kodeRS
        ]);

        return redirect()->route('cssd-item-set.index')->with('success', 'Set Item Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cssdItemset  $cssdItemset
     * @return \Illuminate\Http\Response
     */
    public function show(cssdItemset $cssdItemset)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cssdItemset  $cssdItemset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = cssdItemset::with('DetailItem')->where('KodeRs', auth()->user()->kodeRS)->where('id', $id)->first();
        // dd($data);
        $itemIds = $data->DetailItem->ItemId;
        $getItems = cssdMasterItem::with('getMerk', 'getTipe')->whereIn('id', $itemIds)->get();
        $items = cssdMasterItem::where('KodeRs', auth()->user()->kodeRS)->get();
        // dd($items);
        return view('cssd.master-item-set.edit', compact('data', 'items', 'getItems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cssdItemset  $cssdItemset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cssdItemset $cssdItemset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cssdItemset  $cssdItemset
     * @return \Illuminate\Http\Response
     */
    public function destroy(cssdItemset $cssdItemset)
    {
        //
    }
}
