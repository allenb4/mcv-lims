<?php

namespace App\Imports;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Patient;
use App\Models\Contract;
use Maatwebsite\Excel\Validators\Failure;

class PatientImport implements ToModel,WithStartRow,WithValidation,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Patient|null
     */
    public $startRow=0;
    public function model(array $row)
    {
        $this->startRow++;
        if(isset($row['id']))
        {
            $patient=Patient::find($row['id']);

            if(isset($row['contract'])&&!empty($row['contract']))
            {
                $contract=Contract::where('title',$row['contract'])->first();
            }

            if(isset($patient))
            {                             
                $patient->update([
                    'name'=>$row['name'],
                    'national_id'=>$row['national_id'],
                    'passport_no'=>$row['passport_no'],
                    'gender'=>$row['gender'],
                    'dob'=>$row['dob'],
                    'phone'=>$row['phone'],
                    'email'=>$row['email'],
                    'address'=>$row['address'],
                    'contract_id'=>(isset($contract))?$contract['id']:''
                ]);

            }
            else{
                $patient=Patient::create([
                    'name'=>$row['name'],
                    'national_id'=>$row['national_id'],
                    'passport_no'=>$row['passport_no'],
                    'gender'=>$row['gender'],
                    'dob'=>$row['dob'],
                    'phone'=>$row['phone'],
                    'email'=>$row['email'],
                    'address'=>$row['address'],
                    'contract_id'=>(isset($contract))?$contract['id']:''
                ]);

                patient_code($patient['id']);
            }
        }
        else{
            $patient=Patient::create([
                'name'=>$row['name'],
                'national_id'=>$row['national_id'],
                'passport_no'=>$row['passport_no'],
                'gender'=>$row['gender'],
                'dob'=>$row['dob'],
                'phone'=>$row['phone'],
                'email'=>$row['email'],
                'address'=>$row['address'],
                'contract_id'=>(isset($contract))?$contract['id']:''
            ]);

            patient_code($patient['id']);
        }
    }


    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }


    public $id='';
    public function rules(): array
    {
        return [
            'id'=>function($attribute, $value, $onFailure) {
               $this->id=$value;
            },
            'name'=>[
                'required',
                function($attribute, $value, $onFailure) {
                    $patient=Patient::where('name',$value)->where('id','!=',$this->id)->first();
                    if (isset($patient)) {
                         $onFailure('Name has already been taken');
                    }
                }
            ],
            'gender'=>[
                'required',
                Rule::in(['male','female']),
            ],
            'dob'=>'required|date',
            'phone'=>[
                'nullable',
                function($attribute, $value, $onFailure) {
                    $patient=Patient::where('phone',$value)->where('id','!=',$this->id)->first();
                    if (isset($patient)) {
                         $onFailure('Phone has already been taken');
                    }
                }
            ],
            'email'=>[
                'nullable',
                'email',
                function($attribute, $value, $onFailure) {
                    $patient=Patient::where('email',$value)->where('id','!=',$this->id)->first();
                    if (isset($patient)) {
                         $onFailure('Email has already been taken');
                    }
                }
            ],
            'national_id'=>[
                'nullable',
                function($attribute, $value, $onFailure) {
                    $patient=Patient::where('national_id',$value)->where('id','!=',$this->id)->first();
                    if (isset($patient)) {
                         $onFailure('National ID has already been taken');
                    }
                }
            ],
            'passport_no'=>[
                'nullable',
                function($attribute, $value, $onFailure) {
                    $patient=Patient::where('passport_no',$value)->where('id','!=',$this->id)->first();
                    if (isset($patient)) {
                         $onFailure('Passport no has already been taken');
                    }
                }
            ],
            'address'=>'nullable'
        ];
    }

   
}