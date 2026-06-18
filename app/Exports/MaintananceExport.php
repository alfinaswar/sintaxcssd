<?php

namespace App\Exports;

use App\Models\Maintanance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MaintananceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell
{
    protected $filterRs;
    protected $bulanAwal;
    protected $bulanAkhir;
    protected $tahun;
    protected $klasifikasi;
    protected $rowNumber = 3; // Mulai dari baris ke-3 (setelah header laporan)

    public function __construct($filterRs, $bulanAwal, $bulanAkhir, $tahun, $klasifikasi)
    {
        $this->filterRs = $filterRs;
        $this->bulanAwal = $bulanAwal;
        $this->bulanAkhir = $bulanAkhir;
        $this->tahun = $tahun;
        $this->klasifikasi = $klasifikasi;
    }

    public function collection()
    {
        $query = Maintanance::with([
            'getInventaris' => function ($q) {
                if ($this->klasifikasi) {
                    $q->where('klasifikasi', $this->klasifikasi);
                }
            },
            'getRs'
        ])
            ->orderBy('kode_item', 'asc')
            ->orderBy('bulan', 'asc');

        // Filter berdasarkan RS
        if ($this->filterRs) {
            $query->where('nama_rs', $this->filterRs);
        }

        // Filter berdasarkan tahun
        $query->whereYear('created_at', $this->tahun);

        // Filter berdasarkan range bulan
        $query->whereBetween('bulan', [$this->bulanAwal, $this->bulanAkhir]);

        return $query->get();
    }

    public function startCell(): string
    {
        return 'A3'; // Mulai dari baris ke-3
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'No SN',
            'No Inventaris',
            'Unit',
            'Klasifikasi',
            'Bulan',
            'Keterangan',
            'Tanggal Input',
            'Tanggal Update'
        ];
    }

    public function map($row): array
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $inventaris = $row->getInventaris;

        $this->rowNumber++;

        return [
            $this->rowNumber - 2, // Nomor urut
            $inventaris ? $inventaris->nama : ($row->kode_item ?? '-'), // Nama Barang
            $inventaris ? ($inventaris->no_sn ?? '-') : '-', // No SN
            $inventaris ? ($inventaris->no_inventaris ?? '-') : '-', // No Inventaris
            $inventaris ? ($inventaris->unit ?? '-') : '-', // Unit
            $inventaris ? ($inventaris->klasifikasi ?? '-') : '-', // Klasifikasi
            $months[$row->bulan] ?? $row->bulan, // Bulan (nama)
            $row->keterangan ?? '-', // Keterangan
            $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-', // Tanggal Input
            $row->updated_at ? $row->updated_at->format('d/m/Y H:i') : '-' // Tanggal Update
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header Laporan (Baris 1) - 10 kolom A-J
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'LAPORAN PREVENTIF MAINTENANCE ALAT');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Info Laporan (Baris 2)
        $rsName = '';
        if ($this->filterRs) {
            $rs = \App\Models\MasterRs::where('kodeRS', $this->filterRs)->first();
            $rsName = $rs ? $rs->namaRS : 'Semua RS';
        }

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', "Rumah Sakit: {$rsName} | Periode: {$this->getBulanName($this->bulanAwal)} - {$this->getBulanName($this->bulanAkhir)} {$this->tahun}");
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header Tabel (Baris 3) - 10 kolom
        $sheet->getStyle('A3:J3')->getFont()->setBold(true);
        $sheet->getStyle('A3:J3')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet->getStyle('A3:J3')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Merge cell untuk Nama Barang yang sama (Kolom B)
        $this->mergeNamaBarangCells($sheet);

        // Border untuk semua cell data
        $lastRow = $this->rowNumber + 2;
        $sheet->getStyle("A3:J{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        return [];
    }

    protected function mergeNamaBarangCells($sheet)
    {
        $data = $this->collection();
        if ($data->isEmpty()) {
            return;
        }

        $startRow = 4; // Data mulai baris ke-4
        $currentNamaBarang = '';
        $mergeStartRow = 4;
        $rowIndex = 4;

        foreach ($data as $row) {
            $inventaris = $row->getInventaris;
            $namaBarang = $inventaris ? $inventaris->nama : ($row->kode_item ?? '-');

            if ($namaBarang !== $currentNamaBarang) {
                // Merge cell untuk nama barang sebelumnya (Kolom B)
                if ($currentNamaBarang !== '' && ($rowIndex - $mergeStartRow) > 1) {
                    $sheet->mergeCells("B{$mergeStartRow}:B" . ($rowIndex - 1));
                    $sheet->getStyle("B{$mergeStartRow}")->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }

                $currentNamaBarang = $namaBarang;
                $mergeStartRow = $rowIndex;
            }

            $rowIndex++;
        }

        // Merge cell untuk nama barang terakhir
        if ($currentNamaBarang !== '' && ($rowIndex - $mergeStartRow) > 1) {
            $sheet->mergeCells("B{$mergeStartRow}:B" . ($rowIndex - 1));
            $sheet->getStyle("B{$mergeStartRow}")->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER);
        }
    }

    protected function getBulanName($bulan)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $months[$bulan] ?? '';
    }
}
