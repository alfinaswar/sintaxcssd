<?php

namespace App\Exports;

use Auth;
use App\Models\DataInventaris;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use DB;

class PmExport implements FromView, WithEvents, WithStyles
{
    use Exportable;
    public function __construct(string $bulan, string $tahun, string $jenis)
    {
        $this->bulan = $bulan ?? '';
        $this->tahun = $tahun ?? '';
        $this->jenis = $jenis ?? '';
        // dd($tgl_akhir);
    }
    public function view(): View
    {
        $bulan1 = $this->bulan;
        // dd(Auth::user()->kodeRS);
        $tahun1 = $this->tahun;
        $data = DataInventaris::with([
            'DataMaintenance' => function ($query) use ($bulan1, $tahun1) {
                if (!is_null($bulan1)) {
                    $query->where('bulan', $bulan1);
                }
                if (!is_null($tahun1)) {
                    $query->whereYear('created_at', $tahun1);
                }
            }
        ])
            ->where('pengguna', $this->jenis)
            ->where('nama_rs', Auth::user()->kodeRS)
            ->get();
        // dd($data);
        return view('excel.excel_pm', compact('data'));
    }
    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();

                $cellRange = 'A1:O' . $lastRow;
                $event->sheet->getDelegate()->getStyle('A4:E4')->getFont()->setName('Times New Roman')->setBold(true)->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O2')->getFont()->setBold(true);
    }
}
