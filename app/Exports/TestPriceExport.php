<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\TestPrice;

class TestPriceExport implements FromView
{
    public function view(): View
    {
        return view('admin.prices._tests_export', [
            'tests' => TestPrice::where('branch_id',session('branch_id'))->get(),
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