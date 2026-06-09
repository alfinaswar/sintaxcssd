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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MaintananceExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell
{
    protected $filterRs;
    protected $bulanAwal;
    protected $bulanAkhir;
    protected $tahun;
    protected $rowNumber = 3; // Mulai dari baris ke-3 (setelah header laporan)

    public function __construct($filterRs, $bulanAwal, $bulanAkhir, $tahun)
    {
        $this->filterRs = $filterRs;
        $this->bulanAwal = $bulanAwal;
        $this->bulanAkhir = $bulanAkhir;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = Maintanance::with(['getInventaris', 'getRs'])
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

        $this->rowNumber++;

        return [
            $this->rowNumber - 2, // Nomor urut
            $row->getInventaris ? $row->getInventaris->nama : ($row->kode_item ?? '-'),
            $months[$row->bulan] ?? '-',
            $row->keterangan ?? '-',
            $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-',
            $row->updated_at ? $row->updated_at->format('d/m/Y H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header Laporan (Baris 1)
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN PREVENTIF MAINTENANCE ALAT');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Info Laporan (Baris 2)
        $rsName = '';
        if ($this->filterRs) {
            $rs = \App\Models\MasterRs::where('kodeRS', $this->filterRs)->first();
            $rsName = $rs ? $rs->namaRS : 'Semua RS';
        }

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', "Rumah Sakit: {$rsName} | Periode: {$this->getBulanName($this->bulanAwal)} - {$this->getBulanName($this->bulanAkhir)} {$this->tahun}");
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header Tabel (Baris 3)
        $sheet->getStyle('A3:F3')->getFont()->setBold(true);
        $sheet->getStyle('A3:F3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet->getStyle('A3:F3')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A3:F3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:F3')->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Merge cell untuk Nama Barang yang sama
        $this->mergeNamaBarangCells($sheet);

        // Border untuk semua cell
        $lastRow = $this->rowNumber + 2; // +2 karena mulai dari baris 3
        $sheet->getStyle("A3:F{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [
            1 => ['font' => ['bold' => true]],
        ];
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
            $namaBarang = $row->getInventaris ? $row->getInventaris->nama : ($row->kode_item ?? '-');

            if ($namaBarang !== $currentNamaBarang) {
                // Merge cell untuk nama barang sebelumnya
                if ($currentNamaBarang !== '' && ($rowIndex - $mergeStartRow) > 1) {
                    $sheet->mergeCells("B{$mergeStartRow}:B" . ($rowIndex - 1));
                    $sheet->getStyle("B{$mergeStartRow}")->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
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
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
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
