<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Models\Category;
use DataTables;
class CategoriesController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_category',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_category',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_category',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_category',   ['only' => ['destroy','bulk_delete']]);
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
            $model=Category::query();

            return DataTables::eloquent($model)
            ->addColumn('action',function($category){
                return view('admin.categories._action',compact('category'));
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->toJson();
        }
        return view('admin.categories.index');
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category=Category::create($request->except('_token','_method','files'));
    
        session()->flash('success',__('Category created successfully'));

        return redirect()->route('admin.categories.index');
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
        $category=Category::findOrFail($id);
        return view('admin.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {

        $category=Category::findOrFail($id);
        $category->update($request->except('_token','_method','files'));

        session()->flash('success',__('Category updated successfully'));

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=Category::findOrFail($id);
        $category->delete();

        session()->flash('success',__('Category deleted successfully'));

        return redirect()->route('admin.categories.index');
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
            $category=Category::findOrFail($id);
            $category->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.categories.index');
    }
}
