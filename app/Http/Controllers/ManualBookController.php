<?php

namespace App\Http\Controllers;

use App\Models\ManualBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ManualBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ManualBook::where('kodeRS', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '';
                    $btnlihat = '';
                    $btn = '';
                    if (auth()->user()->role == 'Admin') {
                        $btnEdite = '<a href="' . route('manualbook.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                        $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                        $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    }
                    return $btn;
                })
                ->addColumn('Dokumen', function ($row) {
                    if ($row->Dokumen) {
                        $url = asset('storage/manual-book/' . $row->Dokumen);
                        return '<a href="' . $url . '" target="_blank" class="btn btn-outline-primary">Lihat Manual Book</a>';
                    } else {
                        return '-';
                    }
                })

                ->rawColumns(['action', 'Dokumen'])
                ->make(true);
        }

        return view('manual-book.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manual-book.create');
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
        $data['KodeRS'] = auth()->user()->kodeRS;
        $data['UserCreate'] = auth()->user()->name;
        if ($request->hasFile('Dokumen')) {
            $Dokumen = $request->file('Dokumen');
            $Dokumen->storeAs('public/manual-book', $Dokumen->hashName());
            $Dokumen = $Dokumen->hashName();
            $data['Dokumen'] = $Dokumen;
        }
        $query = ManualBook::create($data);
        return redirect()->route('manualbook.index')->with('success', 'Data berhasil ditambahkan');
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
        $data = ManualBook::find($id);
        return view('manual-book.update', compact('data'));
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
        $data = $request->all();
        $data['KodeRS'] = auth()->user()->kodeRS;
        $data['UserUpdate'] = auth()->user()->name;


        $manualBook = ManualBook::findOrFail($id);

        if ($request->hasFile('Dokumen')) {
            $Dokumen = $request->file('Dokumen');
            $Dokumen->storeAs('public/manual-book', $Dokumen->hashName());
            $Dokumen = $Dokumen->hashName();
            $data['Dokumen'] = $Dokumen;
        } else {
            unset($data['Dokumen']);
        }

        $manualBook->update($data);

        return redirect()->route('manualbook.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ManualBook::findOrFail($id);

        if ($data->Dokumen && Storage::exists('public/manual-book/' . $data->Dokumen)) {
            Storage::delete('public/manual-book/' . $data->Dokumen);
        }

        $data->delete();

        return response()->json(['msg' => 'Data berhasil dihapus'], 200);
    }
}
