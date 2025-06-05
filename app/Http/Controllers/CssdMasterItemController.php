<?php

namespace App\Http\Controllers;

use App\Models\cssdMasterItem;
use App\Models\cssdMerk;
use Illuminate\Http\Request;
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
            if (auth()->user()->hasRole('superadmin_cssd')) {
                $data = cssdMasterItem::orderBy('id', 'desc')->get();
            } else {
                $data = cssdMasterItem::where('KodeRs', auth()->user()->kodeRS)->orderBy('id', 'desc')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdite = '<a href="' . route('master.css-master-item.edit', $row->id) . '"><button type="button" class="btn btn-outline-success btn-icon" ><i class="fa fa-cogs"></i></button></a>';
                    $btnlihat = '<button type="button" class="btn btn-outline-danger btn-icon" onclick="delete_data(event,' . $row->id . ')" ><i class="fa fa-times"></i></button>';
                    $btn = $btnEdite . '&nbsp;' . $btnlihat;
                    return $btn;
                })
                ->rawColumns(['action'])
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
        $merks = cssdMerk::get();
        // dd($merks);
        return view('cssd.master-item.create', compact('merks'));
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
        dd($data);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cssdMasterItem  $cssdMasterItem
     * @return \Illuminate\Http\Response
     */
    public function edit(cssdMasterItem $cssdMasterItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cssdMasterItem  $cssdMasterItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cssdMasterItem $cssdMasterItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cssdMasterItem  $cssdMasterItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(cssdMasterItem $cssdMasterItem)
    {
        //
    }
}
