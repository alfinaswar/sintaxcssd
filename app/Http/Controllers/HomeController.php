<?php

namespace App\Http\Controllers;

use App\Models\DataInventaris;
use App\Models\Maintanance;
use App\Models\MasalahModel;
use App\Models\PenghapusanAset;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user();
        $kodeRS = $user->kodeRS;

        $rsNames = DB::table('master_rs')->pluck('nama', 'kodeRS');
        $namaRs = $rsNames[$kodeRS] ?? 'RS ' . $kodeRS;

        // Semua data dashboard mengikuti RS milik user yang login.
        $assets = DataInventaris::query()->where('nama_rs', $kodeRS);

        // Ringkasan aset
        $totalInv = (clone $assets)->count();
        $invMedis = (clone $assets)->where('pengguna', 'Medis')->count();
        $invUmum = (clone $assets)->where('pengguna', 'Non Medis')->count();
        $nilaiAset = (int) (clone $assets)->sum('harga');
        $hargaTerisi = (clone $assets)->whereNotNull('harga')->count();
        $pengguna = User::where('kodeRS', $kodeRS)->count();

        // Status kalibrasi memakai catatan kalibrasi terbaru per aset
        $latestKalibrasi = DB::table('kalibrasi')
            ->select('assetID', DB::raw('MAX(id) as last_id'))
            ->whereNull('deleted_at')
            ->groupBy('assetID');

        // Ambil aset wajib kalibrasi lebih dulu. Join langsung ke data_inventaris
        // tanpa index kode_item membuat query lambat pada data besar.
        $wajibItems = (clone $assets)->where('isKalibrasi', '1')->pluck('kode_item');
        $wajibSet = array_flip($wajibItems->all());
        $wajibKalibrasi = $wajibItems->count();

        $kalibrasiRows = DB::table('kalibrasi as k')
            ->joinSub($latestKalibrasi, 'x', function ($join) {
                $join->on('x.last_id', '=', 'k.id');
            })
            ->select('k.assetID', 'k.exp_date', 'k.tgl_kalibrasi', 'k.kodeRS')
            ->where('k.kodeRS', $kodeRS)
            ->get();

        $today = Carbon::today();
        $kalibrasiTercatat = 0;
        $kalibrasiExpired = 0;
        $kalibrasi30 = 0;
        $kalibrasi90 = 0;
        $prioritasKalibrasi = collect();

        foreach ($kalibrasiRows as $row) {
            if (!isset($wajibSet[$row->assetID])) {
                continue;
            }

            $kalibrasiTercatat++;

            // Lewati tanggal kalibrasi yang tidak valid (mis. tahun 0002).
            if (!$row->exp_date || $row->exp_date < '2000-01-01' || $row->exp_date > '2100-01-01') {
                continue;
            }

            $expDate = Carbon::parse($row->exp_date)->startOfDay();
            $sisaHari = (int) $today->diffInDays($expDate, false);

            if ($sisaHari < 0) {
                $kalibrasiExpired++;
            } elseif ($sisaHari <= 30) {
                $kalibrasi30++;
            } elseif ($sisaHari <= 90) {
                $kalibrasi90++;
            }

            $prioritasKalibrasi->push((object) [
                'assetID' => $row->assetID,
                'exp_date' => $row->exp_date,
                'tgl_kalibrasi' => $row->tgl_kalibrasi,
                'sisa_hari' => $sisaHari,
            ]);
        }

        $kalibrasiBelumTercatat = max(0, $wajibKalibrasi - $kalibrasiTercatat);

        $prioritasKalibrasi = $prioritasKalibrasi->sortBy('exp_date')->take(10);

        $asetMendesak = DataInventaris::whereIn('kode_item', $prioritasKalibrasi->pluck('assetID'))
            ->where('nama_rs', $kodeRS)
            ->get()
            ->keyBy('kode_item');

        $kalibrasiMendesak = $prioritasKalibrasi->map(function ($row) use ($asetMendesak) {
            $aset = $asetMendesak->get($row->assetID);

            $row->kode_item = $row->assetID;
            $row->no_inventaris = $aset->no_inventaris ?? null;
            $row->nama = $aset->nama ?? null;
            $row->nama_rs = $aset->nama_rs ?? null;
            $row->unit = $aset->unit ?? null;

            return $row;
        });

        // Top departemen
        $perDepartemen = (clone $assets)
            ->selectRaw("COALESCE(NULLIF(TRIM(departemen), ''), 'Belum diisi') as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $departemenChart = [
            'labels' => $perDepartemen->pluck('label')->values(),
            'data' => $perDepartemen->pluck('total')->map(fn ($total) => (int) $total)->values(),
        ];

        // Klasifikasi risiko
        $perKlasifikasi = (clone $assets)
            ->where('pengguna', 'Medis')
            ->selectRaw("CASE WHEN klasifikasi IS NULL OR TRIM(klasifikasi) = '' OR klasifikasi = 'None' THEN 'Belum diisi' ELSE klasifikasi END as label, COUNT(*) as total")
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $klasifikasiColors = [
            'High Risk' => '#fd397a',
            'Medium Risk' => '#ffb822',
            'Low to Medium Risk' => '#5d78ff',
            'Low Risk' => '#1dc9b7',
            'Belum diisi' => '#c5cbe3',
        ];

        $klasifikasiChart = [
            'labels' => $perKlasifikasi->pluck('label')->values(),
            'data' => $perKlasifikasi->pluck('total')->map(fn ($total) => (int) $total)->values(),
            'colors' => $perKlasifikasi->map(function ($row) use ($klasifikasiColors) {
                return $klasifikasiColors[$row->label] ?? '#9aa0ac';
            })->values(),
        ];

        // Tren penambahan aset 12 bulan terakhir
        $startTrend = Carbon::now()->startOfMonth()->subMonths(11);
        $trendRows = (clone $assets)
            ->where('created_at', '>=', $startTrend)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $trendLabels = [];
        $trendData = [];
        for ($i = 0; $i < 12; $i++) {
            $bulan = $startTrend->copy()->addMonths($i);
            $key = $bulan->format('Y-m');
            $trendLabels[] = $namaBulan[$bulan->month - 1] . ' ' . $bulan->format('y');
            $trendData[] = (int) ($trendRows[$key] ?? 0);
        }

        $trendChart = [
            'labels' => $trendLabels,
            'data' => $trendData,
        ];

        // Operasional
        $maintenance12Bulan = Maintanance::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->where('nama_rs', $kodeRS)
            ->count();

        $masalah = MasalahModel::where('nama_rs', $kodeRS)->count();

        $penghapusan = PenghapusanAset::where('KodeRS', $kodeRS)
            ->select('Status', DB::raw('COUNT(*) as total'))
            ->groupBy('Status')
            ->pluck('total', 'Status');

        // Cakupan catatan kalibrasi
        $kelengkapan = [
            'kalibrasi' => $wajibKalibrasi > 0 ? round($kalibrasiTercatat / $wajibKalibrasi * 100) : 0,
        ];

        return view('home', compact(
            'namaRs',
            'totalInv',
            'invMedis',
            'invUmum',
            'nilaiAset',
            'hargaTerisi',
            'pengguna',
            'wajibKalibrasi',
            'kalibrasiTercatat',
            'kalibrasiExpired',
            'kalibrasi30',
            'kalibrasi90',
            'kalibrasiBelumTercatat',
            'kalibrasiMendesak',
            'rsNames',
            'departemenChart',
            'klasifikasiChart',
            'trendChart',
            'maintenance12Bulan',
            'masalah',
            'penghapusan',
            'kelengkapan'
        ));
    }
}
