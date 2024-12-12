<?php

namespace App\Exports;

use App\Models\Test;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TestExport implements FromView
{
    public function view(): View
    {
        return view('admin.tests._export', [
            'tests' => Test::where('parent_id',0)->get()
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