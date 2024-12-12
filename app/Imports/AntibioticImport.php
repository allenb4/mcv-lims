<?php

namespace App\Imports;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Antibiotic;

class AntibioticImport implements ToModel,WithStartRow,WithValidation,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Doctor|null
     */
    public function model(array $row)
    {
        Antibiotic::create([
            'name'=>$row['name'],
            'shortcut'=>$row['name'],
            'commercial_name'=>$row['commercial_name']
        ]);
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
            'name'=>'required',
        ];
    }

   
}