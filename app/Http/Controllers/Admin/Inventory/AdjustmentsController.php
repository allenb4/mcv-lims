<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdjustmentRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Adjustment;
use DataTables;
class AdjustmentsController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_adjustment',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_adjustment',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_adjustment',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_adjustment',   ['only' => ['destroy','bulk_delete']]);
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
            $model=Adjustment::with('branch');

            return DataTables::eloquent($model)
            ->addColumn('action',function($adjustment){
                return view('admin.inventory.adjustments._action',compact('adjustment'));
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }

        return view('admin.inventory.adjustments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.inventory.adjustments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdjustmentRequest $request)
    {
        $adjustment=Adjustment::create([
            'date'=>$request['date'],
            'branch_id'=>$request['branch_id'],
        ]);

        //products
        if($request->has('products'))
        {
            foreach($request['products'] as $product)
            {
                $adjustment->products()->create([
                    'branch_id'=>$request['branch_id'],
                    'product_id'=>$product['id'],
                    'quantity'=>$product['quantity'],
                    'type'=>$product['type'],
                    'note'=>$product['note']
                ]);
            }
        }

        session()->flash('success','Adjustment created successfully');
        return redirect()->route('admin.inventory.adjustments.index');
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
        $adjustment=Adjustment::findOrFail($id);

        return view('admin.inventory.adjustments.edit',compact('adjustment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdjustmentRequest $request, $id)
    {
        $adjustment=Adjustment::findOrFail($id);

        $adjustment->update([
            'date'=>$request['date'],
            'branch_id'=>$request['branch_id'],
        ]);

        //products
        $adjustment->products()->delete();
        if($request->has('products'))
        {
            foreach($request['products'] as $product)
            {
                $adjustment->products()->create([
                    'branch_id'=>$request['branch_id'],
                    'product_id'=>$product['id'],
                    'quantity'=>$product['quantity'],
                    'type'=>$product['type'],
                    'note'=>$product['note']
                ]);
            }
        }

        session()->flash('success','Adjustment updated successfully');
        return redirect()->route('admin.inventory.adjustments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adjustment=Adjustment::findOrFail($id);
        
        $adjustment->products()->delete();
        $adjustment->delete();

        session()->flash('success','Adjustment deleted successfully');
        return redirect()->route('admin.inventory.adjustments.index');
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
            $adjustment=Adjustment::find($id);
            $adjustment->products()->delete();
            $adjustment->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.inventory.adjustments.index');
    }
}
