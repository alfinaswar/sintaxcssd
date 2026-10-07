<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use Illuminate\Http\Request;

class ApiSinta extends Controller
{
    public function DataInventaris(Request $request)
    {
        $rs = $request->attributes->get('rs');
        // dd($rs);
        return response()->json(
            DataInventaris::where('nama_rs', $rs->kodeRS)->get()
        );
        // return $rs;
    }
}
