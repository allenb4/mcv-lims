<?php

namespace App\Exports;

use App\Models\CulturePrice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CulturePriceExport implements FromView
{
    public function view(): View
    {
        return view('admin.prices._cultures_export', [
            'cultures' => CulturePrice::where('branch_id',session('branch_id'))->get(),
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