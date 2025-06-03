<?php
namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\MasalahModel;

class TestingExport implements FromView
{
    public function view(): View
    {
        return view('excel.excel_masalah', [
            'masalah' => MasalahModel::all()
        ]);
    }
}?>