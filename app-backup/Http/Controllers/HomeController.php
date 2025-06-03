<?php

namespace App\Http\Controllers;
use App\Models\DataInventaris;
use App\Models\User;
use Illuminate\Http\Request;
use Rawilk\Printing\Facades\Printing;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $inv_umum = DataInventaris::where('pengguna','umum')->count();
        $pengguna = User::count();
        $inv_medis = DataInventaris::where('pengguna', 'Medis')->count();
        $total_inv = DataInventaris::count();
        // dd($inv_umum);
        return view('home', compact('inv_umum', 'pengguna', 'inv_medis', 'total_inv'));
    }
    public function countdata()
    {
    }
}
