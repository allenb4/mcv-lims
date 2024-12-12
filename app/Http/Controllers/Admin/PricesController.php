<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ExcelImportRequest;
use App\Exports\TestPriceExport;
use App\Exports\CulturePriceExport;
use App\Exports\PackagePriceExport;
use App\Imports\TestPriceImport;
use App\Imports\CulturePriceImport;
use App\Imports\PackagePriceImport;
use App\Models\Test;
use App\Models\Culture;
use App\Models\Package;
use App\Models\TestPrice;
use App\Models\CulturePrice;
use App\Models\PackagePrice;
use DataTables;
use Excel;

class PricesController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_test_prices',     ['only' => ['tests']]);
        $this->middleware('can:update_test_prices',   ['only' => ['tests_submit']]);
        $this->middleware('can:view_package_prices',     ['only' => ['packages']]);
        $this->middleware('can:update_pacakage_prices',   ['only' => ['pacakages_submit']]);
        $this->middleware('can:view_culture_prices',     ['only' => ['cultures']]);
        $this->middleware('can:update_culture_prices',   ['only' => ['cultures_submit']]);
    }

    /**
     * tests price list
     *
     * @return \Illuminate\Http\Response
     */
    public function tests(Request $request)
    {
        if($request->ajax())
        {
            $model=TestPrice::with('test.category')
                            ->where('branch_id',session('branch_id'));              
    
            return DataTables::eloquent($model)
                                ->editColumn('price',function($test){
                                    return view('admin.prices._test_price',compact('test'));
                                })
                                ->toJson();
        }

        return view('admin.prices.tests');
    }

    /**
     * update tests prices
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function tests_submit(Request $request)
    {        
        if($request->has('tests'))
        {
            foreach($request['tests'] as $key=>$value)
            {
                TestPrice::where('id',$key)
                            ->where('branch_id',session('branch_id'))
                            ->update([
                                'price'=>$value
                            ]);
            }
        }

        session()->flash('success',__('Tests prices updated successfully'));

        return redirect()->back();
    }

     /**
     * cultures price list
     *
     * @return \Illuminate\Http\Response
     */
    public function cultures(Request $request)
    {
        if($request->ajax())
        {
            $model=CulturePrice::with('culture.category')
                            ->where('branch_id',session('branch_id'));              
    
            return DataTables::eloquent($model)
                                ->editColumn('price',function($culture){
                                    return view('admin.prices._culture_price',compact('culture'));
                                })
                                ->toJson();
        }

        return view('admin.prices.cultures');
    }

     /**
     * update cultures prices
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cultures_submit(Request $request)
    {
        if($request->has('cultures'))
        {
            foreach($request['cultures'] as $key=>$value)
            {
                CulturePrice::where('id',$key)
                            ->where('branch_id',session('branch_id'))
                            ->update([
                                'price'=>$value
                            ]);
            }
        }

        session()->flash('success',__('Cultures prices updated successfully'));

        return redirect()->back();
    }

    /**
     * packges price list
     *
     * @return \Illuminate\Http\Response
     */
    public function packages(Request $request)
    {
        if($request->ajax())
        {
            $model=PackagePrice::with('package.category')
                            ->where('branch_id',session('branch_id'));              
    
            return DataTables::eloquent($model)
                            ->editColumn('price',function($package){
                                return view('admin.prices._package_price',compact('package'));
                            })
                            ->toJson();
        }

        return view('admin.prices.packages');
    }

     /**
     * update packages prices
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function packages_submit(Request $request)
    {
        if($request->has('packages'))
        {
            foreach($request['packages'] as $key=>$value)
            {
                PackagePrice::where('id',$key)->update([
                    'price'=>$value
                ]);
            }
        }

        session()->flash('success',__('Packages prices updated successfully'));

        return redirect()->back();
    }


    public function tests_prices_export()
    {
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new TestPriceExport, 'tests_prices.xlsx');
    }

    public function cultures_prices_export()
    {
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new CulturePriceExport, 'cultures_prices.xlsx');
    }

    public function packages_prices_export()
    {
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new PackagePriceExport, 'packages_prices.xlsx');
    }


    /**
    * Import tests prices
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function tests_prices_import(ExcelImportRequest $request)
    {
        if($request->hasFile('import'))
        {
            ob_end_clean(); // this
            ob_start(); // and this

            //import tests
            Excel::import(new TestPriceImport, $request->file('import'));        
        }

        session()->flash('success',__('Tests prices imported successfully'));

        return redirect()->back();
    }

    /**
    * Import cultures prices
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function cultures_prices_import(ExcelImportRequest $request)
    {
        if($request->hasFile('import'))
        {
            ob_end_clean(); // this
            ob_start(); // and this

            //import tests
            Excel::import(new CulturePriceImport, $request->file('import'));        
        }

        session()->flash('success',__('Cultures prices imported successfully'));

        return redirect()->back();
    }

    /**
    * Import packages prices
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function packages_prices_import(ExcelImportRequest $request)
    {
        if($request->hasFile('import'))
        {
            ob_end_clean(); // this
            ob_start(); // and this

            //import packages
            Excel::import(new PackagePriceImport, $request->file('import'));        
        }

        session()->flash('success',__('Packages prices imported successfully'));

        return redirect()->back();
    }
}
