<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class MaintenanceExport implements FromView, WithEvents, WithStyles
{
    use Exportable;
    public function __construct(string $tgl_mulai, string $tgl_akhir, string $rs)
    {
        // dd($tgl_mulai);
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_akhir = $tgl_akhir;
        $this->rs = $rs;

    }
    public function view(): View
    {
        if ($this->rs != 'none') {
            return view('excel.excel_maintenance', [
                'maintenance' => DB::connection("mysql")
                    ->table('masalah')
                    ->where('nama_rs', '>=', $this->rs)
                    ->where('created_at', '>=', $this->tgl_mulai)
                    ->where('created_at', '<=', $this->tgl_akhir)
                    ->get()
            ]);
        } else {
            return view('excel.excel_maintenance', [
                'maintenance' => DB::connection("mysql")
                    ->table('masalah')
                    ->where('created_at', '>=', $this->tgl_mulai)
                    ->where('created_at', '<=', $this->tgl_akhir)
                    ->get()
            ]);
        }

    }
    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();

                $cellRange = 'A4:E' . $lastRow;
                $event->sheet->getDelegate()->getStyle('A4:E4')->getFont()->setName('Times New Roman')->setBold(true)->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2')->getFont()->setBold(true);
    }
}
