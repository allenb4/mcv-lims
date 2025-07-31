<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Group;
use App\Models\Test;
use App\Models\Culture;
use App\Models\GroupTest;
use App\Models\GroupCulture;
use App\Models\Branch;
use App\Models\Contract;
use App\Models\Setting;
use App\Models\Patient;
use App\Models\GroupTestResult;
use App\Models\GroupCultureResult;
use App\Models\CultureOption;
use App\Models\GroupCultureOption;
use App\Models\Package;
use App\Models\GroupPackage;
use App\Models\PaymentMethod;
use App\Http\Requests\Admin\GroupRequest;
use App\Http\Requests\Admin\BulkActionRequest;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use DataTables;

class GroupsController extends Controller
{
     /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_group',     ['only' => ['index', 'show','ajax']]);
        $this->middleware('can:create_group',   ['only' => ['create', 'store']]);
        $this->middleware('can:edit_group',     ['only' => ['edit', 'updae']]);
        $this->middleware('can:delete_group',   ['only' => ['destroy','bulk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.groups.index');
    }

    /**
    * get groups datatable
    *
    * @access public
    * @var  @Request $request
    */
    public function ajax(Request $request)
    {
        $model=Group::with('patient','contract','created_by_user')
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
        // ->editColumn('subtotal',function($group){
        //     return formated_price($group['subtotal']);
        // })
        // ->editColumn('discount',function($group){
        //     return formated_price($group['discount']);
        // })
        // ->editColumn('total',function($group){
        //     return formated_price($group['total']);
        // })
        // ->editColumn('paid',function($group){
        //     return formated_price($group['paid']);
        // })
        // ->editColumn('due',function($group){
        //     return view('admin.groups._due',compact('group'));
        // })
        ->editColumn('done',function($group){
            return view('admin.groups._status',compact('group'));
        })
        ->addColumn('action',function($group){
            return view('admin.groups._action',compact('group'));
        })
        ->addColumn('bulk_checkbox',function($item){
            return view('partials._bulk_checkbox',compact('item'));
        })
        ->editColumn('created_at',function($group){
            return date('Y-m-d H:i',strtotime($group['created_at']));
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
        return view('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(GroupRequest $request)
     {
        $group=Group::create($request->except('_token','tests','cultures','packages','payments','DataTables_Table_0_length','DataTables_Table_1_length','DataTables_Table_2_length'));
        
        $group->update([
            'branch_id'=>session('branch_id'),
            'created_by'=>auth()->guard('admin')->user()->id
        ]);
 
        //store assigned tests
        if($request->has('tests'))
        {
            foreach($request['tests'] as $test)
            {               
                GroupTest::create([
                    'group_id'=>$group->id,
                    'test_id'=>$test['id'],
                    'price'=>$test['price']
                ]);
            }
        }
 
        //store assigned cultures
        $culture_options=CultureOption::where('parent_id',0)->get();
        if($request->has('cultures'))
        {
             foreach($request['cultures'] as $culture)
             {                
                 $group_culture=GroupCulture::create([
                     'group_id'=>$group->id,
                     'culture_id'=>$culture['id'],
                     'price'=>$culture['price']
                 ]);
 
                 //assign default report
                 foreach($culture_options as $culture_option)
                 {
                     GroupCultureOption::create([
                         'group_culture_id'=>$group_culture['id'],
                         'culture_option_id'=>$culture_option['id'],
                     ]);
                 }
             }
         }
 
         //store assigned packages
         if($request->has('packages'))
         {
             foreach($request['packages'] as $package)
             {
                 // packages tests and cultures
                 $original_package=Package::find($package['id']);
 
                 $group_package=GroupPackage::create([
                     'group_id'=>$group['id'],
                     'package_id'=>$package['id'],
                     'price'=>$package['price']
                 ]);
 
                 //tests
                 foreach($original_package['tests'] as $test)
                 {
                     GroupTest::create([
                         'group_id'=>$group['id'],
                         'test_id'=>$test['test']['id'],
                         'package_id'=>$group_package['id']
                     ]);
                 }
 
                 //cultures
                 foreach($original_package['cultures'] as $culture)
                 {
                     $group_culture=GroupCulture::create([
                         'group_id'=>$group['id'],
                         'culture_id'=>$culture['culture']['id'],
                         'package_id'=>$group_package['id']
                     ]);
 
                     //assign default report
                     foreach($culture_options as $culture_option)
                     {
                         GroupCultureOption::create([
                             'group_culture_id'=>$group_culture['id'],
                             'culture_option_id'=>$culture_option['id'],
                         ]);
                     }
                 }
             }
         }
 
         //payments
         if($request->has('payments'))
         {
                 foreach($request['payments'] as $payment)
                 {
                     $group->payments()->create([
                         'date'=>$payment['date'],
                         'payment_method_id'=>$payment['payment_method_id'],
                         'amount'=>$payment['amount'],
                     ]);
                 }
         }
 
        //barcode
        generate_barcode($group['id']);
 
        //assign default report 
        $this->assign_tests_report($group['id']);
 
        //assign consumption 
        $this->assign_consumption($group['id']);
 
        //calculations
        group_test_calculations($group['id']);
 
        //save receipt pdf
        $group=Group::find($group['id']);
        
        // Generating Receipt is not working
        $pdf=generate_pdf($group,2);
 
        if(isset($pdf))
        {
           $group->update(['receipt_pdf'=>$pdf]);
        }
 
        //send notification with the patient code
        $patient=Patient::find($group['patient_id']);
        send_notification('patient_code',$patient);
 
        session()->flash('success',__('Group saved successfully'));
 
        return redirect()->route('admin.groups.show',$group['id']);
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
        $barcode_settings=setting('barcode');

        return view('admin.groups.show',compact('group','barcode_settings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group=Group::with(['tests.test.test_price','cultures.culture.culture_price','packages.package.package_price'])
                    ->where('branch_id',session('branch_id'))
                    ->findOrFail($id);
                    
        $tests=Test::where('parent_id',0)->orWhere('separated',true)->get();
        $cultures=Culture::all();
        $packages=Package::all();

        return view('admin.groups.edit',compact('group','tests','cultures','packages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, $id)
    {
        $group=Group::where('branch_id',session('branch_id'))
                    ->findOrFail($id);

        $group->update($request->except('_method',
                                        '_token',
                                        'tests',
                                        'cultures',
                                        'packages',
                                        'payments',
                                        'DataTables_Table_0_length',
                                        'DataTables_Table_1_length',
                                        'DataTables_Table_2_length'
                                    ));

        $group->update([
            'contract_id'=>(isset($request['contract_id']))?$request['contract_id']:''
        ]);

        //store assigned tests
        $selected_tests=[];
        if($request->has('tests'))
        {
            foreach($request['tests'] as $test)
            {
                $selected_tests[]=$test['id'];

                $group_test=GroupTest::where([
                    ['group_id',$id],
                    ['test_id',$test['id']],
                ])->first();
                
                if(isset($group_test))
                {                
                    $group_test->update([
                        'price'=>$test['price']
                    ]);
                }
                else{
                    GroupTest::create([
                        'group_id'=>$group->id,
                        'test_id'=>$test['id'],
                        'price'=>$test['price']
                    ]);
                }
            }
        }

        //delete unselected tests
        $group->tests()->whereNotIn('test_id',$selected_tests)->delete();

        //store assigned cultures
        $selected_cultures=[];
        $culture_options=CultureOption::where('parent_id',0)->get();
        if($request->has('cultures'))
        {
            foreach($request['cultures'] as $culture)
            {
                $selected_cultures[]=$culture['id'];

                $group_culture=GroupCulture::where([
                    ['group_id',$id],
                    ['culture_id',$culture['id']]
                ])->first();

                if(isset($group_culture))
                {                
                    $group_culture->update([
                        'price'=>$culture['price']
                    ]);
                }
                else{
                    $group_culture=GroupCulture::create([
                        'group_id'=>$group->id,
                        'culture_id'=>$culture['id'],
                        'price'=>$culture['price']
                    ]);

                    //assign default report
                    foreach($culture_options as $culture_option)
                    {
                        GroupCultureOption::create([
                            'group_culture_id'=>$group_culture['id'],
                            'culture_option_id'=>$culture_option['id'],
                        ]);
                    }
                }
            }
        }

        //delete unselected cultures
        $group->cultures()->whereNotIn('culture_id',$selected_cultures)->get();

        //store assigned packages
        $packages_selected=[];
        if($request->has('packages'))
        {
            foreach($request['packages'] as $package)
            {
                $packages_selected[]=$package['id'];

                $group_package=GroupPackage::where([
                                        ['group_id',$id],
                                        ['package_id',$package['id']]
                                    ])->first();

                // original package
                $original_package=Package::find($package['id']);

                if(isset($group_package))
                {
                    $group_package->update([
                        'price'=>$package['price']
                    ]);
                }
                else{
                    $group_package=GroupPackage::create([
                        'group_id'=>$group['id'],
                        'package_id'=>$package['id'],
                        'price'=>$package['price'],
                    ]);

                    //tests
                    foreach($original_package['tests'] as $test)
                    {
                        GroupTest::create([
                            'group_id'=>$group['id'],
                            'test_id'=>$test['test']['id'],
                            'package_id'=>$group_package['id']
                        ]);
                    }

                    //cultures
                    foreach($original_package['cultures'] as $culture)
                    {
                        $group_culture=GroupCulture::create([
                            'group_id'=>$group['id'],
                            'culture_id'=>$culture['culture']['id'],
                            'package_id'=>$group_package['id']
                        ]);

                        //assign default report
                        foreach($culture_options as $culture_option)
                        {
                            GroupCultureOption::create([
                                'group_culture_id'=>$group_culture['id'],
                                'culture_option_id'=>$culture_option['id'],
                            ]);
                        }
                    }
                }
            }
        }

        //delete unselected packages
        $unselected_packages=GroupPackage::where([
                                ['group_id',$id],
                            ])->whereNotIn('package_id',$packages_selected)
                            ->get();

        if(count($unselected_packages))
        {
            foreach($unselected_packages as $unselected_package)
            {
                $unselected_package->tests()->delete();
                $unselected_package->cultures()->delete();
                $unselected_package->delete();
            }
        }

        //payments
        \Log::info(['payments' => $request['payments']]);
        $group->payments()->delete();
        if ($request->has('payments')) {
            foreach ($request['payments'] as $payment) {
                $group->payments()->create([
                    'date' => $payment['date'],
                    'payment_method_id' => $payment['payment_method_id'],
                    'amount' => $payment['amount'],
                ]);
            }
        }

        //assign default report 
        $this->assign_tests_report($id);

        //assign consumption 
        $this->assign_consumption($group['id']);

        //calculations
        group_test_calculations($id);

        //save receipt pdf
        $group=Group::with([
            'tests',
            'tests.test',
            'cultures', 
            'cultures.culture', 
            'all_tests',
            'all_cultures',
            'packages',
            'packages.package',
            'patient',
            'doctor',
            'branch',
            'contract',
            'payments',
            'payments.payment_method',
            'consumptions',
            'created_by_user',
            'signed_by_user'
            ])->where('id',$id)->first();
        
        // Generating Receipt for Invoice is not working
        $pdf=generate_pdf($group->toArray(),2);
       
        if(isset($pdf))
        {
            $group->update(['receipt_pdf'=>$pdf]);
        }

        //send notification with the patient code
        $patient=Patient::find($group['patient_id']);
        send_notification('patient_code',$patient);

        session()->flash('success',__('Group updated successfully'));

        return redirect()->route('admin.groups.show',$id);
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
        session()->flash('success',__('Group deleted successfully'));
        return redirect()->route('admin.groups.index');
    }


    /**
     * generate pdf
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {
        $group=Group::with('patient','analyses','cultures')->where('id',$id)->first();

        $response=generate_pdf($group,2);

        if(!empty($response))
        {
            return redirect($response['url']);
        }
        else{
            session()->flash('failed',__('Something Went Wrong'));
            return redirect()->back();
        }

    }


    /**
     * assign default tests report
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign_tests_report($id)
    {
        $group=Group::with('tests')->where('id',$id)->first();

        //tests reports
        foreach($group['tests'] as $test)
        {
            if(!$test->has_results)
            {
                $test->update(['has_results'=>true]);
                
                $separated=Test::where('id',$test['test_id'])->first();
                
                if($separated['separated'])
                {
                    GroupTestResult::create([
                        'group_test_id'=>$test['id'],
                        'test_id'=>$test['test_id'],
                    ]);
                }

                foreach($test['test']['components'] as $component) 
                {
                    GroupTestResult::create([
                        'group_test_id'=>$test['id'],
                        'test_id'=>$component['id'],
                    ]);
                }
            }
        }

        //packages reports
        if(count($group['packages']))
        {
            foreach($group['packages'] as $package)
            {
                if(count($package['tests']))
                {
                    foreach($package['tests'] as $test)
                    {
                        if(!$test->has_results)
                        {
                            $test->update(['has_results'=>true]);

                            $separated=Test::where('id',$test['test_id'])->first();
                                
                            if($separated['separated'])
                            {
                                GroupTestResult::create([
                                    'group_test_id'=>$test['id'],
                                    'test_id'=>$test['test_id'],
                                ]);
                            }
    
                            foreach($test['test']['components'] as $component) 
                            {
                                GroupTestResult::create([
                                    'group_test_id'=>$test['id'],
                                    'test_id'=>$component['id'],
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }


    /**
     * print barcode
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print_barcode(Request $request,$id)
    {
        $request->validate([
            'number'=>'required|numeric|min:1'
        ]);

        $group=Group::findOrFail($id);

        $number=$request['number'];

        $pdf=print_barcode($group,$number);

        return redirect($pdf);
    }

    /**
     * send receipt mail
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send_receipt_mail(Request $request,$id)
    {
        $group=Group::findOrFail($id);
        $patient=$group['patient'];

        send_notification('receipt',$patient,$group);

        session()->flash('success',__('Mail sent successfully'));

        return redirect()->route('admin.groups.index');
    }

    /**
     * Assign consumptions to invoice
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assign_consumption($id)
    {
        $group=Group::find($id);
        $group->consumptions()->delete();

        if(isset($group))
        {
            foreach($group['all_tests'] as $test)
            {
                if(isset($test['test']))
                {
                    foreach($test['test']['consumptions'] as $consumption)
                    {
                        $group->consumptions()->create([
                            'branch_id'=>$group['branch_id'],
                            'testable_type'=>'App\Models\Test',
                            'testable_id'=>$test['test_id'],
                            'product_id'=>$consumption['product_id'],
                            'quantity'=>$consumption['quantity']
                        ]);
                    }
                }
            }

            foreach($group['all_cultures'] as $culture)
            {
                if(isset($culture['culture']))
                {
                    foreach($culture['culture']['consumptions'] as $consumption)
                    {
                        $group->consumptions()->create([
                            'branch_id'=>$group['branch_id'],
                            'testable_type'=>'App\Models\Culture',
                            'testable_id'=>$culture['culture_id'],
                            'product_id'=>$consumption['product_id'],
                            'quantity'=>$consumption['quantity']
                        ]);
                    }
                }
            }
        }

    }

    /**
     * Print working paper
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function working_paper($id)
    {
        $group=Group::findOrFail($id);

        $pdf=generate_pdf($group,7);

        return redirect($pdf);
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

        return redirect()->route('admin.groups.index');
    }

     /**
     * Bulk print barcodes
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
     * Bulk print receipts
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_print_receipt(BulkActionRequest $request)
    {
        $groups=Group::whereIn('id',$request['ids'])->get();

        $pdf = PDFMerger::init();

        foreach($groups as $group)
        {
            //generate pdf
            $url=generate_pdf($group,2);

            $pdf->addString(file_get_contents($url));
        }
      
        $pdf->merge();
        $pdf->save('uploads/pdf/bulk.pdf');

        return redirect('uploads/pdf/bulk.pdf');
    }

     /**
     * Bulk print working paper
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_print_working_paper(BulkActionRequest $request)
    {
        $groups=Group::whereIn('id',$request['ids'])->get();

        $pdf=print_bulk_working_paper($groups);

        return redirect($pdf);
    }

    /**
     * Bulk send receipt mail
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulk_send_receipt_mail(BulkActionRequest $request)
    {
        $groups=Group::whereIn('id',$request['ids'])->get();

        foreach($groups as $group)
        {
            $patient=$group['patient'];
            send_notification('receipt',$patient,$group);
        }

        session()->flash('success',__('Receipts have been sent successfully'));
        return redirect()->route('admin.groups.index');
    }

    
}
