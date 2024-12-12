<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SupplierRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Supplier;
use DataTables;
class SuppliersController extends Controller
{
    
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_supplier',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_supplier',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_supplier',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_supplier',   ['only' => ['destroy','bulk_delete']]);
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
            $model=Supplier::query();

            return DataTables::eloquent($model)
            ->addColumn('action',function($supplier){
                return view('admin.inventory.suppliers._action',compact('supplier'));
            })
            ->editColumn('total',function($supplier){
                return formated_price($supplier['total']);
            })
            ->editColumn('paid',function($supplier){
                return formated_price($supplier['paid']);
            })
            ->editColumn('due',function($supplier){
                return formated_price($supplier['due']);
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }

        return view('admin.inventory.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.inventory.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        Supplier::create($request->except('_token'));

        session()->flash('success',__('Supplier created successfully'));

        return redirect()->route('admin.inventory.suppliers.index');
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
        $supplier=Supplier::findOrFail($id);

        return view('admin.inventory.suppliers.edit',compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        $supplier=Supplier::findOrFail($id);

        $supplier->update($request->except('_token'));

        session()->flash('success',__('Supplier updated successfully'));

        return redirect()->route('admin.inventory.suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier=Supplier::findOrFail($id);
        $supplier->delete();

        session()->flash('success',__('Supplier deleted successfully'));

        return redirect()->route('admin.inventory.suppliers.index');
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
            $supplier=Supplier::find($id);
            $supplier->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.inventory.suppliers.index');
    }
}
