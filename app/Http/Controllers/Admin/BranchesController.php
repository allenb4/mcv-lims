<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BranchRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Branch;
use App\Models\UserBranch;
use App\Models\Test;
use App\Models\Culture;
use App\Models\Package;
use App\Models\TestPrice;
use App\Models\CulturePrice;
use App\Models\PackagePrice;
use App\Models\Product;
use DataTables;

class BranchesController extends Controller
{
     /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_branch',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_branch',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_branch',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_branch',   ['only' => ['destroy','bulk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.branches.index');
    }

    /**
    * get branches datatable
    *
    * @access public
    * @var  @Request $request
    */
    public function ajax(Request $request)
    {
        $model=Branch::query();

        return DataTables::eloquent($model)
        ->addColumn('action',function($branch){
            return view('admin.branches._action',compact('branch'));
        })
        ->addColumn('bulk_checkbox',function($item){
            return view('partials._bulk_checkbox',compact('item'));
        })
        ->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products=Product::all();
        return view('admin.branches.create',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request)
    {
        $branch=Branch::create($request->except('_token','_method','products'));

        UserBranch::create([
            'branch_id'=>$branch['id'],
            'user_id'=>1
        ]);

        //tests
        $tests=Test::where('parent_id',0)->orWhere('separated',true)->get();
        foreach($tests as $test)
        {
            TestPrice::create([
                'test_id'=>$test['id'],
                'branch_id'=>$branch['id'],
                'price'=>$test['price']
            ]);
        }

        //cultures
        $cultures=Culture::all();
        foreach($cultures as $culture)
        {
            CulturePrice::create([
                'culture_id'=>$culture['id'],
                'branch_id'=>$branch['id'],
                'price'=>$test['price']
            ]);
        }

        //packages
        $packages=Package::all();
        foreach($packages as $package)
        {
            PackagePrice::create([
                'package_id'=>$package['id'],
                'branch_id'=>$branch['id'],
                'price'=>$test['price']
            ]);
        }

        //assign products
        if($request->has('products'))
        {
            foreach($request['products'] as $product_id=>$product)
            {
                $branch->products()->create([
                    'product_id'=>$product_id,
                    'initial_quantity'=>$product['initial_quantity'],
                    'alert_quantity'=>$product['alert_quantity'],
                ]);
            }
        }
        

        session()->flash('success',__('Branch created successfully'));

        return redirect()->route('admin.branches.index');
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
        $branch=Branch::find($id);

        return view('admin.branches.edit',compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, $id)
    {
        $branch=Branch::findOrFail($id);
        $branch->update($request->except('_token','_method','products'));

        //assign products
        $branch->products()->delete();
        if($request->has('products'))
        {
            foreach($request['products'] as $product_id=>$product)
            {
                $branch->products()->create([
                    'product_id'=>$product_id,
                    'initial_quantity'=>$product['initial_quantity'],
                    'alert_quantity'=>$product['alert_quantity'],
                ]);
            }
        }
        
        
        session()->flash('success',__('Branch updated successfully'));

        return redirect()->route('admin.branches.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branches_count=Branch::count();

        if($branches_count==1)
        {
            session()->flash('failed',__('You can\'t delete the only existing branch'));

            return redirect()->route('admin.branches.index');
        }
        
        $branch=Branch::findOrFail($id);
        $branch->tests()->delete();
        $branch->cultures()->delete();
        $branch->packages()->delete();
        $branch->user_branches()->delete();
        $branch->products()->delete();
        $branch->delete();

        session()->flash('success',__('Branch deleted successfully'));

        return redirect()->route('admin.branches.index');
    }

     /**
     * Bulk delete
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_delete(BulkActionRequest $request)
    {
        $branches_count=Branch::count();

        if($branches_count==1)
        {
            session()->flash('failed',__('You can\'t delete the only existing branch'));

            return redirect()->route('admin.branches.index');
        }

        foreach($request['ids'] as $id)
        {
            $branch=Branch::findOrFail($id);
            $branch->tests()->delete();
            $branch->cultures()->delete();
            $branch->packages()->delete();
            $branch->user_branches()->delete();
            $branch->products()->delete();
            $branch->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.branches.index');
    }
}
