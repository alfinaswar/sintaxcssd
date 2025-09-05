<?php

namespace App\Http\Controllers;

use App\Models\cssdMasterItem;
use App\Models\cssdMasterSatuan;
use App\Models\cssdMasterSupplier;
use App\Models\cssdMasterType;
use App\Models\cssdMerk;
use App\Models\MasterItemGroup;
use App\Models\MasterRs;
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
        if ($request->ajax()) {
            if (auth()->user()->hasRole('superadmin_cssd') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('Admin')) {
                $data = cssdMasterItem::with('getNama', 'getMerk', 'getTipe', 'getNamaRS', 'getSatuan')
                    ->where('Aktif', 'Y')
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $data = cssdMasterItem::with('getNama', 'getMerk', 'getTipe', 'getNamaRS', 'getSatuan')
                    ->where('KodeRs', auth()->user()->kodeRS)
                    ->where('Aktif', 'Y')
                    ->orderBy('id', 'desc')
                    ->get();
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
        $Alat = MasterItemGroup::orderBy('Nama', 'ASC')->get();
        $Rs = MasterRs::get();
        return view('cssd.master-item.index', compact('Alat', 'Rs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $masteritem = MasterItemGroup::with('getMerk')->latest()->get();
        $merks = cssdMerk::get();
        // dd($merks);
        $tipe = cssdMasterType::where('KodeRs', auth()->user()->kodeRS)->get();
        $Satuan = cssdMasterSatuan::where('KodeRs', auth()->user()->kodeRS)->get();
        $Supplier = cssdMasterSupplier::get();
        return view('cssd.master-item.create', compact('merks', 'tipe', 'Satuan', 'masteritem', 'Supplier'));
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
            'Tipe' => 'required|string',
            'Qty' => 'required|integer|min:1',
            'TahunPerolehan' => 'required|integer|between:2010,' . date('Y'),
            'KondisiBarang' => 'required|in:B,KB,R',
            'Gambar' => 'required|file|mimes:jpeg,png,jpg,gif',
            'Satuan' => 'required|string',
            'Supplier' => 'required|string',

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }


        $data = $request->all();
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
            $image = \Intervention\Image\Facades\Image::make($file);
            $image->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save(storage_path('app/public/cssd_item/' . $namaFile), 65);
        }
        if (isset($data['Qty']) && $data['Qty'] > 1) {
            for ($i = 0; $i < $data['Qty']; $i++) {
                $data['idUser'] = auth()->user()->id;
                $data['KodeRs'] = auth()->user()->kodeRS;
                $data['Kode'] = $this->generateKode($i); // <<-- beda tiap loop
                $data['Harga'] = str_replace('.', '', $request->Harga);
                $data['Gambar'] = $namaFile ?? '';
                cssdMasterItem::create($data);
            }
        } else {
            // Jika Qty tidak ada atau kurang dari 1, tetap simpan satu data
            $data['idUser'] = auth()->user()->id;
            $data['KodeRs'] = auth()->user()->kodeRS;
            $data['Kode'] = $this->generateKode(0);
            $data['Harga'] = str_replace('.', '', $request->Harga);
            $data['Gambar'] = $namaFile ?? '';
            cssdMasterItem::create($data);
        }
        return redirect()->route('master-cssd.cssd-master-item.index')->with('success', 'Item Berhasil Ditambahkan');
    }

    private function generateKode($increment = 0)
    {
        $kodeRsab = MasterRs::where('kodeRS', auth()->user()->kodeRS)->first()->keterangan;
        $tahunFull = date('Y');
        $tahun = substr($tahunFull, 2, 2);
        $bulan = date('m');

        $lastItem = cssdMasterItem::whereYear('created_at', $tahunFull)
            ->whereMonth('created_at', $bulan)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastItem && preg_match('/(\d{4})$/', $lastItem->Kode, $matches)) {
            $lastNumber = (int) $matches[1];
        } else {
            $lastNumber = 0;
        }
        $nomorUrut = $lastNumber + 1 + $increment;
        $nomorUrut = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        return 'RSAB' . $kodeRsab . $bulan . $tahun . $nomorUrut;
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
        $masteritem = MasterItemGroup::with('getMerk')->latest()->get();
        $data = cssdMasterItem::find($id);
        $merks = cssdMerk::get();
        $tipe = cssdMasterType::where('KodeRs', auth()->user()->kodeRS)->get();
        $Satuan = cssdMasterSatuan::where('KodeRs', auth()->user()->kodeRS)->get();
        $Supplier = cssdMasterSupplier::get();
        // dd($masteritem);
        return view('cssd.master-item.edit', compact('merks', 'tipe', 'Satuan', 'data', 'masteritem', 'Supplier'));

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
            'Nama' => 'required|string|max:255',
            'Merk' => 'required|string',
            'Tipe' => 'required|string',
            'Qty' => 'required|integer|min:1',
            'TahunPerolehan' => 'required|integer|between:2010,' . date('Y'),
            'KondisiBarang' => 'required|in:B,KB,R',
            'Gambar' => 'nullable|file|mimes:jpeg,png,jpg,gif',
            'Satuan' => 'required|string',
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
            $image = \Intervention\Image\Facades\Image::make($file);
            $image->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save(storage_path('app/public/cssd_item/' . $namaFile), 65);
        }
        $data['Kode'] = $this->generateKode();
        $data['UserUpdate'] = auth()->user()->name;
        $data['KodeRs'] = auth()->user()->kodeRS;
        $data['Gambar'] = $namaFile ?? '';

        cssdMasterItem::find($id)->update($data);
        return redirect()->route('master-cssd.cssd-master-item.index')->with('success', 'Item Berhasil Diubah');
    }

    public function destroy($id)
    {
        $data = cssdMasterItem::find($id);
        $data->delete();
        return response()->json(['msg' => 'Data berhasil di hapus'], 200);
    }
}
