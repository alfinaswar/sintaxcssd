<?php

namespace App\Exports;

use App\Models\MasalahModel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class PembelianExport implements FromView, WithEvents, WithStyles
{
    use Exportable;
    public function __construct(string $tgl_mulai, string $tgl_akhir)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_akhir = $tgl_akhir;
        // dd($tgl_akhir);
    }
    public function view(): View
    {
        // return view('excel.excel_masalah', [
        //     'masalah' => MasalahModel::where('created_at', '>=', $this->tgl_mulai)
        //         ->where('created_at', '<=', $this->tgl_akhir)
        //         ->get()
        // ]);
        return view('excel.excel_pembelian', [
        'pembelian' => DB::connection("mysql2")
                ->table('po')
                ->where('po.DepartemenID', 'INV-MEDIS')
                ->where('po.TanggalBuat', '>=', $this->tgl_mulai)
                ->where('po.TanggalBuat', '<=', $this->tgl_akhir)
                ->join('po2', 'po2.POID', '=', 'po.POID')
                ->join('masteritem', 'masteritem.ItemID', '=', 'po2.ItemID')

                ->get()
        ]);

    }
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $cellRange = 'A4:F' . $lastRow;
                $event->sheet->getDelegate()->getStyle('A4:F4')->getFont()->setName('Times New Roman')->setBold(true)->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2')->getFont()->setBold(true);
    }
}
