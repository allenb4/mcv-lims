<?php

namespace App\Exports;

use App\Models\Antibiotic;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Branch;
use App\Models\Product;

class BranchProductExport implements FromView
{
    public function view(): View
    {
        $branch=Branch::findOrFail(request('branch_id'));
        
        $products=(request()->has('product_id'))?Product::whereIn('id',request('product_id'))->get():$products=Product::all();
                
        $branch_products=[];
        foreach($products as $product)
        {
            $initial=$product->branches()->where('branch_id',$branch['id'])->sum('initial_quantity');
            $purchases=$product->purchases()->where('branch_id',$branch['id'])->sum('quantity');
            $in=$product->adjustments()->where('type',1)->where('branch_id',$branch['id'])->sum('quantity');
            $out=$product->adjustments()->where('type',2)->where('branch_id',$branch['id'])->sum('quantity');
            $transfers_from=$product->transfers()->where('from_branch_id',$branch['id'])->sum('quantity');
            $transfers_to=$product->transfers()->where('to_branch_id',$branch['id'])->sum('quantity');
            $consumptions=$product->consumptions()->where('branch_id',$branch['id'])->sum('quantity');

            $stock_quantity=$initial+$purchases+$in+$transfers_to-$out-$transfers_from-$consumptions;

            $branch_products[]=['product'=>$product,'quantity'=>$stock_quantity];
        }

        return view('admin.reports._export_branch_products', [
            'products' => $branch_products,
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