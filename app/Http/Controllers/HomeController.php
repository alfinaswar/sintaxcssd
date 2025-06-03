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
        if (auth()->user()->role == 'admin') {
            $total_inv = $this->countTotalInv();
            $pengguna = $this->countPengguna();
            $inv_umum = $this->countInvUmum();
            $inv_medis = $this->countInvMedis();
        } else {
            $inv_umum = DataInventaris::where('pengguna', 'Non Medis')->where('nama_rs', auth()->user()->kodeRS)->count();
            $pengguna = User::where('kodeRS', auth()->user()->kodeRS)->count();
            $inv_medis = DataInventaris::where('pengguna', 'Medis')->where('nama_rs', auth()->user()->kodeRS)->count();
            $total_inv = DataInventaris::where('nama_rs', auth()->user()->kodeRS)->count();
        }
        // masing masing rs

        // total semua rs

        return view('home', compact('inv_umum', 'pengguna', 'inv_medis', 'total_inv'));
    }
    private function countPengguna()
    {
        $penguna = User::count();
        return $penguna;
    }
    private function countInvUmum()
    {
        $inv_umum = DataInventaris::where('pengguna', 'Non Medis')->count();
        return $inv_umum;
    }
    private function countInvMedis()
    {
        $inv_medis = DataInventaris::where('pengguna', 'Medis')->count();
        return $inv_medis;
    }
    private function countTotalInv()
    {
        $total_inv = DataInventaris::count();
        return $total_inv;
    }
    private function ChartJenisPengguna()
    {

    }
}
