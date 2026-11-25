<?php

namespace App\Imports;

use App\Models\InventarisKso;
use App\Models\MasterAlatKso;
use App\Models\MasterMerkKso;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class InventarisKsoImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;
    protected ?string $defaultKodeRs;

    public function __construct(?string $defaultKodeRs = null)
    {
        $this->defaultKodeRs = $defaultKodeRs;
    }

    public function collection(Collection $rows)
    {
        $alatCache = MasterAlatKso::all()
            ->mapWithKeys(fn ($a) => [strtoupper(trim($a->nama)) => $a->id])
            ->toArray();

        $merkCache = MasterMerkKso::all()
            ->mapWithKeys(fn ($m) => [strtoupper(trim($m->nama)) => $m->id])
            ->toArray();

        DB::transaction(function () use ($rows, &$alatCache, &$merkCache) {
            foreach ($rows as $row) {
                $namaAlat = trim((string) ($row['nama_alat'] ?? ''));
                if ($namaAlat === '') {
                    continue;
                }

                $alatKey = strtoupper($namaAlat);
                if (!isset($alatCache[$alatKey])) {
                    $newAlat = MasterAlatKso::create([
                        'nama' => $alatKey,
                        'nama_rs' => null,
                    ]);
                    $alatCache[$alatKey] = $newAlat->id;
                }

                $merkId = null;
                $merkRaw = trim((string) ($row['merk'] ?? ''));
                if ($merkRaw !== '' && $merkRaw !== '-') {
                    $merkKey = strtoupper($merkRaw);
                    if (!isset($merkCache[$merkKey])) {
                        $newMerk = MasterMerkKso::create([
                            'nama' => $merkKey,
                            'nama_rs' => null,
                        ]);
                        $merkCache[$merkKey] = $newMerk->id;
                    }
                    $merkId = $merkCache[$merkKey];
                }

                $jenisRaw = trim((string) ($row['jenis'] ?? ''));
                $pengguna = (stripos($jenisRaw, 'NON') !== false) ? 'Non Medis' : 'Medis';

                $kodeRs = trim((string) ($row['kode_rs'] ?? ''));
                if ($kodeRs === '') {
                    $kodeRs = $this->defaultKodeRs ?? (auth()->check() ? auth()->user()->kodeRS : null);
                }

                InventarisKso::create([
                    'Nama' => $alatCache[$alatKey],
                    'Merk' => $merkId,
                    'Tipe' => $this->nullableStr($row['tipe'] ?? null),
                    'NoSn' => $this->nullableStr($row['no_sn'] ?? null),
                    'Vendor' => $this->nullableStr($row['vendor'] ?? null),
                    'TanggalKerjasama' => $this->parseDate($row['awal_kerjasama'] ?? null),
                    'AkhirKerjasama' => $this->parseDate($row['akhir_kerjasama'] ?? null),
                    'NamaRS' => $kodeRs,
                    'Departemen' => $this->nullableStr($row['departemen'] ?? null),
                    'Unit' => $this->nullableStr($row['unit'] ?? null),
                    'Pengguna' => $pengguna,
                    'Klasifikasi' => $this->nullableStr($row['klasifikasi_risiko'] ?? null),
                    'Keterangan' => $this->nullableStr($row['keterangan'] ?? null),
                    'UserCreate' => auth()->check() ? auth()->user()->name : 'import-excel',
                ]);

                $this->importedCount++;
            }
        });
    }

    protected function nullableStr($val): ?string
    {
        $str = trim((string) ($val ?? ''));
        return $str === '' ? null : $str;
    }

    protected function parseDate($val): ?string
    {
        if ($val === null) {
            return null;
        }
        $str = trim((string) $val);
        if ($str === '' || $str === '-') {
            return null;
        }

        if (is_numeric($val)) {
            $num = (float) $val;
            if ($num > 20000 && $num < 70000) {
                try {
                    return ExcelDate::excelToDateTimeObject($num)->format('Y-m-d');
                } catch (\Throwable $e) {
                    return null;
                }
            }
        }

        try {
            return Carbon::parse(str_replace('/', '-', $str))->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
