<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PurchaseRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Purchase;
use DataTables;

class PurchasesController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_purchase',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_purchase',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_purchase',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_purchase',   ['only' => ['destroy','bulk_delete']]);
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
            $model=Purchase::with('branch','supplier');

            return DataTables::eloquent($model)
            ->addColumn('action',function($purchase){
                return view('admin.inventory.purchases._action',compact('purchase'));
            })
            ->editColumn('total',function($purchase){
                return formated_price($purchase['total']);
            })
            ->editColumn('paid',function($purchase){
                return formated_price($purchase['paid']);
            })
            ->editColumn('due',function($purchase){
                return formated_price($purchase['due']);
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }

        return view('admin.inventory.purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.inventory.purchases.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(purchaseRequest $request)
    {
        $purchase=Purchase::create([
            'date'=>$request['date'],
            'branch_id'=>$request['branch_id'],
            'supplier_id'=>$request['supplier_id'],
            'subtotal'=>$request['subtotal'],
            'tax'=>$request['tax'],
            'total'=>$request['total'],
            'paid'=>$request['paid'],
            'due'=>$request['due'],
            'note'=>$request['note'],
        ]);

        //products
        if($request->has('products'))
        {
            foreach($request['products'] as $product)
            {
                $purchase->products()->create([
                    'branch_id'=>$request['branch_id'],
                    'product_id'=>$product['id'],
                    'price'=>$product['price'],
                    'quantity'=>$product['quantity'],
                    'total_price'=>$product['total_price'],
                ]);
            }
        }

        //payments
        if($request->has('payments'))
        {
            foreach($request['payments'] as $payment)
            {
                $purchase->payments()->create([
                    'date'=>$payment['date'],
                    'amount'=>$payment['amount'],
                    'payment_method_id'=>$payment['payment_method_id']
                ]);
            }
        }

        session()->flash('success',__('Product created successfully'));
        return redirect()->route('admin.inventory.purchases.index');
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
        $purchase=Purchase::findOrFail($id);

        return view('admin.inventory.purchases.edit',compact('purchase'));
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
        $purchase=Purchase::findOrFail($id);

        $purchase->update([
            'date'=>$request['date'],
            'branch_id'=>$request['branch_id'],
            'supplier_id'=>$request['supplier_id'],
            'subtotal'=>$request['subtotal'],
            'tax'=>$request['tax'],
            'total'=>$request['total'],
            'paid'=>$request['paid'],
            'due'=>$request['due'],
            'note'=>$request['note'],
        ]);

        //products
        $purchase->products()->delete();
        if($request->has('products'))
        {
            foreach($request['products'] as $product)
            {
                $purchase->products()->create([
                    'branch_id'=>$request['branch_id'],
                    'product_id'=>$product['id'],
                    'price'=>$product['price'],
                    'quantity'=>$product['quantity'],
                    'total_price'=>$product['total_price'],
                ]);
            }
        }

        //payments
        $purchase->payments()->delete();
        if($request->has('payments'))
        {
            foreach($request['payments'] as $payment)
            {
                $purchase->payments()->create([
                    'date'=>$payment['date'],
                    'amount'=>$payment['amount'],
                    'payment_method_id'=>$payment['payment_method_id']
                ]);
            }
        }

        session()->flash('success',__('Product updated successfully'));
        return redirect()->route('admin.inventory.purchases.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase=Purchase::findOrFail($id);

        //delete relations
        $purchase->products()->delete();
        $purchase->payments()->delete();
        $purchase->delete();

        session()->flash('success',__('Product deleted successfully'));
        return redirect()->route('admin.inventory.purchases.index');
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
            $purchase=Purchase::find($id);
            //delete relations
            $purchase->products()->delete();
            $purchase->payments()->delete();
            $purchase->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.inventory.purchases.index');
    }
}
