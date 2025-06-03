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
    public function __construct(string $tgl_mulai, string $tgl_akhir, string $unit, string $rs)
    {
        $this->tgl_mulai = $tgl_mulai;
        $this->tgl_akhir = $tgl_akhir;
        $this->unit = $unit;
        $this->rs = $rs;
    }
    public function view(): View
    {
        if ($this->rs != 'none') {

            if ($this->rs == 'K') {       //ayani
                $selectdb = 'mysql2';
            } elseif ($this->rs == 'I') { //panam
                $selectdb = 'mysql3';
            } elseif ($this->rs == 'B') { //batan
                $selectdb = 'mysql4';
            } elseif ($this->rs == 'A') { //sudirman
                $selectdb = 'mysql5';
            } elseif ($this->rs == 'G') { //ujung batu
                $selectdb = 'mysql6';
            } elseif ($this->rs == 'S') { //bagan batu
                $selectdb = 'mysql7';
            } elseif ($this->rs == 'R') { //botania
                $selectdb = 'mysql8';
            } elseif ($this->rs == 'D') { //dUMAI
                $selectdb = 'mysql9';
            }
            return view('excel.excel_pembelian', [
                'pembelian' => DB::connection($selectdb)
                    ->table('po')
                    ->where('po.DepartemenID', $this->unit)
                    ->where('po.TanggalBuat', '>=', $this->tgl_mulai)
                    ->where('po.TanggalBuat', '<=', $this->tgl_akhir)
                    ->join('po2', 'po2.POID', '=', 'po.POID')
                    ->join('masteritem', 'masteritem.ItemID', '=', 'po2.ItemID')
                    ->get()
            ]);
        } else {
            if (auth()->check()) {
                $kodeRS = auth()->user()->kodeRS;
                if ($kodeRS === 'K') {       //ayani
                    $selectdb = 'mysql2';
                } elseif ($kodeRS == 'I') { //panam
                    $selectdb = 'mysql3';
                } elseif ($kodeRS == 'B') { //batan
                    $selectdb = 'mysql4';
                } elseif ($kodeRS == 'A') { //sudirman
                    $selectdb = 'mysql5';
                } elseif ($kodeRS == 'G') { //ujung batu
                    $selectdb = 'mysql6';
                } elseif ($kodeRS == 'S') { //bagan batu
                    $selectdb = 'mysql7';
                } elseif ($kodeRS == 'R') { //botania
                    $selectdb = 'mysql8';
                } elseif ($kodeRS == 'D') { //dUMAI
                    $selectdb = 'mysql9';
                }
            }
            return view('excel.excel_pembelian', [
                'pembelian' => DB::connection($selectdb)
                    ->table('po')
                    ->where('po.DepartemenID', $this->unit)
                    ->where('po.TanggalBuat', '>=', $this->tgl_mulai)
                    ->where('po.TanggalBuat', '<=', $this->tgl_akhir)
                    ->join('po2', 'po2.POID', '=', 'po.POID')
                    ->join('masteritem', 'masteritem.ItemID', '=', 'po2.ItemID')
                    ->get()
            ]);
        }


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
