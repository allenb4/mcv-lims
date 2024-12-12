<?php

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::truncate();

        Branch::create([
            'name'=>'Main Branch',
            'address'=>'USA',
            'phone'=>'00',
            'lat'=>'27.77',
            'lng'=>'30.88',
            'zoom_level'=>8
        ]);
    }
}
