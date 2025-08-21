<?php

namespace App\Http\Controllers;

use App\Models\MasterItemGroup;
use Illuminate\Http\Request;

class CekStokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CekStok()
    {
        $kodeRs = auth()->user()->kodeRs;
        $data = MasterItemGroup::with('getMerk')
            ->withCount([
                'getListItem as Stok' => function ($query) use ($kodeRs) {
                    $query->where('KodeRs', $kodeRs);
                },
                'getInUse as jumlah_in_use' => function ($query) use ($kodeRs) {
                    $query->where('KodeRs', $kodeRs);
                }
            ])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {
                $item->Idle = ($item->Stok ?? 0) - ($item->jumlah_in_use ?? 0);
                return $item;
            });
        return $data;
    }
    public function index()
    {

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
