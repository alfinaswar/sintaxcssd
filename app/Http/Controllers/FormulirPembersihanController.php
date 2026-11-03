<?php

namespace App\Http\Controllers;

use App\Exports\ItemRuanganExport;
use App\Exports\LaporanPembersihanAlat;
use App\Helpers\R2Client;
use App\Models\DataInventaris;
use App\Models\FormulirPembersihan;
use App\Models\MasterMerk;
use App\Models\MasterRs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
class FormulirPembersihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->check() && auth()->user()->role == 'admin') {
            $data = MasterRs::get();
        } else {
            $data = MasterRs::where('kodeRS', auth()->user()->kodeRS)->get();
        }
        $merk = MasterMerk::where('nama_rs', auth()->user()->kodeRS)->get();
        $alat = DataInventaris::select('nama')->distinct()->get();
        return view('laporan.monitoring.index', compact('data', 'merk', 'alat'));
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            $query = FormulirPembersihan::select([
                'formulir_pembersihans.id',
                'formulir_pembersihans.kode_item',
                'formulir_pembersihans.idUser',
                'formulir_pembersihans.createdBy',
                'formulir_pembersihans.Tanggal',
                'formulir_pembersihans.Status',
                'formulir_pembersihans.Keterangan',
                'formulir_pembersihans.created_at',
                'data_inventaris.real_name as inventaris_real_name',
                'data_inventaris.no_inventaris as inventaris_no_inventaris',
                'users.name as creator_name'
            ])
                ->join('data_inventaris', 'formulir_pembersihans.kode_item', '=', 'data_inventaris.kode_item')
                ->leftJoin('users', 'formulir_pembersihans.createdBy', '=', 'users.id');

            if ($user && $user->role !== "admin") {
                $query->where('data_inventaris.nama_rs', $user->kodeRS);
            } elseif ($request->filled('filter_rs')) {
                $query->where('data_inventaris.nama_rs', $request->filter_rs);
            }

            if ($request->filled('filter_tanggal')) {
                $query->whereDate('formulir_pembersihans.Tanggal', $request->filter_tanggal);
            }

            if ($request->filled('filter_status')) {
                $query->where('formulir_pembersihans.Status', $request->filter_status);
            }

            $query->latest('formulir_pembersihans.created_at');

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->search['value'])) {
                        $keyword = $request->search['value'];
                        $query->where(function ($q) use ($keyword) {
                            $q->where('formulir_pembersihans.kode_item', 'like', "%{$keyword}%")
                                ->orWhere('data_inventaris.real_name', 'like', "%{$keyword}%")
                                ->orWhere('data_inventaris.no_inventaris', 'like', "%{$keyword}%")
                                ->orWhere('formulir_pembersihans.idUser', 'like', "%{$keyword}%")
                                ->orWhere('formulir_pembersihans.Status', 'like', "%{$keyword}%")
                                ->orWhere('formulir_pembersihans.Keterangan', 'like', "%{$keyword}%");
                        });
                    }
                }, true)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="javascript:void(0)" onclick="openEditModal(' . $row->id . ')" class="btn btn-outline-warning btn-icon btn-sm" data-toggle="kt-tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></a> ';
                    $btnDelete = '<form action="' . route('pa.maintenance.destroy', $row->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" data-skin="brand" data-toggle="kt-tooltip" data-placement="top" title="Hapus Data" class="btn btn-outline-danger btn-icon btn-sm"><i class="fa fa-trash"></i></button>'
                        . '</form>';
                    return $btnEdit . $btnDelete;
                })
                ->addColumn('kode_item', function ($row) {
                    $nama = $row->inventaris_real_name ?? '-';
                    $no_inventaris = $row->inventaris_no_inventaris ?? '-';
                    return '<span class="font-weight-bold">' . e($nama) . '</span><br>'
                        . '<small class="text-dark">' . e($no_inventaris) . '</small> &bull; '
                        . '<small class="text-muted">' . e($row->kode_item) . '</small>';
                })
                ->addColumn('nama_user', function ($row) {
                    return $row->idUser ?: ($row->creator_name ?: '-');
                })
                ->addColumn('tanggal', function ($row) {
                    return $row->Tanggal ? date('d-m-Y', strtotime($row->Tanggal)) : '-';
                })
                ->addColumn('status_text', function ($row) {
                    $status = $row->Status;
                    if ($status === 'Bersih' || $status == '1') {
                        return '<span class="badge badge-success px-2 py-1">Sudah Dibersihkan</span>';
                    } elseif ($status === 'Tidak' || $status == '2') {
                        return '<span class="badge badge-danger px-2 py-1">Belum Dibersihkan</span>';
                    } else {
                        return '<span class="badge badge-secondary px-2 py-1">' . e($status ?: '-') . '</span>';
                    }
                })
                ->addColumn('keterangan', function ($row) {
                    return $row->Keterangan ?: '-';
                })
                ->rawColumns(['action', 'kode_item', 'status_text'])
                ->make(true);
        }

        $rs = MasterRs::all();
        return view('pembersihan-alat.index', compact('rs'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Print(Request $request)
    {
        // dd($request->all());
        $unit = $request->unit;
        $namaunit = str_replace('/', ' atau ', $request->unit);
        $jenis = $request->jenis;
        $alat = $request->nama;
        $rs = $request->rs;

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        if ($jenis == 'Medis') {
            $header = 'Medis';
        } else {
            $header = 'Umum';
        }
        if ($request->format == 'excel') {
            $nama_file = 'laporan Monitoring Alat ' . $namaunit . '.xlsx';
            return Excel::download(new LaporanPembersihanAlat($unit, $jenis, $alat, $rs, $bulan, $tahun), $nama_file);


        } else if ($request->format == 'pdf') {


            $query = DataInventaris::with([
                'getLaporanMonitoring' => function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun);
                }
            ])
                ->when($unit, function ($query) use ($unit) {
                    return $query->where('unit', $unit);
                })
                ->when($alat, function ($query) use ($alat) {
                    return $query->where('kode_item', $alat);
                })
                ->when($jenis, function ($query) use ($jenis) {
                    return $query->where('pengguna', $jenis);
                })
                ->when($rs, function ($query) use ($rs) {
                    return $query->where('nama_rs', $rs);
                })
                ->orderBy('unit', 'asc')
                ->get();


            // dd($query);
            $pdf = Pdf::loadView('laporan.monitoring.cetak_pdf', compact('query', 'unit', 'header'));
            return $pdf->stream('Laporan Monitoring Alat ' . $unit . '.pdf');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idUser' => 'required|string',
            'Tanggal' => 'required|date',
            'Status' => 'required|string',
            'Keterangan' => 'nullable|string',
            'idAlat' => 'required|string',
            'kode_item' => 'required|string',
            'Before' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:15240',
            'After' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:15240',
        ]);

        $data = $validated;
        $data['createdBy'] = auth()->id() ?? 1;

        if ($request->hasFile('Before')) {
            // Sekarang $data['Before'] hanya berisi nama file saja
            $data['Before'] = $this->compressImage($request->file('Before'), 'Before');
        }

        if ($request->hasFile('After')) {
            // Sekarang $data['After'] hanya berisi nama file saja
            $data['After'] = $this->compressImage($request->file('After'), 'After');
        }

        FormulirPembersihan::create($data);

        return redirect()->back()->with('success', 'Data Telah Disimpan ke R2');
    }

    // Fungsi ini meng-upload file ke folder 'gambar/Pembersihan/{folderName}' di R2.
    private function compressImage($file, $folderName, $maxBytes = 900000)
    {
        $image = Image::make($file->getRealPath());
        $image->orientate();
        $image->resize(1280, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $quality = 50;
        $image->encode('jpg', $quality);

        while (strlen($image->getEncoded()) > $maxBytes && $quality > 20) {
            $quality -= 5;
            $image->encode('jpg', $quality);
        }

        $filename = time() . '_' . Str::random(10) . '.jpg';

        // Upload ke folder R2: 'gambar/Pembersihan/{folderName}/{filename}'
        $r2Path = "gambar/Pembersihan/{$folderName}/{$filename}";
        $tempFilePath = sys_get_temp_dir() . '/' . $filename;
        $image->save($tempFilePath);

        try {
            $r2Client = new R2Client();
            $r2Client->upload($tempFilePath, $r2Path, 'image/jpeg');
            File::delete($tempFilePath);
        } catch (\Exception $e) {
            File::delete($tempFilePath);
            throw new \Exception('Gagal upload ke R2: ' . $e->getMessage());
        }

        // Yang disimpan di database HANYA nama file-nya saja (tanpa path folder R2-nya)
        return $filename;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pembersihan = FormulirPembersihan::with('getuser')->find($id);

        if (!$pembersihan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $r2 = new R2Client();
        $tanggalFormatted = $pembersihan->Tanggal ? date('Y-m-d', strtotime($pembersihan->Tanggal)) : '';

        // Sesuaikan status value: jika 'Bersih' -> '1', jika 'Tidak' -> '2'
        $statusVal = $pembersihan->Status;
        if ($statusVal === 'Bersih') {
            $statusVal = '1';
        } elseif ($statusVal === 'Tidak') {
            $statusVal = '2';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pembersihan->id,
                'kode_item' => $pembersihan->kode_item,
                'user' => $pembersihan->idUser ?: ($pembersihan->getuser ? $pembersihan->getuser->name : '-'),
                'tanggal' => $tanggalFormatted,
                'status' => $statusVal,
                'before' => $pembersihan->Before,
                'after' => $pembersihan->After,
                'before_url' => $pembersihan->Before ? $r2->getUrl('gambar/Pembersihan/Before/' . $pembersihan->Before) : null,
                'after_url' => $pembersihan->After ? $r2->getUrl('gambar/Pembersihan/After/' . $pembersihan->After) : null,
                'keterangan' => $pembersihan->Keterangan,
            ]
        ]);
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
        $pembersihan = FormulirPembersihan::find($id);

        if (!$pembersihan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required',
            'keterangan' => 'nullable|string'
        ]);

        $statusMapped = $request->status;
        if ($request->status == '1') {
            $statusMapped = 'Bersih';
        } elseif ($request->status == '2') {
            $statusMapped = 'Tidak';
        }

        $pembersihan->update([
            'Tanggal' => $request->tanggal,
            'Status' => $statusMapped,
            'Keterangan' => $request->keterangan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pembersihan alat berhasil diperbarui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pembersihan = FormulirPembersihan::find($id);

        if ($pembersihan) {
            if ($pembersihan->Before || $pembersihan->After) {
                try {
                    $r2 = new R2Client();
                    if ($pembersihan->Before) {
                        $r2->delete('gambar/Pembersihan/Before/' . $pembersihan->Before);
                    }
                    if ($pembersihan->After) {
                        $r2->delete('gambar/Pembersihan/After/' . $pembersihan->After);
                    }
                } catch (\Exception $e) {
                    // ignore to prevent blocking db deletion
                }
            }

            $pembersihan->delete();

            return redirect()->back()->with('success', 'Data pembersihan alat berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }

    /**
     * Export laporan pembersihan alat ke Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $rs = ($user && $user->role !== 'admin') ? $user->kodeRS : $request->filter_rs;
        $tahun = $request->tahun ?: date('Y');
        $bulan_awal = $request->bulan_awal;
        $bulan_akhir = $request->bulan_akhir;

        $nama_file = 'Laporan-Pembersihan-Alat-' .
            ($bulan_awal ? $bulan_awal . '-' : '') .
            ($bulan_akhir ? $bulan_akhir . '-' : '') .
            $tahun . '.xlsx';

        return Excel::download(
            new LaporanPembersihanAlat(null, null, null, $rs, $bulan_awal, $tahun),
            $nama_file
        );
    }
}
