<?php

namespace App\Exports;

use App\Models\PackagePrice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PackagePriceExport implements FromView
{
    public function view(): View
    {
        return view('admin.prices._packages_export', [
            'packages' => PackagePrice::where('branch_id',session('branch_id'))->get(),
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