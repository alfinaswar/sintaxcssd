<?php

namespace App\Http\Controllers;

use App\Models\FormulirPembersihan;
use Illuminate\Http\Request;

class FormulirPembersihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = $request->all();
        $data['createdBy'] = auth()->user()->id ?? 1;

        if ($request->hasFile('Before')) {
            $file = $request->file('Before');
            $filename = $file->getClientOriginalName() . '-' . microtime(true) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/gambar/Pembersihan/Before'), $filename);
            $data['Before'] = $filename;
        }

        if ($request->hasFile('After')) {
            $file = $request->file('After');
            $filename = $file->getClientOriginalName() . '-' . microtime(true) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/gambar/Pembersihan/After'), $filename);
            $data['After'] = $filename;
        }

        FormulirPembersihan::create($data);
        return redirect()->back()->with('success', 'Data Telah Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function show(FormulirPembersihan $formulirPembersihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function edit(FormulirPembersihan $formulirPembersihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormulirPembersihan $formulirPembersihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormulirPembersihan  $formulirPembersihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormulirPembersihan $formulirPembersihan)
    {
        //
    }
}
