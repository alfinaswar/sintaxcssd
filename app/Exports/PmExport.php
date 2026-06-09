<?php

namespace App\Exports;

use Auth;
use App\Models\DataInventaris;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PmExport implements FromView, WithEvents, WithStyles
{
    use Exportable;

    // ✅ Deklarasi properti (wajib untuk PHP 8.2+)
    public $bulan_mulai;
    public $bulan_akhir;
    public $tahun;
    public $jenis;
    public $kategori_risk;
    public $filter_rs;

    public function __construct(
        ?string $bulan_mulai,
        ?string $bulan_akhir,
        ?string $tahun,
        ?string $jenis,
        ?string $kategori_risk = null,
        ?string $filter_rs = null
    ) {
        $this->bulan_mulai = $bulan_mulai;
        $this->bulan_akhir = $bulan_akhir;
        $this->tahun = $tahun;
        $this->jenis = $jenis;
        $this->kategori_risk = $kategori_risk;
        $this->filter_rs = $filter_rs;
    }

    public function view(): View
    {
        $data = DataInventaris::whereHas('DataMaintenance', function ($query) {
            // Filter bulan
            if ($this->bulan_mulai && $this->bulan_akhir) {
                $query->whereBetween('bulan', [$this->bulan_mulai, $this->bulan_akhir]);
            } elseif ($this->bulan_mulai) {
                $query->where('bulan', $this->bulan_mulai);
            }

            // Filter tahun
            if ($this->tahun) {
                $query->whereYear('created_at', $this->tahun);
            }

            // Kategori risk filter should NOT be here, so it's removed from DataMaintenance relation
        })
            ->with([
                'DataMaintenance' => function ($query) {
                    if ($this->bulan_mulai && $this->bulan_akhir) {
                        $query->whereBetween('bulan', [$this->bulan_mulai, $this->bulan_akhir]);
                    } elseif ($this->bulan_mulai) {
                        $query->where('bulan', $this->bulan_mulai);
                    }
                    if ($this->tahun) {
                        $query->whereYear('created_at', $this->tahun);
                    }
                    // Kategori risk filter should NOT be here
                }
            ])
            // Now apply kategori_risk filter on DataInventaris
            ->when($this->kategori_risk, function ($q, $kategori_risk) {
                return $q->where('klasifikasi', $kategori_risk);
            })
            ->when($this->jenis, function ($q, $jenis) {
                return $q->where('pengguna', $jenis);
            })
            ->when($this->filter_rs, function ($q, $rs) {
                return $q->where('nama_rs', $rs);
            })
            ->when(!$this->filter_rs && Auth::user()->role !== 'admin', function ($q) {
                return $q->where('nama_rs', Auth::user()->kodeRS);
            })
            ->get();

        return view('excel.excel_pm', [
            'data' => $data,
            'bulan_mulai' => $this->bulan_mulai,
            'bulan_akhir' => $this->bulan_akhir,
            'tahun' => $this->tahun,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();

                // Hitung jumlah kolom dinamis (3 kolom tetap + jumlah bulan)
                $mulai = $this->bulan_mulai ?? 1;
                $akhir = $this->bulan_akhir ?? 12;
                $jumlahKolom = 3 + ($akhir - $mulai + 1);

                // Convert angka kolom ke huruf (3 = C, 4 = D, dst)
                $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($jumlahKolom);

                $cellRange = 'A1:' . $lastCol . $lastRow;

                $event->sheet->getDelegate()
                    ->getStyle('A1:' . $lastCol . '2')
                    ->getFont()
                    ->setBold(true);

                $event->sheet->getDelegate()
                    ->getStyle($cellRange)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Auto-size columns
                foreach (range('A', $lastCol) as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:O2' => ['font' => ['bold' => true]],
        ];
    }
}
