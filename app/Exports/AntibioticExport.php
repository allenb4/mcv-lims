<?php

namespace App\Exports;

use App\Models\Antibiotic;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AntibioticExport implements FromView
{
    public function view(): View
    {
        return view('admin.antibiotics._export', [
            'antibiotics' => Antibiotic::all(),
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'I' =>  "0",
        ];
    }
}

?>