<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\Timezone;
use App\Models\Branch;
use App\Http\Requests\Admin\GeneralSettingRequest;
use App\Http\Requests\Admin\EmailSettingRequest;
use App\Http\Requests\Admin\ReportSettingRequest;
use App\Http\Requests\Admin\SmsSettingRequest;
use App\Http\Requests\Admin\WhatsappSettingRequest;
use App\Http\Requests\Admin\ApiSettingRequest;
use App\Http\Requests\Admin\BarcodeSettingRequest;

class SettingsController extends Controller
{
    /**
     * assign roles
     */
    public function __construct()
    {
        $this->middleware('can:view_setting',['only' => [
                                                            'index',
                                                            'info_submit',
                                                            'emails_submit',
                                                            'reports_submit',
                                                            'sms_submit',
                                                            'whatsapp_submit',
                                                            'api_keys_submit',
                                                            'barcode_submit',
                                                        ]]);
    }

    public function index()
    {
        //general
        $settings=setting('info');
        $currencies=Currency::all();
        
        //reports
        $reports_settings=setting('reports');

        //barcode
        $barcode_settings=setting('barcode');

        //emails
        $emails_settings=setting('emails');

        //sms
        $sms_settings=setting('sms');

        //whatsapp
        $whatsapp_settings=setting('whatsapp');

        //api keys
        $api_keys_settings=setting('api_keys');

        //timezone
        $timezone_settings=setting('info')['timezone'];
        $timezone_settings=Timezone::where('timezone',$timezone_settings)->first();

        $report_branches=Branch::all();

        return view('admin.settings.index',compact(
            'settings',
            'currencies',
            'reports_settings',
            'barcode_settings',
            'emails_settings',
            'sms_settings',
            'whatsapp_settings',
            'api_keys_settings',
            'timezone_settings',
            'report_branches'
        ));
        
    }


