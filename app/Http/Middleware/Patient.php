<?php

namespace App\Http\Middleware;

use App\Models\Patient as PatientModel;
use Closure;
use Auth;
use Cache;
class Patient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Schema::hasTable('migrations'))
        {
            if(!Auth::guard('patient')->check())
            {
                return redirect()->route('patient.auth.login');
            }
    
            //add online
            PatientModel::where('id',auth()->guard('patient')->user()->id)
                    ->update([
                        'last_activity'=>now()
                    ]);

            //share settings
            $info=setting('info');
            $api_keys=setting('api_keys');

            
            view()->share([
                'info'=>$info,
                'api_keys'=>$api_keys
            ]);
        }
        
        return $next($request);
    }
}
