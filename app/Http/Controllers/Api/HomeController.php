<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class HomeController extends Controller
{
    public function get_countries(Request $request)
    {
        if($request->has('nationality'))
        {
            $countries=Country::where('nationality','like','%'.$request['nationality'].'%')->take(10)->get();
        }
        else{
            $countries=Country::take(10)->get();
        }

        return Response::response(200,'success',['countries'=>$countries]); 
    }
}
