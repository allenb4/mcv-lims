<?php

use Illuminate\Database\Seeder;
use App\Models\Culture;
use App\Models\CulturePrice;
use App\Models\Branch;

class CulturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Culture::truncate();
        
        $culture=Culture::create([
            'category_id'=>1,
            'name'=>'Blood Culture',
            'price'=>'100'
        ]);

        CulturePrice::truncate();

        $branches=Branch::all();

        foreach($branches as $branch)
        {
            CulturePrice::create([
                'branch_id'=>$branch['id'],
                'culture_id'=>$culture['id'],
                'price'=>$culture['price']
            ]);
        }
    }
}
