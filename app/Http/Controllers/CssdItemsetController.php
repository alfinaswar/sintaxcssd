<?php

namespace App\Http\Controllers;

use App\Models\cssdItemset;
use App\Models\cssdItemsetDetail;
use App\Models\cssdMasterItem;
use App\Models\MasterNamaSet;
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
                $data = cssdItemset::with('getNamaset', 'getrs', 'DetailItem')->orderBy('id', 'desc')->get();
            } else {
                $data = cssdItemset::with('getNamaset', 'getrs', 'DetailItem')->where('KodeRs', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('cssd-item-set.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->addColumn('Item', function ($row) {
                    $details = $row->DetailItem;
                    $labels = [];

                    if ($details && is_array($details->ItemId)) {
                        $itemIds = array_slice($details->ItemId, 0, 5);
                        foreach ($itemIds as $index => $itemId) {
                            $item = cssdMasterItem::with('getNama')->find($itemId);
                            $qty = is_array($details->Qty) && isset($details->Qty[$index]) ? $details->Qty[$index] : 0;
                            if ($item) {
                                $namaItem = $item->getNama->Nama ?? '-';
                                $labels[] = '<span class="badge badge-primary m-1">' . $namaItem . ' (Qty: ' . $qty . ')</span>';
                            }
                        }
                    }

                    return implode(' ', $labels) ?: '-';
                })
                ->rawColumns(['action', 'Item'])
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
        // dd(auth()->user()->kodeRS);
        $NamaSet = MasterNamaSet::get();
        $items = cssdMasterItem::with('getNama')->where('KodeRs', auth()->user()->kodeRS)->get();
        // dd($items);
        return view('cssd.master-item-set.create', compact('items', 'NamaSet'));
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
        if ($request->NamaSet == 'setbaru') {
            $namaset = $request->setBaru;
            MasterNamaSet::create([
                'Nama' => $request->setBaru,
                'UserCreate' => auth()->user()->name,
            ]);
            $namaset = MasterNamaSet::where('UserCreate', auth()->user()->name)->latest()->first()->id;
        } else {
            $namaset = $request->NamaSet;
        }

        cssdItemset::create([
            'KodeRs' => auth()->user()->kodeRS,
            'Nama' => $namaset,
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
        $itemIds = $data->DetailItem->ItemId;
        $detailItems = [];
        foreach ($itemIds as $key => $value) {
            $detailItems[] = cssdMasterItem::where('id', $value)->first();
        }
        $idinstrumen = $data->DetailItem->ItemId;
        $NamaInstrumen = cssdMasterItem::whereIn('id', (array) $idinstrumen)->get();
        $data->DetailItem->NamaInstrumen = $detailItems;
        // dd($data);


        $getItems = cssdMasterItem::with(['getMerk', 'getTipe', 'getNama'])
            ->whereIn('id', $itemIds)
            ->get();

        $items = cssdMasterItem::where('KodeRs', auth()->user()->kodeRS)->get();

        $NamaSet = MasterNamaSet::get();
        // dd($data);
        return view('cssd.master-item-set.edit', compact('data', 'items', 'getItems', 'NamaInstrumen', 'NamaSet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cssdItemset  $cssdItemset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->all();
        // dd($data);
        $itemsetHeader = cssdItemset::find($id);
        $itemsetHeader->update([
            'KodeRs' => auth()->user()->kodeRS,
            'Nama' => $request->NamaSet,
            'idUser' => auth()->user()->id,
        ]);

        $cssddetail = cssdItemsetDetail::where('IdItemset', $id);
        $cssddetail->update([
            'ItemId' => $request->Item,
            'Qty' => $request->Qty,
            'KodeRs' => auth()->user()->kodeRS
        ]);
        return redirect()->route('cssd-item-set.index')->with('success', 'Set Item Berhasil Ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cssdItemset  $cssdItemset
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = cssdItemset::with('DetailItem')->find($id);
        $data->DetailItem->delete();
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
