<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventarisKsoTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'nama_alat',
            'merk',
            'tipe',
            'no_sn',
            'vendor',
            'awal_kerjasama',
            'akhir_kerjasama',
            'kode_rs',
            'departemen',
            'unit',
            'jenis',
            'klasifikasi_risiko',
            'keterangan',
        ];
    }

    public function array(): array
    {
        return [
            [
                'HEMODIALYSIS MACHINE',
                'B. BRAUN',
                'DIALOG +',
                '223202',
                'PT. B BRAUN MEDICAL INDONESIA',
                '2025-04-05',
                '2027-04-05',
                auth()->check() ? auth()->user()->kodeRS : 'A',
                'PENUNJANG MEDIS',
                'HEMODIALISA',
                'Medis',
                'High Risk',
                'Laik Pakai',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
