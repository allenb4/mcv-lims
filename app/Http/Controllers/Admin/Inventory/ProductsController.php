<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Product;
use App\Models\Branch;
use DataTables;
class ProductsController extends Controller
{
    
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_product',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_product',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_product',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_product',   ['only' => ['destroy','bulk_delete']]);
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
            $model=Product::query();

            return DataTables::eloquent($model)
            ->addColumn('action',function($product){
                return view('admin.inventory.products._action',compact('product'));
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }

        return view('admin.inventory.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product_branches=Branch::all();
        return view('admin.inventory.products.create',compact('product_branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product=Product::create($request->except('_token','branch'));

        //branch products
        if($request->has('branch'))
        {
            foreach($request['branch'] as $branch_id=>$branch)
            {
                $product->branches()->create([
                    'branch_id'=>$branch_id,
                    'initial_quantity'=>$branch['initial_quantity'],
                    'alert_quantity'=>$branch['alert_quantity']
                ]);
            }
        }

        session()->flash('success',__('product created successfully'));

        return redirect()->route('admin.inventory.products.index');
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
        $product=Product::findOrFail($id);

        return view('admin.inventory.products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product=Product::findOrFail($id);

        $product->update($request->except('_token','branch'));

        //branch products
        $product->branches()->delete();
        if($request->has('branch'))
        {
            foreach($request['branch'] as $branch_id=>$branch)
            {
                $product->branches()->create([
                    'branch_id'=>$branch_id,
                    'initial_quantity'=>$branch['initial_quantity'],
                    'alert_quantity'=>$branch['alert_quantity']
                ]);
            }
        }

        session()->flash('success',__('product updated successfully'));

        return redirect()->route('admin.inventory.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $product->branches()->delete();
        $product->delete();

        session()->flash('success',__('product deleted successfully'));

        return redirect()->route('admin.inventory.products.index');
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
            $product=Product::find($id);
            $product->branches()->delete();
            $product->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.inventory.products.index');
    }

    public function product_alerts()
    {
        $product_alerts=\DB::select(\DB::raw('
        SELECT branches.id, branch_id , branches.name , product_name , Qty  , stock_alert
        FROM 
        (
        SELECT branch_products.branch_id , branch_products.product_id ,products.name product_name , sum(A.quantity) AS Qty  ,branch_products.alert_quantity  As stock_alert
        FROM
        (
            SELECT branch_id , product_id , initial_quantity as quantity
            FROM branch_products
            UNION ALL 
            SELECT branch_id , product_id , quantity
            FROM purchase_products                                     
            UNION ALL 
            SELECT branch_id , product_id , quantity*-1
            FROM product_consumptions
            UNION ALL 
            SELECT from_branch_id , product_id , quantity*-1
            FROM transfer_products
            UNION ALL
            SELECT to_branch_id , product_id , quantity
            FROM transfer_products
            UNION ALL  
            SELECT branch_id , product_id , CASE WHEN type = 1  THEN quantity ELSE quantity *-1  END AS quantity
            FROM adjustment_products
        ) AS A JOIN products ON products.id = A.product_id
        
        JOIN branch_products ON A.product_id = branch_products.product_id
        WHERE products.deleted_at IS NULL
        GROUP BY branch_id,product_id
        HAVING Qty <= branch_products.alert_quantity
        ) AS B LEFT JOIN branches ON B.branch_id = branches.id
        WHERE branches.deleted_at IS NULL
        '));

        return view('admin.inventory.products.product_alerts',compact('product_alerts'));
    }
}
