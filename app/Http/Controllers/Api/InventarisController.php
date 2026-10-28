<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataInventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * Kolom yang boleh dibaca lewat API. Kolom audit internal
     * (UserCreate, UserId, UpdateById, deleted_at) sengaja tidak diekspos.
     */
    private const COLUMNS = [
        'id',
        'kode_item',
        'assetID',
        'nama',
        'real_name',
        'merk',
        'no_inventaris',
        'no_sn',
        'harga',
        'tanggal_beli',
        'tgl_kalibrasi',
        'tgl_expire',
        'isKalibrasi',
        'klasifikasi',
        'departemen',
        'unit',
        'pengguna',
        'keterangan',
        'gambar',
        'dokumen',
        'manualbook',
        'ROID',
        'RO2ID',
        'nama_rs',
        'created_at',
        'updated_at',
    ];

    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
            'departemen' => 'nullable|integer|min:1',
            'unit' => 'nullable|string|max:100',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $rs = $request->attributes->get('rs');

        $query = DataInventaris::query()
            ->select(self::COLUMNS)
            ->where('nama_rs', $rs->kodeRS);

        if (!empty($validated['q'])) {
            // Escape wildcard agar input tidak bisa melebarkan hasil pencarian.
            $term = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $validated['q']) . '%';

            $query->where(function ($w) use ($term) {
                $w->where('nama', 'like', $term)
                    ->orWhere('real_name', 'like', $term)
                    ->orWhere('kode_item', 'like', $term)
                    ->orWhere('no_inventaris', 'like', $term)
                    ->orWhere('no_sn', 'like', $term);
            });
        }

        if (!empty($validated['departemen'])) {
            $query->where('departemen', $validated['departemen']);
        }

        if (!empty($validated['unit'])) {
            $query->where('unit', $validated['unit']);
        }

        $result = $query->orderBy('id')
            ->paginate($validated['per_page'] ?? 25);

        return response()->json($result)
            ->header('Cache-Control', 'no-store, private')
            ->header('X-Content-Type-Options', 'nosniff');
    }

    public function show(Request $request, $kodeItem)
    {
        $rs = $request->attributes->get('rs');

        $item = DataInventaris::query()
            ->select(self::COLUMNS)
            ->where('nama_rs', $rs->kodeRS)
            ->where('kode_item', $kodeItem)
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['data' => $item])
            ->header('Cache-Control', 'no-store, private')
            ->header('X-Content-Type-Options', 'nosniff');
    }
}
