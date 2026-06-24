<?php

namespace App\Exports;

use App\Models\InventarisKso;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Http\Request;

class InventarisKsoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, ShouldAutoSize, WithCustomStartCell
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Judul Sheet
     */
    public function title(): string
    {
        return 'Laporan Inventaris KSO';
    }

    /**
     * Mulai dari cell A4 (untuk memberi ruang judul & info filter di atas)
     */
    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * Query data dengan filter (sesuai permintaan user)
     */
    public function collection()
    {
        $request = $this->request;

        $data = InventarisKso::with('getNamaAlat', 'getMerk', 'getRS', 'getDepartemen');

        // Filter berdasarkan role user
        if (!auth()->user() || (strtolower(auth()->user()->role) !== 'admin')) {
            $data->where('NamaRS', auth()->user()->kodeRS);
        }

        // Filtering
        if ($request->filter_pengguna) {
            $data->where('Pengguna', $request->filter_pengguna);
        }
        if ($request->filter_rs) {
            $data->where('NamaRS', $request->filter_rs);
        }
        if ($request->filter_departemen) {
            $data->where('Departemen', $request->filter_departemen);
        }
        if ($request->filter_unit) {
            $data->where('Unit', $request->filter_unit);
        }
        if ($request->filter_tahun_kerjasama) {
            $data->whereYear('TanggalKerjasama', $request->filter_tahun_kerjasama);
        }
        // FIX: whereYear tidak cocok untuk kolom string 'Nama', gunakan where biasa
        if ($request->filter_nama_barang) {
            $data->where('Nama', 'like', '%' . $request->filter_nama_barang . '%');
        }
        if ($request->filter_pencarian) {
            $search = $request->filter_pencarian;
            $data->where(function ($q) use ($search) {
                $q->where('Nama', 'like', "%{$search}%")
                    ->orWhere('KodeBarang', 'like', "%{$search}%")
                    ->orWhere('NoSn', 'like', "%{$search}%")
                    ->orWhere('Merk', 'like', "%{$search}%");
            });
        }

        return $data->orderBy('TanggalKerjasama', 'desc')->get();
    }

    /**
     * Heading kolom
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Asset ID',
            'Nama Alat',
            'Merk',
            'Tipe',
            'No. SN',
            'Vendor KSO',
            'Tgl Kerjasama',
            'Akhir Kerjasama',
            'RS',
            'Departemen',
            'Unit',
            'Jenis',
            'Klasifikasi',
            'Keterangan',
        ];
    }

    /**
     * Mapping data per baris
     */
    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->KodeBarang,
            $item->AssetID,
            // Ambil nama dari relasi jika ada, fallback ke kolom langsung
            $item->getNamaAlat->Nama ?? $item->Nama,
            $item->getMerk->Nama ?? $item->Merk,
            $item->Tipe,
            $item->NoSn,
            $item->Vendor,
            $item->TanggalKerjasama ? \Carbon\Carbon::parse($item->TanggalKerjasama)->format('d/m/Y') : '-',
            $item->AkhirKerjasama ? \Carbon\Carbon::parse($item->AkhirKerjasama)->format('d/m/Y') : '-',
            $item->getRS->nama ?? $item->NamaRS,
            $item->getDepartemen->nama ?? $item->Departemen,
            $item->Unit,
            $item->Pengguna,
            $item->Klasifikasi,
            $item->Keterangan,
        ];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 15,
            'C' => 15,
            'D' => 30,
            'E' => 18,
            'F' => 18,
            'G' => 18,
            'H' => 22,
            'I' => 16,
            'J' => 16,
            'K' => 20,
            'L' => 20,
            'M' => 18,
            'N' => 12,
            'O' => 18,
            'P' => 30,
        ];
    }

    /**
     * Styling: Judul, Info Filter, Header Tabel, Border
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn(); // P

        // === JUDUL UTAMA (A1) ===
        $sheet->mergeCells('A1:P1');
        $sheet->setCellValue('A1', 'LAPORAN INVENTARIS KSO');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '007BFF']],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // === SUB JUDUL (A2) ===
        $sheet->mergeCells('A2:P2');
        $sheet->setCellValue('A2', 'RS AWAL ROS - Tanggal Cetak: ' . date('d F Y H:i') . ' WIB');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // === INFO FILTER (A3) ===
        $sheet->mergeCells('A3:P3');
        $request = $this->request;
        $filterText = 'Filter: ';
        $filterText .= $request->filter_pengguna ? "Jenis={$request->filter_pengguna} | " : '';
        $filterText .= $request->filter_rs ? "RS={$request->filter_rs} | " : '';
        $filterText .= $request->filter_departemen ? "Departemen={$request->filter_departemen} | " : '';
        $filterText .= $request->filter_unit ? "Unit={$request->filter_unit} | " : '';
        $filterText .= $request->filter_tahun_kerjasama ? "Tahun={$request->filter_tahun_kerjasama} | " : '';
        $filterText .= $request->filter_pencarian ? "Pencarian=\"{$request->filter_pencarian}\"" : 'Semua Data';
        $sheet->setCellValue('A3', rtrim($filterText, ' |'));
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['size' => 10, 'italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7F1FF']],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // Baris ke-4 kosong sebagai pemisah
        $sheet->getRowDimension(4)->setRowHeight(8);

        // === HEADER TABEL (Baris ke-5) ===
        $headerRange = "A5:{$lastCol}5";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '343A40']],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
            ],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(25);

        // === DATA BODY ===
        if ($lastRow >= 6) {
            $bodyRange = "A6:{$lastCol}{$lastRow}";
            $sheet->getStyle($bodyRange)->applyFromArray([
                'font' => ['size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
                ],
            ]);

            // Kolom No (A) center
            $sheet->getStyle("A6:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Kolom Tanggal (I, J) center
            $sheet->getStyle("I6:J{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Kolom Jenis (N) center
            $sheet->getStyle("N6:N{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Zebra striping (baris genap)
            for ($i = 6; $i <= $lastRow; $i++) {
                if ($i % 2 == 0) {
                    $sheet->getStyle("A{$i}:{$lastCol}{$i}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F8F9FA');
                }
            }
        }

        // === FOOTER: TOTAL DATA ===
        $footerRow = $lastRow + 1;
        $sheet->mergeCells("A{$footerRow}:C{$footerRow}");
        $sheet->setCellValue("A{$footerRow}", 'Total Data:');
        $sheet->getStyle("A{$footerRow}:C{$footerRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ]);
        $sheet->setCellValue("D{$footerRow}", $lastRow - 5 . ' Alat');
        $sheet->getStyle("D{$footerRow}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '007BFF']],
        ]);

        return [];
    }
}
