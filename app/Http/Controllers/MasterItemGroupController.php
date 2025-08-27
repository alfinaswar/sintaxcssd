<?php

namespace App\Http\Controllers;

use App\Models\cssdMerk;
use App\Models\MasterItemGroup;
use App\Models\MasterMerk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MasterItemGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MasterItemGroup::with(['getMerk', 'getListItem.getItemDalamSet'])
                ->withCount('getListItem')
                ->orderBy('Nama', 'Asc');
            if ($request->merk) {
                $query->whereHas('getMerk', function ($q) use ($request) {
                    $q->where('id', $request->merk);
                });
            }
            $data = $query->get()
                ->map(function ($item) {
                    $total_item = 0;
                    $total_in_use = 0;

                    if ($item->getListItem) {
                        foreach ($item->getListItem as $listItem) {
                            $total_item++;

                            $in_use = 0;
                            if ($listItem->getItemDalamSet && $listItem->getItemDalamSet->count() > 0) {
                                $in_use = $listItem->getItemDalamSet->count();
                            }
                            $total_in_use += $in_use;
                        }
                    }

                    $item->Stok = $total_item;
                    $item->jumlah_in_use = $total_in_use;
                    $item->Idle = $total_item - $total_in_use;
                    return $item;
                });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('master-cssd.item-group.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    return $btnEdite . '&nbsp;' . $btnlihat;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // untuk dropdown filter merk
        $merk = cssdMerk::orderBy('Merk', 'ASC')->get();
        return view('cssd.master-item-group.index', compact('merk'));
    }

    public function Stok(Request $request)
    {
        if ($request->ajax()) {
            $data = MasterItemGroup::with('getListItem')->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jumlah_item', function ($row) {
                    // Hitung jumlah item dari relasi getListItem
                    return $row->getListItem ? $row->getListItem->count() : 0;
                })
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('master-cssd.item-group.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action', 'gambar'])
                ->make(true);
        }

        return view('cssd.master-item-group.stok');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Merk = cssdMerk::orderBy('Merk', 'ASC')->get();
        return view('cssd.master-item-group.create', compact('Merk'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nama' => 'required|string|max:255',
            'Merk' => 'required|string',
            'Foto' => 'required|file|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();
        if ($request->Merk == 'MerkBaru') {
            $Merk = $request->merk_baru;
            cssdMerk::create([
                'KodeRs' => auth()->user()->kodeRS,
                'Merk' => $request->merk_baru,
                'idUser' => auth()->user()->id
            ]);
            $data['Merk'] = cssdMerk::where('idUser', auth()->user()->id)->latest()->first()->id;
        } else {
            $data['Merk'] = $request->Merk;
        }
        if ($request->hasFile('Foto')) {
            $file = $request->file('Foto');
            $namaFile = rand() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/cssd_item_group', $namaFile);
            $data['Foto'] = $namaFile;
        }
        // dd($data);
        MasterItemGroup::create($data);
        return redirect()->route('master-cssd.item-group.index')->with('success', 'Item Berhasil Ditambahkan');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterItemGroup  $masterItemGroup
     * @return \Illuminate\Http\Response
     */
    public function show(MasterItemGroup $masterItemGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterItemGroup  $masterItemGroup
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Merk = cssdMerk::orderBy('Merk', 'ASC')->get();
        $itemGroup = MasterItemGroup::find($id);
        return view('cssd.master-item-group.edit', compact('itemGroup', 'Merk'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterItemGroup  $masterItemGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'Nama' => 'required|string|max:255',
            'Merk' => 'required|string',
            'Kode' => 'required|string|max:255',
            'Foto' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $itemGroup = MasterItemGroup::findOrFail($id);

        $data = $request->all();

        if ($request->Merk == 'MerkBaru') {
            $Merk = $request->merk_baru;
            $merkBaru = cssdMerk::create([
                'KodeRs' => auth()->user()->kodeRS,
                'Merk' => $request->merk_baru,
                'idUser' => auth()->user()->id
            ]);
            $data['Merk'] = $merkBaru->id;
        } else {
            $data['Merk'] = $request->Merk;
        }

        if ($request->hasFile('Foto')) {
            $file = $request->file('Foto');
            $namaFile = rand() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/cssd_item_group', $namaFile);
            $data['Foto'] = $namaFile;
        } else {
            // Jika tidak upload foto baru, jangan update kolom Foto
            unset($data['Foto']);
        }

        $itemGroup->update($data);

        return redirect()->route('master-cssd.item-group.index')->with('success', 'Item Berhasil Diupdate');
    }

    /**   
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterItemGroup  $masterItemGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = MasterItemGroup::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
