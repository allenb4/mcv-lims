<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Test;
use App\Models\GroupTest;
use App\Models\GroupCulture;
use App\Models\GroupTestResult;
use App\Models\GroupCultureResult;
use App\Models\GroupCultureOption;
use App\Models\Antibiotic;
use App\Models\Setting;
use App\Models\Patient;
use App\Models\TestOption;
use App\Models\Category;
use App\Models\TestReferenceRange;
use App\Http\Requests\Admin\UpdateCultureResultRequest;
use App\Http\Requests\Admin\UploadReportRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use App;
use DataTables;
use PDF;
class MedicalReportsController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_medical_report',     ['only' => ['index', 'show']]);
        $this->middleware('can:create_mediacl_report',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_medical_report',     ['only' => ['edit', 'update']]);
        $this->middleware('can:delete_medical_report',   ['only' => ['destroy','bulk_delete']]);
        $this->middleware('can:sign_medical_report',   ['only' => ['sign']]);
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
            $model=Group::query()
                    ->with('patient','tests','cultures','contract','signed_by_user','created_by_user')
                    ->where('branch_id',session('branch_id'));

            if($request['filter_status']!='')
            {
                $model->where('done',$request['filter_status']);
            }
            
            if($request['filter_barcode']!='')
            {
                $model->where('barcode',$request['filter_barcode']);
            }

            if($request['filter_created_by']!='')
            {
                $model->whereIn('created_by',$request['filter_created_by']);
            }

            if($request['filter_signed_by']!='')
            {
                $model->whereIn('signed_by',$request['filter_signed_by']);
            }

            if($request['filter_contract']!='')
            {
                $model->whereIn('contract_id',$request['filter_contract']);
            }

            if($request['filter_date']!='')
            {
                //format date
                $date=explode('-',$request['filter_date']);
                $from=date('Y-m-d',strtotime($date[0]));
                $to=date('Y-m-d 23:59:59',strtotime($date[1]));

                //select groups of date between
                ($date[0]==$date[1])?$model->whereDate('created_at',$from):$model->whereBetween('created_at',[$from,$to]);
            }
            
            return DataTables::eloquent($model)
            ->editColumn('patient.gender',function($group){
                return __(ucwords($group['patient']['gender']));
            })
            ->editColumn('tests',function($group){
                return view('admin.medical_reports._tests',compact('group'));
            })
            ->addColumn('signed',function($group){
                return view('admin.medical_reports._signed',compact('group'));
            })
            ->editColumn('done',function($group){
                return view('admin.medical_reports._status',compact('group'));
            })
            ->addColumn('action',function($group){
                return view('admin.medical_reports._action',compact('group'));
            })
            ->addColumn('bulk_checkbox',function($item){
                return view('partials._bulk_checkbox',compact('item'));
            })
            ->editColumn('created_at',function($group){
                return date('Y-m-d H:i',strtotime($group['created_at']));
            })
            ->toJson();
        }
        return view('admin.medical_reports.index');
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group=Group::where('branch_id',session('branch_id'))
                    ->findOrFail($id);
        $next=Group::where('id','>',$id)->orderBy('id','asc')->first();
        $previous=Group::where('id','<',$id)->orderBy('id','desc')->first();

        return view('admin.medical_reports.show',compact('group','next','previous'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete group
        $group=Group::findOrFail($id);
        $group->payments()->delete();

        //delete group tests
        $group_tests=GroupTest::where('group_id',$id)->get();

        //delete test results
        foreach($group_tests as $group_test)
        {
           GroupTestResult::where('group_test_id',$group_test['id'])->delete();
        }
        GroupTest::where('group_id',$id)->delete();

        //delete group cultures
        $group_cultures=GroupCulture::where('group_id',$id)->get();
        foreach($group_cultures as $group_culture)
        {
            GroupCultureResult::where('group_culture_id',$group_culture['id'])->delete();
        }
        GroupCulture::where('group_id',$id)->delete();
        
        //delete packages
        $group->packages()->delete();

        //delete consumption
        $group->consumptions()->delete();

        //delete group
        $group->delete();

        //return success
        session()->flash('success',__('Medical report deleted successfully'));
        return redirect()->route('admin.medical_reports.index');
    }

    /**
     * Generate report pdf
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request,$id)
    {
        $group=Group::findOrFail($id);

        if($group['uploaded_report'])
        {
            return redirect($group['report_pdf']);
        }

        //set null if no analysis or cultures selected
        if(empty($request['tests']))
        {
            $request['tests']=[-1];
        }
        if(empty($request['cultures']))
        {
            $request['cultures']=[-1];
        }

        //categories
        $categories=Category::all();

        foreach($categories as $category)
        {
            $tests=GroupTest::whereHas('test',function($query)use($category){
                return $query->where('category_id',$category['id']);
            })->where('group_id',$group['id'])->whereIn('id',$request['tests'])->get();

            $category['tests']=$tests->sortBy(function($test){
                                    return $test->test->components->count();
                                });

            $category['cultures']=GroupCulture::whereHas('culture',function($query)use($category){
                return $query->where('category_id',$category['id']);
            })->where('group_id',$group['id'])->whereIn('id',$request['cultures'])->get();
        }

        //find group
        $group=Group::with([
            'all_tests'=>function($q)use($request){
               return $q->whereIn('id',$request['tests']);
            },
            'all_cultures'=>function($q)use($request){
                return $q->whereIn('id',$request['cultures']);
            }
        ])->where('id',$id)->first();


        //generate pdf
        $data=['group'=>$group,'categories'=>$categories];
        \Log::info(['GENERATE REPORT PDF' => $data]);

        // GENERATE PDF is not yet working
        $pdf=generate_pdf($data);
        // dd($pdf);
        // return redirect($pdf);//return pdf url
        return $pdf;//return pdf url
    }

    /**
     * Print report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_report($id)
    {
        $group=Group::findOrFail($id);

        if($group['uploaded_report'])
        {
            return redirect($group['report_pdf']);
        }
       
        //categories
        $categories=Category::all();

        foreach($categories as $category)
        {
            $tests=GroupTest::whereHas('test',function($query)use($category){
                return $query->where('category_id',$category['id']);
            })->where('group_id',$group['id'])->get();

            $category['tests']=$tests->sortBy(function($test){
                                    return $test->test->components->count();
                                });

            $category['cultures']=GroupCulture::whereHas('culture',function($query)use($category){
                return $query->where('category_id',$category['id']);
            })->where('group_id',$group['id'])->get();
        }

        //generate pdf
        $data=['group'=>$group->toArray(),'categories'=>$categories->toArray()];
        $pdf=generate_pdf($data);

        return redirect($pdf);//return pdf url
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group=Group::with(['all_tests'=>function($q){
                        return $q->with('test.components');
                    },'all_cultures'])->where('id',$id)
                    ->where('branch_id',session('branch_id'))
                    ->firstOrFail();

        $select_antibiotics=Antibiotic::all();

        $next=Group::where('id','>',$id)->orderBy('id','asc')->first();
        $previous=Group::where('id','<',$id)->orderBy('id','desc')->first();

        return view('admin.medical_reports.edit',compact('group','select_antibiotics','next','previous'));
    }

    /**
     * Update analysis report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group_test=GroupTest::where('id',$id)->firstOrFail();

        $group=Group::where('id',$group_test['group_id'])
                    ->where('branch_id',session('branch_id'))
                    ->firstOrFail();

        $group->update([
            'uploaded_report'=>false
        ]);
        
        GroupTest::where('id',$id)->update([
           'done'=>true,
           'comment'=>$request['comment']
        ]);

        $group=Group::find($group_test['group_id']);
        
        //check if all reports done
        check_group_done($group_test['group_id']);

        //update result
        if($request->has('result'))
        {
            foreach($request['result'] as $key=>$result)
            {
                $group_test_result=GroupTestResult::where('id',$key)->first();

                $test=Test::where('id',$group_test_result['test_id'])->first();

                //add if new option created
                if(isset($test)&&$test['type']=='select')
                {
                    $option=TestOption::where([
                        ['test_id',$test['id']],
                        ['name',$result['result']]
                    ])->first();

                    if(!isset($option))
                    {
                        TestOption::create([
                            'name'=>$result['result'],
                            'test_id'=>$test['id']
                        ]);
                    }
                }

                if(!isset($result['status']))
                {
                    $result['status']='';
                }

                if(!isset($result['result']))
                {
                    $result['result']='';
                }
                
                //update result
                $group_test_result->update([
                    'result'=>$result['result'],
                    'status'=>$result['status']
                ]);
            }
        }

        //generate pdf
        $categories=Category::all();

        foreach($categories as $category)
        {
            $tests=GroupTest::whereHas('test',function($query)use($category){
                return $query->where('category_id',$category['id']);
            })->where('group_id',$group['id'])->get();

            $category['tests']=$tests->sortBy(function($test){
                                    return $test->test->components->count();
                                });

            $category['cultures']=GroupCulture::whereHas('culture',function($query)use($category){
                return $query->where('category_id',$category['id']);
            })->where('group_id',$group['id'])->get();
        }

        // Generate Medical Report PDF is not working properly
        $pdf=generate_pdf([
            'categories'=>$categories,
            'group'=>$group,
        ]);

        if(isset($pdf))
        {
            $group->update(['report_pdf'=>$pdf]);
        }
      
        session()->flash('success',__('Test result saved successfully'));

        return redirect()->back();
    }

    /**
     * Update culture report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_culture(UpdateCultureResultRequest $request,$id)
    {        
        $group_culture=GroupCulture::findOrFail($id);  
        
        $group=Group::where('id',$group_culture['group_id'])
                    ->where('branch_id',session('branch_id'))
                    ->firstOrFail();

        $group->update([
            'uploaded_report'=>false
        ]);
      
        GroupCultureResult::where('group_culture_id',$id)->delete();

        $group_culture->update([
            'done'=>true,
            'comment'=>$request['comment']
        ]);

        //save options
        if($request->has('culture_options'))
        {
            foreach($request['culture_options'] as $key=>$value)
            {
                GroupCultureOption::where('id',$key)->update([
                    'value'=>$value
                ]);
            }
        }
        
        //save antibiotics
        if($request->has('antibiotic'))
        {
            foreach($request['antibiotic'] as $antibiotic)
            {
                if(!empty($antibiotic['antibiotic'])&&!empty($antibiotic['sensitivity']))
                {
                    GroupCultureResult::create([
                        'group_culture_id'=>$id,
                        'antibiotic_id'=>$antibiotic['antibiotic'],
                        'sensitivity'=>$antibiotic['sensitivity'],
                    ]);
                }
            }
        }


        //check if all reports done
        check_group_done($group_culture['group_id']);

        //generate pdf
        $categories=Category::all();

        foreach($categories as $category)
        {
            $tests=GroupTest::whereHas('test',function($query)use($category){
                                return $query->where('category_id',$category['id']);
                            })->where('group_id',$group['id'])->get();

            $category['tests']=$tests->sortBy(function($test){
                return $test->test->components->count();
            });

            $category['cultures']=GroupCulture::whereHas('culture',function($query)use($category){
                return $query->where('category_id',$category['id']);
            })->where('group_id',$group['id'])->get();
        }

        $pdf=generate_pdf([
            'categories'=>$categories,
            'group'=>$group,
        ]);

        if(isset($pdf))
        {
            $group->update(['report_pdf'=>$pdf]);
        }

        session()->flash('success',__('Culture result saved successfully'));

        return redirect()->back();
       
    }

    /**
     * Sign report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sign($id)
    {
        $group=Group::where('id',$id)->firstOrFail();

        $group->update([
            'uploaded_report'=>false
        ]);

        if(!empty(auth()->guard('admin')->user()->signature))
        {
            //add signature
            $group->update([
                'signed_by'=>auth()->guard('admin')->user()->id
            ]);

            //generate pdf
            $categories=Category::all();

            foreach($categories as $category)
            {
                $tests=GroupTest::whereHas('test',function($query)use($category){
                                        return $query->where('category_id',$category['id']);
                                    })->where('group_id',$group['id'])->get();

                $category['tests']=$tests->sortBy(function($test){
                                        return $test->test->components->count();
                                    });

                $category['cultures']=GroupCulture::whereHas('culture',function($query)use($category){
                                        return $query->where('category_id',$category['id']);
                                    })->where('group_id',$group['id'])->get();
            }

            $pdf=generate_pdf([
                'group'=>$group,
                'categories'=>$categories
            ]);

            if(isset($pdf))
            {
                $group->update(['report_pdf'=>$pdf]);
            }

            //send notification to patient
            // send_notification('tests_notification',$group['patient']);
            
            session()->flash('success',__('Report signed successfully'));
    
            return redirect()->back(); 
        }

        session()->flash('failed',__('Please select your signature first'));
    
        return redirect()->back(); 
        
    }

    /**
     * Send report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send_report_mail(Request $request,$id)
    {
        $group=Group::findOrFail($id);
        $patient=$group['patient'];

        send_notification('report',$patient,$group);

        session()->flash('success',__('Mail sent successfully'));

        return redirect()->route('admin.medical_reports.index');
    }


    /**
     * upload report
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upload_report(UploadReportRequest $request,$id)
    {
        $group=Group::findOrFail($id);

        if($request->has('report'))
        {
            $report=$request->file('report');

            $report->move('uploads/pdf','report_'.$group['id'].'.pdf');
            
            $group->update([
                'uploaded_report'=>true,
                'report_pdf'=>url('uploads/pdf/report_'.$group['id'].'.pdf')
            ]);
        }

        session()->flash('success',__('Report updated successfully'));

        return redirect()->back();
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
            //delete group
            $group=Group::findOrFail($id);
            $group->payments()->delete();

            //delete group tests
            $group_tests=GroupTest::where('group_id',$id)->get();

            //delete test results
            foreach($group_tests as $group_test)
            {
                GroupTestResult::where('group_test_id',$group_test['id'])->delete();
            }
            GroupTest::where('group_id',$id)->delete();

            //delete group cultures
            $group_cultures=GroupCulture::where('group_id',$id)->get();
            foreach($group_cultures as $group_culture)
            {
                GroupCultureResult::where('group_culture_id',$group_culture['id'])->delete();
            }
            GroupCulture::where('group_id',$id)->delete();
            
            //delete packages
            $group->packages()->delete();

            //delete consumption
            $group->consumptions()->delete();

            //delete group
            $group->delete();
        }

        session()->flash('success',__('Bulk deleted successfully'));

        return redirect()->route('admin.medical_reports.index');
    }

    /**
     * Bulk print report
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_print_report(BulkActionRequest $request)
    {
        $pdf = PDFMerger::init();

        foreach($request['ids'] as $id)
        {
            $group=Group::find($id);

            //generate pdf
            $categories=Category::all();

            foreach($categories as $category)
            {
                $tests=GroupTest::whereHas('test',function($query)use($category){
                                    return $query->where('category_id',$category['id']);
                                })->where('group_id',$group['id'])->get();

                $category['tests']=$tests->sortBy(function($test){
                    return $test->test->components->count();
                });

                $category['cultures']=GroupCulture::whereHas('culture',function($query)use($category){
                    return $query->where('category_id',$category['id']);
                })->where('group_id',$group['id'])->get();
            }

            $pdf_url=generate_pdf([
                'categories'=>$categories,
                'group'=>$group,
            ]);

            $pdf->addString(file_get_contents($pdf_url));
        }
      
        $pdf->merge();
        $pdf->save('uploads/pdf/bulk.pdf');

        return redirect('uploads/pdf/bulk.pdf');
    }

    /**
     * Bulk print barcode
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_print_barcode(BulkActionRequest $request)
    {
        $groups=Group::whereIn('id',$request['ids'])->get();

        $pdf=print_bulk_barcode($groups);

        return redirect($pdf);
    }

     /**
     * Bulk sign report
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_sign_report(BulkActionRequest $request)
    {
        if(!empty(auth()->guard('admin')->user()->signature))
        {
            $groups=Group::whereIn('id',$request['ids'])->get();

            $categories=Category::all();

            foreach($groups as $group)
            {
                $group->update([
                    'uploaded_report'=>false
                ]);
        
                //add signature
                $group->update([
                    'signed_by'=>auth()->guard('admin')->user()->id
                ]);

                //generate pdf
                foreach($categories as $category)
                {
                    $tests=GroupTest::whereHas('test',function($query)use($category){
                                            return $query->where('category_id',$category['id']);
                                        })->where('group_id',$group['id'])->get();

                    $category['tests']=$tests->sortBy(function($test){
                                            return $test->test->components->count();
                                        });

                    $category['cultures']=GroupCulture::whereHas('culture',function($query)use($category){
                                            return $query->where('category_id',$category['id']);
                                        })->where('group_id',$group['id'])->get();
                }

                $pdf=generate_pdf([
                    'group'=>$group,
                    'categories'=>$categories
                ]);

                if(isset($pdf))
                {
                    $group->update(['report_pdf'=>$pdf]);
                }

                //send notification to patient
                send_notification('tests_notification',$group['patient']);
            }

            session()->flash('success',__('Reports signed successfully'));
    
            return redirect()->back(); 
        }

        session()->flash('failed',__('Please select your signature first'));
    
        return redirect()->back(); 
    }
    

    /**
     * Bulk send report mail
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_send_report_mail(BulkActionRequest $request)
    {
        $groups=Group::whereIn('id',$request['ids'])
                    ->where('signed_by','!=',null)
                    ->get();

        if(!count($groups))
        {
            session()->flash('failed',__('You should sign the reports to be sent'));
            return redirect()->back();
        }

        foreach($groups as $group)
        {
            $patient=$group['patient'];
            send_notification('report',$patient,$group);  
        }

        session()->flash('success',__('Report mails sent successfully'));

        return redirect()->route('admin.medical_reports.index');
    }
}
