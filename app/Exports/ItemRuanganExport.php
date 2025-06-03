<?php

namespace App\Exports;

use App\Models\DataInventaris;
use App\Models\MasterRs;
use App\Models\MasalahModel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use DB;

class ItemRuanganExport implements FromView, WithEvents, WithStyles
{
    use Exportable;
    public function __construct(string $unit = null, string $jenis = null, string $nama = null, string $merk = null, string $rs = null)
    {
        $this->unit = $unit;
        $this->jenis = $jenis;
        $this->nama = $nama;
        $this->merk = $merk;
        $this->rs = $rs;
    }

    public function view(): View
    {
        $rs = MasterRs::where('kodeRS', $this->rs)->first();
        $query = DataInventaris::
            when($this->unit, function ($query) {
                return $query->where('unit', $this->unit);
            })
            ->when($this->nama, function ($query) {
                return $query->where('nama', $this->nama);
            })
            ->when($this->merk, function ($query) {
                return $query->where('merk', $this->merk);
            })
            ->when($this->jenis, function ($query) {
                return $query->where('pengguna', $this->jenis);
            })
            ->when($this->rs, function ($query) {
                return $query->where('nama_rs', $this->rs);
            })
            ->orderBy('unit', 'asc')
            ->get();


        // dd($query);

        return view('excel.excel_item-ruangan', compact('query', 'rs'));
    }
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $cellRange = 'A4:G' . $lastRow;
                $event->sheet->getDelegate()->getStyle('A4:G4')->getFont()->setName('Times New Roman')->setBold(true)->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2')->getFont()->setBold(true);
    }

}
