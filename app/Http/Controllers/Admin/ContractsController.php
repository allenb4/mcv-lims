<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ContractRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Test;
use App\Models\Culture;
use App\Models\Package;
use App\Models\Contract;
use DataTables;

class ContractsController extends Controller
{
    
     /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_contract',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_contract',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_contract',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_contract',   ['only' => ['destroy','bulk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.contracts.index');
    }

    /**
    * get antibiotics datatable
    *
    * @access public
    * @var  @Request $request
    */
    public function ajax(Request $request)
    {
        $model=Contract::query();

        return DataTables::eloquent($model)
        ->editColumn('discount',function($contract){
            return $contract['discount'].' %';
        })
        ->addColumn('action',function($contract){
            return view('admin.contracts._action',compact('contract'));
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
        $tests=Test::where('parent_id',0)->orWhere('separated',true)->get();
        $cultures=Culture::all();
        $packages=Package::all();

        return view('admin.contracts.create',compact('tests','cultures','packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractRequest $request)
    {
       $contract=Contract::create([
        'title'=>$request['title'],
        'description'=>$request['description'],
        'discount_type'=>$request['discount_type'],
        'discount_percentage'=>$request['discount_percentage']
       ]);

       if($request->has('tests'))
       {
           foreach($request['tests'] as $id=>$price)
           {
               $contract->tests()->create([
                   'priceable_type'=>'App\Models\Test',
                   'priceable_id'=>$id,
                   'price'=>$price
               ]);
           }
       }

       if($request->has('cultures'))
       {
           foreach($request['cultures'] as $id=>$price)
           {
               $contract->cultures()->create([
                   'priceable_type'=>'App\Models\Culture',
                   'priceable_id'=>$id,
                   'price'=>$price
               ]);
           }
       }

       if($request->has('packages'))
       {
           foreach($request['packages'] as $id=>$price)
           {
               $contract->packages()->create([
                   'priceable_type'=>'App\Models\Package',
                   'priceable_id'=>$id,
                   'price'=>$price
               ]);
           }
       }

       session()->flash('success',__('Contract created successfully'));

       return redirect()->route('admin.contracts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contract=Contract::findOrFail($id);

        return view('admin.contracts.edit',compact('contract'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContractRequest $request, $id)
    {
       $contract=Contract::findOrFail($id);
       $contract->update([
           'title'=>$request['title'],
           'description'=>$request['description'],
           'discount_type'=>$request['discount_type'],
           'discount_percentage'=>$request['discount_percentage']
       ]);

       $contract->tests()->delete();
       if($request->has('tests'))
       {
           foreach($request['tests'] as $id=>$price)
           {
               $contract->tests()->create([
                   'priceable_type'=>'App\Models\Test',
                   'priceable_id'=>$id,
                   'price'=>$price
               ]);
           }
       }

       $contract->cultures()->delete();
       if($request->has('cultures'))
       {
           foreach($request['cultures'] as $id=>$price)
           {
               $contract->cultures()->create([
                   'priceable_type'=>'App\Models\Culture',
                   'priceable_id'=>$id,
                   'price'=>$price
               ]);
           }
       }

       $contract->packages()->delete();
       if($request->has('packages'))
       {
           foreach($request['packages'] as $id=>$price)
           {
               $contract->packages()->create([
                   'priceable_type'=>'App\Models\Package',
                   'priceable_id'=>$id,
                   'price'=>$price
               ]);
           }
       }

       session()->flash('success',__('Contract updated successfully'));

       return redirect()->route('admin.contracts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract=Contract::findOrFail($id);
        $contract->prices()->delete();
        $contract->delete();

        session()->flash('success',__('Contract deleted successfully'));

        return redirect()->route('admin.contracts.index');
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
            $contract=Contract::findOrFail($id);
            $contract->prices()->delete();
            $contract->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.contracts.index');
    }
}
