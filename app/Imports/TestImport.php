<?php

namespace App\Imports;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Test;
use App\Models\Category;
use App\Models\Branch;
use App\Models\TestPrice;
use App\Models\ContractPrice;
use App\Models\Contract;

class TestImport implements ToModel,WithStartRow,WithValidation,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Test|null
     */
    public function model(array $row)
    {
        $category=Category::firstOrCreate([
            'name'=>$row['category']
        ]);

        $test=Test::firstOrCreate([
            'parent_id'=>0,
            'name'=>$row['test_name'],
            'shortcut'=>$row['test_name'],
            'category_id'=>$category['id']
        ]);

        if($test['price']==0)
        {
            $test->update([
                'price'=>isset($row['price'])?$row['price']:0
            ]);
        }

        $test=Test::find($test['id']);

        $component=Test::firstOrCreate([
            'name'=>$row['component_name'],
            'parent_id'=>$test['id'],
            'unit'=>$row['unit'],
            'type'=>'text'
        ]);

        if(isset($row['reference_range']))
        {
            $component->update([
                'reference_range'=>$row['reference_range']
            ]);
        }

        //refernece ranges
        if(isset($row['gender'])&&isset($row['age_from_days'])&&isset($row['age_to_days']))
        {
            if($row['age_to_days']>365)
            {
                $age_unit='year';
                $age_from=$row['age_from_days']/365;
                $age_to=$row['age_to_days']/365;
            }
            elseif($row['age_to_days']>30)
            {
                $age_unit='month';
                $age_from=$row['age_from_days']/30;
                $age_to=$row['age_to_days']/30;
            }
            else{
                $age_unit='day';
                $age_from=$row['age_from_days'];
                $age_to=$row['age_to_days'];
            }

            $component->reference_ranges()->firstOrCreate([
                'gender'=>($row['gender']=='male')?'male':'female',
                'age_unit'=>$age_unit,
                'age_from'=>(int)$age_from,
                'age_to'=>(int)$age_to,
                'age_from_days'=>$row['age_from_days'],
                'age_to_days'=>$row['age_to_days'],
                'normal_from'=>$row['normal_from'],
                'normal_to'=>$row['normal_to'],
                'critical_low_from'=>$row['critical_low_from'],
                'critical_high_from'=>$row['critical_high_from'],
            ]);
        }
        

        //branch prices
        $branches=Branch::all();
        foreach($branches as $branch)
        {
            $test_price=TestPrice::where([
                                    ['branch_id',$branch['id']],
                                    ['test_id',$test['id']],
                                ])->first();

            if(!isset($test_price))
            {
                $test_price=TestPrice::create([
                    'branch_id'=>$branch['id'],
                    'test_id'=>$test['id'],
                ]);

                $test_price->update([
                    'price'=>$test['price']
                ]);
            }
        }

        //contract prices
        $contracts=Contract::all();
        foreach($contracts as $contract)
        {
            $contract_price=ContractPrice::where([
                                ['contract_id',$contract['id']],
                                ['priceable_type','App\Models\Test'],
                                ['priceable_id',$test['id']],
                            ])->first();

            if(!isset($contract_price))
            {
                ContractPrice::create([
                                ['contract_id',$contract['id']],
                                ['priceable_type','App\Models\Test'],
                                ['priceable_id',$test['id']],
                            ]);

                $contract_price->update([
                    'price'=>($contract['discount_type']==1)?($contract['discount_percentage']*$test['price']/100):$test['price']
                ]);
            }
        }
    }


    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }


    public function rules(): array
    {
        return [
            'category'=>'required',
            'test_name'=>'required',
            'component_name'=>'required',
        ];
    }

   
}