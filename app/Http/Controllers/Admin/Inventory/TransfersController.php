<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\TransferRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Transfer;
use App\Models\TransferProduct;
use DataTables;

class TransfersController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_transfer',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_transfer',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_transfer',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_transfer',   ['only' => ['destroy','bulk_delete']]);
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
            $model=Transfer::with('from_branch','to_branch');

            return DataTables::eloquent($model)
            ->addColumn('action',function($transfer){
                return view('admin.inventory.transfers._action',compact('transfer'));
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }

        return view('admin.inventory.transfers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.inventory.transfers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferRequest $request)
    {
        $transfer=Transfer::create([
            'date'=>$request['date'],
            'from_branch_id'=>$request['from_branch_id'],
            'to_branch_id'=>$request['to_branch_id']
        ]);

        //products
        if($request->has('products'))
        {
            foreach($request['products'] as $product)
            {
                $transfer->products()->create([
                    'product_id'=>$product['id'],
                    'quantity'=>$product['quantity'],
                    'from_branch_id'=>$request['from_branch_id'],
                    'to_branch_id'=>$request['to_branch_id']
                ]);
            }
        }

        session()->flash('success',__('Transfer created successfully'));
        return redirect()->route('admin.inventory.transfers.index');
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
        $transfer=Transfer::findOrFail($id);

        return view('admin.inventory.transfers.edit',compact('transfer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransferRequest $request, $id)
    {
        $transfer=Transfer::findOrFail($id);

        $transfer->update([
            'date'=>$request['date'],
            'from_branch_id'=>$request['from_branch_id'],
            'to_branch_id'=>$request['to_branch_id']
        ]);

        //products
        $transfer->products()->delete();
        if($request->has('products'))
        {
            foreach($request['products'] as $product)
            {
                $transfer->products()->create([
                    'product_id'=>$product['id'],
                    'quantity'=>$product['quantity'],
                    'from_branch_id'=>$request['from_branch_id'],
                    'to_branch_id'=>$request['to_branch_id']
                ]);
            }
        }

        session()->flash('success',__('Transfer updated successfully'));
        return redirect()->route('admin.inventory.transfers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transfer=Transfer::findOrFail($id);
        $transfer->products()->delete();
        $transfer->delete();

        session()->flash('success',__('Transfer deleted successfully'));
        return redirect()->route('admin.inventory.transfers.index');
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
            $transfer=Transfer::find($id);
            $transfer->products()->delete();
            $transfer->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.inventory.transfers.index');
    }
}
