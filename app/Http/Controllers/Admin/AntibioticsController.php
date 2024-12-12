<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Antibiotic;
use App\Http\Requests\Admin\AntibioticRequest;
use App\Http\Requests\Admin\ExcelImportRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use App\Imports\AntibioticImport;
use App\Exports\AntibioticExport;
use DataTables;
use Excel;
class AntibioticsController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_antibiotic',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_antibiotic',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_antibiotic',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_antibiotic',   ['only' => ['destroy','bulk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.antibiotics.index');
    }

    /**
    * get antibiotics datatable
    *
    * @access public
    * @var  @Request $request
    */
    public function ajax(Request $request)
    {
        $model=Antibiotic::query();

        return DataTables::eloquent($model)
        ->addColumn('action',function($antibiotic){
            return view('admin.antibiotics._action',compact('antibiotic'));
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
        return view('admin.antibiotics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AntibioticRequest $request)
    {
        Antibiotic::create($request->except('_token'));
        session()->flash('success','Antibiotic saved successfully');
        return redirect()->route('admin.antibiotics.index');
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
        $antibiotic=Antibiotic::findOrFail($id);
        return view('admin.antibiotics.edit',compact('antibiotic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AntibioticRequest $request, $id)
    {
        $antibiotic=Antibiotic::findOrFail($id);
        $antibiotic->update($request->except('_token','_method'));
        session()->flash('success','Antibiotic updated successfully');
        return redirect()->route('admin.antibiotics.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $antibiotic=Antibiotic::findOrFail($id);
        $antibiotic->delete();
        session()->flash('success','Antibiotic deleted successfully');
        return redirect()->route('admin.antibiotics.index');
    }

    /**
    * Export antibiotics
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function export()
    {
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new AntibioticExport, 'antibiotics.xlsx');
    }

    /**
    * Import antibiotics
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function import(ExcelImportRequest $request)
    {
        Antibiotic::truncate();
        
        if($request->hasFile('import'))
        {
            ob_end_clean(); // this
            ob_start(); // and this
            Excel::import(new AntibioticImport, $request->file('import'));
        }

        session()->flash('success',__('Antibiotics imported successfully'));

        return redirect()->back();
    }

    /**
    * Download antibiotics template
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function download_template()
    {
        ob_end_clean(); // this
        ob_start(); // and this
        return response()->download(storage_path('app/public/antibiotics_template.xlsx'),'antibiotics_template.xlsx');
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
            $antibiotic=Antibiotic::findOrFail($id);
            $antibiotic->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.antibiotics.index');
    }
}
