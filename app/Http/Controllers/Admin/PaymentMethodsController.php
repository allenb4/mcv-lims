<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PaymentMethodRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\PaymentMethod;
use DataTables;
class PaymentMethodsController extends Controller
{
     /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_payment_method',     ['only' => ['index', 'show']]);
        $this->middleware('can:create_payment_method',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_payment_method',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_payment_method',   ['only' => ['destroy','bulk_delete']]);
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
            $model=PaymentMethod::query();

            return DataTables::eloquent($model)
            ->addColumn('action',function($payment_method){
                return view('admin.accounting.payment_methods._action',compact('payment_method'));
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }

        return view('admin.accounting.payment_methods.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.accounting.payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentMethodRequest $request)
    {
        PaymentMethod::create([
            'name'=>$request['name']
        ]);

        session()->flash('success',__('Payment method created successfully'));

        return redirect()->route('admin.payment_methods.index');
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
        $payment_method=PaymentMethod::findOrFail($id);
        return view('admin.accounting.payment_methods.edit',compact('payment_method'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethodRequest $request, $id)
    {
        $payment_method=PaymentMethod::findOrFail($id);

        $payment_method->update([
            'name'=>$request['name']
        ]);

        session()->flash('success',__('Payment method updated successfully'));

        return redirect()->route('admin.payment_methods.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment_method=PaymentMethod::findOrFail($id);
        $payment_method->delete();

        session()->flash('success',__('Payment method deleted successfully'));

        return redirect()->route('admin.payment_methods.index');
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
            $payment_method=PaymentMethod::find($id);
            $payment_method->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.payment_methods.index');
    }
}
