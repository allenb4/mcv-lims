<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Test;
use App\Models\Culture;
use App\Models\Branch;
use App\Models\PackagePrice;
use App\Models\ContractPrice;
use App\Models\Contract;
use DataTables;

class PackagesController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_package',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_package',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_package',     ['only' => ['edit', 'updae']]);
        $this->middleware('can:delete_package',   ['only' => ['destroy','bulk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $model=Package::with('tests','cultures','category');
            
            return DataTables::eloquent($model)
            ->editColumn('price',function($package){
                return formated_price($package['price']);
            })
            ->addColumn('tests',function($package){
                return view('admin.packages._tests',compact('package'));
            })
            ->addColumn('action',function($package){
                return view('admin.packages._action',compact('package'));
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }

        return view('admin.packages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageRequest $request)
    {
        $package=Package::create([
            'name'=>$request['name'],
            'shortcut'=>$request['shortcut'],
            'price'=>$request['price'],
            'precautions'=>$request['precautions']
        ]);

        //create package price
        $branches=Branch::all();
        foreach($branches as $branch)
        {
            PackagePrice::create([
                'branch_id'=>$branch['id'],
                'package_id'=>$package['id'],
                'price'=>$request['price']
            ]);
        }

        //contracts prices
        $contracts=Contract::all();
        foreach($contracts as $contract)
        {
            $contract->packages()->create([
                'priceable_type'=>'App\Models\Package',
                'priceable_id'=>$package['id'],
                'price'=>($contract['discount_type']==1)?($request['price']*$contract['discount_percentage']/100):$request['price']
            ]);
        }

        //tests
        if($request->has('tests'))
        {
            foreach($request['tests'] as $test_id)
            {
                $test=Test::find($test_id);
                
                if(isset($test))
                {
                    $package->tests()->create([
                        'testable_id'=>$test['id'],
                        'testable_type'=>'App\Models\Test'
                    ]);
                }
            }
        }

        //cultures
        if($request->has('cultures'))
        {
            foreach($request['cultures'] as $culture_id)
            {
                $culture=Culture::find($culture_id);

                if(isset($culture))
                {
                    $package->cultures()->create([
                        'testable_id'=>$culture['id'],
                        'testable_type'=>'App\Models\Culture'
                    ]);
                }
            }
        }

        session()->flash('success',__('Package created successfully'));

        return redirect()->route('admin.packages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package=Package::findOrFail($id);

        return view('admin.packages.edit',compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $package=Package::findOrFail($id);

        //update package
        $package->update([
            'name'=>$request['name'],
            'shortcut'=>$request['shortcut'],
            'price'=>$request['price'],
            'precautions'=>$request['precautions']
        ]);

        //update contract prices
        $contracts=Contract::all();
        foreach($contracts as $contract)
        {
            $package_contract=ContractPrice::firstOrCreate([
                'contract_id'=>$contract['id'],
                'priceable_type'=>'App\Models\Package',
                'priceable_id'=>$id,
            ]);

            if($contract['discount_type']==1)
            {
                $package_contract->update([
                    'price'=>($request['price']*$contract['discount_percentage']/100)
                ]);
            }
        }

        $package->tests()->delete();
        $package->cultures()->delete();

        if($request->has('tests'))
        {
            foreach($request['tests'] as $test_id)
            {
                $test=Test::find($test_id);
                
                if(isset($test))
                {
                    $package->tests()->create([
                        'testable_id'=>$test['id'],
                        'testable_type'=>'App\Models\Test'
                    ]);
                }
            }
        }

        if($request->has('cultures'))
        {
            foreach($request['cultures'] as $culture_id)
            {
                $culture=Culture::find($culture_id);

                if(isset($culture))
                {
                    $package->cultures()->create([
                        'testable_id'=>$culture['id'],
                        'testable_type'=>'App\Models\Culture'
                    ]);
                }
            }
        }

        session()->flash('success',__('Package updated successfully'));

        return redirect()->route('admin.packages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $package=Package::findOrFail($id);

        $package->tests()->delete();
        $package->cultures()->delete();
        $package->prices()->delete();
        $package->contract_prices()->delete();
        $package->delete();

        session()->flash('success',__('Package deleted successfully'));

        return redirect()->route('admin.packages.index');
    }

    /**
     * Bulk delete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_delete(BulkActionRequest $request)
    {
        foreach($request['ids'] as $id)
        {
            $package=Package::find($id);
            $package->tests()->delete();
            $package->cultures()->delete();
            $package->prices()->delete();
            $package->contract_prices()->delete();
            $package->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.packages.index');
    }

}
