<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\CultureExport;
use App\Imports\CultureImport;
use App\Http\Requests\Admin\CultureRequest;
use App\Http\Requests\Admin\ExcelImportRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Culture;
use App\Models\Branch;
use App\Models\ContractPrice;
use App\Models\CulturePrice;
use App\Models\Contract;
use DataTables;
use Excel;

class CulturesController extends Controller
{

    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_culture',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_culture',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_culture',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_culture',   ['only' => ['destroy','bulk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cultures=Culture::all();
        return view('admin.cultures.index',compact('cultures'));
    }

    /**
    * get cultures datatable
    *
    * @access public
    * @var  @Request $request
    */
    public function ajax(Request $request)
    {
        $model=Culture::with('category');

        return DataTables::eloquent($model)
        ->editColumn('price',function($culture){
            return formated_price($culture['price']);
        })
        ->addColumn('action',function($culture){
            return view('admin.cultures._action',compact('culture'));
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
        return view('admin.cultures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CultureRequest $request)
    {
        $branches=Branch::all();

        $culture=Culture::create($request->except('_token','comments','consumptions'));

        //assign prices to branches
        foreach($branches as $branch)
        {
            CulturePrice::create([
                'branch_id'=>$branch['id'],
                'culture_id'=>$culture['id'],
                'price'=>$request['price']
            ]);
        }

        //contracts prices
        $contracts=Contract::all();
        foreach($contracts as $contract)
        {
            $contract->cultures()->create([
                'priceable_type'=>'App\Models\Culture',
                'priceable_id'=>$culture['id'],
                'price'=>($contract['discount_type']==1)?($culture['price']*$contract['discount_percentage']/100):$culture['price']
            ]);
        }

        //comments
        if($request->has('comments'))
        {
            foreach($request['comments'] as $comment)
            {
                $culture->comments()->create([
                    'comment'=>$comment
                ]);
            }
        }

        //consumptions
        if($request->has('consumption'))
        {
            foreach($request['consumptions'] as $consumption)
            {
                $culture->consumptions()->create([
                   'product_id'=>$consumption['product_id'],
                   'quantity'=>$consumption['quantity']
               ]);
            }
        }

        session()->flash('success','Culture saved successfully');
        return redirect()->route('admin.cultures.index');
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
        $culture=Culture::findOrFail($id);
        return view('admin.cultures.edit',compact('culture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CultureRequest $request, $id)
    {
        $culture=Culture::findOrFail($id);
        $culture->update($request->except('_token','_method','comments','consumptions'));

        //contracts prices
        $contracts=Contract::all();
        foreach($contracts as $contract)
        {
            $culture_contract=ContractPrice::firstOrCreate([
                'contract_id'=>$contract['id'],
                'priceable_type'=>'App\Models\Culture',
                'priceable_id'=>$id,
            ]);

            if($contract['discount_type']==1)
            {
                $culture_contract->update([
                    'price'=>($request['price']*$contract['discount_percentage']/100)
                ]);
            }
        }

        //comments
        $culture->comments()->delete();
        if($request->has('comments'))
        {
            foreach($request['comments'] as $comment)
            {
                $culture->comments()->create([
                    'comment'=>$comment
                ]);
            }
        }

        //consumptions
        $culture->consumptions()->delete();
        if($request->has('consumptions'))
        {
            foreach($request['consumptions'] as $consumption)
            {
                $culture->consumptions()->create([
                   'product_id'=>$consumption['product_id'],
                   'quantity'=>$consumption['quantity']
               ]);
            }
        }

        session()->flash('success','Culture updated successfully');
        return redirect()->route('admin.cultures.index');
    }

    /**
    * Export cultures
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function export()
    {
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new CultureExport, 'cultures.xlsx');
    }

    /**
    * Import cultures
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function import(ExcelImportRequest $request)
    {
        if($request->hasFile('import'))
        {
            ob_end_clean(); // this
            ob_start(); // and this
            Excel::import(new CultureImport, $request->file('import'));
        }

        session()->flash('success',__('Cultures imported successfully'));

        return redirect()->back();
    }

    /**
    * Download cultures template
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function download_template()
    {
        ob_end_clean(); // this
        ob_start(); // and this
        return response()->download(storage_path('app/public/cultures_template.xlsx'),'cultures_template.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $culture=Culture::findOrFail($id);
        $culture->prices()->delete();
        $culture->contract_prices()->delete();
        $culture->delete();

        session()->flash('success','Culture deleted successfully');
        return redirect()->route('admin.cultures.index');
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
            $culture=Culture::find($id);
            $culture->prices()->delete();
            $culture->contract_prices()->delete();
            $culture->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.cultures.index');
    }
}
