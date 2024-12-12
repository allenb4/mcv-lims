<?php

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Patient::truncate();

        Patient::create([
            'code'=>'1593914720',
            'name'=>'patient',
            'gender'=>'male',
            'dob'=>'1995-08-28',
            'phone'=>'00',
            'email'=>'patient@gmail.com',
            'address'=>'PH',
        ]);
    }
}
