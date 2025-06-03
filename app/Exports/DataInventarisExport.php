<?php

namespace App\Exports;

use App\Models\DataInventaris;
use App\Models\MasterRs;
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

class DataInventarisExport implements FromView, WithEvents, WithStyles
{
    use Exportable;
    public function __construct(string $filter_unit = null, string $filter_jenis = null, string $filter_departemen = null, string $filter_pembelian = null, string $filter_pencarian = null, string $filter_rs = null)
    {
        $this->filter_unit = $filter_unit;
        $this->filter_departemen = $filter_departemen;
        $this->filter_jenis = $filter_jenis;
        $this->filter_pencarian = $filter_pencarian;
        $this->filter_pembelian = $filter_pembelian;
        $this->filter_rs = $filter_rs;
    }

    public function view(): View
    {
        $rs = MasterRs::where('kodeRS', $this->filter_rs)->first();
        $query = DataInventaris::
            when($this->filter_unit, function ($query) {
                return $query->where('unit', $this->filter_unit);
            })
            ->when($this->filter_departemen, function ($query) {
                return $query->where('departemen', $this->filter_departemen);
            })
            ->when($this->filter_jenis, function ($query) {
                return $query->where('pengguna', $this->filter_jenis);
            })
            ->when($this->filter_pembelian, function ($query) {
                return $query->whereYear('tanggal_beli', $this->filter_pembelian);
            })
            ->when($this->filter_pencarian, function ($query) {
                return $query->where('nama', 'like', '%' . $this->filter_pencarian . '%');
            })
            ->when($this->filter_rs, function ($query) {
                return $query->where('nama_rs', $this->filter_rs);
            })
            ->orderBy('nama', 'asc')
            ->get();
        // dd($query);
        return view('excel.excel_item-ruangan', compact('query', 'rs'));
    }
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $cellRange = 'A4:H' . $lastRow;
                $event->sheet->getDelegate()->getStyle('A4:H4')->getFont()->setName('Times New Roman')->setBold(true)->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2')->getFont()->setBold(true);
    }

}
