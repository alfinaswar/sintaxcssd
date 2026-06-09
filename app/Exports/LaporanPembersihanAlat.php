<?php

namespace App\Exports;

use App\Models\DataInventaris;
use App\Models\MasterRs;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanPembersihanAlat implements FromArray, WithEvents, WithStyles
{
    use Exportable;

    protected $unit;
    protected $jenis;
    protected $alat;
    protected $rs;
    protected $bulan;
    protected $tahun;

    public function __construct(
        string $unit = null,
        string $jenis = null,
        string $alat = null,
        string $rs = null,
        string $bulan = null,
        string $tahun = null
    ) {
        $this->unit = $unit;
        $this->jenis = $jenis;
        $this->alat = $alat;
        $this->rs = $rs;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function array(): array
    {
        $rs = MasterRs::where('kodeRS', $this->rs)->first();
        $rsNama = $rs ? ($rs->namaRS ?? '') : '-';

        $query = DataInventaris::with([
            'getLaporanMonitoring' => function ($q) {
                if ($this->bulan) {
                    $q->whereMonth('created_at', $this->bulan);
                }
                if ($this->tahun) {
                    $q->whereYear('created_at', $this->tahun);
                }
            }
        ])
            ->when($this->unit, function ($query) {
                return $query->where('unit', $this->unit);
            })
            ->when($this->alat, function ($query) {
                return $query->where('kode_item', $this->alat);
            })
            ->when($this->jenis, function ($query) {
                return $query->where('pengguna', $this->jenis);
            })
            ->when($this->rs, function ($query) {
                return $query->where('nama_rs', $this->rs);
            })
            ->orderBy('unit', 'asc')
            ->get();

        $bulanTahun = '-';
        if (!empty($this->bulan) && !empty($this->tahun)) {
            $bulanTahun = \Carbon\Carbon::create($this->tahun, $this->bulan)->translatedFormat('F Y');
        } elseif (!empty($this->bulan)) {
            $bulanTahun = \Carbon\Carbon::create(null, $this->bulan)->translatedFormat('F');
        } elseif (!empty($this->tahun)) {
            $bulanTahun = $this->tahun;
        }

        $meta = [
            ['Laporan Pembersihan Alat - Rumah Sakit Awal Bros'],
            ['Rumah Sakit', $rsNama],
            ['Unit', $this->unit ?? '-'],
            ['Jenis', $this->jenis ?? '-'],
            ['Alat', !empty($this->alat) ? $this->alat : '-'],
            ['Bulan / Tahun', $bulanTahun],
            ['Tanggal Cetak', now()->translatedFormat('d F Y')],
            ['Total Alat', $query->count()],
            [],
        ];

        $data = [];

        if ($query->count() === 0) {
            $data[] = ['Tidak ada data yang sesuai dengan filter yang dipilih.'];
        } else {
            foreach ($query as $key => $item) {
                // Header per alat
                $headerAlat = ($key + 1) . '. ' . ($item->nama ?? '-');
                if (!empty($item->no_inventaris)) {
                    $headerAlat .= ' / ' . $item->no_inventaris;
                }
                if (!empty($item->merk)) {
                    $headerAlat .= ' (' . $item->merk . ')';
                }
                if (!empty($item->unit)) {
                    $headerAlat .= ' — ' . $item->unit;
                }
                $data[] = [$headerAlat, '', '', '', ''];
                // Table Head
                $data[] = [
                    'No',
                    'Tanggal',
                    'Status',
                    'Petugas',
                    'Keterangan'
                ];
                // Data rows
                if ($item->getLaporanMonitoring && $item->getLaporanMonitoring->count()) {
                    foreach ($item->getLaporanMonitoring as $no => $lap) {
                        $tanggal = !empty($lap->created_at) ? \Carbon\Carbon::parse($lap->created_at)->format('d/m/Y H:i') : '-';
                        $status = $lap->Status ?? '-';
                        $statusLower = strtolower($status);

                        if ($statusLower === 'bersih') {
                            $status = $lap->Status;
                        } elseif ($statusLower === 'kotor') {
                            $status = $lap->Status;
                        }

                        $data[] = [
                            $no + 1,
                            $tanggal,
                            $status,
                            $lap->idUser ?? '-',
                            $lap->Keterangan ?? '-'
                        ];
                    }
                } else {
                    $data[] = [
                        '',
                        '',
                        '',
                        '',
                        'Belum ada data monitoring'
                    ];
                }
                $data[] = []; // Kosong antar alat
            }
        }

        // Footer
        $footer = [
            [],
            ['Dicetak otomatis oleh sistem', '', '', '', now()->format('d F Y H:i') . ' WIB']
        ];

        return array_merge($meta, $data, $footer);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Try to apply some formatting for headers/total rows
                $sheet = $event->sheet->getDelegate();

                // Bold main title (A1)
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                // Lebarkan kolom tanggal (kolom B) agar lebih lebar
                $sheet->getColumnDimension('B')->setWidth(22); // Lebih lebar dari default, bisa disesuaikan

                // Bold for per alat header and table header
                $highestRow = $sheet->getHighestRow();
                for ($row = 1; $row <= $highestRow; $row++) {
                    $firstCell = $sheet->getCell('A' . $row)->getValue();
                    $secondCell = $sheet->getCell('B' . $row)->getValue();
                    // Per alat header: only A column, rest empty
                    if (!empty($firstCell) && empty($secondCell)) {
                        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(12);
                    }
                    // Table header: matches 'No', 'Tanggal', etc
                    if (
                        trim($firstCell) === 'No' &&
                        trim($sheet->getCell('B' . $row)->getValue()) === 'Tanggal'
                    ) {
                        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
                        $sheet->getStyle("A{$row}:E{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    }
                }

                // Add border for data rows
                $startDataRow = 1;
                for ($row = 1; $row <= $highestRow; $row++) {
                    $firstCell = $sheet->getCell('A' . $row)->getValue();
                    if ($firstCell === 'No') {
                        $startDataRow = $row + 1;
                        // Continue until blank row or new alat
                        for ($r = $startDataRow; $r <= $highestRow; $r++) {
                            $val = $sheet->getCell("A$r")->getValue();
                            if (empty($val))
                                break;
                            $sheet->getStyle("A{$r}:E{$r}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                        }
                    }
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Optional: You can further style (bolden) important meta rows
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setBold(true);
    }
}
