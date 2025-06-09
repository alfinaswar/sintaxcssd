<?php

namespace App\Http\Controllers;

use App\Models\cssdMasterItem;
use App\Models\cssdMasterSatuan;
use App\Models\cssdMasterType;
use App\Models\cssdMerk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CssdMasterItemController extends Controller
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
                $data = cssdMasterItem::with('getMerk', 'getTipe', 'getNamaRS', 'getSatuan')->orderBy('id', 'desc')->get();
            } else {
                $data = cssdMasterItem::with('getMerk', 'getTipe', 'getNamaRS', 'getSatuan')->where('KodeRs', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('master-cssd.cssd-master-item.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->addColumn('gambar', function ($row) {
                    if ($row->Gambar == null) {
                        return '<span class="badge bg-warning fw-bold text-dark">Tidak ada gambar</span>';
                    } else {
                        return '<img src="' . asset('storage/cssd_item/' . $row->Gambar) . '" alt="Gambar" class="img-fluid" style="max-width: 100px;">';
                    }
                })
                ->rawColumns(['action', 'gambar'])
                ->make(true);
        }

        return view('cssd.master-item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merks = cssdMerk::where('KodeRs', auth()->user()->kodeRS)->get();
        $tipe = cssdMasterType::where('KodeRs', auth()->user()->kodeRS)->get();
        $Satuan = cssdMasterSatuan::where('KodeRs', auth()->user()->kodeRS)->get();
        // dd($merks);
        return view('cssd.master-item.create', compact('merks', 'tipe', 'Satuan'));
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
            'ROID' => 'nullable|string|max:255',
            'SerialNumber' => 'required|string|max:255',
            'Nama' => 'required|string|max:255',
            'Merk' => 'required|string',
            'merk_baru' => 'required_if:Merk,MerkBaru|string|max:255',
            'Tipe' => 'required|string',
            'tipe_baru' => 'required_if:Tipe,TipeBaru|string|max:255',
            'Qty' => 'required|integer|min:1',
            'TahunPerolehan' => 'required|integer|between:2010,' . date('Y'),
            'KondisiBarang' => 'required|in:B,KB,R',
            'Gambar' => 'required|file|mimes:jpeg,png,jpg,gif|max:5000',
            'Satuan' => 'required|string',
            'satuan_baru' => 'required_if:Satuan,SatuanBaru|string|max:255',
            'Harga' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        // dd($data);
        if ($request->Tipe == 'TipeBaru') {
            $tipe = $request->tipe_baru;
            cssdMasterType::create([
                'KodeRs' => auth()->user()->kodeRS,
                'Tipe' => $request->tipe_baru,
                'idUser' => auth()->user()->id
            ]);
            $data['Tipe'] = cssdMasterType::where('idUser', auth()->user()->id)->latest()->first()->id;
        } else {
            $data['Tipe'] = $request->Tipe;
        }
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
        if ($request->Satuan == 'SatuanBaru') {
            $Merk = $request->satuan_baru;
            cssdMerk::create([
                'KodeRs' => auth()->user()->kodeRS,
                'Satuan' => $request->satuan_baru,
                'idUser' => auth()->user()->id
            ]);
            $data['Satuan'] = cssdMasterSatuan::where('idUser', auth()->user()->id)->latest()->first()->id;
        } else {
            $data['Satuan'] = $request->Satuan;
        }
        if ($request->hasFile('Gambar')) {
            $file = $request->file('Gambar');
            $namaFile = microtime(true) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/cssd_item', $namaFile);
            $data['Gambar'] = $namaFile;
        }
        $data['Kode'] = $this->generateKode();
        $data['idUser'] = auth()->user()->id;
        $data['KodeRs'] = auth()->user()->kodeRS;
        $data['Harga'] = str_replace('.', '', $request->Harga);

        cssdMasterItem::create($data);
        return redirect()->route('master-cssd.cssd-master-item.index')->with('success', 'Item Berhasil Ditambahkan');
    }

    private function generateKode()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $nomorUrut = cssdMasterItem::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count() + 1;
        $nomorUrut = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
        return 'CSSD' . $bulan . $tahun . $nomorUrut;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cssdMasterItem  $cssdMasterItem
     * @return \Illuminate\Http\Response
     */
    public function show(cssdMasterItem $cssdMasterItem)
    {
        //
    }

    public function edit($id)
    {
        $data = cssdMasterItem::find($id);
        $merks = cssdMerk::where('KodeRs', auth()->user()->kodeRS)->get();
        $tipe = cssdMasterType::where('KodeRs', auth()->user()->kodeRS)->get();
        $Satuan = cssdMasterSatuan::where('KodeRs', auth()->user()->kodeRS)->get();
        // dd($merks);
        return view('cssd.master-item.edit', compact('merks', 'tipe', 'Satuan', 'data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cssdMasterItem  $cssdMasterItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ROID' => 'nullable|string|max:255',
            'SerialNumber' => 'required|string|max:255',
            'Nama' => 'required|string|max:255',
            'Merk' => 'required|string',
            'merk_baru' => 'required_if:Merk,MerkBaru|string|max:255',
            'Tipe' => 'required|string',
            'tipe_baru' => 'required_if:Tipe,TipeBaru|string|max:255',
            'Qty' => 'required|integer|min:1',
            'TahunPerolehan' => 'required|integer|between:2010,' . date('Y'),
            'KondisiBarang' => 'required|in:B,KB,R',
            'Gambar' => 'required|file|mimes:jpeg,png,jpg,gif|max:5000',
            'Satuan' => 'required|string',
            'satuan_baru' => 'required_if:Satuan,SatuanBaru|string|max:255',
            'Harga' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();
        // dd($data);
        if ($request->Tipe == 'TipeBaru') {
            $tipe = $request->tipe_baru;
            cssdMasterType::create([
                'KodeRs' => auth()->user()->kodeRS,
                'Tipe' => $request->tipe_baru,
                'idUser' => auth()->user()->id
            ]);
            $data['Tipe'] = cssdMasterType::where('idUser', auth()->user()->id)->latest()->first()->id;
        } else {
            $data['Tipe'] = $request->Tipe;
        }
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
        if ($request->Satuan == 'SatuanBaru') {
            cssdMasterSatuan::create([
                'KodeRs' => auth()->user()->kodeRS,
                'Satuan' => $request->satuan_baru,
                'idUser' => auth()->user()->id
            ]);
            $data['Satuan'] = cssdMasterSatuan::where('idUser', auth()->user()->id)->latest()->first()->id;
        } else {
            $data['Satuan'] = $request->Satuan;
        }
        if ($request->hasFile('Gambar')) {
            $file = $request->file('Gambar');
            $namaFile = microtime(true) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/cssd_item', $namaFile);
            $data['Gambar'] = $namaFile;
        }
        $data['Kode'] = $this->generateKode();
        $data['idUser'] = auth()->user()->id;
        $data['KodeRs'] = auth()->user()->kodeRS;
        $data['Harga'] = str_replace('.', '', $request->Harga);

        cssdMasterItem::find($id)->update($data);
        return redirect()->route('master-cssd.cssd-master-item.index')->with('success', 'Item Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cssdMasterItem  $cssdMasterItem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = cssdMasterItem::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