    /**
     * update settings info
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function info_submit(GeneralSettingRequest $request)
    {
        //old settings
        $old_settings=Setting::where('key','info')->first();
        $old_settings=json_decode($old_settings['value'],true);
        $settings=$request->except('logo','_token');
        
        //social links
        $settings['socials']['facebook']=$request['facebook'];
        $settings['socials']['twitter']=$request['twitter'];
        $settings['socials']['instagram']=$request['instagram'];
        $settings['socials']['youtube']=$request['youtube'];

        //update currency cache
        cache()->put('currency',$request['currency']);
    
        //update banner
        if($request->hasFile('banner'))
        {
            $logo=$request->file('banner');
            $logo->move('assets/images','banner.jpg');
        }

        //update logo
        if($request->hasFile('logo'))
        {
           $request->file('logo')->move('img','logo.png');
        }

        //update reports logo
        if($request->hasFile('reports_logo'))
        {
            $request->file('reports_logo')->move('img','reports_logo.png');
        }

        //update preloader
        if($request->hasFile('preloader'))
        {
            $extension=$request->file('preloader')->getClientOriginalExtension();
            $request->file('preloader')->move('img','preloader.'.$extension);
            $settings['preloader']='preloader.'.$extension;
        }
        else{
            $settings['preloader']=$old_settings['preloader'];
        }

        $info=Setting::where('key','info')->firstOrFail();
        $info->update([
            'value'=>json_encode($settings)
        ]);

       session()->flash('success',__('Settings Updated successfully'));

       return redirect()->route('admin.settings.index');
    }

    

    /**
     * update emails settings
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emails_submit(EmailSettingRequest $request)
    {
       $settings=$request->except('_token');

       $settings['patient_code']['active']=($request->has('patient_code.active'))?true:false;
       $settings['reset_password']['active']=($request->has('reset_password.active'))?true:false;
       $settings['tests_notification']['active']=($request->has('tests_notification.active'))?true:false;

       //update setting record in database
       $emails=Setting::where('key','emails')->firstOrFail();
       $emails->update([
         'value'=>json_encode($settings)
       ]);
       
       session()->flash('success',__('Settings Updated successfully'));

       return redirect()->route('admin.settings.index');
    }

    /**
     * update reports settings
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reports_submit(ReportSettingRequest $request)
    {
        $request['show_header']=($request->has('show_header'))?true:false;
        $request['show_footer']=($request->has('show_footer'))?true:false;
        $request['show_signature']=($request->has('show_signature'))?true:false;

        $settings=json_encode($request->except('_method','_token','branches'));

        $reports=Setting::where('key','reports')->firstOrFail();
        $reports->update([
            'value'=>$settings
        ]);

        //report branches
        $branches=Branch::all();

        foreach($branches as $branch)
        {
            $branch->update([
                'show_header_image'=>($request->has('branches.'.$branch['id'].'.show_header_image'))?true:false,
                'show_watermark_image'=>($request->has('branches.'.$branch['id'].'.show_watermark_image'))?true:false,
                'show_footer_image'=>($request->has('branches.'.$branch['id'].'.show_footer_image'))?true:false,
                'report_footer'=>$request['branches'][$branch['id']]['report_footer']
            ]);

            //upload header
            if($request->hasFile('branches.'.$branch['id'].'.header_image'))
            {
                $header_image=$request->file('branches.'.$branch['id'].'.header_image');
                $name='header_'.$branch['id'].'.'.$header_image->getClientOriginalExtension();
                $header_image->move('uploads/branches',$name);
                $branch->update([
                    'header_image'=>$name
                ]);
            }

            //upload watermark
            if($request->hasFile('branches.'.$branch['id'].'.watermark_image'))
            {
                $watermark_image=$request->file('branches.'.$branch['id'].'.watermark_image');
                $name='watermark_'.$branch['id'].'.'.$watermark_image->getClientOriginalExtension();
                $watermark_image->move('uploads/branches',$name);
                $branch->update([
                    'watermark_image'=>$name
                ]);
            }

            //upload footer
            if($request->hasFile('branches.'.$branch['id'].'.footer_image'))
            {
                $footer_image=$request->file('branches.'.$branch['id'].'.footer_image');
                $name='footer_'.$branch['id'].'.'.$footer_image->getClientOriginalExtension();
                $footer_image->move('uploads/branches',$name);
                $branch->update([
                    'footer_image'=>$name
                ]);
            }

        }

        session()->flash('success',__('Settings Updated successfully'));

        return redirect()->route('admin.settings.index');
    }

    /**
     * update reports settings
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sms_submit(SmsSettingRequest $request)
    {
        $settings=$request->except('_method','_token');

        $settings['patient_code']['active']=($request->has('patient_code.active'))?true:false;
        $settings['tests_notification']['active']=($request->has('tests_notification.active'))?true:false;
    
        $sms=Setting::where('key','sms')->firstOrFail();
        $sms->update([
            'value'=>$settings
        ]);

        session()->flash('success',__('Settings Updated successfully'));

        return redirect()->route('admin.settings.index');
    }

    /**
     * update whatsapp settings
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function whatsapp_submit(WhatsappSettingRequest $request)
    {
        $whatsapp_settings=[];

        $whatsapp_settings['receipt']['active']=(isset($request['receipt']['active']))?true:false; 
        $whatsapp_settings['report']['active']=(isset($request['report']['active']))?true:false;    
        
        $whatsapp_settings['receipt']['message']=$request['receipt']['message'];
        $whatsapp_settings['report']['message']=$request['report']['message'];


        $whatsapp=Setting::where('key','whatsapp')->firstOrFail();
        $whatsapp->update([
            'value'=>json_encode($whatsapp_settings)
        ]);

        session()->flash('success',__('Settings Updated successfully'));

        return redirect()->route('admin.settings.index');
    }


    /**
     * update api keys settings
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function api_keys_submit(ApiSettingRequest $request)
    {
        $api_keys=[];
        $api_keys['google_map']=$request['google_map'];

        $api_keys_setting=Setting::where('key','api_keys')->firstOrFail();
        $api_keys_setting->update([
            'value'=>json_encode($api_keys)
        ]);

        session()->flash('success',__('Settings Updated successfully'));
       
        return redirect()->route('admin.settings.index');
    }

     /**
     * update barcode settings
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function barcode_submit(BarcodeSettingRequest $request)
    {
        $barcode_settings=Setting::where('key','barcode')->firstOrFail();
        $barcode_settings->update([
            'value'=>json_encode($request->except('_token'))
        ]);

        session()->flash('success',__('Settings Updated successfully'));
       
        return redirect()->route('admin.settings.index');
    }
}
