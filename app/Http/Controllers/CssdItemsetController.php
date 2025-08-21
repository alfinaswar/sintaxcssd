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
    protected $CekStok;

    public function __construct(CekStokController $CekStok)
    {
        $this->CekStok = $CekStok;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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
                    $details = cssdItemsetDetail::where('IdItemset', $row->id)->get();
                    $label = '-';

                    if ($details && $details->count() > 0) {
                        $labels = [];
                        foreach ($details as $detail) {
                            $item = cssdMasterItem::with('getNama')->find($detail->ItemId);
                            $qty = $detail->Qty ?? 0;

                            if ($item) {
                                $namaItem = $item->getNama->Nama ?? '-';
                                $sn = $item->SerialNumber ?? '-';
                                $labels[] = '<span class="badge badge-primary m-1">' . $namaItem . ' (SN: ' . $sn . ', Qty: ' . $qty . ')</span>';
                            }
                        }
                        $label = implode(' ', $labels);
                    }

                    return $label;
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
        // $cekStokController = app(CekStokController::class);
        // $Stok = $cekStokController->CekStok();
        // dd($Stok);
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

        // $data = $request->all();
        // $groupedItems = [];
        // if (isset($data['Item']) && is_array($data['Item'])) {
        //     foreach ($data['Item'] as $item) {
        //         // Cek apakah $item adalah array dan punya key 'name'
        //         if (is_array($item) && isset($item['name'])) {
        //             $name = $item['name'];
        //         } else {
        //             // fallback ke value langsung jika tidak ada key 'name'
        //             $name = $item;
        //         }
        //         if (!isset($groupedItems[$name])) {
        //             $groupedItems[$name] = 1;
        //         } else {
        //             $groupedItems[$name]++;
        //         }
        //     }
        // }

        // $result = [];
        // foreach ($groupedItems as $itemId => $total) {
        //     $result[] = [
        //         'ItemId' => $itemId,
        //         'Total' => $total
        //     ];
        // }

        // $cekStokController = app(CekStokController::class);
        // $Stok = $cekStokController->CekStok();



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
            'Kode' => $this->GenerateKode(),
            'Nama' => $namaset,
            'idUser' => auth()->user()->id,
        ]);
        $getlatestid = cssdItemset::where('idUser', auth()->user()->id)->latest()->first()->id;

        foreach ($request->Item as $key => $value) {
            cssdItemsetDetail::create([
                'IdItemset' => $getlatestid,
                'ItemId' => $value,
                'Qty' => $request->Qty[$key],
                'KodeRs' => auth()->user()->kodeRS
            ]);
        }


        return redirect()->route('cssd-item-set.index')->with('success', 'Set Item Berhasil Ditambahkan');
    }
    private function GenerateKode()
    {
        $prefix = 'SET';
        $tanggal = date('dmy');
        $lastItemset = cssdItemset::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastItemset && isset($lastItemset->Kode)) {
            $lastKode = $lastItemset->Kode;
            $lastNumber = (int) substr($lastKode, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $kodeBaru = $prefix . $tanggal . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        return $kodeBaru;
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
        $data = cssdItemset::with('DetailItem', 'DetailItem.MasterItem')->where('KodeRs', auth()->user()->kodeRS)->where('id', $id)->first();
        // dd($data);
        $items = cssdMasterItem::where('KodeRs', auth()->user()->kodeRS)->get();

        $NamaSet = MasterNamaSet::get();
        // dd($data);
        return view('cssd.master-item-set.edit', compact('data', 'items', 'NamaSet'));
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
        // Cek apakah user memilih set baru atau set yang sudah ada
        if ($request->NamaSet == 'setbaru') {
            $namaset = $request->setBaru;
            $newSet = MasterNamaSet::create([
                'Nama' => $request->setBaru,
                'UserCreate' => auth()->user()->name,
            ]);
            $namaset = $newSet->id;
        } else {
            $namaset = $request->NamaSet;
        }

        // Ambil data itemset yang akan diupdate
        $itemset = cssdItemset::findOrFail($id);

        // Update data utama itemset
        $itemset->update([
            'KodeRs' => auth()->user()->kodeRS,
            // 'Kode' => $this->GenerateKode(), // Biasanya kode tidak diupdate, jika perlu silakan aktifkan
            'Nama' => $namaset,
            'idUser' => auth()->user()->id,
        ]);

        // Hapus detail lama
        cssdItemsetDetail::where('IdItemset', $itemset->id)->delete();

        if ($request->Item && is_array($request->Item)) {
            foreach ($request->Item as $key => $value) {
                cssdItemsetDetail::create([
                    'IdItemset' => $itemset->id,
                    'ItemId' => $value,
                    'Qty' => $request->Qty[$key],
                    'KodeRs' => auth()->user()->kodeRS
                ]);
            }
        }

        return redirect()->route('cssd-item-set.index')->with('success', 'Set Item Berhasil Diupdate');
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
