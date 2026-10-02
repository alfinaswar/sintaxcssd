<?php

namespace App\Exports;

use App\Models\MasterRs;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Pastikan model RsModel yang sesuai dengan master RS (misal: MasterRs, RumahSakit, dll)
use App\Models\RsModel; // Ganti dengan model sesuai di project kamu

class PerluKalibrasiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $data;

    protected $mapRs;

    protected $reportTitle = 'Laporan Perlu Kalibrasi';
    protected $printedDate;

    public function __construct($data)
    {
        $this->data = $data;

        // Ambil mapping kode RS dari tabel master RS
        $this->mapRs = MasterRs::pluck('nama', 'kodeRS')->toArray();

        $this->printedDate = now()->format('d-m-Y H:i');
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            $kodeRs = trim($item['nama_rs'] ?? '');
            $namaRsLengkap = $this->mapRs[$kodeRs] ?? ($kodeRs ?: 'Tidak Diketahui');

            $tglTerakhir = isset($item['tgl_kalibrasi']) && $item['tgl_kalibrasi']
                ? \Carbon\Carbon::parse($item['tgl_kalibrasi'])->format('d-m-Y')
                : '-';

            $expDate = isset($item['exp_date']) && $item['exp_date']
                ? \Carbon\Carbon::parse($item['exp_date'])->format('d-m-Y')
                : '-';

            return [
                'Kode Item' => $item['kode_item'] ?? '-',
                'No Inventaris' => $item['no_inventaris'] ?? '-', // Tambahan kolom
                'Nama Alat' => $item['nama'] ?? '-',
                'Rumah Sakit' => $namaRsLengkap,
                'Unit' => $item['unit'] ?? '-',
                'Departemen' => $item['departemen'] ?? '-',
                'Tgl Kalibrasi Terakhir' => $tglTerakhir,
                'Exp Date' => $expDate,
                'Status' => $item['status'] ?? 'Belum Dikalibrasi',
            ];
        });
    }

    // headings digunakan pada baris HEADINGS (tapi di excel akan di baris 3, karena atasnya untuk Judul dan Tanggal Cetak)
    public function headings(): array
    {
        return [
            'Kode Item',
            'No Inventaris', // Tambahan heading
            'Nama Alat',
            'Rumah Sakit',
            'Unit',
            'Departemen',
            'Tgl Kalibrasi Terakhir',
            'Exp Date',
            'Status'
        ];
    }

    /**
     * Sheet title for improved professionalism & compatibility with WithMultipleSheets
     */
    public function title(): string
    {
        return 'Perlu Kalibrasi';
    }

    /**
     * Events digunakan agar bisa custom row sebelum headings.
     */
    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\BeforeSheet::class => function ($event) {
                $sheet = $event->sheet->getDelegate();

                // Insert title at A1, merged until I1
                $sheet->setCellValue('A1', $this->reportTitle);
                $sheet->mergeCells('A1:I1');

                // Insert printed date at A2
                $sheet->setCellValue('A2', 'Tanggal Cetak: ' . $this->printedDate);
                $sheet->mergeCells('A2:I2');

                // Geser headings ke baris ke-3, dan data mulai baris ke-4
                // (Maatwebsite\Excel akan otomatis tulis heading di A3 dst)

                // Optional: Tinggi baris title dan date
                $sheet->getRowDimension(1)->setRowHeight(26);
                $sheet->getRowDimension(2)->setRowHeight(22);
            },

            \Maatwebsite\Excel\Events\AfterSheet::class => function ($event) {
                $sheet = $event->sheet->getDelegate();

                // Style for Title (A1:I1)
                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF333333'],
                        'size' => 15,
                        'name' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Style for "Tanggal Cetak" (A2:I2)
                $sheet->getStyle('A2:I2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'color' => ['argb' => 'FF666666'],
                        'size' => 11,
                        'name' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Style for heading row (row 3)
                $sheet->getStyle('A3:I3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                        'size' => 12,
                        'name' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF34495e'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFAAAAAA'],
                        ],
                    ],
                ]);

                // Style for all data rows (mulai row 4)
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A4:I{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                    'font' => [
                        'size' => 11,
                        'name' => 'Calibri'
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFBBBBBB'],
                        ],
                    ],
                ]);

                // Zebra striping data rows
                for ($row = 4; $row <= $highestRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:I{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF4F8FB');
                    }
                }

                // Set fixed height for heading
                $sheet->getRowDimension(3)->setRowHeight(28);
            },
        ];
    }

    /**
     * KENAPA still pakai styles? Untuk autoSize implementation only, event styling ambil alih UI
     */
    public function styles(Worksheet $sheet)
    {
        // No-op, semua styling lewat event, return empty.
        return [];
    }
}
